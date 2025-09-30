<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Grad School HTML5 Template</title>


    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">

    <link rel="stylesheet" href="../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../assets/css/owl.css">
    <link rel="stylesheet" href="../assets/css/lightbox.css">
</head>

<body>


    <header class="main-header clearfix" role="header">
        <div class="logo">
            <a href="#"><em>Santa</em> Teresita</a>
        </div>
        <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>
        <nav id="menu" class="main-nav" role="navigation">
            <ul class="main-menu">
                <li><a href="#section1">Home</a></li>
                <li class="has-submenu"><a href="#section2">About Us</a>
                    <ul class="sub-menu">
                        <li><a href="#section2">Who we are?</a></li>
                        <li><a href="#section3">What we do?</a></li>
                        <li><a href="#section3">How it works?</a></li>
                    </ul>
                </li>
                <li><a href="#section4">Courses</a></li>
                <li><a href="#section6">Contact</a></li>

    
                <?php if (isset($_SESSION['nombre'])): ?>
                    <?php
         
                      $rolActual = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
                    ?>

                    <?php if ($rolActual === 'Administrador'): ?>
                        <li>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Admin/admin_usuarios_list.php">
                                🛠️ Editar perfiles
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($rolActual === 'Docente'): ?>
                        <li>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Docente/ListaDocente.php">
                                👨‍🏫 Ver Perfiles Alumnos
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="#" style="color: #ffffffff;">
                            👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                        </a>
                    </li>
                    <li>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color: red;">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Login/Login.php" style="color: #ff0000ff;">
                            <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>


    <section class="section main-banner" id="top" data-section="section1">
        <video autoplay muted loop id="bg-video">
            <source src="assets/images/choose-us-image-01.png" type="video/mp4" />
        </video>
        <div class="video-overlay header-text">
            <div class="caption">
                <h6>Graduate School of Management</h6>
                <h2><em>Your</em> Classroom</h2>
                <div class="main-button">
                    <div class="scroll-to-section"><a href="#section2">Discover more</a></div>
                </div>
            </div>
        </div>
    </section>

  
    <section class="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="features-post">
                        <div class="features-content">
                            <div class="content-show">
                                <h4><i class="fa fa-pencil"></i>All Courses</h4>
                            </div>
                            <div class="content-hide">
                                <p>Curabitur id eros vehicula, tincidunt libero eu, lobortis mi.</p>
                                <div class="scroll-to-section"><a href="#section2">More Info.</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="features-post second-features">
                        <div class="features-content">
                            <div class="content-show">
                                <h4><i class="fa fa-graduation-cap"></i>Virtual Class</h4>
                            </div>
                            <div class="content-hide">
                                <p>Curabitur id eros vehicula, tincidunt libero eu, lobortis mi.</p>
                                <div class="scroll-to-section"><a href="#section3">Details</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="features-post third-features">
                        <div class="features-content">
                            <div class="content-show">
                                <h4><i class="fa fa-book"></i>Real Meeting</h4>
                            </div>
                            <div class="content-hide">
                                <p>Curabitur id eros vehicula, tincidunt libero eu, lobortis mi.</p>
                                <div class="scroll-to-section"><a href="#section4">Read More</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section coming-soon" data-section="section3">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-xs-12">
                    <div class="continer centerIt">
                        <div>
                            <h4>Take <em>any online course</em> and win $326 for your next class</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="right-content"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section contact" data-section="section6">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Let’s Keep In Touch</h2>
                    </div>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div id="map">
                        <iframe src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="422px" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

 
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p><i class="fa fa-copyright"></i> Copyright 2025 by Grad School |
                        Design: <a href="https://templatemo.com" rel="sponsored" target="_parent">TemplateMo</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <script src="../assets/js/isotope.min.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/lightbox.js"></script>
    <script src="../assets/js/tabs.js"></script>
    <script src="../assets/js/video.js"></script>
    <script src="../assets/js/slick-slider.js"></script>
    <script src="../assets/js/custom.js"></script>

</body>
</html>
