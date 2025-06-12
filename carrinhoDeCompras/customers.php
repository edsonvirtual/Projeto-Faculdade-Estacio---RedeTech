<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';


if (!Auth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
$customer_id = $_GET['id'] ?? null;

// Processar formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if ($action === 'add') {
        $stmt = Database::getConnection()->prepare("INSERT INTO customers (name, email, phone, address, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $address, $_SESSION['user_id']]);
        
        header('Location: customers.php?success=1');
        exit;
    } elseif ($action === 'edit' && $customer_id) {
        $stmt = Database::getConnection()->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE customer_id = ?");
        $stmt->execute([$name, $email, $phone, $address, $customer_id]);
        
        header('Location: customers.php?success=1');
        exit;
    }
}

// Excluir cliente
if ($action === 'delete' && $customer_id) {
    $stmt = Database::getConnection()->prepare("DELETE FROM customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    
    header('Location: customers.php?success=1');
    exit;
}

// Buscar cliente para edição
$customer = null;
if ($action === 'edit' && $customer_id) {
    $stmt = Database::getConnection()->prepare("SELECT * FROM customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        header('Location: customers.php');
        exit;
    }
}

// Listar clientes
if ($action === 'list') {
    $stmt = Database::getConnection()->query("SELECT * FROM customers ORDER BY name");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include 'templetes/header.php';
?>

<div class="container">
    <?php if ($action === 'list'): ?>
        <h2>Clientes</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Operação realizada com sucesso!</div>
        <?php endif; ?>
        
        <a href="customers.php?action=add" class="btn btn-primary mb-3">Adicionar Cliente</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= htmlspecialchars($customer['phone']) ?></td>
                        <td>
                            <a href="customers.php?action=edit&id=<?= $customer['customer_id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="customers.php?action=delete&id=<?= $customer['customer_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
        <h2><?= $action === 'add' ? 'Adicionar' : 'Editar' ?> Cliente</h2>
        
        <form method="post">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $customer['name'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $customer['email'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Telefone:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= $customer['phone'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label for="address">Endereço:</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?= $customer['address'] ?? '' ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="customers.php" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</div>

<?php include 'templetes/footer.php'; ?>