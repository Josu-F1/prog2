<?php
// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['usuario_id'])) {
    // Redirigir según el tipo de usuario
    if ($_SESSION['tipo_usuario'] === 'administrador') {
        header("Location: ../admin/gestion_users.php");
        exit();
    } elseif ($_SESSION['tipo_usuario'] === 'empleado') {
        header("Location: ../employee/home_employee.php");
        exit();
    } else {
        header("Location: ../client/home_client.php");
        exit();
    }
}
?>