<?php namespace App\Models;

class ShujutsuModel extends BaseModel
{
    protected $table = 'shujutsu';
    protected $allowedFields = ['shoken_id', 'ukeban_id', 'date', 'warrantyStart', 'warrantyEnd', 'warrantyMax'];
    protected $validationRules = [
        'date' => 'required',
    ];
}
