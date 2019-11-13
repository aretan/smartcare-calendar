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
            return view('Calendar/Show', $data);
        } else {
            var_dump('no data: '.$shoken_id);
        }
    }

    public function new()
    {
        return view('Calendar/New');
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
        return redirect()->to("{$data['id']}/");
    }

    public function update()
    {
        return view('Calendar/New');
    }
}
