<?php
require_once __DIR__ . '/conexao.php';

$resultado = mysqli_query($conn, "SELECT DATABASE() AS banco");
$dados = mysqli_fetch_assoc($resultado);

echo '<h1>Conexão realizada com sucesso!</h1>';
echo '<p>Banco conectado: <strong>' . htmlspecialchars($dados['banco']) . '</strong></p>';
?>