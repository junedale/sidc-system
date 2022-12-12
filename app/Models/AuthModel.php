<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'auth_users';
    protected $primaryKey       = 'emp_no';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_no',
        'username',
        'email',
        'password',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'emp_no'                    => [
            'label'    => 'Employee Number',
            'rules'    => 'required|numeric|is_unique[auth_users.emp_no,emp_no,{emp_no}]',
            'errors'   => [
                'required'              => 'The employee number field is required',
                'numeric'               => 'Employee number must only contain numeric characters',
                'is_unique'             => 'Employee number must be unique'
            ]
        ],
        'username'                  => [
            'label'    => 'Username',
            'rules'    => 'required|regex_match[/\A[a-zA-Z0-9\.]+\z/]|is_unique[auth_users.username,emp_no,{emp_no}]',
            'errors'   => [
                'required'              => 'The username field is required',
                'regex_match'           => 'Username must only contain alpha numeric characters',
                'is_unique'             => 'Username must be unique'
            ]
        ],
        'email'                     => [
            'label'    => 'Email',
            'rules'    => 'required|valid_email|is_unique[auth_users.email,emp_no,{emp_no}]',
            'errors'   => [
                'required'              => 'The email field is required',
                'valid_email'           => 'Email must be unique',
                'is_unique'             => 'Email must be unique'
            ]
        ],
        'password'                  => [
            'label'    => 'Password',
            'rules'    => 'required|min_length[8]|alpha_numeric_punct',
            'errors'   => [
                'required'              => 'The password field is required',
                'min_length'            => 'Password must contain at least 8 characters',
                'alpha_numeric_punct'   => 'Purpose must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ],
        'status'                    => [
            'label'    => 'Status',
            'rules'    => 'permit_empty|numeric',
            'errors'   => [
                'required'              => 'The status field is required',
                'numeric'               => 'Status must only contain numeric characters'
            ]
        ],
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    protected function hashPassword($data)
    {
        if(empty($data['data']['password']))
        {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }

    public function validateCredentials($data): bool
    {
        $cred = $this->select('emp_no, username, password')
            ->where('username', $data['username'])
            ->find();

        if(!empty($cred))
        {
            if(password_verify($data['password'], $cred[0]['password']))
            {

                $user = $this->db->table('auth_user_details')
                    ->select('emp_no, name, user_group')
                    ->where('emp_no', $cred[0]['emp_no'])
                    ->get(1)->getResultArray()[0];

                $rawPermissions = $this->db->table('user_permissions')
                    ->select('permission')
                    ->where('emp_no', $user['emp_no'])
                    ->get()->getResultArray();

                $permissions = [];
                foreach ($rawPermissions as $permission) {
                    $permissions[] = $permission['permission'];
                }

                session()->set($user);
                session()->set('isLoggedIn', true);
                session()->set('permissions', $permissions);

                return true;
            }
        }

        return false;
    }

    public function getAllUsers(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table('auth_users')->select('auth_users.emp_no, name, username, email, auth_users.date_added')
                ->join('auth_user_details', 'auth_users.emp_no = auth_user_details.emp_no');
    }

    public function getUser($id)
    {
        return $this->select('auth_users.emp_no, name, username, email, department, position, superior, user_group')
                ->join('auth_user_details', 'auth_users.emp_no = auth_user_details.emp_no')
                ->where('auth_users.emp_no', $id)
                ->find()[0];
    }

    public function getStats()
    {
        return [
            'total'  => $this->db->table('auth_user_details')->countAll(),
            'active' => $this->where('status', 1)->countAllResults()
        ];
    }
}
