<?php


namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use think\Request;
use think\Session;
use app\common\model\ProductCenter;
use app\common\model\ProductSpecification;
use app\common\model\NewsCenter;
use app\common\model\HonoraryQualification;
use app\common\model\GeneralKnowledgeEncyclopedia;
use think\Db;

function get_model($type, $target, $args)
{

    switch ($type) {
        case 'product_center':
            if ($target == 'where') return ProductCenter::query($args);
            else if ($target == 'get') return ProductCenter::get($args);
            else return new ProductCenter($args);
        case 'product_specification':
            if ($target == 'where')
                return ProductSpecification::where(...$args);
            else if ($target == 'get') return ProductSpecification::get($args);
            else return new ProductSpecification($args);
        case 'news_center':
            if ($target == 'where') return NewsCenter::where(...$args);
            else if ($target == 'get') return NewsCenter::get($args);
            else return new NewsCenter($args);
        case 'honorary_qualification':
            if ($target == 'where') return HonoraryQualification::where(...$args);
            else if ($target == 'get') return HonoraryQualification::get($args);
            else return new HonoraryQualification($args);
        case 'general_knowledge_encyclopedia':
            if ($target == 'where') return GeneralKnowledgeEncyclopedia::where(...$args);
            else if ($target == 'get') return GeneralKnowledgeEncyclopedia::get($args);
            else return new GeneralKnowledgeEncyclopedia($args);
    }
}

