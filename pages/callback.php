<?php
    session_start();
    require_once '../vendor/autoload.php';
    require_once '../util/config.php';
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

if(isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google\Service\Oauth2($client);
    $google_account_info= $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;

    
    include '../util/conexion.php';
    $sql = $conn->prepare("SELECT id,tipo_usuario FROM usuarios WHERE email=?");
    $sql->bind_param("s",$email);
    $sql->execute();
    $result=$sql->get_result();

    if($result->num_rows===1){
        $usuario = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

        /*if($usuario['tipo_usuario']=='administrador'){            
            header("Location: ./iniciarSesion.php");
        }else if($usuario['tipo_usuario']=='empleado'){
            header("Location: ./iniciarSesion.php");
        }else if($usuario['tipo_usuario']=='cliente'){
            header("Location: ./iniciarSesion.php");
        }else{
            header("Location: ./iniciarSesion.php");
        }*/

        echo $name."</br>";
        echo $usuario['tipo_usuario'];
        header("Location: ./iniciarSesion.php");
        exit();
    }else{
        echo "no se encontro ningun usuario con el correo: "+$email;
    }

}
$conn->close();
exit();

?>