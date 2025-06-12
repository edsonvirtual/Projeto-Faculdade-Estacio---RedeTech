<?php
session_start();
require 'conexao.php';

$msgLogin = '';
$erroCadastro = '';
$sucessoCadastro = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'login') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $msgLogin = "Preencha todos os campos!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msgLogin = "Email inválido!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            header("Location: index.php");
            exit;
        } else {
            $msgLogin = "E-mail ou senha incorretos.";
        }
    }
}

// Processar cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'cadastrar') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$nome || !$email || !$senha) {
        $erroCadastro = "Preencha todos os campos para cadastro!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroCadastro = "Email inválido!";
    } else {
        // Verifica se o email já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erroCadastro = "Este e-mail já está cadastrado!";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            if ($stmt->execute([$nome, $email, $senhaHash])) {
                $sucessoCadastro = "Cadastro realizado com sucesso! Agora faça login.";
            } else {
                $erroCadastro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - RedeTech</title>

    <!-- Seus links de CSS e fontes (mantive seu código original, só removi repetições) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="css/responsive.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/slide.css" />
    <link rel="stylesheet" href="css/css -login/reset.css" />
    <link rel="stylesheet" href="css/css -login/colors.css" />
    <link rel="stylesheet" href="css/css -login/main.css" />
    <link rel="stylesheet" href="css/css -login/login-container.css" />
    <link rel="stylesheet" href="css/css -login/form-container.css" />
    <link rel="stylesheet" href="css/css -login/form.css" />
    <link rel="stylesheet" href="css/css -login/form-title.css" />
    <link rel="stylesheet" href="css/css -login/form-social.css" />
    <link rel="stylesheet" href="css/css -login/social-icon.css" />
    <link rel="stylesheet" href="css/css -login/form-input-container.css" />
    <link rel="stylesheet" href="css/css -login/form-input.css" />
    <link rel="stylesheet" href="css/css -login/form-button.css" />
    <link rel="stylesheet" href="css/css -login/overlay-container.css" />
    <link rel="stylesheet" href="css/css -login/overlay.css" />
    <link rel="stylesheet" href="css/css -login/mobile-text.css" />

    <script src="js/login.js" defer></script>
</head>
<body>
    <div class="container-geral">
        <header>
            <a href="#" class="logo"><img src="img/redetech copy.png" alt="RedeTech Logo" /></a>
            <input type="checkbox" id="menu-bar" />
            <nav class="navbar">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="empresa.php">Empresa</a></li>
                    <li><a href="quemsomos.php">Quem Somos</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li>
                        <a href="servicos.php">Serviços</a>
                        <ul>
                            <li><a href="#">Manutenção</a></li>
                            <li><a href="#">Produtos</a></li>
                            <li><a href="#">Manutenção</a></li>
                            <li><a href="#">Manutenção</a></li>
                        </ul>
                    </li>
                    <li><a href="contato.php">Contato</a></li>
                    <li><a href="orcamento.php">Orçamento</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </header>

        <main class="main-login">
            <div class="login-container" id="login-container">
                <div class="form-container">
                    <!-- Formulário Login -->
                    <form class="form form-login" method="POST" action="" novalidate>
                        <h2 class="form-title">Entrar com</h2>
                        <p class="form-text">ou utilize sua conta</p>
                        <div class="form-input-container">
                            <input type="email" name="email" class="form-input" placeholder="Email" required />
                            <input type="password" name="senha" class="form-input" placeholder="Senha" required />
                        </div>
                        <a href="#" class="form-link">Esqueceu a senha?</a>

                        <?php if ($msgLogin): ?>
                            <p style="color:red; margin-top:10px;"><?= htmlspecialchars($msgLogin) ?></p>
                        <?php endif; ?>

                        <button type="submit" name="acao" value="login" class="form-button">Logar</button>
                        <p class="mobile-text">
                            Não tem conta?
                            <a href="#" id="open-register-mobile">Registre-se</a>
                        </p>
                    </form>

                    <!-- Formulário Cadastro -->
                    <form class="form form-register" method="POST" action="" novalidate>
                        <h2 class="form-title">Criar Conta</h2>
                        <p class="form-text">ou cadastre seu email</p>
                        <div class="form-input-container">
                            <input type="text" name="nome" class="form-input" placeholder="Nome" required />
                            <input type="email" name="email" class="form-input" placeholder="Email" required />
                            <input type="password" name="senha" class="form-input" placeholder="Senha" required />
                        </div>

                        <?php if ($erroCadastro): ?>
                            <p style="color:red; margin-top:10px;"><?= htmlspecialchars($erroCadastro) ?></p>
                        <?php elseif ($sucessoCadastro): ?>
                            <p style="color:green; margin-top:10px;"><?= htmlspecialchars($sucessoCadastro) ?></p>
                        <?php endif; ?>

                        <button type="submit" name="acao" value="cadastrar" class="form-button">Cadastrar</button>
                        <p class="mobile-text">
                            Já tem conta?
                            <a href="#" id="open-login-mobile">Login</a>
                        </p>
                    </form>
                </div>

                <div class="overlay-container">
                    <div class="overlay">
                        <h2 class="form-title form-title-light">Já tem conta?</h2>
                        <p class="form-text">Para entrar na nossa plataforma faça login com suas informações</p>
                        <button class="form-button form-button-light" id="open-login">Entrar</button>
                    </div>
                    <div class="overlay">
                        <h2 class="form-title form-title-light">Olá Aluno!</h2>
                        <p class="form-text">Cadastre-se e comece a usar a nossa plataforma on-line</p>
                        <button class="form-button form-button-light" id="open-register">Cadastrar</button>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <div class="interface-footer">
                <section class="line-footer1">
                    <h2>Quero receber informações</h2>
                    <form action="">
                        <input type="email" name="" id="email" />
                        <input type="submit" value="Cadastrar" />
                    </form>
                </section>

                <section class="line-footer2">
                    <div class="box-line-footer">
                        <img src="img/redetech copy.png" alt="Logo Mr.Clean" />
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione, at.</p>
                    </div>

                    <div class="box-line-footer">
                        <h3>Links úteis</h3>
                        <div class="links-footer">
                            <a href="index.php">Home</a>
                            <a href="empresa.php">Empresa</a>
                            <a href="quemsomos.php">Quem Somos</a>
                            <a href="login.php">Login</a>
                        </div>
                    </div>

                    <div class="box-line-footer">
                        <h3>Lojas online</h3>
                        <div class="links-footer">
                            <a href="index.php">Home</a>
                            <a href="empresa.php">Empresa</a>
                            <a href="quemsomos.php">Quem Somos</a>
                            <a href="login.php">Login</a>
                        </div>
                    </div>

                    <div class="box-line-footer">
                        <h3>Landing page</h3>
                        <div class="links-footer">
                            <a href="index.php">Home</a>
                            <a href="empresa.php">Empresa</a>
                            <a href="quemsomos.php">Quem Somos</a>
                            <a href="login.php">Login</a>
                        </div>
                    </div>

                    <div class="box-line-footer">
                        <h3>Siga-me</h3>
                        <div class="btn-redes">
                            <a href=""><button><i class="bi bi-facebook"></i></button></a>
                            <a href=""><button><i class="bi bi-instagram"></i></button></a>
                            <a href=""><button><i class="bi bi-whatsapp"></i></button></a>
                        </div>
                    </div>
                </section>

                <section class="line-footer3">
                    <p>&copy; Todos os direitos reservados -- RedeTech soluções em informática --</p>
                </section>
            </div>
        </footer>
    </div>

    <script src="js/login.js" defer></script>
</body>
</html>
