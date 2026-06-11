<?php
$db_url = getenv('DATABASE_URL');
if ($db_url) {
    $db = parse_url($db_url);
    $host = $db['host'];
    $port = $db['port'];
    $dbname = ltrim($db['path'], '/');
    $user = $db['user'];
    $pass = $db['pass'];
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
} else {
    die("Variable DATABASE_URL no encontrada. Configúrala en Render.");
}
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { die("Error BD: " . $e->getMessage()); }

// Crear tablas automáticamente
$sql = "
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS leads (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    telefono VARCHAR(50),
    email VARCHAR(255),
    tipo VARCHAR(50) CHECK (tipo IN ('compra','venta')),
    presupuesto DECIMAL(12,2),
    zona VARCHAR(255),
    fuente VARCHAR(100),
    user_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS propiedades (
    id SERIAL PRIMARY KEY,
    direccion TEXT NOT NULL,
    precio DECIMAL(12,2),
    tipo VARCHAR(50),
    habitaciones INT,
    banos INT,
    metros_cuadrados INT,
    estado VARCHAR(50) DEFAULT 'disponible',
    user_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS oportunidades (
    id SERIAL PRIMARY KEY,
    lead_id INTEGER REFERENCES leads(id) ON DELETE CASCADE,
    propiedad_id INTEGER REFERENCES propiedades(id) ON DELETE SET NULL,
    etapa VARCHAR(50) DEFAULT 'nuevo',
    fecha_estimada_cierre DATE,
    comision_esperada DECIMAL(10,2),
    user_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE
);
";
$pdo->exec($sql);
?>