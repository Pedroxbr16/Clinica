<?php
// Conexão com o banco de dados
$conexao = mysqli_connect("127.0.0.1", "root", "", "angelo");

if ($conexao->connect_errno) {
    die("Falha ao conectar: (" . $conexao->connect_errno . ") " . $conexao->connect_error);
}

$query = "";
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search = mysqli_real_escape_string($conexao, $_GET['query']);
    $query = "WHERE nome LIKE '%$search%' OR cpf LIKE '%$search%' OR email LIKE '%$search%'";
}

$registros_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

$sql = "SELECT id, nome, cpf, email FROM pacientes $query ORDER BY nome ASC LIMIT $offset, $registros_por_pagina";
$result = $conexao->query($sql);

$tabela = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tabela .= "<tr onclick=\"location.href='../config/editar.php?id={$row['id']}';\">";
        $tabela .= "<td>{$row['nome']}</td>";
        $tabela .= "<td>{$row['cpf']}</td>";
        $tabela .= "<td>{$row['email']}</td>";
        $tabela .= "</tr>";
    }
} else {
    $tabela = "<tr><td colspan='3' class='text-center'>Nenhum paciente cadastrado</td></tr>";
}

// Código para paginação
$sql_total = "SELECT COUNT(*) as total FROM pacientes $query";
$result_total = $conexao->query($sql_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

$paginacao = '<nav><ul class="pagination justify-content-center">';
$limite_paginas = 10;
$start = max(1, $pagina_atual - intval($limite_paginas / 2));
$end = min($total_paginas, $start + $limite_paginas - 1);

if ($start > 1) {
    $paginacao .= '<li class="page-item"><a class="page-link" href="#" data-page="1">&laquo; Primeira</a></li>';
}

if ($pagina_atual > 1) {
    $paginacao .= '<li class="page-item"><a class="page-link" href="#" data-page="'.($pagina_atual - 1).'">&lsaquo; Anterior</a></li>';
}

for ($i = $start; $i <= $end; $i++) {
    $active = $i == $pagina_atual ? 'active' : '';
    $paginacao .= '<li class="page-item '. $active .'"><a class="page-link" href="#" data-page="'. $i .'">'. $i .'</a></li>';
}

if ($pagina_atual < $total_paginas) {
    $paginacao .= '<li class="page-item"><a class="page-link" href="#" data-page="'.($pagina_atual + 1).'">Próxima &rsaquo;</a></li>';
}

if ($end < $total_paginas) {
    $paginacao .= '<li class="page-item"><a class="page-link" href="#" data-page="'. $total_paginas .'">Última &raquo;</a></li>';
}

$paginacao .= '</ul></nav>';

echo json_encode(['tabela' => $tabela, 'paginacao' => $paginacao]);
?>
