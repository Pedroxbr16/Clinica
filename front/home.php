<?php
session_start();

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    echo "<script>alert('$mensagem');</script>";
    unset($_SESSION['mensagem']);
}

// Conexão com o banco de dados
$conexao = mysqli_connect("127.0.0.1", "root", "", "angelo");

if ($conexao->connect_errno) {
    echo "Falha ao conectar: (" . $conexao->connect_errno . ") " . $conexao->connect_error;
}

// Configuração de paginação
$registros_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Filtros de busca
$filtro = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conexao, $_GET['search']);
    $filtro = "WHERE nome LIKE '%$search%' OR cpf LIKE '%$search%' OR email LIKE '%$search%'";
}

// Obter total de registros para paginação
$sql_total = "SELECT COUNT(*) as total FROM pacientes $filtro";
$result_total = $conexao->query($sql_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obter registros da página atual
$sql = "SELECT id, nome, cpf, email FROM pacientes $filtro ORDER BY nome ASC LIMIT $offset, $registros_por_pagina";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes Cadastrados</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .table {
            margin-top: 20px;
            cursor: pointer;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .btn {
            margin-bottom: 20px;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="text-right">
            <a href="../login.php" class="btn btn-primary">Deslogar</a>
        </div>
        <h2>Pacientes Cadastrados</h2>
        <form class="search-bar" method="get" action="">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Buscar paciente...">
        </form>
        
        <table class="table table-bordered" id="patientsTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr onclick="location.href='../config/editar.php?id=<?php echo $row['id']; ?>'">
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['cpf']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Nenhum paciente cadastrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php
                // Limite de páginas visíveis na paginação
                $limite_paginas = 10;
                $start = max(1, $pagina_atual - intval($limite_paginas / 2));
                $end = min($total_paginas, $start + $limite_paginas - 1);
                
                if ($start > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?pagina=1&search='. (isset($_GET['search']) ? $_GET['search'] : '') .'">&laquo; Primeira</a></li>';
                }
                
                if ($pagina_atual > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?pagina='. ($pagina_atual - 1) .'&search='. (isset($_GET['search']) ? $_GET['search'] : '') .'">&lsaquo; Anterior</a></li>';
                }
                
                for ($i = $start; $i <= $end; $i++) {
                    $active = $i == $pagina_atual ? 'active' : '';
                    echo '<li class="page-item '. $active .'"><a class="page-link" href="?pagina='. $i .'&search='. (isset($_GET['search']) ? $_GET['search'] : '') .'">'. $i .'</a></li>';
                }
                
                if ($pagina_atual < $total_paginas) {
                    echo '<li class="page-item"><a class="page-link" href="?pagina='. ($pagina_atual + 1) .'&search='. (isset($_GET['search']) ? $_GET['search'] : '') .'">Próxima &rsaquo;</a></li>';
                }
                
                if ($end < $total_paginas) {
                    echo '<li class="page-item"><a class="page-link" href="?pagina='. $total_paginas .'&search='. (isset($_GET['search']) ? $_GET['search'] : '') .'">Última &raquo;</a></li>';
                }
                ?>
            </ul>
        </nav>

        <div class="text-right">
            <a href="formulariopaciente.php" class="btn btn-primary">Cadastrar Paciente</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase();
                var rows = $('#patientsTable tbody tr');

                rows.each(function() {
                    var name = $(this).find('td:eq(0)').text().toLowerCase();
                    var cpf = $(this).find('td:eq(1)').text().toLowerCase();
                    var email = $(this).find('td:eq(2)').text().toLowerCase();

                    if (name.includes(searchValue) || cpf.includes(searchValue) || email.includes(searchValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
      <script>
        $(document).ready(function() {
            function loadPacientes(query = '') {
                $.ajax({
                    url: 'buscar_pacientes.php',
                    method: 'GET',
                    data: { query: query },
                    success: function(response) {
                        $('#pacientesTableBody').html(response);
                    }
                });
            }

            loadPacientes();

            $('#search').on('input', function() {
                const query = $(this).val();
                loadPacientes(query);
            });
        });
    </script>
</body>
</html>
