
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' | ' : '' ?><?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
      <title>RedeTech</title>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>"><?= SITE_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.html">Home</a>
                    </li>
                 
                    <li class="nav-item">
                        <a class="nav-link" href="../empresa.html">Empresa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Carrinho</a>
                    </li>
                    <?php if (Auth::isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="order_details.php">Pedidos</a>
                        </li>
                        <?php if (Auth::isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="customers.php">Clientes</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (Auth::isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php"><?= $_SESSION['username'] ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Cadastrar</a>
                        </li>
                        
                    <?php endif; ?>
                    </ul>
            </div>
        </div>
    </nav>
    
    <div class="container"></div></div>