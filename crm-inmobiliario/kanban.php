<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require_once 'config.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT o.*, l.nombre as lead_nombre FROM oportunidades o LEFT JOIN leads l ON o.lead_id = l.id WHERE o.user_id = ?");
$stmt->execute([$user_id]);
$oportunidades = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Kanban</title><link rel="stylesheet" href="assets/css/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>
<body>
<div class="sidebar">...</div>
<div class="main">
    <h2>Tablero Kanban</h2>
    <div class="kanban-board">
        <?php
        $etapas = ['nuevo','contactado','visita','oferta','negociacion','cerrado'];
        foreach($etapas as $etapa):
            $items = array_filter($oportunidades, function($o) use ($etapa) { return $o['etapa'] == $etapa; });
        ?>
        <div class="kanban-col" data-stage="<?= $etapa ?>">
            <h3><?= ucfirst($etapa) ?></h3>
            <div class="kanban-cards">
                <?php foreach($items as $item): ?>
                <div class="card" data-id="<?= $item['id'] ?>">
                    <strong><?= htmlspecialchars($item['lead_nombre']) ?></strong><br>
                    <?= $item['comision_esperada'] ? "Comisión: $".$item['comision_esperada'] : '' ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="assets/js/kanban.js"></script>
</body>
</html>