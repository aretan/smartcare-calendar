<?php namespace App\Controllers\Api\V1;

class Shujutsu extends ApiController
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
            $request = $this->smartcare->addShujutsuWarranty($request);
        }

        $data = array_merge($this->_getParentId($this), $request);
        $model = $this->_getModel($this);
        $model->insert($data);
        $data['id'] = $model->insertID();

        if ($this->request->isAJAX()) {
            return $this->respondCreated($data);
        } else {
            return redirect()->to("/{$data['shoken_id']}/");
        }
    }
}
