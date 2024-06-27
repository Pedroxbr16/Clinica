<?php 

$conexao = mysqli_connect("127.0.0.1","root","");



mysqli_select_db($conexao,  "angelo");

if($conexao->connect_errno)
{
    echo "falha ao conectar (" . $mysqli->connect_errno .") " .$mysqli->connect_errno;
}
else
{
    
}
    

?>





