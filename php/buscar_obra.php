<?php
session_start();
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['erro' => 'Obra inválida'], JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "SELECT o.*, m.nome AS municipio
        FROM obra o
        INNER JOIN municipio m ON o.fk_municipio_id_municipio = m.id_municipio
        WHERE o.id_obra = $id LIMIT 1";

$resultado = mysqli_query($conn, $sql);
$obra = mysqli_fetch_assoc($resultado);

echo json_encode($obra ? $obra : ['erro' => 'Obra não encontrada'], JSON_UNESCAPED_UNICODE);
?>