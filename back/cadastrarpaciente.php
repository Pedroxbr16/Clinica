<?php
include_once('testelogin.php');
include_once('config.php');


$nome = $_POST["nome"];
$cpf = $_POST["cpf"];
$plano = $_POST["plano"];
$email = $_POST["email"];
$telefone = $_POST["telefone"];
$sexo = $_POST["sexo"];
$nascimento = $_POST["data_nascimento"];
$cep = $_POST["cep"];
$cidade = $_POST["cidade"];
$estado = $_POST["estado"];
$id = $_SESSION['id'];



$inserir = " INSERT INTO paciente (nome,  cpf, nascimento, responsavel, sexo, cep, estado, cidade, telefone, email)
VALUES('$nome','$cpf', '$nascimento', '$id', '$sexo', '$cep', '$estado', '$cidade', '$telefone', '$email' )";
$inserindo = mysqli_query($conexao, $inserir);

header('Location: home.php');
?>
