<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Calendar extends Controller
{
    protected $helpers = ['form'];

    public function index()
    {
        return view('Calendar/Index');
    }

    public function show($shoken_id=null, $ukeban_id=null, $mode='until')
    {
        $data['shoken'] = (new \App\Models\ShokenModel())->find($shoken_id);
        if (!$data['shoken']) {
            return redirect()->to('/');
        }

        // これ全部受番ModelにおしこんでViewから呼ぶように変更したい
        $condition['shoken_id'] = $shoken_id;
        $data['shoken']['ukeban'] = (new \App\Models\UkebanModel())->where($condition)->orderBy('created_at')->findAll();
        $data['shoken']['nyuin'] = (new \App\Models\NyuinModel())->where($condition)->orderBy('warrantyStart')->findAll();
        $data['shoken']['shujutsu'] = (new \App\Models\ShujutsuModel())->where($condition)->orderBy('warrantyStart')->findAll();
        $data['shoken']['tsuin'] = (new \App\Models\TsuinModel())->where($condition)->orderBy('date')->findAll();
        $data['shoken']['bunsho'] = (new \App\Models\BunshoModel())->where($condition)->orderBy('date')->findAll();

        // 受番しぼりこみ機能
        if (!$ukeban_id && count($data['shoken']['ukeban'])) {
            $ukeban_id = end($data['shoken']['ukeban'])['id'];
        }
        $data['ukeban_id'] = $ukeban_id;
        $data['mode'] = $mode;

        // 計算
        $data['shoken'] = \App\Libraries\Smartcare::calcTsuin($data['shoken']);

        // カレンダー表示開始日
        $data['date'] = date('Y-m-d', strtotime('- 1 year'));
        foreach ($data['shoken']['nyuin'] as $value) {
            if ($data['date'] > $value['warrantyStart']) {
                $data['date'] = $value['warrantyStart'];
            }
        }
        foreach ($data['shoken']['shujutsu'] as $value) {
            if ($data['date'] > $value['warrantyStart']) {
                $data['date'] = $value['warrantyStart'];
            }
        }
        foreach ($data['shoken']['tsuin'] as $value) {
            if ($data['date'] > $value['date']) {
                $data['date'] = $value['date'];
            }
        }
        foreach ($data['shoken']['bunsho'] as $value) {
            if ($data['date'] > $value['date']) {
                $data['date'] = $value['date'];
            }
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

        if (isset($data['id'])) {
            $exists = $model->find($data['id']);
            if ($exists) {
                $data['validation'] = $model->getValidation();
                $data['validation']->setError('id', 'この証券番号は既に登録されています。');
                return view('Calendar/New', $data);
            }
        }

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

        if (isset($data['id'])) {
            $exists = $model->find($data['id']);
            if ($exists) {
                $data['validation'] = $model->getValidation();
                $data['validation']->setError('id', 'この受付番号は既に登録されています。');
                return view('Calendar/Ukeban', $data);
            }
        }

        $model->insert($data);
        if ($model->errors()) {
            $data['validation'] = $model->getValidation();
            return view('Calendar/Ukeban', $data);
        }
        return redirect()->to("/{$data['shoken_id']}/");
    }

    public function event($shoken_id, $ukeban_id, $mode)
    {
        $data = $this->request->getPost();
        $data = array_merge($data, ['shoken_id' => $shoken_id]);
        $model = 'App\\Models\\' . ucfirst($data['type']) . 'Model';
        $model = new $model();

        if ($data['type'] != 'nyuin') {
            $data = \App\Libraries\Smartcare::addShujutsuWarranty($data);

            $exists = $model->where([
                'shoken_id' => $data['shoken_id'],
                'date' => $data['date'],
            ])->selectCount('id')->first()['id'];
            if ($exists) {
                $data['validation'] = $model->getValidation();
                $data['validation']->setError('date', '既に登録されている日付です。');
                $data['ukeban_id'] = $ukeban_id;
                $data['mode'] = $mode;
                $data['ukeban'] = (new \App\Models\UkebanModel())->where(['shoken_id' => $shoken_id])->orderBy('created_at')->findAll();
                return view('Calendar/Event', $data);
            }
        } else {
            preg_match('/^(?<start>.+) - (?<end>.+)/', $data['daterange'], $matches);
            $data['start'] = $matches['start'];
            $data['end'] = $matches['end'];
            $data = \App\Libraries\Smartcare::addNyuinWarranty($data);

            $exists = $model->where([
                'shoken_id' => $data['shoken_id'],
                'start <=' => $data['end'],
                'end >=' => $data['start'],
            ])->selectCount('id')->first()['id'];

            if ($exists) {
                $data['validation'] = $model->getValidation();
                $data['validation']->setError('daterange', '期間の重複する入院があります。');
                $data['ukeban_id'] = $ukeban_id;
                $data['mode'] = $mode;
                $data['ukeban'] = (new \App\Models\UkebanModel())->where(['shoken_id' => $shoken_id])->orderBy('created_at')->findAll();
                return view('Calendar/Event', $data);
            }
        }

        $model->insert($data);
        if ($model->errors()) {
            $data['ukeban'] = (new \App\Models\UkebanModel())->where(['shoken_id' => $shoken_id])->orderBy('created_at')->findAll();
            $data['validation'] = $model->getValidation();
            return view('Calendar/Event', $data);
        }
        return redirect()->to("/{$shoken_id}/{$ukeban_id}/{$mode}/");
    }

    public function delete($shoken_id, $ukeban_id, $mode)
    {
        $data = $this->request->getPost();
        $data = array_merge($data, ['shoken_id' => $shoken_id]);
        $model = 'App\\Models\\' . ucfirst($data['type']) . 'Model';
        $model = new $model();

        $model->delete($data['id']);
        return redirect()->to("/{$shoken_id}/{$ukeban_id}/{$mode}/");
    }

    public function batch($shoken_id, $ukeban_id, $mode)
    {
        $tsuin = [
            'shoken_id' => $shoken_id,
            'ukeban_id' => $this->request->getPost('ukeban_id'),
        ];

        $data = [];
        foreach (explode(' ', str_replace('/', '-', str_replace(["\n", ','], ' ', $this->request->getPost('date')))) as $date) {
            $date = trim($date);
            $int = preg_match('/^[1-2][0-9]{3}-[0-1]?[0-9]-[0-3]?[0-9]$/', $date);
            if (!$int) continue;
            $request['date'] = $date;
            $data[] = array_merge($tsuin, $request);
        }

        $model = new \App\Models\TsuinModel();
        $model->insertBatch($data);

        return redirect()->to("/{$shoken_id}/{$ukeban_id}/{$mode}/");
    }
}
