<?php
echo "<h2>🔍 Verificación de rutas absolutas</h2>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . $_SERVER["DOCUMENT_ROOT"] . "</p>";
echo "<p><strong>__DIR__:</strong> " . __DIR__ . "</p>";

echo "<h3>Rutas esperadas:</h3>";
$paths = [
    'db.php' => $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php",
    'CursoModel.php' => $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php",
    'CursoController.php' => $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/controller/CursoController.php",
    'Dashboard Cursos' => $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php",
    'LoginController.php' => $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/controller/LoginController.php",
];

foreach ($paths as $label => $path) {
    echo "<p>🔸 <strong>$label</strong>: " . (file_exists($path) ? "✅ Encontrado" : "❌ No encontrado") . "<br><code>$path</code></p>";
}

echo "<hr><h3>Redirección simulada del Login:</h3>";
$url = "http://localhost:8080/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php";
echo "<a href='$url'>$url</a>";
?>