class Index extends Controller
{
    protected $beforeActionList = [
        'authorization_validation'
    ];
    protected function authorization_validation()
    {

        return true;
        $req = Request::instance();
        if (!(($req->isPost() && $req->baseUrl() == '/admin/login') || Session::has('id'))) {
            echo "*(*(";
            header('HTTP/1.1 302 Move Temporarily'); //发出301头部
            header('Location:' . $req->domain() . '/admin/login.html'); //跳转到带www的网址
            exit;
        }
    }
    public function category_curd()
    {
        // $list = Admin::where('id', '<', '10')->paginate(10);
        // dump($list);
        $req = Request::instance();
        if (!array_key_exists('is_from_api', $req->param())) return view('/index-template');
        switch ($req->method()) {
            case 'GET':
                // $list = get_model($req->param()['target'], 'where', ['id', '<', '10'])->order('id desc')->page($req->param()['current'], 10)->select('id name description');
                // $list=get_model($req->param()['target'], 'where','select * from '
                $start = (string) 10 * ($req->param()['current'] - 1);
                if (in_array($req->param()['target'], ["product_center", "product_specification"]))
                    $sql1 = "select id,name from " . $req->param()['target'] . " order by sequence asc,id asc limit " . $start . ",10";
                else $sql1 = "select id,name,created_at from " . $req->param()['target'] . " order by created_at desc limit " . $start . ",10";
                $sql2 = "select count(*) from " . $req->param()['target'];
                // dump(Db::query($sql2));
                return json(['content' => Db::query($sql1), 'size' => Db::query($sql2)[0]['count(*)']]);
                // if (!array_search($req->param()['target'], ['product_center', 'product_specification']))
                //     $list = $list->column('name,id');
                // else $list = $list->column('name,id,created_at');
                // dump($list);
        }
    }
    public function item_curd()
    {
        $req = Request::instance();
        if (!array_key_exists('is_from_api', $req->param())) return view('/index-template');
        switch ($req->method()) {
            case 'GET':
                if ($req->param()['id'] == "new_item") return json(['max' => Db::query("select count(*) from " . $req->param()['target'])[0]['count(*)']]);
                $sql = "select * from " . $req->param()['target'] . " where id= " . $req->param()['id'];
                $res = Db::query($sql)[0];
                if (in_array($req->param()['target'], ["product_center", "product_specification"]))
                    $res['max'] = Db::query("select count(*) from " . $req->param()['target'])[0]['count(*)'];
                return json($res);
            case 'POST':
                $body = $req->post();
                if (in_array($req->param()['target'], ["product_center", "product_specification"])) {
                    $target_path = ROOT_PATH .  'public' . DS . 'image' . DS . explode(DS, $body['image_uri'])[0];
                    if (!is_dir($target_path)) mkdir($target_path . DS);
                    copy(RUNTIME_PATH . DS . 'temp' . DS . $body['image_uri'], ROOT_PATH .  'public' . DS . 'image' . DS . $body['image_uri']);
                    $body['image_uri'] = str_replace("\\", '/', $body['image_uri']);
                }
                $item = get_model($req->param()['target'], '', $body);
                $item->save();
                if (
                    in_array($req->param()['target'], ["product_center", "product_specification"]) &&
                    $exists_item = get_model($req->param()['target'], 'get', ['sequence' => $body['sequence']])
                ) {
                    dump($exists_item);
                    $temp1 = $item->id + 100;
                    $temp2 = $exists_item->id;
                    Db::execute("update " . $req->param()['target'] . " set id=" . $temp1 . " where id=" . ($temp1 - 100));
                    Db::execute("update " . $req->param()['target'] . " set id=" . ($temp1 - 100) . " where id=" . $temp2);
                    Db::execute("update " . $req->param()['target'] . " set id=" . $temp2 . " where id=" . $temp1);
                }
                return "success";
            case 'PUT':
                $body = $req->post();
                // $body = get_model('product_center', 'get', ['sequence' => 6]);
                // dump($body);
                // exit;
                if (
                    in_array($req->param()['target'], ["product_center", "product_specification"]) &&
                    strstr($body['image_uri'], RUNTIME_PATH . DS . 'temp')
                ) {
                    $target_path = ROOT_PATH .  'public' . DS . 'image' . DS . explode(DS, $body['image_uri'])[0];
                    if (!is_dir($target_path)) mkdir($target_path . DS);
                    copy(RUNTIME_PATH . DS . 'temp' . DS . $body['image_uri'], ROOT_PATH .  'public' . DS . 'image' . DS . $body['image_uri']);
                    $body['image_uri'] = str_replace("\\", '/', $body['image_uri']);
                }
                $item = get_model($req->param()['target'], 'get', ['id' => $req->param()['id']]);
                if (
                    in_array($req->param()['target'], ["product_center", "product_specification"]) &&
                    $item->sequence != $body['sequence'] &&
                    $exists_item = get_model($req->param()['target'], 'get', ['sequence' => $body['sequence']])
                ) {
                    dump($exists_item);
                    $temp1 = $item->id + 100;
                    $temp2 = $exists_item->id;
                    Db::execute("update " . $req->param()['target'] . " set id=" . $temp1 . " where id=" . ($temp1 - 100));
                    Db::execute("update " . $req->param()['target'] . " set id=" . ($temp1 - 100) . " where id=" . $temp2);
                    Db::execute("update " . $req->param()['target'] . " set id=" . $temp2 . " where id=" . $temp1);
                }
                $item = get_model($req->param()['target'], '', '');
                dump($item);
                dump($body);
                $body['id'] = $req->param()['id'];
                $item->saveAll([$body]);
                return "success";
            case 'DELETE':
                $item = get_model($req->param()['target'], 'get', ['id' => $req->param()['id']]);
                $item->delete();
                return "success";
        }
    }
    public function login()
    {
        return "goog";
        $req = Request::instance();
        // dump($req->post(false)['username']);
        // dump($req->post('username'));
        $admin = Admin::get(['username' => $req->post('username'), 'password' => $req->post('password')]);
        // dump($admin);
        // echo ("dsdsd");
        // return 11;
        if ($req->method() != "POST") return view('/login');
        if (!$admin) {
            header("HTTP/1.1 403 Forbidden");
            exit;
        } else {
            Session::set("id", $admin->id);
            // header('HTTP/1.1 303 See Other'); //发出301头部
            // header('Location:' . $req->domain() . '/admin/product_center'); //跳转到带www的网址
            return "success";
            // exit;
        }
    }
    public function index()
    {
        $req = Request::instance();
        header('HTTP/1.1 302 Move Temporarily'); //发出301头部
        $target = Session::has('id') ? 'product_center' : 'login';
        header('Location:' . $req->domain() . '/admin/' . $target);
        exit;
    }
    public function image()
    {
        $req = Request::instance();
        if ($req->header('content-type') == "application/json") {
            $data = explode(',', $req->post()[0]);
            preg_match("/^data:.+\/(.+);base64/", $data[0], $file_type);
            $raw_data = base64_decode($data[1]);
            $file_name = RUNTIME_PATH . DS . 'temp' . DS . uniqid() . md5($data[1]) . "." . $file_type[1];
            file_put_contents($file_name, $raw_data);
            return $file_name;
        }
        $file = request()->file('file');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $file_name = RUNTIME_PATH . DS . 'temp';

            $info = $file->move($file_name);

            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                return $info->getSaveName();
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}
