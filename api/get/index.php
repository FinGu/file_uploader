<?php
require '../../autoload.php';

use responses\response_structure;

$id_not_set_or_wrong = new response_structure(array(
    'success' => false,
    'message' => 'id not set or wrong'
));

$id_not_set_or_wrong = $id_not_set_or_wrong->to_json();

$id = $_GET['id'] ?? null;

if($id === null)
    die($id_not_set_or_wrong);

$connection = new mysqli(mysqli_data['host'], mysqli_data['username'], mysqli_data['password'], mysqli_data['database']);

$file_data = fetch\file_with_id($connection, $id);

if(!$file_data)
    die($id_not_set_or_wrong);

$wrong_or_not_set_password = new response_structure(array(
    'success' => false,
    'message' => 'wrong or not set password'
));

$wrong_or_not_set_password = $wrong_or_not_set_password->to_json();

$encrypted = (bool)$file_data['encrypted']; // hurr durr tinyint

$password = $_GET['password'] ?? null;

$file_content = $encrypted ?
    uploader\decrypt_file($file_data['location'], $password) : file_get_contents($file_data['location']);

if($encrypted && $password === null || empty($file_content))
    die($wrong_or_not_set_password);

$file_basename = $file_data['original_name'].'.'.$file_data['extension'];

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"".$file_basename."\"");
echo $file_content;
