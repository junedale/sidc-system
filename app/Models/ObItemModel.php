<?php

namespace App\Models;

use CodeIgniter\Model;

class ObItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'ob_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'request_id',
        'sched_date',
        'destination',
        'departure',
        'arrival',
        'purpose'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'request_id'                => [
            'label'          => 'Request ID',
            'rules'          => 'permit_empty|numeric',
            'errors'         => [
                'numeric'    => 'Request ID must only contain numeric characters'
            ]
        ],
        'sched_date'                 => [
            'label'          => 'Scheduled Date',
            'rules'          => 'required|valid_date',
            'errors'         => [
                'required'   => 'Scheduled Date field is required',
                'valid_date' => 'Scheduled Date must be a valid date'
            ]
        ],
        'destination'                 => [
            'label'          => 'Destination',
            'rules'          => 'required|alpha_numeric_punct',
            'errors'         => [
                'required'            => 'Destination field is required',
                'alpha_numeric_punct' => 'Reason must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ],
        'departure'                   => [
            'label'          => 'Departure Time',
            'rules'          => 'permit_empty',
            'errors'         => [
                'required'   => 'Departure Time field is required'
            ]
        ],
        'arrival'                     => [
            'label'          => 'Arrival Time',
            'rules'          => 'permit_empty',
            'errors'         => [
                'required'   => 'Arrival Time field is required'
            ]
        ],
        'purpose'                   => [
            'label'        => 'Reason',
            'rules'        => 'required|alpha_numeric_punct',
            'errors'       => [
                'required'            => 'Reason field is required',
                'alpha_numeric_punct' => 'Reason must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ]
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
}
