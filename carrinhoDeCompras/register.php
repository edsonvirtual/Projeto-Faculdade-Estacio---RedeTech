<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    
    if ($password !== $confirm_password) {
        $error = "As senhas não coincidem!";
    } else {
        if (Auth::register($username, $email, $password, $full_name)) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $error = "Usuário ou email já cadastrado!";
        }
    }
}

include 'templetes/header.php';
?>

<div class="container">
    <h2>Cadastro de Usuário</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="username">Nome de Usuário:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="full_name">Nome Completo:</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmar Senha:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
    
    <p class="mt-3">Já tem uma conta? <a href="login.php">Faça login</a></p>
</div>

<?php include 'templetes/footer.php'; ?>