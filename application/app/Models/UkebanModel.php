<?php namespace App\Models;

class UkebanModel extends BaseModel
{
    protected $table = 'ukeban';
    protected $allowedFields = ['id', 'shoken_id'];
    protected $validationRules    = [
        'id'        => [
            'label' => '受付番号',
            'rules' => 'required|alpha_numeric|exact_length[14]',
        ],
        'shoken_id' => [
            'label' => '証券番号',
            'rules' => 'required|numeric|exact_length[9]',
        ],
    ];
}
