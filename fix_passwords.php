<?php

$host = '127.0.0.1';
$db   = 'aulavirtual';
$user = 'root';
$pass = '';
$port = 3307; 

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $hash = password_hash('123', PASSWORD_BCRYPT);

    $sql = "UPDATE usuario 
            SET Contrasena = :hash, Estado = 'Activo' 
            WHERE Email IN (
                'admin@santateresita.ac.cr',
                'docente@santateresita.ac.cr',
                'estudiante@santateresita.ac.cr'
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':hash' => $hash]);

    echo "✅ Contraseñas actualizadas correctamente.<br>";
    echo "🔐 Nuevo hash: $hash<br>";
    echo "Usuarios actualizados: " . $stmt->rowCount();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
