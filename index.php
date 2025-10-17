<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Aula Virtual Santa Teresita">
  <meta name="author" content="Luis Memo Rivero">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">

  <title>Santa Teresita - Aula Virtual</title>
  
  <!-- ✅ CORRECTO: apuntar a /view/assets -->
  <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/fontawesome.css">
  <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/templatemo-grad-school.css">
  <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/owl.css">
  <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/lightbox.css">
</head>

<body>

<header class="main-header clearfix" role="header">
  <div class="logo">
    <a href="#"><em>Santa</em> Teresita</a>
  </div>
  <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>
  <nav id="menu" class="main-nav" role="navigation">
    <ul class="main-menu">
      <li><a href="#section1" class="active">Home</a></li>
      <li class="has-submenu">
        <a href="#section2">About Us</a>
        <ul class="sub-menu">
          <li><a href="#section2">Who we are?</a></li>
          <li><a href="#section3">What we do?</a></li>
          <li><a href="#section3">How it works?</a></li>
        </ul>
      </li>
      <li><a href="/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php">Courses</a></li>
      <li><a href="#section6">Contact</a></li>

      <?php if (isset($_SESSION['nombre'])): ?>
        <li><a href="#" style="color:#fff;">👤 <?= htmlspecialchars($_SESSION['nombre']); ?></a></li>
        <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color:red;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      <?php else: ?>
        <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<section class="section main-banner" id="top" data-section="section1">
  <video autoplay muted loop id="bg-video">
    <source src="/Aula-Virtual-Santa-Teresita/view/assets/images/course-video.mp4" type="video/mp4" />
  </video>

  <div class="video-overlay header-text">
    <div class="caption">
      <h6>Bienvenido al Aula Virtual</h6>
      <h2><em>Santa</em> Teresita</h2>
      <div class="main-button">
        <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php">Ir a Cursos</a>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="container text-center">
    <p><i class="fa fa-copyright"></i> 2025 Santa Teresita - Aula Virtual |
    Design: <a href="https://templatemo.com" target="_blank">TemplateMo</a></p>
  </div>
</footer>

<!-- ✅ Scripts también desde /view/assets -->
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/isotope.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/owl-carousel.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/lightbox.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/tabs.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/video.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/slick-slider.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/custom.js"></script>

</body>
</html>
