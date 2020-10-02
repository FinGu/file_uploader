<?php
$DIR = __DIR__ . '/';

$dir_array = array(
    $DIR.'functions/*.php',
    $DIR.'functions/responses/*.php',
    $DIR.'functions/fetch/*.php'
);

foreach($dir_array as $dir)
    foreach(glob($dir) as $php_file)
        require_once $php_file;

define('files_folder', $DIR.'files/');

define('mysqli_data', array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'file_uploader'
));