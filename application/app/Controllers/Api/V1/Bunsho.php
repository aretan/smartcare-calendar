<?php namespace App\Controllers\Api\V1;

class Bunsho extends ApiController
{
    /**
     * テーブルにレコードを挿入する関数
     * POST /api/v1/{controller}/create
     * POST /api/v1/{controller}
     */
    public function create()
    {
        $request = $this->request->getJSON(true);
        if (!$request) {
            $request = $this->request->getPost();
        }

        $data = array_merge($this->_getParentId($this), $request);
        $this->_getModel($this)->insert($data);

        if ($this->request->isAJAX()) {
            return $this->respondCreated();
        } else {
            return redirect()->to("/{$data['shoken_id']}");
        }
    }
}
