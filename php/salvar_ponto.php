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

$idPonto = (int)($_POST['id_ponto'] ?? 0);
$obra = (int)($_POST['fk_obra_id_obra'] ?? 0);
$asBuilt = (int)($_POST['fk_as_built_n_as_built'] ?? 0);
$latitude = trim($_POST['latitude'] ?? '');
$longitude = trim($_POST['longitude'] ?? '');
$tipoPoste = trim($_POST['tipo_poste'] ?? '');
$materialPoste = trim($_POST['material_poste'] ?? '');
$alturaPoste = trim($_POST['altura_poste'] ?? '');
$esforcoPoste = trim($_POST['esforco_poste'] ?? '');
$tipoEquipamento = trim($_POST['tipo_equipamento'] ?? '');
$potencia = trim($_POST['potencia'] ?? '');
$placa = trim($_POST['placa'] ?? '');
$cia = trim($_POST['cia'] ?? '');
$estruturaPrimaria = trim($_POST['estrutura_primaria'] ?? '');
$estruturaSecundaria = trim($_POST['estrutura_secundaria'] ?? '');

if ($obra <= 0 || $latitude === '' || $longitude === '' || $tipoPoste === '' || $materialPoste === '') {
    die('Preencha os campos obrigatórios. <script>history.back();</script>');
}

$latitudeBanco = (float)$latitude;
$longitudeBanco = (float)$longitude;

$usuario = (int)$_SESSION['id_usuario'];
$resultado = mysqli_query($conn, "SELECT id_obra,nota FROM obra WHERE id_obra=$obra AND fk_usuario_id_usuario=$usuario LIMIT 1");
$obraDados = mysqli_fetch_assoc($resultado);

if (!$obraDados) {
    die('Obra não encontrada ou não pertence ao usuário. <script>history.back();</script>');
}

$notaObra = $conn->real_escape_string($obraDados['nota'] ?? '');
$notaBanco = $notaObra;

if ($asBuilt > 0) {
    $resultado = mysqli_query($conn, "SELECT n_as_built FROM as_built WHERE n_as_built=$asBuilt AND fk_obra_id_obra=$obra LIMIT 1");
    if (!mysqli_fetch_assoc($resultado)) {
        die('O As Built selecionado não pertence à obra escolhida. <script>history.back();</script>');
    }
}

$tipoBanco = $conn->real_escape_string($tipoPoste);
$materialBanco = $conn->real_escape_string($materialPoste);
$alturaBanco = $conn->real_escape_string($alturaPoste);
$esforcoBanco = $conn->real_escape_string($esforcoPoste);

