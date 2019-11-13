<?php namespace App\Controllers;

class Calendar extends WebController
{
    public function index()
    {
        return view('Calendar/Index');
    }

    public function show($shoken_id=null)
    {
        $data['shoken'] = (new \App\Models\ShokenModel())->find($shoken_id);
        if ($data['shoken']) {
            $data['ukeban'] = (new \App\Models\UkebanModel())->where([
                'shoken_id' => $shoken_id,
            ])->orderBy('date')->findAll();

            $data['nyuin'] = \App\Libraries\Common::groupByKey('ukeban_id',
                (new \App\Models\NyuinModel())->where([
                    'shoken_id' => $shoken_id,
                ])->findAll());
            $data['shujutsu'] = \App\Libraries\Common::groupByKey('ukeban_id',
                (new \App\Models\ShujutsuModel())->where([
                    'shoken_id' => $shoken_id,
                ])->findAll());
            $data['tsuin'] = \App\Libraries\Common::groupByKey('ukeban_id',
                (new \App\Models\TsuinModel())->where([
                    'shoken_id' => $shoken_id,
                ])->findAll());

            return view('Calendar/Show', $data);
        }
        redirect()->to('/');
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
