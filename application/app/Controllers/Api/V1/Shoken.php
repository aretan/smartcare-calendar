<?php namespace App\Controllers\Api\V1;

class Shoken extends ApiController
{
    /**
     * 証券に紐づくデータ全部もってくる
     * GET /api/v1/shoken/show/{$id}
     * GET /api/v1/shoken/{$id}
     */
    public function show($id = null)
    {
        $data = (new \App\Models\ShokenModel())->find($id);
        $data['ukeban'] = (new \App\Models\UkebanModel())->where(['shoken_id' => $data['id']])->orderBy('date')->findAll();
        $data['nyuin'] = (new \App\Models\NyuinModel())->where(['shoken_id' => $data['id']])->findAll();
        $data['shujutsu'] = (new \App\Models\ShujutsuModel())->where(['shoken_id' => $data['id']])->findAll();
        $data['tsuin'] = (new \App\Models\TsuinModel())->where(['shoken_id' => $data['id']])->findAll();

        $smartcare = new \App\Libraries\Smartcare();
        $data['result'] = $smartcare->separateTsuin(
            $data['tsuin'],
            $smartcare->conbineNyuin($data['nyuin']),
            $data['shujutsu']
        );
        return $this->respond($data, 200);
    }
}
