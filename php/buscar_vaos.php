<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$sql = "SELECT v.id_vao, v.distancia, v.tipo_condutor,
               v.fk_pontos_coletados_id_ponto_origem,
               v.fk_pontos_coletados_id_ponto_destino,
               po.nota AS nota_origem, po.obra AS obra_origem,
               pd.nota AS nota_destino, pd.obra AS obra_destino
        FROM vao v
        INNER JOIN (
            SELECT pc.id_ponto, pc.nota_obra AS nota, o.titulo AS obra
            FROM pontos_coletados pc
            INNER JOIN poste_geolocalizacao pg
              ON pc.fk_poste_geolocalizacao_nota = pg.nota
             AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao
            INNER JOIN obra o ON pg.fk_obra_id_obra = o.id_obra
        ) po ON po.id_ponto = v.fk_pontos_coletados_id_ponto_origem
        INNER JOIN (
            SELECT pc.id_ponto, pc.nota_obra AS nota, o.titulo AS obra
            FROM pontos_coletados pc
            INNER JOIN poste_geolocalizacao pg
              ON pc.fk_poste_geolocalizacao_nota = pg.nota
             AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao
            INNER JOIN obra o ON pg.fk_obra_id_obra = o.id_obra
        ) pd ON pd.id_ponto = v.fk_pontos_coletados_id_ponto_destino
        ORDER BY v.id_vao DESC";

$resultado = mysqli_query($conn, $sql);
$lista = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $lista[] = $linha;
}

echo json_encode($lista, JSON_UNESCAPED_UNICODE);
?>