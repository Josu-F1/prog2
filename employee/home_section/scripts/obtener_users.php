<?php
include '../../../util/conexion.php';

$sql = "SELECT nombre, id FROM usuarios";
$result = mysqli_query($conn,$sql);

$data = array();
if ($result->num_rows>0){
    while($row = mysqli_fetch_array($result)){
        $data[]= array(
            'nombre'=> $row['nombre'],
            'id'=> $row['id']
        );
    }
} else{
    echo "no existen elementos";
}
echo json_encode($data);

?>