<?php
$hostname_localhost = "mysql.railway.internal";
$database_localhost = "railway";
$username_localhost = "root";
$pass_localhost = "UGOmQptMrqMKFKFdeBSpUSRbpCxLqOzD";

$json = array();

if (isset($_GET["_ID"]) && isset($_GET["nombre"]) && isset($_GET["direccion"]) && isset($_GET["telefono1"])) {
    $_ID = $_GET['_ID'];
    $nombre = $_GET['nombre'];
    $telefono1 = $_GET['telefono1'];
    $telefono2 = isset($_GET['telefono2']) ? $_GET['telefono2'] : null;
    $direccion = $_GET['direccion'];
    $notas = isset($_GET['notas']) ? $_GET['notas'] : null;
    $favorite = isset($_GET['favorite']) ? $_GET['favorite'] : null;

    $conexion = mysqli_connect($hostname_localhost, $username_localhost, $pass_localhost, $database_localhost);

    if ($conexion === false) {
        $json['error'] = "Error al conectar a la base de datos: " . mysqli_connect_error();
        echo json_encode($json);
        exit();
    }

    $update = "UPDATE contactos SET nombre=?, telefono1=?, telefono2=?, direccion=?, notas=?, favorite=? WHERE _ID=?";
    $stmt = mysqli_prepare($conexion, $update);

    if ($stmt === false) {
        $json['error'] = "Error al preparar la consulta: " . mysqli_error($conexion);
        echo json_encode($json);
        mysqli_close($conexion);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'sssssis', $nombre, $telefono1, $telefono2, $direccion, $notas, $favorite, $_ID);
    $resultado_update = mysqli_stmt_execute($stmt);

    if ($resultado_update) {
        $consulta = "SELECT * FROM contactos WHERE _ID=?";
        $stmt_select = mysqli_prepare($conexion, $consulta);
        
        if ($stmt_select === false) {
            $json['error'] = "Error al preparar la consulta de selección: " . mysqli_error($conexion);
        } else {
            mysqli_stmt_bind_param($stmt_select, 'i', $_ID);
            mysqli_stmt_execute($stmt_select);
            $resultado = mysqli_stmt_get_result($stmt_select);

            if ($resultado) {
                if ($registro = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                    $json['contacto'][] = $registro;
                }
            } else {
                $json['error'] = "Error al realizar la consulta: " . mysqli_error($conexion);
            }
        }
        mysqli_stmt_close($stmt_select);
    } else {
        $json['error'] = "Error al actualizar el registro: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
    echo json_encode($json);
} else {
    $resulta["_ID"] = 0;
    $resulta["nombre"] = 'WS no retorna';
    $resulta["telefono1"] = 'WS no retorna';
    $resulta["telefono2"] = 'WS no retorna';
    $resulta["direccion"] = 'WS no retorna';
    $resulta["notas"] = 'WS no retorna';
    $resulta["favorite"] = 0;
    $resulta["idMovil"] = 'No retorna';
    $json['contactos'][] = $resulta;
    echo json_encode($json);
}
?>
