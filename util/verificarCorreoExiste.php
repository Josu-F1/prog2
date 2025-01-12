<?php
    include '../util/conexion.php';
    $email = $_POST["email"];
    $conn->begin_transaction();
    try{
    $sql = $conn->prepare("SELECT password,id,nombre FROM usuarios WHERE email = ? ");
    $sql->bind_param("s",$email);
    $sql->execute();
    $result_usuario = $sql->get_result();

    if ($result_usuario->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Este correo ya se encuentra registrado']);
    } else {        
        echo json_encode(['success' => true, 'message' => 'No existen usuarios con ese correo']);
    }
    
    }catch(Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }finally{
        $conn->close();
    }
       

?>

