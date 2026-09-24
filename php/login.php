<?php
session_start();
require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    die('Informe e-mail e senha. <script>history.back();</script>');
}

$email = $conn->real_escape_string($email);
$sql = "SELECT id_usuario,nome,email,senha,tipo_usuario FROM usuario WHERE email = '$email' LIMIT 1";
$resultado = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($resultado);

if (!$usuario || !password_verify($senha, $usuario['senha'])) {
    die('E-mail ou senha incorretos. <script>history.back();</script>');
}

session_regenerate_id(true);
$_SESSION['id_usuario'] = $usuario['id_usuario'];
$_SESSION['nome'] = $usuario['nome'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

header('Location: ../obras.html');
exit;
?>