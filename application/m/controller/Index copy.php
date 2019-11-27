<?php

namespace app\m\controller;

class Index
{
    public function index()
    {
        return view('/index');
        // return "Dsds";
    }
    public function product_center()
    {
        return view("/multimedia_list", ["name" => ["产品中心", "PRODUCT CENTER"], "dir" => "multimedia_list/"]);
    }
    public function product_specification()
    {
        return view("/multimedia_list", ["name" => ["产品规格", "PRODUCT SPECIFICATION"], "dir" => "multimedia_list/"]);
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
