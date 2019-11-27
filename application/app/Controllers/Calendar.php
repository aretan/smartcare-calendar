<?php namespace App\Controllers;

class Calendar extends WebController
{
    public function index()
    {
        return view('Calendar/Index');
    }

    public function show($shoken_id=null, $ukeban_id=null)
    {
        $data['shoken'] = (new \App\Models\ShokenModel())->find($shoken_id);
        if (!$data['shoken']) {
            return redirect()->to('/');
        }

        $condition['shoken_id'] = $shoken_id;

        $data['shoken']['ukeban'] = (new \App\Models\UkebanModel())->where($condition)->orderBy('date')->findAll();

        $data['shoken']['nyuin'] = (new \App\Models\NyuinModel())->where($condition)->orderBy('warrantyStart')->findAll();
        $data['shoken']['shujutsu'] = (new \App\Models\ShujutsuModel())->where($condition)->orderBy('warrantyStart')->findAll();
        $data['shoken']['tsuin'] = (new \App\Models\TsuinModel())->where($condition)->orderBy('date')->findAll();
        $data['shoken']['bunsho'] = (new \App\Models\BunshoModel())->where($condition)->orderBy('date')->findAll();

        $data['ukeban_id'] = $ukeban_id;

        $nyuin = \App\Libraries\Smartcare::groupByUkebanId($data['shoken']['nyuin']);
        $shujutsu = \App\Libraries\Smartcare::groupByUkebanId($data['shoken']['shujutsu']);
        $tsuin = \App\Libraries\Smartcare::groupByUkebanId($data['shoken']['tsuin']);
        $bunsho = \App\Libraries\Smartcare::groupByUkebanId($data['shoken']['bunsho']);

        foreach ($data['shoken']['ukeban'] as $key => $ukeban) {
            $data['shoken']['ukeban'][$key]['nyuin'] = isset($nyuin[$ukeban['id']]) ? $nyuin[$ukeban['id']] : [];
            $data['shoken']['ukeban'][$key]['shujutsu'] = isset($shujutsu[$ukeban['id']]) ? $shujutsu[$ukeban['id']] : [];
            $data['shoken']['ukeban'][$key]['tsuin'] = isset($tsuin[$ukeban['id']]) ? $tsuin[$ukeban['id']] : [];
            $data['shoken']['ukeban'][$key]['bunsho'] = isset($bunsho[$ukeban['id']]) ? $bunsho[$ukeban['id']] : [];
        }

        $nyuinList = $shujutsuList = $tsuinList = $result = [];
        foreach ($data['shoken']['ukeban'] as $key => $ukeban) {
            $nyuinList = array_merge($nyuinList, $ukeban['nyuin']);
            $shujutsuList = array_merge($shujutsuList, $ukeban['shujutsu']);

            $tsuinList = (!empty($result))
                   ? array_merge($result['other']['warranty'], $ukeban['tsuin'])
                   : $ukeban['tsuin'];

            $result = \App\Libraries\Smartcare::tsuinResult(
                $tsuinList,
                $nyuinList,
                $shujutsuList
            );

            foreach ($result as $warranty) {
                if (!isset($warranty['date'])) continue;
                if ($warranty['type'] == 'nyuin') {
                    foreach ($nyuinList as $i => $nyuin) {
                        if ($nyuin['start'] == $warranty['date']) {
                            $nyuinList[$i]['warrantyStart'] = $warranty['warrantyStart'];
                            $nyuinList[$i]['warrantyEnd'] = $warranty['warrantyEnd'];
                            $nyuinList[$i]['warrantyMax'] = $warranty['warrantyMax'];
                            continue 2;
                        }
                    }
                } else {
                    foreach ($shujutsuList as $i => $shujutsu) {
                        if ($shujutsu['date'] == $warranty['date']) {
                            $shujutsuList[$i]['warrantyStart'] = $warranty['warrantyStart'];
                            $shujutsuList[$i]['warrantyEnd'] = $warranty['warrantyEnd'];
                            $shujutsuList[$i]['warrantyMax'] = $warranty['warrantyMax'];
                            continue 2;
                        }
                    }
                }
            }

            $data['shoken']['ukeban'][$key]['result'] = $result;
        }

        $data['final'] = \App\Libraries\Smartcare::tsuinResult(
            $data['shoken']['tsuin'],
            $data['shoken']['nyuin'],
            $data['shoken']['shujutsu']
        );

        $data['no_pay'] = [];
        $data['paypay'] = [];
        foreach ($data['shoken']['tsuin'] as $tsuin) {
            foreach ($data['final']['other']['warranty'] as $warranty) {
                if ($tsuin['date'] == $warranty['date']) {
                    $data['no_pay'][] = $tsuin;
                    continue 2;
                }
            }
            $data['paypay'][] = $tsuin;
        }

        return view('Calendar/Show', $data);
    }

    public function new()
    {
        $model = new \App\Models\ShokenModel();
        $data['validation'] = $model->getValidation();
        return view('Calendar/New', $data);
    }

    public function create()
    {
        $model = new \App\Models\ShokenModel();
        $data = $this->request->getPost();
        $model->insert($data);
        if ($model->errors()) {
            $data['validation'] = $model->getValidation();
            return view('Calendar/New', $data);
        }
        return redirect()->to("/{$data['id']}/");
    }

    public function edit($shoken_id=null)
    {
        $model = new \App\Models\ShokenModel();
        $data = $model->find($shoken_id);
        $data['validation'] = $model->getValidation();
        return view('Calendar/Edit', $data);
    }

    public function update($shoken_id=null)
    {
        $model = new \App\Models\ShokenModel();
        $data = $this->request->getPost();
        $model->save($data);
        if ($model->errors()) {
            $data['validation'] = $model->getValidation();
            return view('Calendar/Edit', $data);
        }
        return redirect()->to("/{$data['id']}/");
    }

    public function ukeban($shoken_id=null)
    {
        $model = new \App\Models\UkebanModel();
        $data['shoken_id'] = $shoken_id;
        $data = array_merge($data, $this->request->getPost());
        $model->insert($data);
        if ($model->errors()) {
            $data['validation'] = $model->getValidation();
            return view('Calendar/Ukeban', $data);
        }
        return redirect()->to("/{$data['shoken_id']}/");
    }
}
