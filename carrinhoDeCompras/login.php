<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';


if (Auth::isLoggedIn()) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (Auth::login($username, $password)) {
        header('Location: products.php');
        exit;
    } else {
        $error = "Usuário ou senha incorretos!";
    }
}

include 'templetes/header.php';
?>

<div class="container">
    <h2>Login</h2>
    <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">Cadastro realizado com sucesso! Faça login para continuar.</div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="username">Nome de Usuário:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
    
    <p class="mt-3">Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
</div>

<?php include 'templetes/footer.php'; ?>