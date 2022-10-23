<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\Pemission;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use Config\Department;
use Config\JobTitle;
use Config\UserGroup;
use Hermawan\DataTables\DataTable;

class Admin extends BaseController
{

    protected $helpers = ['form', 'html', 'user'];
    protected AuthModel $authModel;
    protected UserModel $userModel;


    public function __construct()
    {
        $this->authModel = model(AuthModel::class);
        $this->userModel = model(UserModel::class);
    }

    public function index()
    {
        $data = [
            'title' => 'Admin',
            'stats' => $this->authModel->getStats(),
            'crumb' => [
                0  => [
                    'title'  => 'Admin Settings',
                    'url'    => null
                ],
            ]
        ];

        return view('App\Views\pages\admin\home', $data);
    }

    /** @noinspection PhpParamsInspection */
    public function search()
    {

        return DataTable::of($this->authModel->getAllUsers())
            ->edit('date_added', function($row) {
                return Time::parse($row->date_added)->toLocalizedString('MMMM d, yyyy');
            })
            ->setSearchableColumns(['username', 'name', 'auth_users.emp_no'])
            ->toJson(true);
    }

    public function create($validator = null)
    {
        if(has_permission('user.create'))
        {
            if(strcmp($this->request->getMethod(true), "GET") === 0)
            {
                $data = [
                    'title'      => 'Create User Account',
                    'validation' => $validator,
                    'crumb'      => [
                        0  => [
                            'title'  => 'Admin Settings',
                            'url'    => 'admin'
                        ],
                        1  => [
                            'title'  => 'Create',
                            'url'    => null
                        ],
                    ],
                    'position'   => config(JobTitle::class)->jobTitle,
                    'department' => config(Department::class)->department,
                    'group'      => config(UserGroup::class)->userGroup,
                    'superior'   => $this->userModel->getSuperiors()
                ];

                return view('App\Views\pages\admin\create', $data);
            }
            return $this->createAction();
        }
        return redirect()->to('error/forbidden');
    }

