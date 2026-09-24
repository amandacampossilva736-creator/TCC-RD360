<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.html');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ../obras.html');
    exit;
}

$resultado = mysqli_query($conn, "SELECT
    (SELECT COUNT(*) FROM as_built WHERE fk_obra_id_obra=$id) AS asb,
    (SELECT COUNT(*) FROM poste_geolocalizacao WHERE fk_obra_id_obra=$id) AS poste");
$d = mysqli_fetch_assoc($resultado);

if ((int)$d['asb'] > 0 || (int)$d['poste'] > 0) {
    die('<script>alert("Esta obra possui registros vinculados. Exclua primeiro os registros dependentes.");location.href="../obras.html";</script>');
}

$usuario = (int)$_SESSION['id_usuario'];
if (!mysqli_query($conn, "DELETE FROM obra WHERE id_obra=$id AND fk_usuario_id_usuario=$usuario")) {
    die('Erro ao excluir obra: ' . $conn->error);
}

echo '<script>alert("Obra excluída com sucesso!");location.href="../obras.html";</script>';
?>