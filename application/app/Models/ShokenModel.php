<?php namespace App\Models;

class ShokenModel extends BaseModel
{
    protected $table = 'shoken';
    protected $allowedFields = ['id', 'name', 'date', 'comment'];

    protected $validationRules    = [
        'id'      => [
            'label' => '証券番号',
            'rules' => 'required|numeric|exact_length[9]',
        ],
        'name'    => [
            'label' => '被保険者名',
            'rules' => 'required|max_length[255]',
        ],
        'date'    => [
            'label' => '受付日',
            'rules' => 'required|regex_match[/[0-9]{4}[\/-][0-9]{2}[\/-][0-9]{2}/]',
        ],
        'comment' => [
            'label' => '査定者メモ',
            'rules' => 'max_length[65535]',
        ],
    ];
}
