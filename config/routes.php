<?php

return [
    "root" => [
        "pattern" => "/",
        "method" => "GET",
        "action" => "IndexController@index"
    ],
    "get_all_goods" =>
        [
            "pattern" => "/good",
            "method" => "GET",
            "action" => "GoodController@getAllGoods"
        ],
    "get_one_good" => [
        "pattern" => "/good/{id}/params/{name}",
        "method" => "GET",
        "variables" => [
            "id" => "\d+"
        ],
        "action" => "GoodController@getOneGood"
    ]
];
