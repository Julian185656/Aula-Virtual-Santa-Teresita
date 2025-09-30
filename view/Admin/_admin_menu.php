<?php

require __DIR__ . '/../../model/db.php';
if (isset($_SESSION['usuario']) && $_SESSION['usuario']['Rol'] === 'Administrador'): ?>
  <li><a href="/Aula-Virtual-Santa-Teresita/view/Admin/admin_usuarios_list.php">Editar perfiles</a></li>
<?php endif; ?>
