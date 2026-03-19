<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// == CONFIGURAÇÕES DE SERVIDOR (PRODUÇÃO) ==
date_default_timezone_set('America/Sao_Paulo');
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0); // Mude para 1 apenas se precisar debugar algum erro na Hostinger

// == CONFIGURAÇÃO DO BANCO DE DADOS (HOSTINGER) ==
// Substitua as variáveis pelos dados gerados no hPanel
$host = 'localhost';
$db   = 'caxiasturismo'; // Ex: u123456789_caxias
$user = 'root';          // Ex: u123456789_admin
$pass = '';              // A senha do banco de dados na Hostinger
// Tentar conectar
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexao
if ($conn->connect_error) {
    die("A conexão com o Banco de Dados falhou: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8mb4");

// Garante que exista pelo menos alguns depoimentos para exibir (usado em demo e durante desenvolvimento)
function seed_default_testimonials($conn) {
    // Cria tabela se não existir
    $conn->query("CREATE TABLE IF NOT EXISTS depoimentos (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      cidade VARCHAR(255) DEFAULT NULL,
      destino VARCHAR(255) DEFAULT NULL,
      mensagem TEXT NOT NULL,
      nota TINYINT(1) DEFAULT NULL,
      status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
      criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      aprovado_em TIMESTAMP NULL DEFAULT NULL,
      ip VARCHAR(45) DEFAULT NULL,
      user_agent TEXT DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Adicionar colunas caso estejam faltando (compatibilidade)
    $conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS cidade VARCHAR(255) DEFAULT NULL;");
    $conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS destino VARCHAR(255) DEFAULT NULL;");
    $conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS nota TINYINT(1) DEFAULT NULL;");
    $conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS ip VARCHAR(45) DEFAULT NULL;");
    $conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS user_agent TEXT DEFAULT NULL;");

    $count = $conn->query("SELECT COUNT(*) AS c FROM depoimentos")->fetch_assoc()['c'];
    if ($count == 0) {
        $stmt = $conn->prepare("INSERT INTO depoimentos (nome, email, cidade, destino, mensagem, nota, status, criado_em, aprovado_em, ip, user_agent) VALUES (?, ?, ?, ?, ?, ?, 'approved', NOW(), NOW(), ?, ?)");
        $samples = [
            ['Maria Silva', 'maria@example.com', 'Porto Alegre', 'Santa Lúcia', 'Adorei a visita! Recomendo para quem busca tranquilidade e boa comida.', 5, '127.0.0.1', 'Seeder'],
            ['João Pereira', 'joao@example.com', 'Florianópolis', 'Galópolis', 'Um lugar incrível, cheio de história e natureza. Voltarei em breve!', 4, '127.0.0.1', 'Seeder'],
            ['Ana Costa', 'ana@example.com', 'Curitiba', 'Terceira Légua', 'Uma experiência inesquecível: paisagens, acolhimento e muita cultura local.', 5, '127.0.0.1', 'Seeder'],
        ];
        foreach ($samples as $item) {
            $stmt->bind_param('sssssiis', $item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6], $item[7]);
            $stmt->execute();
        }
        $stmt->close();
    }
}

seed_default_testimonials($conn);

// Função simples para redirecionar se não estiver logado
function checkLogin() {
    if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
        header("Location: login.php");
        exit;
    }
}

// CSRF helpers
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        try {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            // Fallback simples
            $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $token);
}
?>
