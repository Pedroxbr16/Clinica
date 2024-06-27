<?php
include_once('../config.php');

if (!empty($_GET['id'])) {
    $historico_id = $_GET['id'];
    $sqlselect = "SELECT * FROM historico WHERE id='$historico_id'";
    $result = $conexao->query($sqlselect);

    if ($result && $result->num_rows > 0) {
        $historico = $result->fetch_assoc();
        $paciente_id = $historico['paciente_id'];
        $data_consulta = $historico['data_consulta'];
        $historico_texto = $historico['historico'];
    } else {
        echo "Histórico não encontrado.";
        exit();
    }
} else {
    echo "ID do histórico não fornecido.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_historico'])) {
    $data_consulta = $_POST['data_consulta'];
    $historico_texto = $_POST['historico'];

    $sqlupdate = "UPDATE historico SET 
                    data_consulta='$data_consulta',
                    historico='$historico_texto'
                  WHERE id='$historico_id'";

    if ($conexao->query($sqlupdate) === TRUE) {
        header("Location: editar.php?id=$paciente_id");
    } else {
        echo "Erro ao atualizar histórico: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Histórico</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Editar Histórico</h2>
        <form action="editar_historico.php?id=<?php echo $historico_id; ?>" method="post">
            <div class="form-group">
                <label for="data_consulta">Data</label>
                <input type="date" class="form-control" id="data_consulta" name="data_consulta" value="<?php echo $data_consulta; ?>" required>
            </div>
            <div class="form-group">
                <label for="historico">Histórico</label>
                <textarea class="form-control" id="historico" name="historico" rows="3" required><?php echo $historico_texto; ?></textarea>
            </div>
            <button type="submit" name="update_historico" class="btn btn-primary">Salvar</button>
            <a href="editar.php?id=<?php echo $paciente_id; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
