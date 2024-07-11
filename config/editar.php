<?php
include_once('../config.php');

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sqlselect = "SELECT * FROM pacientes WHERE id='$id'";
    $result = $conexao->query($sqlselect);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id = $user['id'];
        $nome = $user['nome'];
        $nascimento = $user['nascimento'];
        $cpf = $user['cpf'];
        $email = $user['email'];
        $endereco = $user['endereco'];
        $bairro = $user['bairro'];
        $cidade = $user['cidade'];
        $estado = $user['estado'];
        $cep = $user['cep'];
        $telefone = $user['telefone'];
        $celular = $user['celular'];
        $foto = isset($user['foto']) ? $user['foto'] : '';
    } else {
        header('Location: home.php');
        exit();
    }
} else {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_paciente'])) {
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

        // Processar a foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $foto = $_FILES['foto'];
            $foto_nome = uniqid() . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
            $foto_caminho = 'uploads/' . $foto_nome;

            // Verificar se a pasta de uploads existe, se não, criar
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            // Mover o arquivo para o diretório de uploads
            if (move_uploaded_file($foto['tmp_name'], $foto_caminho)) {
                // Deletar a foto antiga, se existir
                if (!empty($user['foto']) && file_exists('uploads/' . $user['foto'])) {
                    unlink('uploads/' . $user['foto']);
                }

                // Atualizar o campo de foto no banco de dados
                $sqlupdate = "UPDATE pacientes SET foto='$foto_nome' WHERE id='$id'";
                $conexao->query($sqlupdate);
                $foto = $foto_nome; // Atualizar o valor da variável $foto
            } else {
                echo "Erro ao salvar a foto.";
                exit();
            }
        }

        $sqlupdate = "UPDATE pacientes SET 
                        nome='$nome',
                        nascimento='$nascimento',
                        cpf='$cpf',
                        email='$email',
                        endereco='$endereco',
                        bairro='$bairro',
                        cidade='$cidade',
                        estado='$estado',
                        cep='$cep',
                        telefone='$telefone',
                        celular='$celular'
                      WHERE id='$id'";

        if ($conexao->query($sqlupdate) === TRUE) {
            echo "<script>alert('Paciente alterado com sucesso');</script>";
        } else {
            echo "<script>alert('Erro ao alterar paciente');</script>" . $conexao->error;
        }
    } elseif (isset($_POST['create_historico'])) {
        $data_consulta = $_POST['data_consulta'];
        $historico = $_POST['historico'];

        $sqlhistorico = "INSERT INTO historico (paciente_id, data_consulta, historico) VALUES ('$id', '$data_consulta', '$historico')";

        if ($conexao->query($sqlhistorico) === TRUE) {
            echo "<script>alert('Histórico adicionado com sucesso');</script>";
        } else {
            echo "<script>alert('Erro ao adicionar o histórico');</script>" . $conexao->error;
        }
    }
}

// Configuração de paginação para os históricos
$historicos_por_pagina = 10;
$pagina_atual_historico = isset($_GET['pagina_historico']) ? intval($_GET['pagina_historico']) : 1;
$offset_historico = ($pagina_atual_historico - 1) * $historicos_por_pagina;

// Obter total de registros de históricos para paginação
$sql_total_historico = "SELECT COUNT(*) as total FROM historico WHERE paciente_id='$id'";
$result_total_historico = $conexao->query($sql_total_historico);
$total_historicos = $result_total_historico->fetch_assoc()['total'];
$total_paginas_historico = ceil($total_historicos / $historicos_por_pagina);

