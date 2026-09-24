<?php

session_start();

header('Content-Type: application/json; charset=UTF-8');

echo json_encode(
    [
        'logado'=>isset($_SESSION['id_usuario']),
        'id_usuario'=>$_SESSION['id_usuario']??null
    ],
    JSON_UNESCAPED_UNICODE
);

?>