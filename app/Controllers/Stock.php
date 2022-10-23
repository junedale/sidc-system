<?php

namespace App\Controllers;


use App\Models\SrItemModel;
use App\Models\StockModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;


class Stock extends BaseController
{

    protected $helpers = ['form', 'html', 'user', 'request'];
    protected StockModel  $stockModel;
    protected SrItemModel $itemModel;

    public function __construct()
    {
        $this->stockModel = model(StockModel::class);
        $this->itemModel  = model(SrItemModel::class);
    }

    public function index()
    {
        $stockModel = model(StockModel::class);

        $data = [
            'title'     => 'Stock Request',
            'stats'     => $stockModel->getStats(session()->get('emp_no')),
            'crumb'     => [
                0  => [
                    'title'  => 'Stock Request',
                    'url'    => 'stock'
                ]
            ],
        ];

        return view('App\Views\pages\stock\home', $data);
    }

    /** @noinspection PhpParamsInspection */
    public function search()
    {
        return DataTable::of($this->stockModel->getAllRequest(session()->get('emp_no')))
            ->edit('status', function($row) {
                return to_status($row->status);
            })
            ->edit('date_added', function($row) {
                return Time::parse($row->date_added)->toLocalizedString('MMMM d, yyyy');
            })
            ->setSearchableColumns(['id', 'name'])
            ->toJson(true);
    }

    public function show($id = null, $update = null)
    {

        if(!(has_permission('approval.stock.initial') || has_permission('approval.stock.final')))
        {
            if(in_group('team-lead') || in_group('tsr'))
            {
                if(!$this->stockModel->hasRequest($id))
                {
                    return redirect()->to('error/forbidden');
                }
            }
        }

        $data = [
            'title'     => 'View Stock Request',
            'items'     => $this->itemModel->where('request_id', $id)->findAll(),
            'request'   => $this->stockModel->getRequest($id),
            'crumb'     => [
                0  => [
                    'title'  => 'Stock Request',
                    'url'    => 'stock'
                ],
                1  => [
                    'title'  => 'View',
                    'url'    => 'stock/view/'. $id
                ],
                2  => [
                    'title'  => $id,
                    'url'    => null
                ],
            ],
        ];

        if($update === true)
        {
            $data['title']             = 'Update Stock Request';
            $data['approval']          = true;
            $data['crumb'][1]['title'] = 'Update';
            $data['crumb'][1]['url']   = 'stock/update/'. $id;

        }

        return view('App\Views\pages\stock\details', $data);
    }

    public function create($validator = null)
    {

        if(has_permission('form.create'))
        {
            if(strcmp($this->request->getMethod(true), 'GET') === 0)
            {

                $data = [
                    'title'        => 'Create Stock Request',
                    'validation'   => $validator,
                    'crumb'        => [
                        0  => [
                            'title'  => 'Stock Request',
                            'url'    => 'stock'
                        ],
                        1  => [
                            'title'  => 'Create',
                            'url'    => null
                        ],
                    ],
                ];

                return view('App\Views\pages\stock\create', $data);
            } else {
                return $this->createAction();
            }
        }

        return 'Forbidden';
    }

    private function createAction()
    {
        $stockData = [
            'emp_no'  => session()->get('emp_no'),
            'purpose' => $this->request->getPost('purpose')
        ];

        $itemData = [
            'request_id'      => null,
            'item_name'       => $this->request->getPost('item_name'),
            'quantity'        => $this->request->getPost('quantity')
        ];

        if($this->validate($this->stockModel->getValidationRules()))
        {
            try {
                if($this->stockModel->insert($stockData))
                {
                    $itemData['request_id'] = $this->stockModel->getInsertID();
                    if($this->validate($this->itemModel->getValidationRules()))
                    {
                        if($this->itemModel->insert($itemData))
                        {
                            session()->set(['message' => 'Request has been created', 'modal' => true, 'type' => 'Success']);
                            return redirect()->to(base_url('stock/update/'. $itemData['request_id']));
                        }
                        session()->set(['message' => 'Failed to create request', 'modal' => true, 'type' => 'Unsuccessful']);
                        return  redirect()->to(current_url());
                    } else
                    {
                        $this->stockModel->where('id', $itemData['request_id'])->delete();
                    }
                }
            } catch (\ReflectionException $e) {
                return $e;
            }
        }

        $this->request->setMethod('GET');
        return $this->create($this->validator);
    }

