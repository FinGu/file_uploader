<?php
namespace uploader;

use responses\response_structure;
use utils;
use mysqli;

function encrypt_file($file_location, $password){
    $file_content = file_get_contents($file_location);

    $context_iv = random_bytes(16);

    $encrypted_file = openssl_encrypt($file_content, 'aes-256-cbc', md5($password), 0, $context_iv);

    $encrypted_file .= '|||'.$context_iv;

    return $encrypted_file;
}

function decrypt_file($file_location, $password){
    $file_content = file_get_contents($file_location);

    $file_content = explode('|||', $file_content);

    $context_iv = $file_content[1];

    $file_content = $file_content[0];

    $decrypted_file = openssl_decrypt($file_content, 'aes-256-cbc', md5($password), 0, $context_iv);

    return $decrypted_file;
}

function upload(mysqli $con, $file_array, $password = null) {
    $encryption_enabled = $password !== null;

    if( $file_array['size'] > 20971520 ) //20 mb, 20 * 1048576
        return "the file size is too big (over 20 mb)";

    $file_id = utils\random_string(15);

    $file_location = files_folder.$file_id.'.file';

    $file_data = pathinfo($file_array['name']);

    $stmt = $con->prepare('INSERT INTO files (file_id, original_name, extension, location, encrypted) VALUES(?, ?, ?, ?, ?)');

    @$stmt->bind_param('ssssi',
        $file_id, $file_data['filename'],
        $file_data['extension'], $file_location, $ee = (int)$encryption_enabled);

    $stmt->execute();

    $success = move_uploaded_file($file_array['tmp_name'], $file_location);

    if($success && $encryption_enabled) {
        $encrypted_file = encrypt_file($file_location, $password);

        file_put_contents($file_location, $encrypted_file);
    }

    $access_link = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];

    $file_data = $success ? array(
        'id' => $file_id,
        'link' => "$access_link/api/get/?id=$file_id" .
            ($encryption_enabled ? "&password=$password" : ''),
        'name' => $file_data['filename'],
        'extension' => $file_data['extension'],
        'encrypted' => $encryption_enabled,
        'size' => $file_array['size']
    ) : "the upload wasn't successful";

    return $file_data;
}