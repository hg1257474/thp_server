<?php


namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller
{
    protected $beforeActionList = [
        'authorization_validation'
    ];
    protected function authorization_validation()
    {
        $req = Request::instance();
        if (!(($req->isPost() && $req->baseUrl() == '/admin/login') || Session::has('id'))) {
            echo "*(*(";
            header('HTTP/1.1 302 Move Temporarily'); //发出301头部
            header('Location:' . $req->domain() . '/admin/login.html'); //跳转到带www的网址
            exit;
        }
    }

    public function index()
    {
        $req = Request::instance();
        // dump($req->post(false)['username']);
        // dump($req->post('username'));
        $admin = Admin::get(['username' => $req->post('username'), 'password' => $req->post('password')]);
        // dump($admin);
        if (!$admin) {
            header("HTTP/1.1 403 Forbidden");
            exit;
        } else {
            echo "dsds@@@@@@@@";
            Session::set("is", $admin->id);
            header('HTTP/1.1 303 See Other'); //发出301头部
            header('Location:' . $req->domain() . '/admin/product_center'); //跳转到带www的网址
            exit;
        }
    }
}
