<?php
require_once 'config.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (!empty($email) && !empty($senha)) {
        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            
            if (password_verify($senha, $usuario['senha'])) {
                // Sucesso
                $_SESSION['admin_logado'] = true;
                $_SESSION['admin_id'] = $usuario['id'];
                $_SESSION['admin_nome'] = $usuario['nome'];
                header("Location: index.php");
                exit;
            } else {
                $erro = "E-mail ou senha inválidos.";
            }
        } else {
            $erro = "E-mail ou senha inválidos.";
        }
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrativo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Lato', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background-color: #2E4636;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-header h3 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin: 0;
        }
        .login-body {
            padding: 2rem;
            background-color: white;
        }
        .btn-primary {
            background-color: #2E4636;
            border-color: #2E4636;
            padding: 0.8rem;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #A04000;
            border-color: #A04000;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card login-card">
                <div class="login-header">
                    <h3>Caxias Tem Turismo</h3>
                    <p class="mb-0 mt-2">Painel Administrativo</p>
                </div>
                <div class="login-body">
                    <?php if(!empty($erro)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="admin@caxiastemturismo.com.br">
                        </div>
                        <div class="mb-4">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
