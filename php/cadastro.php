<?php
require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cadastro.html');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar_senha'] ?? '';
$tipo = trim($_POST['tipo_usuario'] ?? '');
$voltar = '<script>history.back();</script>';

if ($nome === '' || $email === '' || $senha === '' || $confirmar === '' || $tipo === '') {
    die('Preencha todos os campos obrigatórios.' . $voltar);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) die('Digite um e-mail válido.' . $voltar);
if ($senha !== $confirmar) die('As senhas não são iguais.' . $voltar);
if (strlen($senha) < 6) die('A senha deve possuir pelo menos 6 caracteres.' . $voltar);
if ($tipo !== 'Técnico' && $tipo !== 'Administrador') die('Tipo de usuário inválido.' . $voltar);

$emailBanco = $conn->real_escape_string($email);
$resultado = mysqli_query($conn, "SELECT id_usuario FROM usuario WHERE email = '$emailBanco' LIMIT 1");

if (mysqli_num_rows($resultado) > 0) {
    die('Este e-mail já está cadastrado.' . $voltar);
}

$nomeBanco = $conn->real_escape_string($nome);
$senhaBanco = $conn->real_escape_string(password_hash($senha, PASSWORD_DEFAULT));
$tipoBanco = $conn->real_escape_string($tipo);

$sql = "INSERT INTO usuario(nome,email,senha,tipo_usuario)
        VALUES('$nomeBanco','$emailBanco','$senhaBanco','$tipoBanco')";

if (!mysqli_query($conn, $sql)) {
    die('Erro ao realizar o cadastro: ' . $conn->error);
}

header('Location: cadastro_sucesso.php');
exit;
?>