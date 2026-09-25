<?php
session_start();
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

$id = (int)($_GET['id'] ?? 0);
$usuario = (int)($_SESSION['id_usuario'] ?? 0);

if ($id <= 0 || $usuario <= 0) {
    echo json_encode(['erro' => 'Obra inválida'], JSON_UNESCAPED_UNICODE);
    exit;
}

$sqlObra = "SELECT o.id_obra, o.titulo, o.projeto_obra, o.nota
            FROM obra o
            WHERE o.id_obra=$id
              AND o.fk_usuario_id_usuario=$usuario
            LIMIT 1";

$resultado = mysqli_query($conn, $sqlObra);
$obra = mysqli_fetch_assoc($resultado);

if (!$obra) {
    echo json_encode(['erro' => 'Obra não encontrada.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$asBuilt = [];
$sqlAsBuilt = "SELECT a.n_as_built, a.titulo, a.responsavel,
                      a.data_fiscalizacao, s.nome_status AS status
               FROM as_built a
               LEFT JOIN status s ON a.fk_status_id_status = s.id_status
               WHERE a.fk_obra_id_obra=$id
               ORDER BY a.n_as_built DESC";

$resultadoAsBuilt = mysqli_query($conn, $sqlAsBuilt);
while ($linha = mysqli_fetch_assoc($resultadoAsBuilt)) {
    $asBuilt[] = $linha;
}

$pontos = [];
$sqlPontos = "SELECT pc.id_ponto, pc.nota_obra, pc.latitude, pc.longitude
              FROM pontos_coletados pc
              INNER JOIN poste_geolocalizacao pg
                  ON pc.fk_poste_geolocalizacao_nota = pg.nota
                 AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao
              WHERE pg.fk_obra_id_obra=$id
              ORDER BY pc.id_ponto DESC";

$resultadoPontos = mysqli_query($conn, $sqlPontos);
while ($linha = mysqli_fetch_assoc($resultadoPontos)) {
    $pontos[] = $linha;
}

$vaos = [];
$sqlVaos = "SELECT v.id_vao, v.distancia, v.tipo_condutor,
                   v.fk_pontos_coletados_id_ponto_origem,
                   v.fk_pontos_coletados_id_ponto_destino
            FROM vao v
            INNER JOIN pontos_coletados po
                ON po.id_ponto = v.fk_pontos_coletados_id_ponto_origem
            INNER JOIN poste_geolocalizacao pgo
                ON po.fk_poste_geolocalizacao_nota = pgo.nota
               AND po.fk_poste_geolocalizacao_id_geolocalizacao = pgo.id_geolocalizacao
            INNER JOIN pontos_coletados pd
                ON pd.id_ponto = v.fk_pontos_coletados_id_ponto_destino
            INNER JOIN poste_geolocalizacao pgd
                ON pd.fk_poste_geolocalizacao_nota = pgd.nota
               AND pd.fk_poste_geolocalizacao_id_geolocalizacao = pgd.id_geolocalizacao
            WHERE pgo.fk_obra_id_obra=$id
              AND pgd.fk_obra_id_obra=$id
            ORDER BY v.id_vao DESC";

$resultadoVaos = mysqli_query($conn, $sqlVaos);
while ($linha = mysqli_fetch_assoc($resultadoVaos)) {
    $vaos[] = $linha;
}

echo json_encode([
    'obra' => $obra,
    'as_built' => $asBuilt,
    'pontos' => $pontos,
    'vaos' => $vaos
], JSON_UNESCAPED_UNICODE);
?>