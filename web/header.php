<? session_start();
if(!isset($_SESSION['iduser'])){ //Comprobamos la sesion esta abierta, sino lo mandamos al index
    header("location:index.php");
} else { ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />    
        <title>Bitcoining.es</title>
        <meta name="viewport" content="width    =device-width, initial-scale=1.0">
        <meta name="description" content="Plataforma de trading para Bitcoins">
        <meta name="author" content="Pau Solucioens">
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/tema_principal.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">

    </head>

  <body>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/bootstrap.min.js"></script>
<!-- Head -->
    <!-- Barra del menu superior -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Bitcoining Trader</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="logout.php"">Exit sesion <? echo $_SESSION['nomuser'] ?></a>
                </li>
            </ul>
          
        </div><!--/.nav-collapse -->
      </div>
    </div>
    
    <!-- Titulo -->
    <div class="container">
        <div class="row">
           <div class="col-md-6 col-md-offset-3" align="center">    
                <img src="img/logo2.jpg" alt="bitcoining.es" width="200px" height="150px">
           </div>
        </div>
    </div>
<!-- /Head -->        

<!-- Cuerpo -->
    <div class="container goldformat">
        <div class="row">
           <div class="col-md-10 col-md-offset-1">
            <h1 align="center">Panel de control de <? echo $_SESSION['nomuser'] ?></h1>  
           </div> 
         </div>
 <? } ?>