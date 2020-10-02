<?php
namespace fetch;

use mysqli;

function file_with_id(mysqli $con, $file_id){
    $stmt = $con->prepare('SELECT * FROM files WHERE file_id=? LIMIT 1');

    $stmt->bind_param('s', $file_id);

    $stmt->execute();

    $query_result = $stmt->get_result();

    return $query_result->num_rows > 0 ?
        $query_result->fetch_assoc() : false;
}