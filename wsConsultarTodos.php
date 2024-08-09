<?php
$hostname_localhost = "mysql.railway.internal";
$database_localhost = "railway";
$username_localhost = "root";
$pass_localhost = "UGOmQptMrqMKFKFdeBSpUSRbpCxLqOzD";

$json = array();

if (isset($_GET['idMovil'])) {
    $idMovil = $_GET['idMovil'];

    $conexion = mysqli_connect($hostname_localhost, $username_localhost, $pass_localhost, $database_localhost);

    if ($conexion === false) {
        $json['error'] = "Error al conectar a la base de datos: " . mysqli_connect_error();
        echo json_encode($json);
        exit();
    }

    $consulta = "SELECT * FROM contactos WHERE idMovil = ? ORDER BY nombre";
    $stmt = mysqli_prepare($conexion, $consulta);

    if ($stmt === false) {
        $json['error'] = "Error al preparar la consulta: " . mysqli_error($conexion);
        echo json_encode($json);
        mysqli_close($conexion);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 's', $idMovil);
    mysqli_stmt_execute($stmt);
    $resultado_consulta = mysqli_stmt_get_result($stmt);

    while ($registro = mysqli_fetch_array($resultado_consulta, MYSQLI_ASSOC)) {
        $json['contactos'][] = $registro;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
} else {
    $json['error'] = "Parámetro 'idMovil' no proporcionado";
}

echo json_encode($json);
?>
