-- Cria tabela para armazenar depoimentos enviados pelos visitantes
CREATE TABLE IF NOT EXISTS depoimentos (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
