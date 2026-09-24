<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.html');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../obras.html');
    exit;
}

$id = (int)($_POST['id_obra'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$projeto = trim($_POST['projeto_obra'] ?? '');
$nota = trim($_POST['nota'] ?? '');
$invest = trim($_POST['id_investimento'] ?? '');
$qtd = (int)($_POST['qtd_postes'] ?? 0);
$uc = (int)($_POST['uc_previsto'] ?? 0);
$data = $_POST['data_energizacao'] ?? '';
$mun = trim($_POST['municipio'] ?? '');

if ($titulo === '' || $projeto === '' || $nota === '' || $invest === '' || $mun === '' || $data === '') {
    die('Preencha os campos obrigatórios. <script>history.back();</script>');
}

$titulo = $conn->real_escape_string($titulo);
$projeto = $conn->real_escape_string($projeto);
$nota = $conn->real_escape_string($nota);
$invest = $conn->real_escape_string($invest);
$data = $conn->real_escape_string($data);
$munBanco = $conn->real_escape_string($mun);
$usuario = (int)$_SESSION['id_usuario'];

$resultado = mysqli_query($conn, "SELECT id_municipio FROM municipio WHERE nome = '$munBanco' LIMIT 1");
$municipio = mysqli_fetch_assoc($resultado);

if ($municipio) {
    $idMunicipio = (int)$municipio['id_municipio'];
} else {
    if (!mysqli_query($conn, "INSERT INTO municipio(nome,estado) VALUES('$munBanco','BA')")) {
        die('Erro ao cadastrar o município: ' . $conn->error);
    }
    $idMunicipio = mysqli_insert_id($conn);
}

if ($id > 0) {
    $sql = "UPDATE obra SET titulo='$titulo', nota='$nota', projeto_obra='$projeto',
            id_investimento='$invest', qtd_postes=$qtd, uc_previsto=$uc,
            data_energizacao='$data', fk_municipio_id_municipio=$idMunicipio
            WHERE id_obra=$id AND fk_usuario_id_usuario=$usuario";
    $mensagem = 'Obra atualizada com sucesso!';
} else {
    $sql = "INSERT INTO obra(titulo,nota,projeto_obra,id_investimento,qtd_postes,uc_previsto,
            data_energizacao,fk_usuario_id_usuario,fk_municipio_id_municipio)
            VALUES('$titulo','$nota','$projeto','$invest',$qtd,$uc,'$data',$usuario,$idMunicipio)";
    $mensagem = 'Obra cadastrada com sucesso!';
}

if (!mysqli_query($conn, $sql)) {
    die('Erro ao salvar obra: ' . $conn->error);
}

echo '<script>alert(' . json_encode($mensagem, JSON_UNESCAPED_UNICODE) . ');location.href="../obras.html";</script>';
?>