<?php
namespace responses;

class response_structure{
    var $success, $message;

    var array $file_data;

    function __construct(array $data){
        $this->success = $data['success'] ?? false;

        $this->message = $data['message'] ?? 'null';

        $this->file_data = $data['file_data'] ?? [];
    }

    function to_json(){
        return json_encode($this);
    }
}