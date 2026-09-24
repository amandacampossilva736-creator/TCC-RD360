<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$sql = "SELECT a.n_as_built, a.responsavel, a.observacoes,
               a.data_fiscalizacao, a.fk_obra_id_obra,
               a.fk_status_id_status, o.titulo AS obra,
               s.nome_status AS status
        FROM as_built a
        INNER JOIN obra o ON a.fk_obra_id_obra = o.id_obra
        INNER JOIN status s ON a.fk_status_id_status = s.id_status
        ORDER BY a.n_as_built DESC";

$resultado = mysqli_query($conn, $sql);
$lista = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $lista[] = $linha;
}

echo json_encode($lista, JSON_UNESCAPED_UNICODE);
?>