<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.html');
    exit;
}

$id = (int)$_SESSION['id_usuario'];
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$conf = $_POST['confirmar_senha'] ?? '';

if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Nome e e-mail válidos são obrigatórios. <script>history.back();</script>');
}

$emailBanco = $conn->real_escape_string($email);
$nomeBanco = $conn->real_escape_string($nome);

$resultado = mysqli_query($conn, "SELECT id_usuario FROM usuario WHERE email = '$emailBanco' AND id_usuario <> $id");
if (mysqli_num_rows($resultado) > 0) {
    die('Este e-mail já pertence a outro usuário. <script>history.back();</script>');
}

if ($senha !== '') {
    if ($senha !== $conf) die('As senhas não conferem. <script>history.back();</script>');
    $senhaBanco = $conn->real_escape_string(password_hash($senha, PASSWORD_DEFAULT));
    $sql = "UPDATE usuario SET nome='$nomeBanco', email='$emailBanco', senha='$senhaBanco' WHERE id_usuario=$id";
} else {
    $sql = "UPDATE usuario SET nome='$nomeBanco', email='$emailBanco' WHERE id_usuario=$id";
}

if (!mysqli_query($conn, $sql)) {
    die('Erro ao atualizar perfil: ' . $conn->error);
}

$_SESSION['nome'] = $nome;
$_SESSION['email'] = $email;

echo '<script>alert("Perfil atualizado com sucesso!");location.href="../perfil.html";</script>';
?>