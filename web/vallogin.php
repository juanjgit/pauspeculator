<? session_start();
if(isset($_SESSION['iduser'])){
    header("location:main.php"); /* Si ha iniciado la sesion, vamos a user.php */
} else { 
        require("classes/monedasClass.php");

        $login = htmlspecialchars(trim($_POST['login']));
        $pass = htmlspecialchars(trim($_POST['pass']));;
        
        $oDatosUsuarios = new Usuario;
        $rsuser = $oDatosUsuarios->getLogin($login, $pass);
        
        if($rsuser!=NULL){ // nos devuelve 1 si encontro el usuario y el password
            foreach ($rsuser as $user){ // CAMBIAR: Esto de hacer un foreach cuando se que solo hay un resultado no me gusta
                $_SESSION['iduser']=$user['id'];
                $_SESSION['nomuser']=$user['nombre'];
                $_SESSION['login']=$login;
            }
            header('Location:main.php');
        } else {
            $errorlogin="Nombre de Usuario o Password Incorrectos";
            header("Location:index.php?errorlogin=".$errorlogin);
        } 
} ?>
