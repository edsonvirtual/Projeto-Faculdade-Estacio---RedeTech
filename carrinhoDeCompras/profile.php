<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';


if (!Auth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$db = Database::getConnection();
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Atualizar perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }
    
    // Verificar se o email já está em uso por outro usuário
    $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        $errors[] = "Este email já está em uso por outro usuário.";
    }
    
    // Alterar senha se fornecida
    if (!empty($new_password)) {
        if (!password_verify($current_password, $user['password'])) {
            $errors[] = "Senha atual incorreta.";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "As novas senhas não coincidem.";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "A senha deve ter pelo menos 6 caracteres.";
        }
    }
    
    if (empty($errors)) {
        // Atualizar dados do usuário
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ?, password = ? WHERE user_id = ?");
            $stmt->execute([$full_name, $email, $hashed_password, $user_id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ? WHERE user_id = ?");
            $stmt->execute([$full_name, $email, $user_id]);
        }
        
        $_SESSION['success'] = "Perfil atualizado com sucesso!";
        header('Location: profile.php');
        exit;
    }
}

include 'templetes/header.php';
?>

<div class="container">
    <h2>Meu Perfil</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="username">Nome de Usuário:</label>
            <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
        </div>
        
        <div class="form-group">
            <label for="full_name">Nome Completo:</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        
        <hr>
        
        <h4>Alterar Senha</h4>
        <p>Deixe em branco se não quiser alterar a senha.</p>
        
        <div class="form-group">
            <label for="current_password">Senha Atual:</label>
            <input type="password" class="form-control" id="current_password" name="current_password">
        </div>
        
        <div class="form-group">
            <label for="new_password">Nova Senha:</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmar Nova Senha:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>
        
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<?php include 'templetes/footer.php'; ?>