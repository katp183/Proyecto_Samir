<?php
// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "18300923"; // Cambia si tu contraseña de MySQL no está vacía
$dbname = "hotel_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Verificar si el correo o el usuario ya existen
$sql = "SELECT * FROM users WHERE email = ? OR username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "El correo o nombre de usuario ya están registrados.";
} else {
    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar usuario en la base de datos
    $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password_hash);

    if ($stmt->execute()) {
        echo "Usuario registrado exitosamente. Redirigiendo...";
        header("Location: login.html");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }
}

// Cerrar conexión
$conn->close();
?>
