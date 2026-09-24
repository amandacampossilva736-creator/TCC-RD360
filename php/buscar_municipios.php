<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$resultado = mysqli_query($conn, "SELECT id_municipio,nome,estado FROM municipio ORDER BY nome");
$lista = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $lista[] = $linha;
}

echo json_encode($lista, JSON_UNESCAPED_UNICODE);
?>