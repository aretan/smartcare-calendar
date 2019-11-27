<?php namespace App\Controllers\Api\V1;

class Tsuin extends ApiController
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
        $model = $this->_getModel($this);
        $model->insert($data);
        $data['id'] = $model->insertID();

        if ($this->request->isAJAX()) {
            return $this->respondCreated($data);
        } else {
            return redirect()->to("/{$data['shoken_id']}");
        }
    }

    public function batch()
    {
        $data = [];
        foreach (explode("\n", $this->request->getPost('date')) as $date) {
            $date = trim($date);
            if (!$date) continue;
            $request['date'] = $date;

            $data[] = array_merge($this->_getParentId($this), $request);
        }

        $model = $this->_getModel($this);
        $model->insertBatch($data);

        return redirect()->to("/{$data['shoken_id']}");
    }
}
