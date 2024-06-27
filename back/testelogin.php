<?php 
session_start();
include_once('config.php');
// testando para ve se esta logado 
if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true))
{
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$sql = " SELECT * FROM responsavel ORDER BY nome DESC";
$result = $conexao->query($sql);
 
// testando a validação de senha 
if(isset($_POST['submit-entrar'])):
    $erros = array();
    $email = mysqli_escape_string($conexao, $_POST['email']) ;
    $senha = mysqli_escape_string($conexao, $_POST['senha']);

    if(empty($email) or empty($senha))
        $erros[]= "<li> o campo login/senha precisa ser preenchido </li>";

    else
        $sql = "SELECT * FROM responsavel WHERE email = '$email' and senha = '$senha'";
        $resultado = mysqli_query($conexao, $sql);

        if(mysqli_num_rows($resultado) == 1):
            $sql = "SELECT * FROM responsavel" ;
            $resultado = mysqli_query($conexao, $sql);
            $dados = mysqli_fetch_array($resultado);
            $_SESSION['logado'] = true; 
            $_SESSION['email'] = $dados['email'];
            $_SESSION['senha'] = $dados['senha'];
            
            header('location:funcao.php');
                
        else:
            $erros [] = "<li> usuario inexistente ou usuario ou senha nao conferem </li>";
            header('location:login.php');
        endif;
    endif;        


    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true))
{
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    
}


?>


  


































   