<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OvertimeModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;

class Overtime extends BaseController
{

    protected $helpers = ['form', 'html', 'user', 'request'];
    protected OvertimeModel $otModel;

    public function __construct()
    {
        $this->otModel = model(OvertimeModel::class);
    }

    public function index()
    {
        $data = [
            'title'  => 'Overtime Request',
            'stats'  => $this->otModel->getStats(session()->get('emp_no')),
            'crumb' => [
                0 => [
                    'title' => 'Overtime Requests',
                    'url'   => null
                ]
            ],
        ];

        return view('App\Views\pages\overtime\home', $data);
    }

    /** @noinspection PhpParamsInspection */
    public function search()
    {

        return DataTable::of($this->otModel->getAllRequest(session()->get('emp_no')))
            ->edit('ot_date', function($row) {
                return Time::parse($row->ot_date)->toLocalizedString('MMMM d, yyyy');
            })
            ->edit('date_added', function($row) {
                return Time::parse($row->date_added)->toLocalizedString('MMMM d, yyyy');
            })
            ->edit('status', function($row) {
                return to_status($row->status);
            })
            ->setSearchableColumns(['id', 'name'])
            ->toJson(true);
    }

    public function show($id = null, $update = null)
    {
        if(!$this->otModel->hasRequest($id)['exist'])
        {
            return redirect()->to('error/forbidden');
        }

        $data = [
            'title'   => 'Overtime Request',
            'request' => $this->otModel->getRequest($id),
            'crumb'   => [
                0 => [
                    'title' => 'Overtime Requests',
                    'url'   => base_url('overtime')
                ],
                1 => [
                    'title' => 'View',
                    'url'   => base_url('overtime/view/'. $id)
                ],
                2 => [
                    'title' => $id,
                    'url'  => null
                ]
            ],
        ];

        if($update === true)
        {
            $data['title']             = 'Update Request';
            $data['approval']          = true;
            $data['crumb'][1]['title'] = 'Update';
            $data['crumb'][1]['url']   = base_url('overtime/update/'. $id);
        }

        return view('App\Views\pages\overtime\details', $data);
    }

    public function create($validator = null)
    {
        if(has_permission('form.create'))
        {
            if(strcmp($this->request->getMethod(true), 'GET') === 0)
            {
                $data = [
                    'title'      => 'Create Overtime Request',
                    'validation' => $validator,
                    'crumb'      => [
                        0 => [
                            'title' => 'Overtime Requests',
                            'url'   => base_url('overtime')
                        ],
                        1 => [
                            'title' => 'Create',
                            'url'   => null
                        ]
                    ]
                ];

                return view('App\Views\pages\overtime\create', $data);
            }
            return $this->createAction();
        }
        return redirect()->to('error/forbidden');
    }

    private function createAction()
    {
        $rules   = $this->otModel->getValidationRules();

        $data = [
            'emp_no'  => session()->get('emp_no'),
            'ot_date' => $this->request->getPost('ot_date'),
            'purpose' => $this->request->getPost('purpose')
        ];

        if(in_group('team-lead'))
        {
            $data['initial_app'] = 1;
            $data['rev_by']      = session()->get('name');
        }

        if($this->validate($rules))
        {
            try {
                if ($this->otModel->insert($data))
                {
                    session()->set(['message' => 'Request successfully created', 'modal' => true, 'type' => 'Success']);
                    return redirect()->to(current_url());
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
        $request = $this->otModel->hasRequest($id);
        
        if(strcmp($this->request->getMethod(true), 'GET') === 0)
        {
            $data = [
                'title'      => 'Update Overtime Request',
                'request'    => $this->otModel->select('id, ot_date, purpose')->where('id', $id)->find()[0],
                'validation' => $validator,
                'crumb'      => [
                    0 => [
                        'title' => 'Overtime Requests',
                        'url'   => base_url('overtime')
                    ],
                    1 => [
                        'title' => 'Update',
                        'url'   => null
                    ]
                ]
            ];
            
            if($request !== null)
            {
                if($request['exist'])
                {
                    if(!$request['editable'])
                        return redirect()->to('overtime/view/'. $id);
                    else
                    {
                        if($request['approval'])
                            return $this->show($id, true);
                        else
                            return view('App\Views\pages\overtime\update', $data);
                    }
                }
            }
            return redirect()->to('errors/forbidden');

        }
        
        return $this->updateAction($id, $request);
    }

    private function updateAction(int $id, array $request)
    {
        $data = [
            'ot_date' => $this->request->getPost('ot_date'),
            'purpose'    => $this->request->getPost('purpose')
        ];

        $rules    = $this->otModel->getValidationRules();
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
                unset($rules['reason'], $rules['purpose'], $rules['ot_date']);
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
                unset($rules['reason'], $rules['purpose'], $rules['ot_date']);
            }
        }

        if($this->validate($rules))
        {
            try {
                if ($this->otModel->update($id, $data)) {
                    session()->set(['message' => 'Request has been updated', 'modal' => true, 'type' => 'Success']);
                    return redirect()->to(current_url());
                }
                session()->set(['message' => 'Failed to update request', 'modal' => true, 'type' => 'Unsuccessful']);
                return redirect()->to(current_url());
            } catch (\ReflectionException $e) {
                return $e;
            }
        }

        $this->request->setMethod('GET');
        return $this->update($id, $this->validator);
    }

    public function cancel($id)
    {
        try {
            if($this->otModel->update($id, ['status' => 2]))
            {
                return json_encode(['message' => 'Request has been cancelled', 'modal' => true, 'type' => 'Successful']);
            }
        } catch (\ReflectionException $e) {
            return $e;
        }
        return json_encode(['message' => 'Failed to cancel request', 'modal' => true, 'type' => 'Unsuccessful']);
    }
}
