<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (isset($_POST['register'])) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, password) VALUES (?, ?)");
        try { $stmt->execute([$email, $hashed]); $error = "Usuario registrado. Inicia sesión."; }
        catch (PDOException $e) { $error = "El correo ya existe."; }
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php'); exit;
        } else { $error = "Credenciales incorrectas."; }
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Login CRM Inmobiliario</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="login-container">
    <h2>CRM Inmobiliario</h2>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="login">Iniciar sesión</button>
        <button type="submit" name="register">Registrarse</button>
    </form>
</div>
</body>
</html>