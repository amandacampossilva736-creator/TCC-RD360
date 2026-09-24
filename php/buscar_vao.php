<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$id = (int)($_GET['id'] ?? 0);
$sql = "SELECT id_vao, distancia, tipo_condutor,
               fk_pontos_coletados_id_ponto_origem,
               fk_pontos_coletados_id_ponto_destino
        FROM vao WHERE id_vao = $id LIMIT 1";

$resultado = mysqli_query($conn, $sql);
$vao = mysqli_fetch_assoc($resultado);

echo json_encode($vao ? $vao : ['erro' => 'Vão não encontrado'], JSON_UNESCAPED_UNICODE);
?>