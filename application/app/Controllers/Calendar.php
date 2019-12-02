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

        // これ全部受番ModelにおしこんでViewから呼ぶように変更したい
        $data['shoken']['ukeban'] = (new \App\Models\UkebanModel())->where($condition)->orderBy('date')->findAll();

        $data['shoken']['nyuin'] = (new \App\Models\NyuinModel())->where($condition)->orderBy('warrantyStart')->findAll();
        $data['shoken']['shujutsu'] = (new \App\Models\ShujutsuModel())->where($condition)->orderBy('warrantyStart')->findAll();
        $data['shoken']['tsuin'] = (new \App\Models\TsuinModel())->where($condition)->orderBy('date')->findAll();
        $data['shoken']['bunsho'] = (new \App\Models\BunshoModel())->where($condition)->orderBy('date')->findAll();

        // 受番しぼりこみ機能
        $data['ukeban_id'] = $ukeban_id;
        $data['shoken'] = \App\Libraries\Smartcare::calcTsuin($data['shoken']);

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
