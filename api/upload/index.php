<?php
require '../../autoload.php';

use responses\response_structure;

$no_file_uploaded = new response_structure(array(
    'success' => false,
    'message' => 'no files were uploaded'
));

if(empty($_FILES))
    die($no_file_uploaded->to_json());

$first_array_key = array_key_first($_FILES);

$file_to_be_uploaded = $_FILES[$first_array_key];

$connection = new mysqli(mysqli_data['host'], mysqli_data['username'], mysqli_data['password'], mysqli_data['database']);

$uploader_output = uploader\upload($connection, $file_to_be_uploaded, $_GET['password'] ?? null);

$was_successful = is_array($uploader_output);

$output_display = new response_structure(array(
    'success' => $was_successful,
    'message' => $was_successful ? 'the upload was done successfully' : $uploader_output,
    'file_data' => $was_successful ? $uploader_output : []
));

die($output_display->to_json());