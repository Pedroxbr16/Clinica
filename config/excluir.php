<?php
session_start();
include_once('../config.php');

if (!empty($_GET['id'])) {
    $id = $_GET['id'];

    // Primeiro, deletar a foto do paciente, se existir
    $sqlselect = "SELECT foto FROM pacientes WHERE id='$id'";
    $result = $conexao->query($sqlselect);
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (!empty($user['foto']) && file_exists('uploads/' . $user['foto'])) {
            unlink('uploads/' . $user['foto']);
        }
    }

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    
        // Excluir históricos relacionados ao paciente
        $sqlDeleteHistorico = "DELETE FROM historico WHERE paciente_id='$id'";
        if ($conexao->query($sqlDeleteHistorico) !== TRUE) {
            echo "Erro ao excluir históricos: " . $conexao->error;
            exit();
        }
    
        // Excluir o paciente
        $sqlDeletePaciente = "DELETE FROM pacientes WHERE id='$id'";
        if ($conexao->query($sqlDeletePaciente) === TRUE) {
            echo "<script>alert('Paciente excluído com sucesso'); window.location.href='../front/home.php';</script>";
        } else {
            echo "Erro ao excluir paciente: " . $conexao->error;
        }
    } else {
        echo "ID de paciente não fornecido.";
    }


    header('Location: ../front/home.php');
    exit();
} else {
    header('Location: ../front/home.php');
    exit();
}
?>
