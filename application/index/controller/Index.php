<?php

namespace app\index\controller;

use think\Request;

class Index
{
    public function index()
    {
        if (is_numeric(array_search(clientOS(), ["android", "iphone"]))) {
            header('HTTP/1.1 302 Move Temporarily'); //发出301头部
            header('Location:' . Request::instance()->domain() . '/m'); //跳转到带www的网址
            exit;
        }
        return view('/index');
    }
    public function product_center()
    {
        return view("/multimedia_list", ["name" => ["产品中心", "PRODUCT CENTER"], "dir" => "multimedia_list/"]);
    }
    public function product_specification()
    {
        return view("/multimedia_list", ["name" => ["产品规格", "PRODUCT SPECIFICATION"], "dir" => "multimedia_list/"]);
    }
    public function contact_us()
    {
        return view("/contact_us");
    }
    public function news_center()
    {
        return view("/list", ["name" => ["新闻中心", "NEWS CENTER"], "dir" => "list/"]);
    }
    public function honorary_qualification()
    {
        return view("/list", ["name" => ["荣誉资质", "HONORARY QUALIFICATION"], "dir" => "list/"]);
    }
    public function general_knowledge_encyclopedia()
    {
        return view("/list", ["name" => ["常识百科", "GENERAL KNOWLEDGE ENCYCLOPEDIA"], "dir" => "list/"]);
    }
    public function company_profile()
    {
        return view("/company_profile");
    }
}
