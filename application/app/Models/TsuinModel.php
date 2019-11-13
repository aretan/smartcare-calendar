<?php namespace App\Models;

class TsuinModel extends BaseModel
{
    protected $table = 'tsuin';
    protected $allowedFields = ['shoken_id', 'ukeban_id', 'date'];
    protected $validationRules = [
        'date' => 'required',
    ];
}
