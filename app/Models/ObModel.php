<?php

namespace App\Models;

class ObModel extends RequestModel
{
    protected $DBGroup          = 'default';
    protected $table            = 'ob_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_no',
        'remarks',
        'transit',
        'rev_by',
        'initial_app',
        'app_by',
        'final_app',
        'status',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'emp_no'                    => [
            'label'    => 'Employee Number',
            'rules'    => 'permit_empty|numeric|is_unique[auth_users.emp_no,emp_no,{emp_no}]',
            'errors'   => [
                'numeric'               => 'Employee number must only contain numeric characters',
                'is_unique'             => 'Employee number must be unique'
            ]
        ],
        'remarks'                   => [
            'label'        => 'Remarks',
            'rules'        => 'permit_empty|alpha_numeric_punct',
            'errors'       => [
                'alpha_numeric_punct' => 'Remarks must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ],
        'transit'                    => [
            'label'        => 'Transit',
            'rules'        => 'permit_empty|alpha_space',
            'errors'       => [
                'alpha_space'  => 'Transit must only contain Alphabetic characters and space'
            ]
        ],
        'rev_by'                      => [
            'label'        => 'Reviewed by',
            'rules'        => 'permit_empty|alpha_space|min_length[4]',
            'errors'       => [
                'alpha_space'  => 'Reviewed by must only contain alphabetic characters and spaces',
                'min_length'   => 'Reviewed by must contain at least four characters'
            ]
        ],
        'app_by'                      => [
            'label'        => 'Approved by',
            'rules'        => 'permit_empty|alpha_space|min_length[4]',
            'errors'       => [
                'alpha_space'  => 'Approved by must only contain alphabetic characters and spaces',
                'min_length'   => 'Approved by must contain at least four characters'
            ]
        ],
        'initial_app'                 => [
            'label'        => 'Initial Approval',
            'rules'        => 'permit_empty|numeric',
            'errors'       => [
                'numeric'  => 'Final approval must only contain numeric characters'
            ]
        ],
        'final_app'                   => [
            'label'        => 'Final Approval',
            'rules'        => 'permit_empty|numeric',
            'errors'       => [
                'numeric'  => 'final approval must only contain numeric characters'
            ]
        ],
        'status'                     => [
            'label'        => 'Status',
            'rules'        => 'permit_empty|numeric',
            'errors'       => [
                'numeric'  => 'Status must only contain numeric characters'
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

    public function getAllRequest(string $id): \CodeIgniter\Database\BaseBuilder
    {
        if(in_group('manager'))
        {
            $data = $this->db->table('ob_requests')->select('ob_requests.id, name, ob_requests.date_added, status')
                ->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no');
        } elseif(in_group('teamlead') || in_group('team-lead'))
        {
            $data = $this->db->table('ob_requests')->select('ob_requests.id, name, ob_requests.date_added, status')
                ->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')
                ->where('ob_requests.emp_no', $id)
                ->orWhere('superior', $id);
        } else
        {
            $data = $this->db->table('ob_requests')->select('ob_requests.id, name, ob_requests.date_added, status')
                ->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')
                ->where('ob_requests.emp_no', $id);
        }

        return $data;
    }

    public function getRequest(int $id): array
    {
        return $this->select('ob_requests.id, name, department, ob_requests.date_added, transit, app_by, rev_by, initial_app, final_app, status')
                ->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')
                ->where('ob_requests.id', $id)
                ->find()[0];
    }

    public function hasRequest(string $id): ?array
    {
        $request = $this->select('ob_requests.emp_no, superior, initial_app, final_app, status')->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where('id', $id)->find();

        if(empty($request))
        {
            return null;
        }

       if(in_group('manager'))
       {
            if(strcmp($request[0]['emp_no'], session()->get('emp_no')) !== 0)
            {
                if((int) $request[0]['status'] === 2 || $request[0]['initial_app'] == null || ($request[0]['final_app'] !== null))
                    return ['approval' => true, 'editable' => false, 'exist' => true];
                else
                    return ['approval' => true, 'editable' => true, 'exist' => true];
            } else
            {
                if((int) $request[0]['status'] !== 2)
                    return ['approval' => false, 'editable' => true, 'exist' => true];
                else
                    return ['approval' => false, 'editable' => false, 'exist' => true];
            }

       } elseif(in_group('teamlead') || in_group('team-lead'))
       {
           if(strcmp($request[0]['emp_no'], session()->get('emp_no')) === 0)
           {
               if((int) $request[0]['status'] === 2 || ($request[0]['status'] !== null ? (int) $request[0]['status'] === 0 : null))
                   return ['approval' => false, 'editable' => false, 'exist' => true];
               else
                   return ['approval' => false, 'editable' => true, 'exist' => true];
           } elseif(strcmp($request[0]['superior'], session()->get('emp_no')) === 0)
           {
               if((int) $request[0]['status'] === 2 || $request[0]['initial_app'] !== null)
                   return ['approval' => true, 'editable' => false, 'exist' => true];
               else
                   return ['approval' => true, 'editable' => true, 'exist' => true];
           }
       } elseif(in_group('tsr') || in_group('admin'))
       {
           if(strcmp($request[0]['emp_no'], session()->get('emp_no')) === 0)
           {
               if((int) $request[0]['status'] === 2 || ($request[0]['status'] !== null ? (int) $request[0]['status'] === 0 : null))
                   return ['approval' => false, 'editable' => false, 'exist' => true];
               else
                   return ['approval' => false, 'editable' => true, 'exist' => true];
           }
       }

        return ['approval' => false, 'editable' => false, 'exist' => false];
    }

    public function getStats(string $id): array
    {
        if(in_group('manager'))
        {
            return [
                'total'       => $this->countAll(),
                'approved'    => $this->where('status', 1)->countAllResults(),
                'disapproved' => $this->where('status', 0)->countAllResults(),
                'cancelled'   => $this->where('status', 2)->countAllResults()
            ];
        } elseif(in_group('teamlead') || in_group('team-lead'))
        {
            return [
                'total'       => $this->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where('superior', session()->get('emp_no'))->orWhere('ob_requests.emp_no', $id)->countAllResults(),
                'approved'    => $this->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where(['superior' => $id, 'status' => 1])->countAllResults() +
                                 $this->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where(['ob_requests.emp_no' => $id, 'status' => 1])->countAllResults(),
                'disapproved' => $this->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where(['superior' => $id, 'status' => 0])->countAllResults() +
                                 $this->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where(['ob_requests.emp_no' => $id, 'status' => 0])->countAllResults(),
                'cancelled'   => $this->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where(['superior' => $id, 'status' => 2])->countAllResults() +
                                 $this->join('auth_user_details', 'ob_requests.emp_no = auth_user_details.emp_no')->where(['ob_requests.emp_no' => $id, 'status' => 2])->countAllResults(),
            ];
        } else
        {
            return [
                'total'       => $this->where('emp_no', $id)->countAllResults(),
                'approved'    => $this->where(['emp_no' => $id, 'status' => 1])->countAllResults(),
                'disapproved' => $this->where(['emp_no' => $id, 'status' => 0])->countAllResults(),
                'cancelled'   => $this->where(['emp_no' => $id, 'status' => 2])->countAllResults(),
            ];
        }
    }
}
