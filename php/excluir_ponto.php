<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.html');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ../pontos.html');
    exit;
}

$resultado = mysqli_query($conn, "SELECT fk_poste_geolocalizacao_nota AS nota,
                                  fk_poste_geolocalizacao_id_geolocalizacao AS geo
                                  FROM pontos_coletados WHERE id_ponto=$id");
$ponto = mysqli_fetch_assoc($resultado);

if (!$ponto) {
    die('Ponto não encontrado.');
}

$nota = $conn->real_escape_string($ponto['nota']);
$geo = (int)$ponto['geo'];

mysqli_query($conn, "DELETE FROM vao WHERE fk_pontos_coletados_id_ponto_origem=$id OR fk_pontos_coletados_id_ponto_destino=$id");
mysqli_query($conn, "DELETE FROM fotos WHERE fk_pontos_coletados_id_ponto=$id");
mysqli_query($conn, "DELETE FROM estrutura_primaria WHERE fk_pontos_coletados_id_ponto=$id");
mysqli_query($conn, "DELETE FROM estrutura_secundaria WHERE fk_pontos_coletados_id_ponto=$id");
mysqli_query($conn, "DELETE FROM equipamentos WHERE fk_poste_geolocalizacao_nota='$nota' AND fk_poste_geolocalizacao_id_geolocalizacao=$geo");

if (!mysqli_query($conn, "DELETE FROM pontos_coletados WHERE id_ponto=$id")) {
    die('Erro ao excluir ponto: ' . $conn->error);
}

mysqli_query($conn, "DELETE FROM poste_geolocalizacao WHERE nota='$nota' AND id_geolocalizacao=$geo");

echo '<script>alert("Ponto excluído com sucesso!");location.href="../pontos.html";</script>';
?>