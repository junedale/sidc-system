<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ObItemModel;
use App\Models\ObModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;

class OffBusiness extends BaseController
{

    protected $helpers = ['form', 'html', 'user', 'request'];
    protected ObModel $obModel;
    protected ObItemModel $obItemModel;

    public function __construct()
    {
        $this->obModel     = model(ObModel::class);
        $this->obItemModel = model(ObItemModel::class);
    }

    public function index()
    {
        $data = [
            'title'  => 'Official Business Request',
            'stats'  => $this->obModel->getStats(session()->get('emp_no')),
            'crumb'  => [
                0 => [
                    'title' => 'Official Business Request',
                    'url'   => null
                ]
            ]
        ];

        return view('App\Views\pages\ob\home', $data);
    }

    /** @noinspection PhpParamsInspection */
    public function search()
    {
        return DataTable::of($this->obModel->getAllRequest(session()->get('emp_no')))
            ->edit('date_added', function($row) {
                return Time::parse($row->date_added)->toLocalizedString('MMMM d, yyyy');
            })
            ->edit('status', function($row) {
                return to_status($row->status);
            })
            ->setSearchableColumns(['id', 'name'])
            ->toJson(true);
    }

    public function show($id = null, ?bool $update = null)
    {
        if(!$this->obModel->hasRequest($id)['exist'])
        {
            return redirect()->to('error/forbidden');
        }

        $data = [
            'title'  => 'View Request',
            'request' => $this->obModel->getRequest($id),
            'items'  => $this->obItemModel->where('request_id', $id)->find(),
            'crumb'  => [
                0 => [
                    'title' => 'Official Business Request',
                    'url'   => base_url('ob')
                ],
                1 => [
                    'title' => 'View',
                    'url'   => base_url('ob/update/'. $id)
                ],
                2 => [
                    'title' => $id,
                    'url'   => null
                ]
            ],
        ];

        if($update === true)
        {
            $data['title']             = 'Update Request';
            $data['approval']          = true;
            $data['crumb'][1]['title'] = 'Update';
            $data['crumb'][1]['url']   = base_url('leave/update/'. $id);
        }


        return view('App\Views\pages\ob\details', $data);
    }

    public function create($validator = null)
    {
        if(has_permission('form.create'))
        {
            if(strcmp($this->request->getMethod(true), 'GET') === 0)
            {
                $data = [
                    'title'     => 'Create Request',
                    'validator' => $validator,
                    'crumb'     => [
                        0 => [
                            'title' => 'Official Business Request',
                            'url'   => base_url('ob')
                        ],
                        1 => [
                            'title' => 'Create',
                            'url'   => null
                        ]
                    ],
                    'transit' => [
                        'company'    => 'Company Vehicle',
                        'personal'   => 'Personal Vehicle',
                        'commute'    => 'Commute'
                    ]
                ];

                return view('App\Views\pages\ob\create', $data);
            }
            return $this->createAction();
        }
        return redirect()->to('error/forbidden');
    }

    private function createAction()
    {
        $rules = $this->obModel->getValidationRules();

        $data = [
            'emp_no'  => session()->get('emp_no'),
            'transit' => $this->request->getPost('transit')
        ];

        if(in_group('team-lead'))
        {
            $data['initial_app'] = 1;
            $data['rev_by']      = session()->get('name');
        }

        if($this->validate($rules))
        {
            try {
                if ($this->obModel->insert($data)) {
                    session()->set(['message' => 'Request successfully created', 'modal' => true, 'type' => 'Success']);
                    return redirect()->to('ob/update/'. $this->obModel->getInsertID());
                }
                session()->set(['message' => 'Failed to create request', 'modal' => true, 'type' => 'Unsuccessful']);
                return redirect()->to(current_url());
            } catch (\ReflectionException $e) {
                return $e;
            }
        }

        $this->request->setMethod('GET');
        return $this->create($this->validator);
    }

    public function update($id = null, $validator = null)
    {
        $request = $this->obModel->hasRequest($id);

        if(has_permission('form.edit'))
        {
            if(strcmp($this->request->getMethod(true), 'GET') === 0)
            {
                $data = [
                    'title'      => 'Update Request',
                    'validation' => $validator,
                    'request'    => $this->obModel->select('transit, id')->where('id' , $id)->find()[0],
                    'items'      => $this->obItemModel->where('request_id', $id)->find(),
                    'crumb'      => [
                        0 => [
                            'title' => 'Official Business Request',
                            'url'   => base_url('ob')
                        ],
                        1 => [
                            'title' => 'Update',
                            'url'   => null
                        ]
                    ],
                    'transit' => [
                        'company'    => 'Company Vehicle',
                        'personal'   => 'Personal Vehicle',
                        'commute'    => 'Commute'
                    ]
                ];

                if($request !== null)
                {
                    if($request['exist'])
                    {
                        if(!$request['editable'])
                            return redirect()->to('ob/view/'. $id);
                        else
                        {
                            if($request['approval'])
                                return $this->show($id, true);
                            else
                                return view('App\Views\pages\ob\update', $data);

                        }
                    }
                }
            }
            return $this->updateAction($id, $request);
        }
        return redirect()->to('error/forbidden');
    }

