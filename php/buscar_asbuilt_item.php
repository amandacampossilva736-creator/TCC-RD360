<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$id = (int)($_GET['id'] ?? 0);
$sql = "SELECT n_as_built,responsavel,observacoes,data_fiscalizacao,
               fk_obra_id_obra,fk_status_id_status
        FROM as_built WHERE n_as_built = $id";

$resultado = mysqli_query($conn, $sql);
$item = mysqli_fetch_assoc($resultado);

echo json_encode($item ? $item : ['erro' => 'As Built não encontrado'], JSON_UNESCAPED_UNICODE);
?>