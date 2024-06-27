<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
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
        .form-control, .form-control-file {
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
        .error-message {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Paciente</h2>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="ficha-tab" data-toggle="tab" href="#ficha" role="tab" aria-controls="ficha" aria-selected="true">Ficha</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="historico-tab" data-toggle="tab" href="#historico" role="tab" aria-controls="historico" aria-selected="false">Histórico</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="ficha" role="tabpanel" aria-labelledby="ficha-tab">
                <form id="pacienteForm" action="../config/cadastrar.php" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="foto">Foto</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*" capture="camera" onchange="previewPhoto(event)">
                                <label class="custom-file-label" for="foto">Escolher arquivo</label>
                            </div>
                            <img id="photoPreview" class="photo-preview" src="#" alt="Preview da Foto" style="display: none;">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="cep">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" onblur="pesquisarCep()" required>
                    </div>
                   <div class="form-row">
                   <div class="form-group col-md-8 ">
                        <label for="endereco">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="numero">Numero</label>
                        <input type="text" class="form-control" id="numero" name="numero">
                    </div>
                   </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="bairro">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cidade">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-control" id="estado" name="estado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" required>
                        <span class="error-message" id="cpf-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento</label>
                        <input type="date" class="form-control" id="nascimento" name="nascimento" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <span class="error-message" id="email-error"></span>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone">
                            <span class="error-message" id="telefone-error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular">
                            <span class="error-message" id="celular-error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <button type="submit" name="cadastrar_paciente" class="btn btn-primary btn-block">Salvar</button>
                        </div>
                        <div class="form-group col-md-6">
                            <button type="reset" onclick="window.location.href='../front/home.php'" class="btn btn-secondary btn-block">Cancelar</button>
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
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
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
        function pesquisarCep() {
            var cep = document.getElementById('cep').value.replace(/\D/g, '');
            if (cep != '') {
                var validacep = /^[0-9]{8}$/;
                if (validacep.test(cep)) {
                    fetch('https://viacep.com.br/ws/' + cep + '/json/')
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById('endereco').value = data.logradouro;
                                document.getElementById('bairro').value = data.bairro;
                                document.getElementById('cidade').value = data.localidade;
                                document.getElementById('estado').value = data.uf;
                            } else {
                                alert('CEP não encontrado.');
                            }
                        })
                        .catch(error => console.error('Erro ao buscar o CEP:', error));
                } else {
                    alert('Formato de CEP inválido.');
                }
            }
        }
        $(document).ready(function(){
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
