<?php namespace App\Models;

class UkebanModel extends BaseModel
{
    protected $table = 'ukeban';
    protected $allowedFields = ['id', 'shoken_id', 'date'];
    protected $validationRules    = [
        'id'        => 'required|alpha_dash|exact_length[16]',
        'shoken_id' => 'required|regex_match[/[0-9]{3}-[0-9]{6}/]',
        'date'      => 'required|regex_match[/[0-9]{4}[\/-][0-9]{2}[\/-][0-9]{2}/]',
    ];

    protected $validationMessages = [
        'shoken_id' => [
            'regex_match' => '証券番号は 000-000000 の形式で入力してください',
        ],
        'date' => [
            'regex_match' => '契約日は 0000/00/00 の形式で入力してください',
        ],
    ];
}
