<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$sql = "SELECT id_status, nome_status FROM status
        WHERE nome_status IN ('Pendente','Em andamento','Concluída')
        ORDER BY FIELD(nome_status,'Pendente','Em andamento','Concluída')";

$resultado = mysqli_query($conn, $sql);
$lista = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $lista[] = $linha;
}

echo json_encode($lista, JSON_UNESCAPED_UNICODE);
?>