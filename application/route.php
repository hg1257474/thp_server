<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
$target_range = "/^((product_center)|(product_specification)|(news_center)|(honorary_qualification)|(general_knowledge_encyclopedia))$/";
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[admin]' => [
        '/login' => 'admin/index/login',
        '/image' => 'admin/index/image',
        '/:target/:id' => [
            'admin/index/item_curd', [], ['target' => $target_range]
        ],
        '/:target' => [
            'admin/index/category_curd', [], ['target' => $target_range]
        ],

        '__miss__' => 'admin/index/index',


    ],
    '[m]' => [
        '/product_center' => 'm/index/product_center',
        '/company_profile' => 'm/index/company_profile',
        '/general_knowledge_encyclopedia' => 'm/index/general_knowledge_encyclopedia',
        '/honorary_qualification' => 'm/index/honorary_qualification',
        '/product_specification' => 'm/index/product_specification',
        '/news_center' => 'm/index/news_center',
        '' => 'm/index/index',

    ],
    '/' => 'index',
    '/product_center' => 'index/product_center',
    '/news_center' => 'index/news_center',
    '/company_profile' => 'index/company_profile',
    '/contact_us' => 'index/contact_us',
    '/general_knowledge_encyclopedia' => 'index/general_knowledge_encyclopedia',
    '/honorary_qualification' => 'index/honorary_qualification',
    '/product_specification' => 'index/product_specification',
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
];
