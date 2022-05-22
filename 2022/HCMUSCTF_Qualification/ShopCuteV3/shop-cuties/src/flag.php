<?php
    header('Content-Type: application/json');
    $response = new stdClass();
    $response->status_code = 403;
    $response->msg = "Error 403 forbidden, can only access by 127.0.0.1";
    if ($_SERVER['REMOTE_ADDR'] === "127.0.0.1") {
        $response->msg = getenv("FLAG");
        $response->status_code = 200;
    }
    echo json_encode($response);
?>