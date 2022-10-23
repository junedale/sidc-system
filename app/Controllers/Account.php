<?php

namespace App\Controllers;

use App\Models\AuthModel;

class Account extends BaseController
{

    protected $helpers  = ['html', 'form', 'user'];
    protected AuthModel $authModel;

    public function __construct()
    {
        $this->authModel = model(AuthModel::class);
    }

    public function login($validator = null)
    {
        if(strcmp($this->request->getMethod(true), 'GET') === 0)
        {
            $data = [
                'title'      => 'SIDC Login',
                'validation' => $validator
            ];

            return view('App\Views\pages\auth\login', $data);
        } else
        {
            return $this->loginAction();
        }
    }

    public function loginAction()
    {
        $rules     = $this->authModel->getValidationRules();

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password')
        ];

        $rules['username'] = 'required';
        unset($rules['email'], $rules['status'], $rules['emp_no']);

        if($this->validate($rules))
        {
            if($this->authModel->validateCredentials($data))
            {
                if(!in_group('admin'))
                {
                    return redirect()->to('stock');
                }
                return redirect()->to('admin');
            }
            $this->request->setMethod('GET');
            session()->setFlashdata(['message' => 'Username or password is incorrect']);
            return $this->login();
        }

        return $this->login($this->validator);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    public function settings($validator = null)
    {
        if(strcmp($this->request->getMethod('true'), 'GET') === 0)
        {
            $data = [
                'title'      => 'Account Settings',
                'user'       => $this->authModel->select('username, email')->where('emp_no', session()->get('emp_no'))->find()[0],
                'validation' => $validator,
                'crumb'      => [
                    0 => [
                        'title' => 'Account Settings',
                        'url'   => base_url('settings')
                    ],
                ]
            ];

            return view('App\Views\pages\account\account', $data);
        }

        return $this->settingsAction();
    }

    private function settingsAction()
    {
        $rules = $this->authModel->getValidationRules();

        $data = [
            'emp_no'     => session()->get('emp_no'),
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'status'     => 1
        ];

        $rules['c_password'] = [
            'label'          => 'Confirm Password',
            'rules'          => 'required_with[password]|min_length[8]|alpha_numeric_punct',
            'errors'         => [
                'required_with' => 'Confirm Password field is required with Password field',
                'min_length'    => 'Password must be at least 8 characters',
                'alpha_numeric_punct' => 'Purpose must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ];

        $rules['username']['rules'] = 'required|regex_match[/\A[a-zA-Z0-9\.]+\z/]|is_unique[auth_users.username,emp_no,'. session()->get('emp_no') .']';
        $rules['email']['rules']    = 'required|valid_email|is_unique[auth_users.email,emp_no,'. session()->get('emp_no') .']';

        unset($rules['status'], $rules['emp_no']);
        if(empty($data['password']))
        {
            unset($rules['password'], $rules['c_password'], $data['password']);
        }

        if($this->validate($rules))
        {
            try {
                if($this->authModel->save($data)) {
                    session()->set(['message' => 'Account has been updated', 'modal' => true, 'type' => 'Success']);
                    return redirect()->to('settings');
                }
                session()->set(['message' => 'Failed to update account', 'modal' => true, 'type' => 'Unsuccessful']);
                return redirect()->to('settings');
            } catch (\ReflectionException $e) {
                return $e;
            }
        }

        $this->request->setMethod('GET');
        return $this->settings($this->validator);

    }
}
