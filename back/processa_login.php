<?php 
include_once('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verificar se o e-mail já existe no banco de dados
    $consulta = "SELECT * FROM usuario WHERE email = '$email'";
    $resultado = $conexao->query($consulta);

    if ($resultado->num_rows > 0) {
        echo "Este e-mail já está cadastrado. Por favor, use outro e-mail.";
    } else
header('location:login.php');
}
?>