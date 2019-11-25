<?php namespace App\Controllers\Api\V1;

class Nyuin extends ApiController
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
            preg_match('/^(?<start>.+) - (?<end>.+)/', $request['daterange'], $matches);
            $request = [
                'start' => $matches['start'],
                'end' => $matches['end'],
            ];
            $request = $this->smartcare->addNyuinWarranty($request);
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
}
