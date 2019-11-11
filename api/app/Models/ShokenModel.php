<?php namespace App\Models;

class ShokenModel extends BaseModel
{
    protected $table = 'shoken';
    protected $allowedFields = ['id', 'result_id', 'name', 'birthday', 'comment'];
}
