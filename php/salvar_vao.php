<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.html');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pontos.html');
    exit;
}

$id = (int)($_POST['id_vao'] ?? 0);
$origem = (int)($_POST['fk_pontos_coletados_id_ponto_origem'] ?? 0);
$destino = (int)($_POST['fk_pontos_coletados_id_ponto_destino'] ?? 0);
$tipoCondutor = trim($_POST['tipo_condutor'] ?? '');

if ($origem <= 0 || $destino <= 0 || $tipoCondutor === '') {
    die('Selecione o ponto de origem, o ponto de destino e o tipo de condutor. <script>history.back();</script>');
}
if ($origem === $destino) {
    die('O ponto de origem e o ponto de destino precisam ser diferentes. <script>history.back();</script>');
}

$resultado = mysqli_query($conn, "SELECT id_ponto,latitude,longitude FROM pontos_coletados WHERE id_ponto IN ($origem,$destino)");
$pontoOrigem = null;
$pontoDestino = null;

while ($ponto = mysqli_fetch_assoc($resultado)) {
    if ((int)$ponto['id_ponto'] === $origem) $pontoOrigem = $ponto;
    if ((int)$ponto['id_ponto'] === $destino) $pontoDestino = $ponto;
}

if (!$pontoOrigem || !$pontoDestino) {
    die('Um dos pontos selecionados não existe. <script>history.back();</script>');
}

$lat1 = (float)$pontoOrigem['latitude'];
$lon1 = (float)$pontoOrigem['longitude'];
$lat2 = (float)$pontoDestino['latitude'];
$lon2 = (float)$pontoDestino['longitude'];
$raioTerra = 6371000;

$lat1Rad = deg2rad($lat1);
$lat2Rad = deg2rad($lat2);
$deltaLat = deg2rad($lat2 - $lat1);
$deltaLon = deg2rad($lon2 - $lon1);
$a = sin($deltaLat / 2) * sin($deltaLat / 2) +
     cos($lat1Rad) * cos($lat2Rad) *
     sin($deltaLon / 2) * sin($deltaLon / 2);
$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
$distancia = number_format($raioTerra * $c, 2, '.', '') . ' m';

$tipoCondutor = $conn->real_escape_string($tipoCondutor);

if ($id > 0) {
    $sql = "UPDATE vao SET distancia='$distancia', tipo_condutor='$tipoCondutor',
            fk_pontos_coletados_id_ponto_origem=$origem,
            fk_pontos_coletados_id_ponto_destino=$destino WHERE id_vao=$id";
    $mensagem = 'Vão atualizado com sucesso!';
} else {
    $sql = "INSERT INTO vao(distancia,tipo_condutor,
            fk_pontos_coletados_id_ponto_origem,fk_pontos_coletados_id_ponto_destino)
            VALUES('$distancia','$tipoCondutor',$origem,$destino)";
    $mensagem = 'Vão cadastrado com sucesso!';
}

if (!mysqli_query($conn, $sql)) {
    die('Erro ao salvar Vão: ' . $conn->error);
}

echo '<script>alert(' . json_encode($mensagem, JSON_UNESCAPED_UNICODE) . ');location.href="../pontos.html";</script>';
?>