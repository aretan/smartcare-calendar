<?php namespace App\Models;

class NyuinModel extends BaseModel
{
    protected $table = 'nyuin';
    protected $allowedFields = ['shoken_id', 'ukeban_id', 'start', 'end', 'warrantyStart', 'warrantyEnd', 'warrantyMax'];
    protected $validationRules = [
        'start' => 'required',
        'end' => 'required',
   ];
}
