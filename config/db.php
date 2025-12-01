<?php
/*
* Archivo: config/db.php
* Objetivo: Conexión segura a la base de datos usando PDO
*/

$host = 'localhost';
$db   = 'biblioteca_db';
$user = 'root';       // Usuario por defecto en XAMPP/WAMP
$pass = '';           // Contraseña por defecto (vacía en Windows, 'root' en Mac)
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa sentencias preparadas reales
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Si quieres probar que conecta, descomenta la siguiente línea:
    // echo "¡Conexión exitosa a la Base de Datos!"; 
} catch (\PDOException $e) {
    // En producción nunca muestres el error real al usuario, regístralo en un log
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
    // Para desarrollo, podrías hacer: die("Error de conexión: " . $e->getMessage());
}
?>