    private function updateAction($id, $request = null)
    {

        if($this->request->isAJAX())
        {
            $data = $this->request->getRawInput();

            if(array_key_exists('transit', $data))
            {
                if($this->validate($this->obModel->getValidationRules()))
                {
                    try {
                        if ($this->obModel->update($id, $data)) {
                            return json_encode(['message' => 'Transit has been updated', 'modal' => true, 'type' => 'Success']);
                        }
                        return json_encode(['message' => 'Failed to update transit', 'modal' => true, 'type' => 'Unsuccessful']);
                    } catch (\ReflectionException $e) {
                        return $e;
                    }
                }
            } else
            {
                if($this->validate($this->obItemModel->getValidationRules()))
                {
                    try {
                        if ($this->obItemModel->update($id, $data)) {
                            return json_encode(['message' => 'Destination has been updated', 'modal' => true, 'type' => 'Success']);
                        }
                        return json_encode(['message' => 'Failed to update destination', 'modal' => true, 'type' => 'Unsuccessful']);
                    } catch (\ReflectionException $e) {
                        return $e;
                    }
                }
            }
        } else
        {

            $data = [
                'request_id'  => $id,
                'sched_date'  => $this->request->getPost('sched_date'),
                'destination' => $this->request->getPost('destination'),
                'purpose'     => $this->request->getPost('purpose')
            ];

            $approval = $this->request->getPost('approval');

            if(in_group('manager'))
            {
                if($request['approval'])
                {
                    $data = [
                        'app_by'     => session()->get('name'),
                        'final_app'  => $approval,
                        'status'     => $approval
                    ];
                }
            } elseif(in_group('team-lead'))
            {
                if($request['approval'])
                {
                    $data = [
                        'rev_by'       => session()->get('name'),
                        'initial_app'  => $approval,
                        'status'       => $approval === null ? null :
                            ((int) $approval === 0 ? 0 : null),
                    ];
                }
            }

            if($request['approval'])
            {

                $rules = $this->obModel->getValidationRules();
                unset($rules['transit']);

                if($this->validate($rules))
                {
                    try {
                        if ($this->obModel->update($id, $data)) {
                            session()->set(['message' => 'Request has been updated', 'modal' => true, 'type' => 'Success']);
                            return redirect()->to(current_url());
                        }
                        session()->set(['message' => 'Failed to update request', 'modal' => true, 'type' => 'Unsuccessful']);
                        return redirect()->to(current_url());
                    } catch (\ReflectionException $e) {
                        return $e;
                    }
                }
            } else
            {
                $rules = $this->obItemModel->getValidationRules();

                if($this->validate($rules))
                {
                    try {
                        if ($this->obItemModel->insert($data)) {
                            session()->set(['message' => 'Destination has been added', 'modal' => true, 'type' => 'Success']);
                            return redirect()->to(current_url());
                        }
                        session()->set(['message' => 'Failed to add destination', 'modal' => true, 'type' => 'Unsuccessful']);
                        return redirect()->to(current_url());
                    } catch (\ReflectionException $e) {
                        return $e;
                    }
                }
            }

            $this->request->setMethod('GET');
            return $this->update($id, $this->validator);
        }

    }

    public function retrieveItem($id)
    {
        return json_encode($this->obItemModel->select('sched_date, destination, arrival, departure, purpose')->where('id', $id)->find());
    }

    public function cancel($id)
    {
        try {
            if($this->obModel->update($id, ['status' => 2]))
                return json_encode(['message' => 'Request has been cancelled', 'modal' => true, 'type' => 'Successful']);
        } catch (\ReflectionException $e) {
            return $e;
        }
        return json_encode(['message' => 'Failed to cancel request', 'modal' => true, 'type' => 'Unsuccessful']);
    }

    public function delete($id)
    {
        try {
            if($this->obItemModel->where('id', $id)->delete())
                return json_encode(['message' => 'Destination has been deleted', 'modal' => true, 'type' => 'Successful']);
        } catch (\ReflectionException $e) {
            return $e;
        }
        return json_encode(['message' => 'Failed to delete', 'modal' => true, 'type' => 'Unsuccessful']);
    }
}
