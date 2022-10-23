<?php

namespace App\Models;

use CodeIgniter\Model;

class SrItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'stock_request_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'request_id',
        'item_name',
        'quantity'
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
            'label'             => 'Request Id',
            'rules'             => 'permit_empty|numeric',
            'errors'            =>[
                'numeric'       => 'Request Id must only contain numeric numbers'
            ]
        ],
        'item_name'                 => [
            'label'             => 'Item name',
            'rules'             => 'required|alpha_numeric_punct',
            'errors'            => [
                'required'      => 'Item name field is required',
                'alpha_numeric_punct' => 'Item name must only contain alphanumeric, space, and (!,#,%,&,*,-,_,+,=,|,:,.0 characters)'
            ]
        ],
        'quantity'                  => [
            'label'             => 'Quantity',
            'rules'             => 'required|numeric',
            'errors'            => [
                'required'      => 'Quantity field iss required',
                'numeric'       => 'Quantity must only only contain numeric characters'
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
}
