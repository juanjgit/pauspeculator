<? session_start();
if(isset($_SESSION['iduser'])){
    header("location:main.php"); /* Si ha iniciado la sesion, vamos a user.php */
} else { 
    if (isset($_GET["errorlogin"])){
        $errorlogin=$_GET["errorlogin"]; 
    } else {
        $errorlogin=NULL;
    } ?>
    
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />    
    <title>Bitcoining.es</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Plataforma de trading para Bitcoins">
    <meta name="author" content="Pau Solucioens">
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tema_principal.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">

</head>

<body>

    <div class="container"> 
         
        <div class="row"> 
            <div class="col-md-4 col-md-offset-4" align="center"> 
                <img src="img/logo2.jpg" alt="bitcoining.es" width="200px" height="150px" class="logo"> 
            </div> 
       </div> <? 
         
        if ($errorlogin!=NULL){ ?> 
        <div class="alert alert-danger">  
             <? echo $errorlogin ?> 
        </div> <? 
        } ?> 
         
        <div class="row"> 
            <div class="col-md-4 col-md-offset-4 goldformat"> 
                <h1 align="center">Validacion Usuario</h1> 
                <form role="form" action="vallogin.php" method="post" id="user_session"> 
                    <div class="form-group"> 
                      <label for="exampleInputLogin">Usuario</label> 
                      <input type="login" class="form-control" id="login"  name="login" size="50" placeholder="Enter User"> 
                    </div> 
                    <div class="form-group"> 
                      <label for="exampleInputPassword1">Password</label> 
                      <input type="password" class="form-control" id="pass"  name="pass" size="50" placeholder="Password"> 
                    </div> 
                    <div align="right"> 
                        <button type="submit" class="btn btn-primary">Login</button>&nbsp; 
                        <a href="resetpassword.php">Password Olvidado?</a> 
                    </div> 
                </form> 
            </div> 
       </div> 
    </div>  
<? 
require("footer.php"); 
} /* Y cerramos el else */ ?>
