<?php namespace App\Models;

class BunshoModel extends BaseModel
{
    protected $table = 'bunsho';
    protected $allowedFields = ['shoken_id', 'ukeban_id', 'date'];
    protected $validationRules = [
        'date' => 'required',
    ];
}
