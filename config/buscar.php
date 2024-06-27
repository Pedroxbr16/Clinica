<?php 
include_once('config.php');
include_once('testelogin.php');



?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
    <style>
      .box-search{
        display: flex;
        justify-content: center;
        gap: .3%;

      }
       body{
            background: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
            color: white;
            text-align: center;
        }
        .table-bg{
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px 15px 0 0;
        }


    </style>
</head>
<body>




 <?php 

 $id = $_SESSION['id'];
 if(!empty($_GET['search']))
 {
     $data = $_GET['search'];
     $sql = "SELECT * FROM paciente WHERE matricula_paciente LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY matricula_paciente DESC";
 }
 else
 {
     $sql = "SELECT * FROM paciente ORDER BY matricula_paciente DESC";
 }
 $result = $conexao->query($sql);







$tabela = $_SESSION['funcao'];
$coluna = $_SESSION['coluna'];


$sql = "SELECT * FROM $tabela WHERE $coluna = '$id'";
$resultado = $conexao->query($sql);

$dados = mysqli_fetch_array($resultado);
$nome = $dados['nome'];





?>
<br>
<h1> Seja Bem Vindo -  <?php echo  $nome; ?></h1>
<br>
<h3>Digite o nome de quem deseja buscar</h3>


<br>

<div class="box-search">

<input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar" >
<button onclick="searchData()" class="btn btn-primary">
<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-search' viewBox='0 0 16 16'>
  <path d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/>
</svg>
</button>
</div>

<br>
<div class=" m-5 ">
<table class = "table text-white table-bg">

  <thead>
    <tr>
     
      <th scope="col">nome</th>
      <th scope="col">cpf</th>
      <th scope="col">data de nascimento </th>
      <th scope="col">sexo</th>
      <th scope="col">cep</th>
      <th scope="col">estado</th>
      <th scope="col">cidade</th>
      <th scope="col">telefone</th>
      <th scope="col">email</th>
      <th scope="col"><i class="bi bi-pencil-fill"></i></th>
      

    </tr>
  </thead>

  <tbody>
    <?php 

$id = $_SESSION['id'];

;








if ($resultado->num_rows > 0) {
  

  if(!empty($_GET['search']))
  {
      $data = $_GET['search'];
      
      $sql = "SELECT * FROM paciente WHERE responsavel = $id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY matricula_paciente DESC";
   
     
     
    
  }
  else
  {
    $sql = "SELECT * FROM paciente ";
   
  }
 
 
  
  $result = $conexao->query($sql);

  
    // Exibir os clientes associados
    while ($row = $result->fetch_assoc()) {

      echo "<tr>";
     
      echo "<td>" . $row['nome']. "</td>";
      echo "<td>" . $row['cpf']. "</td>";
      echo "<td>" . $row['nascimento']. "</td>";
      echo "<td>" . $row['sexo']. "</td>";
      echo "<td>" . $row['cep']. "</td>";
      echo "<td>" . $row['estado']. "</td>";
      echo "<td>" . $row['cidade']. "</td>";
      echo "<td>" . $row['telefone']. "</td>";
      echo "<td>" . $row['email']. "</td>";
      echo "<td>  <a href='editar.php?matricula_paciente=$row[matricula_paciente]' class = 'btn btn-sm btn-primary' > <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
      <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
      </svg></a> 
      <a href='deletar.php?matricula_paciente=$row[matricula_paciente]' class = 'btn btn-sm btn-danger mt-2' > <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
      <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
    </svg></a>
       </td>";
      echo "</tr>";
    }
} else {
    echo "Nenhum cliente associado à matrícula do responsável encontrado.";
} 













    // Consulta para obter pacientes associados ao cliente

    while($user_data = mysqli_fetch_assoc($result))
    {
        

        
    }
    
    ?>

  </tbody>

</table>
</div>







     <a href= "formulariopaciente.php" ><input  type="button" value="Cadastrar Paciente"></a>
     <a href= "buscar.php" ><input  type="button" value="Visualizar Outros Pacientes"></a>
     <a href="home.php"> <input type="button" value=" Voltar ao menu principal"> </a>
</form>
<script>
    var search = document.getElementById('pesquisar');

    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") 
        {
            searchData();
        }
    });

    function searchData()
    {
        window.location = 'buscar.php?search='+search.value;
    }
</script>
</body>
</html>