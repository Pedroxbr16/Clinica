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
    </style>
</head>
<body>
    <div class="container">
        <h2>Pacientes Cadastrados</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar paciente...">
        </div>
        <div class="text-right">
            <a href="formulariopaciente.php" class="btn btn-primary">Cadastrar Paciente</a>
        </div>
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
                        <tr onclick="location.href='editar.php?id=<?php echo $row['id']; ?>'">
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
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.querySelectorAll('#patientsTable tbody tr');

            rows.forEach(function(row) {
                var name = row.cells[0].textContent.toLowerCase();
                var cpf = row.cells[1].textContent.toLowerCase();
                var email = row.cells[2].textContent.toLowerCase();

                if (name.includes(searchValue) || cpf.includes(searchValue) || email.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>