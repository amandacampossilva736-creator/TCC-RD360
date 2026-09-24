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

if (!mysqli_query($conn, "DELETE FROM vao WHERE id_vao=$id")) {
    die('Erro ao excluir Vão: ' . $conn->error);
}

echo '<script>alert("Vão excluído com sucesso!");location.href="../pontos.html";</script>';
?>