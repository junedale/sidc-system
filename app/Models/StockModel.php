<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'stock_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_no',
        'purpose',
        'status',
        'rev_by',
        'app_by',
        'initial_app',
        'final_app'
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
            'label'        => 'Employee Number',
            'rules'        => 'permit_empty|numeric',
            'errors'       => [
                'numeric'  => 'Employee number must only contain numeric characters'
            ]
        ],
        'purpose'                   => [
            'label'        => 'Purpose',
            'rules'        => 'required|alpha_numeric_punct',
            'errors'       => [
                'required' => 'Purpose field is required',
                'alpha_numeric_punct' => 'Purpose must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ],
        'status'                    => [
            'label'        => 'Status',
            'rules'        => 'permit_empty|numeric',
            'errors'       => [
                'numeric'  => 'Status must only contain numeric characters'
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

        if(has_permission('approval.stock.initial') || has_permission('approval.stock.final') || in_group('manager'))
        {
            $data = $this->db->table('stock_requests')
                    ->select('stock_requests.id, name, status, stock_requests.date_added')
                    ->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no');
        } elseif(in_group('team-lead'))
        {
            $data = $this->db->table('stock_requests')
                ->select('stock_requests.id, name, status, stock_requests.date_added')
                ->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')
                ->where('superior', $id);
        } else
        {
            $data = $this->db->table('stock_requests')
                ->select('stock_requests.id, name, status, stock_requests.date_added')
                ->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')
                ->where('stock_requests.emp_no', $id);
        }

        return $data;
    }

    public function getRequest(int $id): array
    {
        return $this->select('stock_requests.id, auth_user_details.name, department, stock_requests.date_added, purpose, rev_by, app_by, initial_app, final_app, stock_requests.status')
            ->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')
            ->where('stock_requests.id', $id)
            ->find()[0];
    }

    public function getStats($id): array
    {
        if(has_permission('approval.stock.initial') || has_permission('approval.stock.final') || in_group('manager'))
        {
            return [
                'total'       => $this->countAll(),
                'approved'    => $this->where('status', 1)->countAllResults(),
                'disapproved' => $this->where('status', 0)->countAllResults(),
                'cancelled'   => $this->where('status', 2)->countAllResults()
            ];
        } else
        {
            if(in_group('team-lead'))
            {
                return [
                    'total'       => $this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where('superior', session()->get('emp_no'))->orWhere('stock_requests.emp_no', $id)->countAllResults(),
                    'approved'    => $this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where(['superior' => $id, 'status' => 1])->countAllResults() +
                                     $this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where(['stock_requests.emp_no' => $id, 'status' => 1])->countAllResults(),
                    'disapproved' => $this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where(['superior' => $id, 'status' => 0])->countAllResults() +
                                     $this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where(['stock_requests.emp_no' => $id, 'status' => 0])->countAllResults(),
                    'cancelled'   => $this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where(['superior' => $id, 'status' => 2])->countAllResults() +
                                     $this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where(['stock_requests.emp_no' => $id, 'status' => 2])->countAllResults(),
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

    public function hasRequest($id): bool
    {
        if(in_group('team-lead'))
        {
            return !empty($this->join('auth_user_details', 'stock_requests.emp_no = auth_user_details.emp_no')->where(['stock_requests.id' => $id, 'superior' => session()->get('emp_no')])->find());
        } elseif(in_group('tsr'))
        {
            return !empty($this->where(['id' => $id, 'emp_no' => session()->get('emp_no')])->find());
        }
        return false;
    }

    public function getRequestStatus($id): bool
    {
        $status = $this->select('initial_app, final_app, status')->where('id', $id)->find();

        if(!empty($status))
        {
            if(has_permission('approval.stock.initial'))
            {
                if($status[0]['initial_app'] !== null && $status[0]['status'] !== null)
                {
                    return true;
                }
                return false;
            } elseif(has_permission('approval.stock.final'))
            {
                if($status[0]['initial_app'] !== null && ($status[0]['final_app'] === null && $status[0]['status'] === null))
                {
                    return false;
                }
                return true;
            } elseif(in_group('tsr'))
            {
                if($status[0]['initial_app'] !== null || $status[0]['final_app'] !== null || $status[0]['status'] !== null)
                {
                    return true;
                }
                return false;
            }
        }

        return true;
    }
}