// Obter registros de históricos da página atual
$sqlhistoricos = "SELECT * FROM historico WHERE paciente_id='$id' LIMIT $offset_historico, $historicos_por_pagina";
$resulthistoricos = $conexao->query($sqlhistoricos);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"   />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 50px;
    }

    h2 {
        color: #007bff;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-group label {
        font-weight: bold;
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

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #545b62;
        border-color: #343a40;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .form-control,
    .form-control-file {
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

    .photo-preview {
        border: 1px solid #ced4da;
        border-radius: 5px;
        width: 150px;
        height: 150px;
        object-fit: cover;
        display: block;
        margin-top: 10px;
    }

    .custom-file-label::after {
        content: "Escolher arquivo";
    }

    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    .nav-tabs .nav-link {
        color: #007bff;
    }

    .modal-body p {
        font-size: 1.2rem;
    }

    .hidden {
        display: none;
    }

    .historico-item {
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .historico-item:hover {
        background-color: #f1f1f1;
    }

    .photo-preview {
        border: 1px solid #ced4da;
        border-radius: 5px;
        width: 150px;
        height: 150px;
        object-fit: cover;
        display: block;
        margin-top: 10px;
    }

    .custom-file-label::after {
        content: "Escolher arquivo";
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Cadastro de Paciente</h2>
        <div class="text-right">
            <button type="button" class="btn btn-danger p-2" onclick="confirmarExclusao()"><i
                    class="fa-solid fa-trash p-2 "></i> Excluir </i></button>

        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="ficha-tab" data-toggle="tab" href="#ficha" role="tab"
                    aria-controls="ficha" aria-selected="true">Ficha</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="historico-tab" data-toggle="tab" href="#historico" role="tab"
                    aria-controls="historico" aria-selected="false">Histórico</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="ficha" role="tabpanel" aria-labelledby="ficha-tab">
                <form action="editar.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data"
                    onsubmit="return validarFormulario()">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome; ?>"
                                required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="foto">Foto</label>
                            <img id="photoPreview" class="photo-preview" src="uploads/<?php echo $foto; ?>"
                                alt="Preview da Foto" style="<?php echo empty($foto) ? 'display: none;' : ''; ?>">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*"
                                    capture="camera" onchange="previewPhoto(event)">
                                <label class="custom-file-label" for="foto">Escolher arquivo</label>
                            </div>
                        </div>

                    </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo $cpf; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="nascimento">Data de Nascimento</label>
                    <input type="date" class="form-control" id="nascimento" name="nascimento"
                        value="<?php echo $nascimento; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $endereco; ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="bairro">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $bairro; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="cidade">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo $cidade; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="estado">Estado</label>
                    <input type="text" class="form-control" id="estado" name="estado" value="<?php echo $estado; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" value="<?php echo $cep; ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone"
                        value="<?php echo $telefone; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="celular">Celular</label>
                    <input type="text" class="form-control" id="celular" name="celular" value="<?php echo $celular; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <button type="submit" name="update_paciente" class="btn btn-primary btn-block">Salvar</button>
                </div>
                <div class="form-group col-md-6">
                    <button type="button" onclick="window.location.href='../front/home.php'"
                        class="btn btn-secondary btn-block">Cancelar</button>
                </div>
            </div>
            </form>
        </div>
        <div class="tab-pane fade" id="historico" role="tabpanel" aria-labelledby="historico-tab">
            <div class="mt-3">
                <h6>Criar Novo Histórico</h6>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="data_consulta">Data</label>
                        <input type="date" class="form-control" id="data_consulta" name="data_consulta" required>
                    </div>
                    <div class="form-group">
                        <label for="historico">Histórico</label>
                        <textarea class="form-control" id="historico" name="historico" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="create_historico" class="btn btn-success">Adicionar Histórico</button>
                </form>

                <div id="historicoContent" class="mt-3">
                    <hr>
                    <h6>Históricos Existentes</h6>
                    <?php if ($resulthistoricos->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while($row = $resulthistoricos->fetch_assoc()): ?>
                        <li class="list-group-item historico-item"
                            onclick="location.href='editar_historico.php?id=<?php echo $row['id']; ?>'">
                            <strong>Data:</strong> <?php echo $row['data_consulta']; ?><br>
                            <strong>Histórico:</strong> <?php echo $row['historico']; ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                    <!-- Paginação dos Históricos -->
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php
                                    if ($pagina_atual_historico > 1) {
                                        echo '<li class="page-item"><a class="page-link" href="?id=' . $id . '&pagina_historico=' . ($pagina_atual_historico - 1) . '">&laquo; Anterior</a></li>';
                                    }

                                    for ($i = 1; $i <= $total_paginas_historico; $i++) {
                                        $active = $i == $pagina_atual_historico ? 'active' : '';
                                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="?id=' . $id . '&pagina_historico=' . $i . '">' . $i . '</a></li>';
                                    }

                                    if ($pagina_atual_historico < $total_paginas_historico) {
                                        echo '<li class="page-item"><a class="page-link" href="?id=' . $id . '&pagina_historico=' . ($pagina_atual_historico + 1) . '">Próxima &raquo;</a></li>';
                                    }
                                    ?>
                        </ul>
                    </nav>
                    <?php else: ?>
                    <p>Nenhum histórico encontrado para este paciente.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#cpf').mask('000.000.000-00');
        $('#cep').mask('00000-000');
        $('#telefone').mask('(00) 0000-0000');
        $('#celular').mask('(00) 00000-0000');

        $('#email').on('blur', function() {
            var email = $(this).val();
            var re = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
            if (!re.test(email)) {
                $('#email-error').text('Formato de email inválido');
            } else {
                $('#email-error').text('');
            }
        });
    });

    function previewPhoto(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('photoPreview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    function confirmarExclusao() {
        if (confirm('Tem certeza que deseja excluir este paciente?')) {
            window.location.href = 'excluir.php?id=<?php echo $id; ?>';
        }
    }

    function validarFormulario() {
        var email = document.getElementById('email').value;
        var emailRegex = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
        if (!emailRegex.test(email)) {
            document.getElementById('email-error').textContent = 'Formato de email inválido';
            return false;
        }

        var telefone = document.getElementById('telefone').value;
        var celular = document.getElementById('celular').value;
        var telefoneRegex = /^\(\d{2}\) \d{4}-\d{4}$/;
        var celularRegex = /^\(\d{2}\) \d{5}-\d{4}$/;

        if (telefone && !telefoneRegex.test(telefone)) {
            document.getElementById('telefone-error').textContent = 'Formato de telefone inválido';
            return false;
        } else {
            document.getElementById('telefone-error').textContent = '';
        }

        if (celular && !celularRegex.test(celular)) {
            document.getElementById('celular-error').textContent = 'Formato de celular inválido';
            return false;
        } else {
            document.getElementById('celular-error').textContent = '';
        }

        return true;
    }
    </script>
    <!-- script do photopreview -->
    <script>
    function previewPhoto(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('photoPreview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>