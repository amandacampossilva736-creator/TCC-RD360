<?php
require_once __DIR__ . '/conexao.php';

echo '<h2>Teste da conexão do RD360</h2>';

$resultado = mysqli_query($conn, "SELECT DATABASE() AS banco");
$dados = mysqli_fetch_assoc($resultado);
echo '<p><strong>Banco conectado:</strong> ' . htmlspecialchars($dados['banco']) . '</p>';

$resultado = mysqli_query($conn, "SELECT @@port AS porta");
$dados = mysqli_fetch_assoc($resultado);
echo '<p><strong>Porta utilizada pelo servidor:</strong> ' . htmlspecialchars($dados['porta']) . '</p>';

echo '<h3>Colunas de poste_geolocalizacao:</h3><ul>';
$resultado = mysqli_query($conn, "SHOW COLUMNS FROM poste_geolocalizacao");
while ($coluna = mysqli_fetch_assoc($resultado)) {
    echo '<li>' . htmlspecialchars($coluna['Field']) . '</li>';
}
echo '</ul>';
?>