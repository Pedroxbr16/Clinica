<?php 


include_once('config.php');
 if(isset($_POST['enviar']))
 {
  $nome = $_POST['nome'];
  $cpf = $_POST['cpf'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  $sexo = $_POST['sexo'];
  $nascimento = $_POST['nascimento'];
  $cep = $_POST['cep'];
  $cidade = $_POST['cidade'];
  $estado = $_POST['estado'];
  $observacao = $_POST['observacao'];
  $responsavel = $_POST['responsavel'];
  $matricula_paciente = $_POST['matricula_paciente'];


    
    


    $sqlupdate = "UPDATE paciente SET nome='$nome',  cpf='$cpf', email='$email', telefone='$telefone', sexo='$sexo', nascimento='$nascimento', cep='$cep', cidade='$cidade', observacao='$observacao', estado='$estado',responsavel='$responsavel' WHERE matricula_paciente='$matricula_paciente'";

    $result = mysqli_query($conexao,$sqlupdate);

    
    if (mysqli_query($conexao, $sqlupdate)) {
        echo "Record updated successfully";
        header('location:home.php');
      } else {
        echo "Error updating record: " . mysqli_error($conexao);
      }
      







 }


?>