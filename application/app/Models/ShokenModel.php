<?php namespace App\Models;

class ShokenModel extends BaseModel
{
    protected $table = 'shoken';
    protected $allowedFields = ['id', 'name', 'date', 'comment'];

    protected $validationRules    = [
        'id'      => 'required|regex_match[/[0-9]{3}-[0-9]{7}/]',
        'name'    => 'required|max_length[255]',
        'date'    => 'required|regex_match[/[0-9]{4}[\/-][0-9]{2}[\/-][0-9]{2}/]',
        'comment' => 'max_length[65535]'
    ];

    protected $validationMessages = [
        'id' => [
            'regex_match' => '証券番号は 000-000000 の形式で入力してください',
        ],
        'date' => [
            'regex_match' => '契約日は 0000/00/00 の形式で入力してください',
        ],
    ];
}
