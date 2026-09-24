<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.html');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ../asbuilt.html');
    exit;
}

mysqli_query($conn, "DELETE FROM fotos WHERE fk_as_built_n_as_built=$id");
mysqli_query($conn, "UPDATE pontos_coletados SET fk_as_built_n_as_built=NULL WHERE fk_as_built_n_as_built=$id");

if (!mysqli_query($conn, "DELETE FROM as_built WHERE n_as_built=$id")) {
    die('Não foi possível excluir este As Built: ' . $conn->error);
}

echo '<script>alert("As Built excluído com sucesso!");location.href="../asbuilt.html";</script>';
?>