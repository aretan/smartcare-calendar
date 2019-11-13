<?php namespace App\Libraries;

class Layout
{
    public function listShoken()
    {
        $data['shoken'] = (new \App\Models\ShokenModel())->findAll();
        return view('Partials/ListShoken', $data);
    }
}
