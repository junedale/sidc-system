<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'auth_user_details';
    protected $primaryKey       = 'emp_no';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_no',
        'name',
        'position',
        'department',
        'superior',
        'user_group'
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
            'label'        => 'Employee Number',
            'rules'        => 'required|numeric|is_unique[auth_user_details.emp_no,emp_no,{emp_no}]',
            'errors'       => [
                'required'     => 'Employee number field is required',
                'numeric'      => 'Employee number must only contain numeric characters',
                'is_unique'    => 'Employee number must be unique'
            ]
        ],
        'name'                      => [
            'label'        => 'Employee Name',
            'rules'        => 'required|alpha_space|min_length[4]',
            'errors'       => [
                'required'     => 'Name field is required',
                'alpha_space'  => 'Name must only contain alphabetic characters and spaces',
                'min_length'   => 'Name must contain at least four characters'
            ]
        ],
        'position'                   => [
            'label'            => 'Position',
            'rules'            => 'required|alpha_space',
            'errors'           => [
                'required'         => 'Position id field is required',
                'alpha_space'      => 'Position must only contain alphabetic characters and spaces'
            ]
        ],
        'department'                => [
            'label'            => 'Department',
            'rules'            => 'required|alpha_space',
            'errors'           => [
                'required'         => 'Department id field is required',
                'alpha_space'      => 'Department must only contain alphabetic characters and spaces'
            ]
        ],
        'superior'                    => [
            'label'            => 'Superior',
            'rules'            => 'permit_empty|numeric',
            'errors'           => [
                'numeric'          => 'Superior must only contain numeric characters'
            ]
        ],
        'user_group'                    => [
            'label'            => 'Superior',
            'rules'            => 'required|alpha_numeric_punct',
            'errors'           => [
                'required'         => 'User group field is required',
                'alpha_numeric_punct' => 'User group must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ],
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getSuperiors(): array
    {
        $data = $this->select('name, emp_no')
                ->where('user_group', 'manager')
                ->orWhere('user_group', 'team-lead')
                ->orderBy('department', 'ASC')
                ->findAll();

        $list = ['0' => 'None'];

        if(empty($data))
            return $list;

        foreach($data as $superior)
        {
            $list[$superior['emp_no']] = $superior['name'];
        }

        return $list;
    }

}
