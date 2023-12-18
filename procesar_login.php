<?php
// Establecer la conexión a la base de datos
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar las credenciales de inicio de sesión
if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Utilizar sentencias preparadas para evitar inyecciones SQL
    $sql = "SELECT * FROM logeado WHERE Nombre = ? AND Contra = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Las credenciales son válidas, iniciar sesión
        session_start();
        $_SESSION['usuario'] = $usuario;

        // Redireccionar a la página principal
        header("Location: login.html");
        exit();
    } else {
        // Credenciales incorrectas, redireccionar al formulario de inicio de sesión
        $mensajeError = "¡Se produjo un error de logeo!";
        echo "<script>alert('$mensajeError'); window.location.href='loginReal.html';</script>";
        exit();
    }

    // Cerrar la declaración preparada
    $stmt->close();
} else {
    // Redireccionar al formulario de inicio de sesión si no se proporcionaron credenciales
    header("Location: loginReal.html");
    exit();
}

// Cerrar la conexión a la base de datos
$conn->close();

?>
