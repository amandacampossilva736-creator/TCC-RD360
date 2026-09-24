<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$id = (int)($_GET['id'] ?? 0);

$sql = "SELECT pc.id_ponto, pc.nota, pc.nota_obra, pc.latitude,
               pc.longitude, pc.fk_as_built_n_as_built,
               pg.id_geolocalizacao, pg.fk_obra_id_obra,
               pg.tipo_poste, pg.material_poste, pg.altura_poste,
               pg.esforco_poste, eq.tipo AS tipo_equipamento,
               eq.potencia, eq.placa, eq.cia,
               ep.tipo_estrutura AS estrutura_primaria,
               es.tipo_estrutura AS estrutura_secundaria
        FROM pontos_coletados pc
        INNER JOIN poste_geolocalizacao pg
          ON pc.fk_poste_geolocalizacao_nota = pg.nota
         AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao
        LEFT JOIN equipamentos eq
          ON eq.fk_poste_geolocalizacao_nota = pg.nota
         AND eq.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao
        LEFT JOIN estrutura_primaria ep
          ON ep.fk_pontos_coletados_id_ponto = pc.id_ponto
        LEFT JOIN estrutura_secundaria es
          ON es.fk_pontos_coletados_id_ponto = pc.id_ponto
        WHERE pc.id_ponto = $id";

$resultado = mysqli_query($conn, $sql);
$ponto = mysqli_fetch_assoc($resultado);

echo json_encode($ponto ? $ponto : ['erro' => 'Ponto não encontrado'], JSON_UNESCAPED_UNICODE);
?>