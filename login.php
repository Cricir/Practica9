<?php
// Conexión a la base de datos (ajusta los valores según tu configuración)
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "usuarios";

// Función para obtener y construir las filas HTML
function obtenerYConstruirFilasHTML($conn) {
    // Realizar una consulta para obtener todos los registros
    $stmtSelect = $conn->query("SELECT id_usuario, Nombre, Apellidos, Profesion, Estado FROM usuarios");
    $result = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

    // Construir las filas HTML para la tabla
    $htmlFilas = "";
    foreach ($result as $fila) {
        $htmlFilas .= "<tr>
                        <td>{$fila['Nombre']}</td>
                        <td>{$fila['Apellidos']}</td>
                        <td>{$fila['Profesion']}</td>
                        <td>{$fila['Estado']}</td>
                        <td><button onclick='editarFila({$fila['id_usuario']}, \"{$fila['Nombre']}\", \"{$fila['Apellidos']}\", \"{$fila['Profesion']}\", \"{$fila['Estado']}\")'>Editar</button></td>
                        <td><button onclick='eliminarFila({$fila['id_usuario']})'>Eliminar</button></td>
                    </tr>";
    }

    return $htmlFilas;
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['guardar'])) {
        // Obtener datos del formulario
        $nombre = $_POST['fnombre'];
        $apellido = $_POST['fapellido'];
        $profesion = $_POST['fprofesion'];
        $estado = $_POST['festado'];

        // Preparar la consulta SQL
        $stmt = $conn->prepare("INSERT INTO usuarios(Nombre, Apellidos, Profesion, Estado) VALUES (:nombre, :apellido, :profesion, :estado)");

        // Bind de parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':profesion', $profesion);
        $stmt->bindParam(':estado', $estado);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener y devolver las filas HTML actualizadas después de guardar
        echo obtenerYConstruirFilasHTML($conn);
    } elseif (isset($_POST['id_usuario_editar'])) {
        // Actualizar usuario
        $id_usuario = $_POST['id_usuario_editar'];
        $nombre = $_POST['fnombre'];
        $apellido = $_POST['fapellido'];
        $profesion = $_POST['fprofesion'];
        $estado = $_POST['festado'];

        $stmtUpdate = $conn->prepare("UPDATE usuarios SET Nombre = :nombre, Apellidos = :apellido, Profesion = :profesion, Estado = :estado WHERE id_usuario = :id_usuario");
        $stmtUpdate->bindParam(':nombre', $nombre);
        $stmtUpdate->bindParam(':apellido', $apellido);
        $stmtUpdate->bindParam(':profesion', $profesion);
        $stmtUpdate->bindParam(':estado', $estado);
        $stmtUpdate->bindParam(':id_usuario', $id_usuario);
        $stmtUpdate->execute();

        // Obtener y devolver las filas HTML actualizadas después de actualizar
        echo obtenerYConstruirFilasHTML($conn);
    } elseif (isset($_POST['id_usuario'])) {
        // Eliminar usuario
        $id_usuario = $_POST['id_usuario'];
        $stmtDelete = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id_usuario");
        $stmtDelete->bindParam(':id_usuario', $id_usuario);
        $stmtDelete->execute();
    
        // Obtener y devolver las filas HTML actualizadas después de eliminar
        echo obtenerYConstruirFilasHTML($conn);
    } else {
        // Devolver las filas HTML al cargar la página
        echo obtenerYConstruirFilasHTML($conn);
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>