    public function update($id = null, $validator = null)
    {
        if(strcmp($this->request->getMethod(true), 'GET') === 0)
        {
            if(has_permission('form.edit'))
            {
                if(has_permission('approval.stock.initial') || has_permission('approval.stock.final'))
                {
                    $status = $this->stockModel->getRequestStatus($id);

                    if($status === false)
                    {
                        return $this->show($id, true);
                    }

                    return redirect()->to('stock/view/'. $id);
                } elseif(in_group('tsr'))
                {

                    if($this->stockModel->hasRequest($id))
                    {
                        if($this->stockModel->getRequestStatus($id) === false)
                        {
                            $data = [
                                'title'      => 'Update Stock Request',
                                'request'    => $this->stockModel->select('id, purpose')->where('id', $id)->find()[0],
                                'items'      => $this->itemModel->where('request_id', $id)->findAll(),
                                'validation' => $validator,
                                'crumb'      => [
                                    0  => [
                                        'title'  => 'Stock Request',
                                        'url'    => 'stock'
                                    ],
                                    1  => [
                                        'title'  => 'Update',
                                        'url'    => null
                                    ],
                                ],
                            ];

                            return view('App\Views\pages\stock\update', $data);
                        }

                        return redirect()->to('stock/view/'. $id);
                    }
                    return redirect()->to('error/forbidden');
                } else
                {
                    return redirect()->to('stock/view/'. $id);
                }
            }

            return redirect()->to('error/forbidden');
        } else
        {
            if(has_permission('form.edit'))
            {
                if(has_permission('approval.stock.initial'))
                {
                    $approval = $this->request->getPost('approval');

                    $data = [
                        'rev_by'       => session()->get('name'),
                        'initial_app'  => $approval,
                        'status'       => $approval === null ? null :
                                          ((int) $approval === 0 ? 0 : null),
                    ];
                } elseif(has_permission('approval.stock.final'))
                {
                    $approval = $this->request->getPost('approval');

                    $data = [
                        'app_by'       => session()->get('name'),
                        'final_app'    => $approval,
                        'status'       => $approval
                    ];
                } elseif(in_group('tsr'))
                {
                    $stockData = [
                        'purpose' => $this->request->getPost('purpose')
                    ];

                    $itemData = [
                        'request_id'      => $id,
                        'item_name'       => $this->request->getPost('item_name'),
                        'quantity'        => $this->request->getPost('quantity')
                    ];

                    $data = ['stockData' => $stockData, 'itemData' => $itemData];
                } else {
                    return redirect()->to('error/forbidden');
                }

                return $this->updateAction($id, $data);
            }
        }

        return 'Bad Request';
    }

    private function updateAction($id, array $data)
    {
        $rules = $this->stockModel->getValidationRules();

        if(array_key_exists('stockData', $data) && array_key_exists('itemData', $data))
        {
            if($this->validate($rules))
            {
                try {
                    if ($this->stockModel->update($id, $data['stockData'])) {
                        $rules = $this->itemModel->getValidationRules();
                        if($this->validate($rules)) {
                            if($this->itemModel->insert($data['itemData']))
                            {
                                session()->set(['message' => 'Item has been added', 'modal' => true, 'type' => 'Success']);
                                return redirect()->to(current_url());
                            }
                            session()->set(['message' => 'Failed to add item', 'modal' => true, 'type' => 'Unsuccessful']);
                            return redirect()->to(current_url());
                        }
                    }
                } catch (\ReflectionException $e) {
                    return $e;
                }
            }
        } else
        {
            unset($rules['purpose']);
            if($this->validate($rules))
            {
                try {
                    if($this->stockModel->update($id, $data))
                    {
                        session()->set(['message' => 'Request has been updated', 'modal' => true, 'type' => 'Success']);
                        return redirect()->to(current_url());
                    }
                    session()->set(['message' => 'Failed to update request', 'modal' => true, 'type' => 'Unsuccessful']);
                    return redirect()->to(current_url());
                } catch (\ReflectionException $e) {
                    return $e;
                }
            }
        }
        $this->request->setMethod('GET');
        return $this->update($id, $this->validator);
    }

    public function delete($id)
    {
        if($this->itemModel->where('id', $id)->delete())
        {
            return json_encode(['message' => 'Item has been deleted', 'modal' => true, 'type' => 'Unsuccessful']);
        }
        return json_encode(['message' => 'Failed to delete item', 'modal' => true, 'type' => 'Unsuccessful']);
    }

    public function cancel($id)
    {
        if($this->stockModel->hasRequest($id))
        {
            try {
                if($this->stockModel->update($id, ['status' => 2])) {
                  return json_encode(['message' => 'Request has been cancelled', 'modal' => true, 'type' => 'Successful']);
                }
                return json_encode(['message' => 'Failed to cancel request', 'modal' => true, 'type' => 'Unsuccessful']);
            } catch (\ReflectionException $e) {
                return $e;
            }
        }
        return redirect()->to('error/forbidden');
    }
}
