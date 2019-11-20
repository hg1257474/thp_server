<?php


namespace app\admin\controller;

use app\admin\model\User;

class Index
{
    public function index()
    {
        // return \app\admin\model\User::get();
        return User::get(['username' => 'admin']);
    }
}
