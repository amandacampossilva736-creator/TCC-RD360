<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$sql = "SELECT pc.id_ponto, pc.nota, pc.nota_obra, pc.latitude,
               pc.longitude, pc.fk_as_built_n_as_built,
               pg.id_geolocalizacao, pg.fk_obra_id_obra,
               pg.tipo_poste, pg.material_poste, pg.altura_poste,
               pg.esforco_poste, o.titulo AS obra,
               ep.tipo_estrutura AS estrutura_primaria,
               es.tipo_estrutura AS estrutura_secundaria,
               eq.tipo AS tipo_equipamento, eq.potencia,
               eq.placa, eq.cia
        FROM pontos_coletados pc
        INNER JOIN poste_geolocalizacao pg
          ON pc.fk_poste_geolocalizacao_nota = pg.nota
         AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao
        LEFT JOIN obra o ON pg.fk_obra_id_obra = o.id_obra
        LEFT JOIN estrutura_primaria ep
          ON ep.fk_pontos_coletados_id_ponto = pc.id_ponto
        LEFT JOIN estrutura_secundaria es
          ON es.fk_pontos_coletados_id_ponto = pc.id_ponto
        LEFT JOIN equipamentos eq
          ON eq.fk_poste_geolocalizacao_nota = pg.nota
         AND eq.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao
        ORDER BY pc.id_ponto DESC";

$resultado = mysqli_query($conn, $sql);
$pontos = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $pontos[] = $linha;
}

echo json_encode($pontos, JSON_UNESCAPED_UNICODE);
?>