<?php
$hostname_localhost = "mysql.railway.internal";
$database_localhost = "railway";
$username_localhost = "root";
$pass_localhost = "UGOmQptMrqMKFKFdeBSpUSRbpCxLqOzD";

$json = array();

if (isset($_GET['_ID'])) {
    $_ID = $_GET['_ID'];

    $conexion = mysqli_connect($hostname_localhost, $username_localhost, $pass_localhost, $database_localhost);

    if ($conexion === false) {
        $json['error'] = "Error al conectar a la base de datos: " . mysqli_connect_error();
        echo json_encode($json);
        exit();
    }

    $delete = "DELETE FROM contactos WHERE _ID = ?";
    $stmt = mysqli_prepare($conexion, $delete);

    if ($stmt === false) {
        $json['error'] = "Error al preparar la consulta: " . mysqli_error($conexion);
        echo json_encode($json);
        mysqli_close($conexion);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $_ID);
    $resultado_delete = mysqli_stmt_execute($stmt);

    if ($resultado_delete) {
        $json['mensaje'] = "Registro eliminado correctamente.";
    } else {
        $json['error'] = "Error al eliminar el registro: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
    echo json_encode($json);
} else {
    $json['error'] = "Parámetro '_ID' no especificado.";
    echo json_encode($json);
}
?>
