<?php

include_once('config.php');
if(isset($_POST['cadastrarmedico']));
$nome = $_POST["nome"];
$cpf = $_POST["cpf"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$telefone = $_POST["telefone"];
$sexo = $_POST["sexo"];
$nascimento = $_POST["data_nascimento"];
$cep = $_POST["cep"];
$cidade = $_POST["cidade"];
$estado = $_POST["estado"];
$endereco = $_POST["endereco"];
$crm = $_POST["crm_medico"];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verificar se o e-mail já existe no banco de dados
    $consulta = "SELECT * FROM usuario WHERE email = '$email'";
    $resultado = $conexao->query($consulta);

    if ($resultado->num_rows > 0) {
    session_start();
        $_SESSION['erros']=$erros[]= "<li> email cadastrado </li>";
        header('location:formulariomedico.php');
    } else
    $inserirdados = " INSERT INTO medico (nome,  cpf, nascimento, telefone, sexo, cep, endereco, estado, cidade, crm_medico)
    VALUES('$nome' , '$cpf' , '$nascimento' , '$telefone' , '$sexo' , '$cep' , '$endereco', '$estado' , '$cidade' ,  '$crm_medico'  )";
    
    $inserirlogin = "INSERT INTO usuario (email, senha, id) values ('$email' , '$senha', '$crm')";
    $inserindo = mysqli_query($conexao, $inserirdados);
    $inserindo = mysqli_query($conexao, $inserirlogin);
    
    header('location: home.php');
}


    
    













?>