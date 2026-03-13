<?php
require_once 'config.php';
checkLogin();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="depoimentos_aprovados.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Nome', 'Email', 'Cidade', 'Destino', 'Nota', 'Mensagem', 'Enviado Em', 'Aprovado Em', 'IP', 'User-Agent']);

$sql = "SELECT * FROM depoimentos WHERE status = 'approved' ORDER BY aprovado_em DESC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['nome'],
        $row['email'],
        $row['cidade'],
        $row['destino'],
        $row['nota'],
        $row['mensagem'],
        $row['criado_em'],
        $row['aprovado_em'],
        $row['ip'],
        $row['user_agent'],
    ]);
}

fclose($output);
exit;
