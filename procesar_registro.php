<?php
// Iniciar sesión
session_start();

// Validar el input del usuario y escapar de ataques XSS
function validarEntrada($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
    $dsn = 'mysql:host=localhost;dbname=db03';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Validar y escapar datos del formulario
        $nombre = validarEntrada($_POST['nombre']);
        $agente_id = validarEntrada($_POST['agente_id']);
        $departamento_id = validarEntrada($_POST['departamento_id']);
        $num_misiones = intval($_POST['num_misiones']); // Convertir a entero
        $descripcion_mision = validarEntrada($_POST['descripcion_mision']);

        // Cifrar campos necesarios
        // Por ejemplo, el Agente ID y el Departamento ID pueden ser cifrados antes de ser almacenados en la base de datos

        // Insertar datos en la base de datos
        $stmt = $pdo->prepare("INSERT INTO agentes (nombre, agente_id, departamento_id, num_misiones, descripcion_mision) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $agente_id, $departamento_id, $num_misiones, $descripcion_mision]);

        // Redirigir después del registro exitoso
        header("Location: registro_exitoso.php");
        exit();
    } catch (PDOException $e) {
        // Manejar errores de conexión o consulta
        die("Error al registrar agente: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Agente Secreto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #555;
        }
        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="number"], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="number"]:focus, textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        label {
            font-weight: bold;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>Registro de Agente Secreto</h2>
    <form method="post" action="procesar_registro.php">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="agente_id">Agente ID:</label><br>
        <input type="text" id="agente_id" name="agente_id" required><br>
        <label for="departamento_id">Departamento ID:</label><br>
        <input type="text" id="departamento_id" name="departamento_id" required><br>
        <label for="num_misiones">Número de Misiones:</label><br>
        <input type="number" id="num_misiones" name="num_misiones" required><br>
        <label for="descripcion_mision">Descripción de la nueva misión:</label><br>
        <textarea id="descripcion_mision" name="descripcion_mision" rows="4" required></textarea><br>
        <input type="submit" value="Registrar Agente">
    </form>
</body>
</html>
