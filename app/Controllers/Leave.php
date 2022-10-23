<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LeaveModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;

class Leave extends BaseController
{

    protected $helpers = ['form', 'html', 'user', 'request'];
    protected LeaveModel $leaveModel;

    public function __construct()
    {
        $this->leaveModel = model(LeaveModel::class);
    }

    public function index()
    {
        $leaveModel = model(LeaveModel::class);

        $data = [
            'title'  => 'Leave Request',
            'stats'  => $leaveModel->getStats(session()->get('emp_no')),
            'crumb'  => [
                0 => [
                    'title' => 'Leave Request',
                    'url'   => null
                ]
            ]
        ];

        return view('App\Views\pages\leave\home', $data);
    }

    public function show($id = null, $update = null)
    {

        if(!$this->leaveModel->hasRequest($id)['exist'])
        {
            return redirect()->to('error/forbidden');
        }

        $data = [
            'title'      => 'View Request',
            'request'    => $this->leaveModel->getRequest($id),
            'crumb'      => [
                0 => [
                    'title' => 'Leave Request',
                    'url'   => base_url('leave')
                ],
                1 => [
                    'title' => 'View',
                    'url'   => base_url('leave/update/'. $id)
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

        return view('App\Views\pages\leave\details', $data);
    }

    /** @noinspection PhpParamsInspection */
    public function search()
    {

        return DataTable::of($this->leaveModel->getAllRequest(session()->get('emp_no')))
            ->edit('leave_date', function($row) {
                return Time::parse($row->leave_date)->toLocalizedString('MMMM d, yyyy');
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

    public function create($validator = null)
    {
        if(has_permission('form.create'))
        {
            if(strcmp($this->request->getMethod(true), 'GET') === 0)
            {
                $data = [
                    'title'      => 'Create Leave Request',
                    'validation' => $validator,
                    'crumb'      => [
                        0 => [
                            'title' => 'Leave Request',
                            'url'   => base_url('leave')
                        ],
                        1 => [
                            'title' => 'Create',
                            'url'   => base_url('leave/create')
                        ]
                    ],
                    'type'       => [
                        'emergency'   => 'Emergency',
                        'vacation'    => 'Vacation',
                        'undertime'   => 'Undertime',
                        'birthday'    => 'Birthday',
                        'other'       => 'Other'
                    ]
                ];

                return view('App\Views\pages\leave\create', $data);
            }

            return $this->createAction();
        }
        return redirect()->to('error/forbidden');
    }

    private function createAction()
    {

        $data = [
            'emp_no'     => session()->get('emp_no'),
            'leave_date' => $this->request->getPost('leave_date'),
            'reason'     => $this->request->getPost('reason'),
            'purpose'    => $this->request->getPost('purpose')
        ];

        if(in_group('team-lead'))
        {
            $data['initial_app'] = 1;
            $data['rev_by']      = session()->get('name');
        }

        if($this->validate($this->leaveModel->getValidationRules()))
        {

            try {
                if ($this->leaveModel->insert($data)) {
                    session()->set(['message' => 'Request has been created', 'modal' => true, 'type' => 'Success']);
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
        $request = $this->leaveModel->hasRequest($id);

        if(strcmp($this->request->getMethod(true), 'GET') === 0)
        {
            $data = [
                'title'      => 'Update Leave Request',
                'request'    => $this->leaveModel->select('id ,leave_date, reason, purpose')->where('id', $id)->find()[0],
                'validation' => $validator,
                'crumb'      => [
                    0 => [
                        'title' => 'Leave Request',
                        'url'   => base_url('leave')
                    ],
                    1 => [
                        'title' => 'Update',
                        'url'   => base_url('leave/update/'. $id)
                    ],
                    2 => [
                        'title' => $id,
                        'url'   => null
                    ]
                ],
                'type'    => [
                    'emergency'   => 'Emergency',
                    'vacation'    => 'Vacation',
                    'undertime'   => 'Undertime',
                    'birthday'    => 'Birthday',
                    'other'       => 'Other'
                ],
            ];

            if($request !== null)
            {
                if($request['exist'])
                {
                   if(!$request['editable'])
                       return redirect()->to('leave/view/'. $id);
                   else
                   {
                       if($request['approval'])
                           return $this->show($id, true);
                       else
                           return view('App\Views\pages\leave\update', $data);
                   }
                }
            }
            return redirect()->to('errors/forbidden');
        }

        return $this->updateAction($id, $request);
    }

    private function updateAction($id, $request)
    {
        $data = [
            'leave_date' => $this->request->getPost('leave_date'),
            'reason'     => $this->request->getPost('reason'),
            'purpose'    => $this->request->getPost('purpose')
        ];

        $rules    = $this->leaveModel->getValidationRules();
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
               unset($rules['reason'], $rules['purpose'], $rules['leave_date']);
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
                unset($rules['reason'], $rules['purpose'], $rules['leave_date']);
            }
        }

        if($this->validate($rules))
        {
            try {
                if ($this->leaveModel->update($id, $data)) {
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
            if($this->leaveModel->update($id, ['status' => 2]))
            {
                return json_encode(['message' => 'Request has been cancelled', 'modal' => true, 'type' => 'Successful']);
            }
            return json_encode(['message' => 'Failed to cancel request', 'modal' => true, 'type' => 'Unsuccessful']);
        } catch (\ReflectionException $e) {
            return $e;
        }
    }


}
