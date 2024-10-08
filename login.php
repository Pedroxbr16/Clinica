<?php 
session_start();
include_once('config.php');


if(isset($_POST['submit-entrar'])):
    $erros = array();
    $email = mysqli_escape_string($conexao, $_POST['email']);
    $senha = mysqli_escape_string($conexao, $_POST['senha']);

    if(empty($email) or empty($senha))
        $erros[]= "<li> o campo login/senha precisa ser preenchido </li>";

    else
        $sql = "SELECT * FROM usuario WHERE email = '$email' and senha = '$senha'";
        $resultado = mysqli_query($conexao, $sql);

        if(mysqli_num_rows($resultado) > 0):
            $sql = "SELECT * FROM usuario WHERE email = '$email' and senha = '$senha'";
            $resultado = mysqli_query($conexao, $sql);

            if(mysqli_num_rows($resultado) == 1 )
            $dados = mysqli_fetch_array($resultado);
            $_SESSION['email'] = $dados['email'];
            $_SESSION['senha'] = $dados['senha'];
            $_SESSION['id'] = $dados['id'];


        
            header('location:./front/home.php');

        else:
            $erros[] = "<li>usuario ou senha nao conferem</li>";
        endif;

        else:
            

    endif;        

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #BEDCFE;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: 'Arial', sans-serif;
        background-image: url('css/imagens/fundo_login.png');
    }
    h2 {
        color: black;
        margin-bottom: 20px;
        text-align: center;
        font-weight:bold
    }
    .login-container {
        background-color: #fff;
        border-radius: 10px;
        -webkit-box-shadow: 7px 10px 29px 5px rgba(0, 0, 0, 1);
        -moz-box-shadow: 7px 10px 29px 5px rgba(0, 0, 0, 1);
        box-shadow: 7px 10px 29px 5px rgba(0, 0, 0, 1);
        padding: 30px;
        max-width: 400px;
        width: 100%;
    }



    .login-container .form-group label {
        font-weight: bold;
        color: 	#696969;

    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 10px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .forgot-password {
        display: block;
        text-align: right;
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .forgot-password a {
        color: #007bff;
        text-decoration: none;
    }

    .forgot-password a:hover {
        text-decoration: underline;
    }

    .inputSubmit {
        width: 100%;
        margin-top: 20px;
        color: white;
        background-color: #1A9DF0;
        border: 0px;
        height: 35px;
        border-radius: 30px;
    }
    </style>
</head>

<body>
<h2>Bem Vindo <br> Faça seu login</h2>
    <div class="login-container">
      
        <form method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <input class="inputSubmit" type="submit" name="submit-entrar" value="Entrar">

        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>