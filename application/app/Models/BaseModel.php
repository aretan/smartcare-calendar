<?php namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    public function find($id = null)
    {
        $data = parent::find($id);
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['deleted_at']);
        return $data;
    }

    public function findAll(int $limit = 0, int $offset = 0)
    {
        $data = parent::findAll($limit, $offset);
        foreach ($data as $key => $value) {
            unset($data[$key]['created_at']);
            unset($data[$key]['updated_at']);
            unset($data[$key]['deleted_at']);
        }
        return $data;
    }

    public function getValidation()
    {
        return $this->validation;
    }
}
