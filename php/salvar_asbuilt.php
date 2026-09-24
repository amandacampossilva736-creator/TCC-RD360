<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.html');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../asbuilt.html');
    exit;
}

$id = (int)($_POST['n_as_built'] ?? 0);
$resp = trim($_POST['responsavel'] ?? '');
$obra = (int)($_POST['fk_obra_id_obra'] ?? 0);
$status = (int)($_POST['fk_status_id_status'] ?? 0);
$data = $_POST['data_fiscalizacao'] ?? '';
$obs = trim($_POST['observacoes'] ?? '');

if ($resp === '' || $obra <= 0 || $status <= 0 || $data === '') {
    die('Preencha os campos obrigatórios. <script>history.back();</script>');
}

$resp = $conn->real_escape_string($resp);
$obs = $conn->real_escape_string($obs);
$data = $conn->real_escape_string($data);

if ($id > 0) {
    $sql = "UPDATE as_built SET responsavel='$resp', observacoes='$obs',
            data_fiscalizacao='$data', fk_obra_id_obra=$obra,
            fk_status_id_status=$status WHERE n_as_built=$id";
    $mensagem = 'As Built atualizado com sucesso!';
} else {
    $sql = "INSERT INTO as_built(responsavel,observacoes,data_fiscalizacao,
            fk_obra_id_obra,fk_status_id_status)
            VALUES('$resp','$obs','$data',$obra,$status)";
    $mensagem = 'As Built cadastrado com sucesso!';
}

if (!mysqli_query($conn, $sql)) {
    die('Erro ao salvar As Built: ' . $conn->error);
}

echo '<script>alert(' . json_encode($mensagem, JSON_UNESCAPED_UNICODE) . ');location.href="../asbuilt.html";</script>';
?>