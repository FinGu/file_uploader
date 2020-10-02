<?php
namespace utils;

function random_string($length, $keyspace = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'){
    $out = '';

    for($i = 0; $i < $length; $i++){
        $rand_index = rand(0, strlen($keyspace) - 1);

        $out .= $keyspace[$rand_index];
    }

    return $out;
}

function get_ip(){
    return $_SERVER['SERVER_ADDR'];
}