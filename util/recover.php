<?php
    include '../util/conexion.php';
    $email = $_POST["email"];
    $conn->begin_transaction();
    try {
        $sql = $conn->prepare("SELECT password, id, nombre, tipo_usuario FROM usuarios WHERE email = ? ");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result_usuario = $sql->get_result();

        if ($result_usuario->num_rows > 0) {
            // Obtener los datos del usuario
            $usuario = $result_usuario->fetch_assoc();

            // Todos los usuarios pueden recuperar su contraseña
            echo json_encode(['success' => true, 'message' => 'Usuario encontrado, puedes recuperar tu contraseña.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No existe un usuario con ese correo, por favor registrese.']);
        }

    } catch(Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $conn->close();
    }
?>

