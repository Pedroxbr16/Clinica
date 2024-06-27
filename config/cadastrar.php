<?php
session_start();
include_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_paciente'])) {
    $nome = $_POST['nome'];
    $nascimento = $_POST['nascimento'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $telefone = $_POST['telefone'];
    $celular = $_POST['celular'];
    $numero = $_POST['numero'];

    // Verificar duplicidade de nome e CPF
    $sqlcheck = "SELECT * FROM pacientes WHERE nome='$nome' OR cpf='$cpf'";
    $resultcheck = $conexao->query($sqlcheck);

    if ($resultcheck->num_rows > 0) {
        $_SESSION['mensagem'] = "Erro: Paciente já cadastrado com o mesmo nome ou CPF.";
        $_SESSION['mensagem_tipo'] = "erro";
        header('Location: ../front/home.php');
        exit();
    }

    // Verificações adicionais no servidor
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensagem'] = "Erro: Formato de email inválido.";
        $_SESSION['mensagem_tipo'] = "erro";
        header('Location: ../front/home.php');
        exit();
    }

    $telefoneRegex = "/^\(\d{2}\) \d{4}-\d{4}$/";
    $celularRegex = "/^\(\d{2}\) \d{5}-\d{4}$/";

    if ($telefone && !preg_match($telefoneRegex, $telefone)) {
        $_SESSION['mensagem'] = "Erro: Formato de telefone inválido.";
        $_SESSION['mensagem_tipo'] = "erro";
        header('Location: ../front/home.php');
        exit();
    }

    if ($celular && !preg_match($celularRegex, $celular)) {
        $_SESSION['mensagem'] = "Erro: Formato de celular inválido.";
        $_SESSION['mensagem_tipo'] = "erro";
        header('Location: ../front/home.php');
        exit();
    }

    $foto_nome = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $foto_nome = uniqid() . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        $foto_caminho = 'uploads/' . $foto_nome;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (!move_uploaded_file($foto['tmp_name'], $foto_caminho)) {
            $_SESSION['mensagem'] = "Erro ao salvar a foto.";
            $_SESSION['mensagem_tipo'] = "erro";
            header('Location: ../front/home.php');
            exit();
        }
    }

    $sqlinsert = "INSERT INTO pacientes (nome, nascimento, cpf, email, endereco, bairro, cidade, estado, cep, telefone, celular, numero, foto) VALUES ('$nome', '$nascimento', '$cpf', '$email', '$endereco', '$bairro', '$cidade', '$estado', '$cep', '$telefone', '$celular', '$numero', '$foto_nome')";

    if ($conexao->query($sqlinsert) === TRUE) {
        $_SESSION['mensagem'] = "Paciente cadastrado com sucesso";
        $_SESSION['mensagem_tipo'] = "sucesso";
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar paciente: " . $conexao->error;
        $_SESSION['mensagem_tipo'] = "erro";
    }

    header('Location: ../front/home.php');
    exit();
}
?>
