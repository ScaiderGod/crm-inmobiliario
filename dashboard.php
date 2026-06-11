<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require_once 'config.php';
$user_id = $_SESSION['user_id'];
$totalLeads = $pdo->prepare("SELECT COUNT(*) FROM leads WHERE user_id = ?")->execute([$user_id])? $pdo->fetchColumn() : 0;
$totalProp = $pdo->prepare("SELECT COUNT(*) FROM propiedades WHERE user_id = ?")->execute([$user_id])? $pdo->fetchColumn() : 0;
$totalOport = $pdo->prepare("SELECT COUNT(*) FROM oportunidades WHERE user_id = ?")->execute([$user_id])? $pdo->fetchColumn() : 0;
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Dashboard</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="sidebar">
    <h3>CRM Inmo</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="leads.php">Leads</a>
    <a href="propiedades.php">Propiedades</a>
    <a href="oportunidades.php">Oportunidades</a>
    <a href="kanban.php">Kanban</a>
    <a href="logout.php">Cerrar sesión</a>
</div>
<div class="main">
    <h1>Panel de control</h1>
    <div class="cards">
        <div class="card">Total Leads: <?= $totalLeads ?></div>
        <div class="card">Total Propiedades: <?= $totalProp ?></div>
        <div class="card">Oportunidades: <?= $totalOport ?></div>
    </div>
</div>
</body>
</html>