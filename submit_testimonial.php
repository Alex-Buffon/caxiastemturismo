<?php
require_once __DIR__ . '/admin/config.php';



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$destino = trim($_POST['destino'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');
$nota = (int)($_POST['nota'] ?? 0);

if ($nome === '' || $email === '' || $mensagem === '' || $nota < 1 || $nota > 5) {
    header('Location: index.php?testimonial_status=error&msg=Preencha+todos+os+campos+e+avalie+com+1+a+5+estrelas');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?testimonial_status=error&msg=Email+inv%C3%A1lido');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

$stmt = $conn->prepare('INSERT INTO depoimentos (nome, email, cidade, destino, mensagem, nota, ip, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssssiis', $nome, $email, $cidade, $destino, $mensagem, $nota, $ip, $user_agent);
$stmt->execute();
$stmt->close();

header('Location: index.php?testimonial_status=success');
exit;
