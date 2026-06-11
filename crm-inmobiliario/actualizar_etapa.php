<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once 'config.php';
if ($_POST['id'] && $_POST['etapa']) {
    $stmt = $pdo->prepare("UPDATE oportunidades SET etapa = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['etapa'], $_POST['id'], $_SESSION['user_id']]);
}
?>