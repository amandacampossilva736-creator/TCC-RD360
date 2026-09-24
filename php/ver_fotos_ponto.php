<?php
require_once __DIR__ . '/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$resultado = mysqli_query($conn, "SELECT caminho_imagem FROM fotos WHERE fk_pontos_coletados_id_ponto=$id ORDER BY id_foto DESC");

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo '<p style="font-family:Arial;text-align:center;margin-top:40px;">Nenhuma foto cadastrada para este ponto.</p>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Foto do Ponto</title>
<style>
body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
img { max-width: 90%; max-height: 80vh; margin: 10px; border-radius: 8px; }
</style>
</head>
<body>
<h2>Foto do Ponto</h2>
<?php while ($foto = mysqli_fetch_assoc($resultado)) { ?>
    <img src="../<?php echo htmlspecialchars($foto['caminho_imagem']); ?>" alt="Foto do ponto">
<?php } ?>
</body>
</html>
