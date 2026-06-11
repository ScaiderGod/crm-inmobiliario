<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require_once 'config.php';
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $stmt = $pdo->prepare("INSERT INTO leads (nombre, telefono, email, tipo, presupuesto, zona, fuente, user_id) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$_POST['nombre'], $_POST['telefono'], $_POST['email'], $_POST['tipo'], $_POST['presupuesto'], $_POST['zona'], $_POST['fuente'], $user_id]);
    header('Location: leads.php'); exit;
}
$leads = $pdo->prepare("SELECT * FROM leads WHERE user_id = ?")->execute([$user_id])? $pdo->fetchAll() : [];
?>
<!DOCTYPE html>
<html>
<head><title>Leads</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="sidebar">... (mismo menú)</div>
<div class="main">
    <h2>Leads</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="email" name="email" placeholder="Email">
        <select name="tipo"><option value="compra">Compra</option><option value="venta">Venta</option></select>
        <input type="number" step="0.01" name="presupuesto" placeholder="Presupuesto">
        <input type="text" name="zona" placeholder="Zona">
        <input type="text" name="fuente" placeholder="Fuente">
        <button type="submit" name="agregar">Agregar lead</button>
    </form>
    <ul><?php foreach($leads as $l): ?><li><?= htmlspecialchars($l['nombre']) ?> - <?= $l['telefono'] ?></li><?php endforeach; ?></ul>
</div>
</body>
</html>