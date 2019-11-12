<?php namespace App\Controllers;

class Calendar extends WebController
{
    public function show($shoken_id=null)
    {
        return view('calendar');
    }
}
