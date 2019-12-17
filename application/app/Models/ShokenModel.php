<?php namespace App\Models;

class ShokenModel extends BaseModel
{
    protected $table = 'shoken';
    protected $allowedFields = ['id', 'name', 'comment'];

    protected $validationRules    = [
        'id'      => [
            'label' => '証券番号',
            'rules' => 'required|numeric|exact_length[9]',
        ],
        'name'    => [
            'label' => '被保険者名',
            'rules' => 'required|max_length[255]',
        ],
        'comment' => [
            'label' => '査定者メモ',
            'rules' => 'max_length[65535]',
        ],
    ];
}
