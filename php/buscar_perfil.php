<?php
session_start();
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['erro' => 'Usuário não logado.']);
    exit;
}

$id = (int)$_SESSION['id_usuario'];
$resultado = mysqli_query($conn, "SELECT nome,email,tipo_usuario FROM usuario WHERE id_usuario = $id");
$usuario = mysqli_fetch_assoc($resultado);

echo json_encode($usuario ? $usuario : ['erro' => 'Usuário não encontrado'], JSON_UNESCAPED_UNICODE);
?>