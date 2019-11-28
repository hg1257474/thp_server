<?php

namespace app\index\controller;

use think\Request;
use think\Db;

class Index
{
    public function index()
    {
        if (is_numeric(array_search(clientOS(), ["android", "iphone"]))) {
            header('HTTP/1.1 302 Move Temporarily'); //发出301头部
            header('Location:' . Request::instance()->domain() . '/m'); //跳转到带www的网址
            exit;
        }
        $product_centers = Db::query("select image_uri,name,id from product_center order by sequence asc,id asc limit 3");
        $general_knowledge_encyclopedia = Db::query("select description,name from general_knowledge_encyclopedia order by created_at desc limit 1")[0];
        $news_centers = Db::query("select created_at,description,id,name from news_center order by created_at desc limit 4");
        return view('/index', ['product_centers' => $product_centers, 'general_knowledge_encyclopedia' => $general_knowledge_encyclopedia, 'news_centers' => $news_centers]);
    }
    public function product_center()
    {

        $params = Request::instance()->param();
        $current = array_key_exists('current', $params) ? $params['current'] : 1;
        $total = Db::query("select count(*) from product_center ")[0]["count(*)"];
        $is_last = $total <= $current * 6;
        $list = Db::query("select image_uri,id,name from product_center order by sequence asc,id asc limit " . (string) (($current - 1) * 6) . ",6");

        return view("/multimedia_list", [
            "name" => ["产品中心", "PRODUCT CENTER"], "dir" => "multimedia_list/",
            "current" => $current,
            "list" => $list, "is_last" => $is_last,
            "pages" => range(1, floor(($total - 1) / 6) + 1)
        ]);
    }
    public function product_specification()
    {

        $params = Request::instance()->param();
        $current = array_key_exists('current', $params) ? $params['current'] : 1;
        $total = Db::query("select count(*) from product_specification ")[0]["count(*)"];
        $is_last = $total <= $current * 6;
        $list = Db::query("select image_uri,id,name from product_specification order by sequence asc,id asc limit " . (string) (($current - 1) * 6) . ",6");
        return view("/multimedia_list", [
            "name" => ["产品规格", "PRODUCT SPECIFICATION"], "dir" => "multimedia_list/",
            "current" => $current,
            "list" => $list, "is_last" => $is_last,
            "pages" => range(1, floor(($total - 1) / 6) + 1)
        ]);
    }
    public function contact_us()
    {
        $params = Request::instance()->param();
        $current = array_key_exists('current', $params) ? $params['current'] : 1;
        $total = Db::query("select count(*) from news_center ")[0]["count(*)"];
        $is_last = $total <= $current * 10;
        $list = Db::query("select created_at,description,id,name from news_center order by created_at desc limit " . (string) (($current - 1) * 10) . ",10");

        return view("/contact_us");
    }
    public function news_center()
    {
        $params = Request::instance()->param();
        $current = array_key_exists('current', $params) ? $params['current'] : 1;
        $total = Db::query("select count(*) from news_center ")[0]["count(*)"];
        $is_last = $total <= $current * 10;
        $list = Db::query("select created_at,description,id,name from news_center order by created_at desc limit " . (string) (($current - 1) * 10) . ",10");
        return view("/list", [
            "name" => ["新闻中心", "NEWS CENTER"], "dir" => "list/",
            "current" => $current,
            "list" => $list, "is_last" => $is_last,
            "pages" => range(1, floor(($total - 1) / 10) + 1)
        ]);
    }
    public function honorary_qualification()
    {
        $params = Request::instance()->param();
        $current = array_key_exists('current', $params) ? $params['current'] : 1;
        $total = Db::query("select count(*) from honorary_qualification ")[0]["count(*)"];
        $is_last = $total <= $current * 10;
        $list = Db::query("select created_at,description,id,name from honorary_qualification order by created_at desc limit " . (string) (($current - 1) * 10) . ",10");
        return view("/list", [
            "name" => ["荣誉资质", "HONORARY QUALIFICATION"], "dir" => "list/",
            "current" => $current,
            "list" => $list, "is_last" => $is_last,
            "pages" => range(1, floor(($total - 1) / 10) + 1)
        ]);
    }
    public function general_knowledge_encyclopedia()
    {

        $params = Request::instance()->param();
        $current = array_key_exists('current', $params) ? $params['current'] : 1;
        $total = Db::query("select count(*) from general_knowledge_encyclopedia ")[0]["count(*)"];
        $is_last = $total <= $current * 10;
        $list = Db::query("select created_at,description,id,name from general_knowledge_encyclopedia order by created_at desc limit " . (string) (($current - 1) * 10) . ",10");
        return view("/list", [
            "name" => ["常识百科", "GENERAL KNOWLEDGE ENCYCLOPEDIA"], "dir" => "list/",
            "current" => $current,
            "list" => $list, "is_last" => $is_last,
            "pages" => range(1, floor(($total - 1) / 10) + 1)
        ]);
    }
    public function company_profile()
    {
        return view("/company_profile");
    }
    public function detail()
    {
        $req = Request::instance();
        $name_map = [
            "product_center" => "产品中心",
            "product_specification" => "产品规格",
            "news_center" => "新闻中心",
            "honorary_qualification" => "荣誉资质",
            "general_knowledge_encyclopedia" => "常识百科",
        ];
        $description = Db::query("select description from " . $req->param()['target'] . " where id= " . $req->param()['id'])[0]['description'];

        return view('/detail', ["name" => $name_map[$req->param()['target']], "description" => $description]);
    }
}
