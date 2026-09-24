<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$sql = "SELECT o.id_obra, o.projeto_obra, o.nota, o.titulo,
               o.id_investimento, o.qtd_postes, o.uc_previsto,
               o.data_energizacao, o.fk_usuario_id_usuario,
               o.fk_municipio_id_municipio, m.nome AS municipio
        FROM obra o
        INNER JOIN municipio m ON o.fk_municipio_id_municipio = m.id_municipio
        ORDER BY o.id_obra DESC";

$resultado = mysqli_query($conn, $sql);
$obras = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $obras[] = $linha;
}

echo json_encode($obras, JSON_UNESCAPED_UNICODE);
?>