    private function createAction()
    {
        $permissionModel = model(Pemission::class);
        $emp_no              = $this->request->getPost('emp_no');

        $authData = [
            'emp_no'   => $emp_no,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => 'Password',
            'status'   => 1
        ];

        $userData = [
            'emp_no'     => $emp_no,
            'name'       => $this->request->getPost('name'),
            'user_group' => $this->request->getPost('user_group'),
            'superior'   => $this->request->getPost('superior'),
            'department' => $this->request->getPost('department'),
            'position'   => $this->request->getPost('position')
        ];


        $rawPermission  = $this->request->getPost('permissions');


        $rules = $this->authModel->getValidationRules();
        $rules['emp_no']['rules']   = 'required|is_unique[auth_users.emp_no,emp_no,{'. $emp_no .'}]';
        $rules['email']['rules']    = 'required|valid_email|is_unique[auth_users.email,emp_no,{'. $emp_no .'}]';
        $rules['username']['rules'] = 'required|regex_match[/\A[a-zA-Z0-9\.]+\z/]|is_unique[auth_users.username,emp_no,{'. $emp_no .'}]';
        unset($rules['password']);

        if($this->validate($rules))
        {
            try {
                if ($this->authModel->insert($authData)) {
                    $rules = $this->userModel->getValidationRules();
                    if($this->validate($rules))
                    {
                        if($this->userModel->insert($userData))
                        {

                            for($i = 0; $i < sizeof($rawPermission); $i++)
                            {
                                $permissionData = [
                                    'emp_no'     => $emp_no,
                                    'permission' => $rawPermission[$i]
                                ];

                                if(!$permissionModel->insert($permissionData))
                                {
                                    session()->set(['message' => 'Failed to add permission', 'modal' => true, 'type' => 'Unsuccessful']);
                                    return redirect()->to(current_url());
                                }
                            }
                            session()->set(['message' => 'Account has been created', 'modal' => true, 'type' => 'Success']);
                            return redirect()->to(current_url());
                        }
                    }
                    $permissionModel->where('emp_no', $emp_no)->delete();
                    $this->userModel->where('emp_no', $emp_no)->delete();
                    $this->authModel->where('emp_no', $emp_no)->delete();
                    $this->request->setMethod('GET');
                    return $this->create($this->validator);
                }
                session()->set(['message' => 'Failed to create user account', 'modal' => true, 'type' => 'Unsuccessful']);
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
        if(has_permission('user.edit'))
        {
            if(strcmp($this->request->getMethod(true), 'GET') === 0)
            {
                $data = [
                    'title'      => 'Update User Account',
                    'user'       => $this->authModel->getUser($id),
                    'validation' => $validator,
                    'crumb'      => [
                        0  => [
                            'title'  => 'Admin Settings',
                            'url'    => 'admin'
                        ],
                        1  => [
                            'title'  => 'Update',
                            'url'    => current_url()
                        ],
                        2  => [
                            'title'  => $id,
                            'url'    => null
                        ],
                    ],
                    'position'   => config(JobTitle::class)->jobTitle,
                    'department' => config(Department::class)->department,
                    'group'      => config(UserGroup::class)->userGroup,
                    'permission' => model(Pemission::class)->select('permission')->where('emp_no', $id)->findAll(),
                    'superior'   => $this->userModel->getSuperiors()
                ];
                return view('App\Views\pages\admin\update', $data);
            }
            return $this->updateAction($id);
        }
        return redirect()->to('error/forbidden');
    }

    private function updateAction($id)
    {
        $permissionModel = model(Pemission::class);

        $authData = [
            'emp_no'   => $id,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];

        $userData = [
            'emp_no'   => $id,
            'name'       => $this->request->getPost('name'),
            'user_group' => $this->request->getPost('user_group'),
            'superior'   => $this->request->getPost('superior'),
            'department' => $this->request->getPost('department'),
            'position'   => $this->request->getPost('position')
        ];

        $rawPermission  = $this->request->getPost('permissions');

        $rules = $this->authModel->getValidationRules();
        $rules['emp_no']['rules']   = 'required|is_unique[auth_users.emp_no,emp_no,'. $id .']';
        $rules['email']['rules']    = 'required|valid_email|is_unique[auth_users.email,emp_no,'. $id .']';
        $rules['username']['rules'] = 'required|regex_match[/\A[a-zA-Z0-9\.]+\z/]|is_unique[auth_users.username,emp_no,'. $id .']';
        unset($rules['password']);


        if($this->validate($rules))
        {
            try {
                if ($this->authModel->update($id, $authData)) {
                    $rules = $this->userModel->getValidationRules();
                    if($this->validate($rules))
                    {
                        if($this->userModel->update($id, $userData))
                        {
                            $permissionModel->where('emp_no', $id)->delete();
                            for($i = 0; $i < sizeof($rawPermission); $i++)
                            {
                                $permissionData = [
                                    'emp_no'     => $id,
                                    'permission' => $rawPermission[$i]
                                ];

                                if(!$permissionModel->insert($permissionData))
                                {
                                    session()->set(['message' => 'Failed to update permission', 'modal' => true, 'type' => 'Unsuccessful']);
                                    return redirect()->to(current_url());
                                }
                            }
                            session()->set(['message' => 'Account has been updated successfully', 'modal' => true, 'type' => 'Success']);
                            return redirect()->to(current_url());
                        }
                    }
                    $this->request->setMethod('GET');
                    return $this->update($id, $this->validator);
                }
                session()->set(['message' => 'Failed to update account', 'modal' => true, 'type' => 'Unsuccessful']);
                return redirect()->to(current_url());
            } catch (\ReflectionException $e) {
                return $e;
            }
        }

        $this->request->setMethod('GET');
        return $this->update($id, $this->validator);
    }

    public function reset($id)
    {
        try {
            if($this->authModel->update($id, ['password' => 'Password']))
            {
                return json_encode(['message' => 'Password has been reset successfully', 'modal' => true, 'type' => 'Successful']);
            }
            return json_encode(['message' => 'Failed to reset password', 'modal' => true, 'type' => 'Unsuccessful']);
        } catch (\ReflectionException $e) {
            return $e;
        }
    }
}
