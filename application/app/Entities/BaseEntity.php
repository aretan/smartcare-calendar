<?php namespace App\Entities;

use CodeIgniter\Entity;

class BaseEntity extends Entity
{
    public function getStart()
    {
        return str_replace('-', '/', $this->attributes['start']);
    }
    public function getEnd()
    {
        return str_replace('-', '/', $this->attributes['end']);
    }
    public function getDate()
    {
        return str_replace('-', '/', $this->attributes['date']);
    }
}
