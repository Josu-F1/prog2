<?php include '../util/conexion.php';
$sql = "SELECT id, nombre, email, tipo_usuario FROM usuarios";
$result = mysqli_query($conn, $sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $data[] = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'email' => $row['email'],
            'tipo_usuario' => $row['tipo_usuario']
        );
    }
} else {
    echo "no existen elementos";
}

$conn->close();
?>

<div id="listUsers" class="content-section active">
    
    <div id="alerta2" class="alert d-none alerta-lateral" role="alert"></div>
    
    <!-- Fila para el buscador y el botón de agregar usuario -->
    <div class="d-flex justify-content-between mb-3">
        <!-- Buscador -->
        <input type="text" id="searchUser" class="form-control w-50" placeholder="Buscar usuario..." />

        <!-- Botón de agregar usuario -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Agregar Usuario</button>
    </div>

    <!-- Tabla de usuarios -->
    <table class="table">
        <thead>
            <tr>
                <th>Usuarios</th>
                <th>Email de los usuarios</th>
                <th>Tipo de Usuario</th>
                <th>Funciones</th>
            </tr>
        </thead>
        <tbody id="tablaUsuarios">
            <?php foreach ($data as $usuario) : ?>
                <tr id="usuario-<?php echo $usuario['id']; ?>">
                    <td class="nombre"><?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['tipo_usuario']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal"
                            data-id="<?php echo $usuario['id']; ?>"
                            data-nombre="<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm eliminar" data-id="<?php echo $usuario['id']; ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Script para filtrar usuarios -->
<script>
    document.getElementById('searchUser').addEventListener('input', function() {
        var searchTerm = this.value.toLowerCase();
        var rows = document.querySelectorAll('#tablaUsuarios tr');

        rows.forEach(function(row) {
            var nombre = row.querySelector('.nombre').textContent.toLowerCase();
            if (nombre.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>



