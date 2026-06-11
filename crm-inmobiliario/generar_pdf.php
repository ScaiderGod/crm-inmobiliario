<?php
session_start();
if (!isset($_SESSION['user_id'])) die("No autorizado");
require_once 'config.php';
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT o.*, l.nombre as lead FROM oportunidades o LEFT JOIN leads l ON o.lead_id = l.id WHERE o.id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) die("Oportunidad no encontrada");
$html = "<html><body><h1>Oportunidad</h1><p>Lead: {$data['lead']}</p><p>Comisión: \${$data['comision_esperada']}</p></body></html>";
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("oportunidad.pdf");
?>