if ($idPonto <= 0) {
    $resultado = mysqli_query($conn, "SELECT MAX(id_geolocalizacao) AS maior FROM poste_geolocalizacao");
    $geoDados = mysqli_fetch_assoc($resultado);
    $idGeo = (int)($geoDados['maior'] ?? 0) + 1;

    $sql = "INSERT INTO poste_geolocalizacao
            (nota,id_geolocalizacao,tipo_poste,material_poste,altura_poste,esforco_poste,
             coordenada_latitude,cordenada_longitude,latitude,longitude,fk_obra_id_obra)
            VALUES('$notaBanco',$idGeo,'$tipoBanco','$materialBanco','$alturaBanco','$esforcoBanco',
                   $latitudeBanco,$longitudeBanco,$latitudeBanco,$longitudeBanco,$obra)";

    if (!mysqli_query($conn, $sql)) {
        die('Erro ao salvar o poste: ' . $conn->error . ' <script>history.back();</script>');
    }

    $asBuiltBanco = $asBuilt > 0 ? $asBuilt : 'NULL';
    $sql = "INSERT INTO pontos_coletados
            (nota_obra,latitude,longitude,fk_as_built_n_as_built,
             fk_poste_geolocalizacao_nota,fk_poste_geolocalizacao_id_geolocalizacao)
            VALUES('$notaObra',$latitudeBanco,$longitudeBanco,$asBuiltBanco,'$notaBanco',$idGeo)";

    if (!mysqli_query($conn, $sql)) {
        die('Erro ao salvar o ponto: ' . $conn->error . ' <script>history.back();</script>');
    }

    $idPonto = mysqli_insert_id($conn);
    $mensagem = 'Ponto cadastrado com sucesso!';

} else {
    $resultado = mysqli_query($conn, "SELECT fk_poste_geolocalizacao_nota,fk_poste_geolocalizacao_id_geolocalizacao
                                       FROM pontos_coletados WHERE id_ponto=$idPonto LIMIT 1");
    $ponto = mysqli_fetch_assoc($resultado);

    if (!$ponto) {
        die('Ponto não encontrado. <script>history.back();</script>');
    }

    $notaPoste = $conn->real_escape_string($ponto['fk_poste_geolocalizacao_nota']);
    $idGeo = (int)$ponto['fk_poste_geolocalizacao_id_geolocalizacao'];
    $asBuiltBanco = $asBuilt > 0 ? $asBuilt : 'NULL';

    $sql = "UPDATE pontos_coletados SET nota_obra='$notaObra', latitude=$latitudeBanco,
            longitude=$longitudeBanco, fk_as_built_n_as_built=$asBuiltBanco
            WHERE id_ponto=$idPonto";

    if (!mysqli_query($conn, $sql)) {
        die('Erro ao atualizar o ponto: ' . $conn->error . ' <script>history.back();</script>');
    }

    $sql = "UPDATE poste_geolocalizacao SET tipo_poste='$tipoBanco', material_poste='$materialBanco',
            altura_poste='$alturaBanco', esforco_poste='$esforcoBanco',
            coordenada_latitude=$latitudeBanco, cordenada_longitude=$longitudeBanco,
            latitude=$latitudeBanco, longitude=$longitudeBanco, fk_obra_id_obra=$obra
            WHERE nota='$notaPoste' AND id_geolocalizacao=$idGeo";

    if (!mysqli_query($conn, $sql)) {
        die('Erro ao atualizar o poste: ' . $conn->error . ' <script>history.back();</script>');
    }

    $mensagem = 'Ponto atualizado com sucesso!';
}

$estruturaPrimaria = $conn->real_escape_string($estruturaPrimaria);
$estruturaSecundaria = $conn->real_escape_string($estruturaSecundaria);
$tipoEquipamento = $conn->real_escape_string($tipoEquipamento);
$potencia = $conn->real_escape_string($potencia);
$placa = $conn->real_escape_string($placa);
$cia = $conn->real_escape_string($cia);

mysqli_query($conn, "DELETE FROM estrutura_primaria WHERE fk_pontos_coletados_id_ponto=$idPonto");
if ($estruturaPrimaria !== '') {
    mysqli_query($conn, "INSERT INTO estrutura_primaria(tipo_estrutura,fk_pontos_coletados_id_ponto)
                         VALUES('$estruturaPrimaria',$idPonto)");
}

mysqli_query($conn, "DELETE FROM estrutura_secundaria WHERE fk_pontos_coletados_id_ponto=$idPonto");
if ($estruturaSecundaria !== '') {
    mysqli_query($conn, "INSERT INTO estrutura_secundaria(tipo_estrutura,fk_pontos_coletados_id_ponto)
                         VALUES('$estruturaSecundaria',$idPonto)");
}

mysqli_query($conn, "DELETE FROM equipamentos
                     WHERE fk_poste_geolocalizacao_nota='$notaBanco'
                     AND fk_poste_geolocalizacao_id_geolocalizacao=$idGeo");

if ($tipoEquipamento !== '' || $potencia !== '' || $placa !== '' || $cia !== '') {
    mysqli_query($conn, "INSERT INTO equipamentos(tipo,potencia,placa,cia,
                         fk_poste_geolocalizacao_nota,fk_poste_geolocalizacao_id_geolocalizacao)
                         VALUES('$tipoEquipamento','$potencia','$placa','$cia','$notaBanco',$idGeo)");
}

if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['name'])) {
    $pasta = dirname(__DIR__) . '/uploads';

    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    for ($i = 0; $i < count($_FILES['fotos']['name']); $i++) {
        if ($_FILES['fotos']['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $nomeOriginal = $_FILES['fotos']['name'][$i];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extensao, $permitidas)) {
            continue;
        }

        $novoNome = uniqid('rd360_') . '.' . $extensao;
        $destino = $pasta . '/' . $novoNome;

        if (move_uploaded_file($_FILES['fotos']['tmp_name'][$i], $destino)) {
            $caminho = $conn->real_escape_string('uploads/' . $novoNome);
            mysqli_query($conn, "INSERT INTO fotos(caminho_imagem,data_foto,fk_pontos_coletados_id_ponto,n_ponto)
                                 VALUES('$caminho',CURDATE(),$idPonto,$idPonto)");
        }
    }
}

echo '<script>alert(' . json_encode($mensagem, JSON_UNESCAPED_UNICODE) . ');location.href="../pontos.html";</script>';
?>