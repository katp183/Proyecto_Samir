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
$email = $_POST['email'];
$password = $_POST['password'];

// Buscar al usuario en la base de datos
$sql = "SELECT user_id, password_hash, role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verificar contraseña
    if (password_verify($password, $user['password_hash'])) {
        // Iniciar sesión
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        echo "Inicio de sesión exitoso. Redirigiendo...";
        // Redirigir a la página adecuada
        header("Location: index.html");
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Correo electrónico no registrado.";
}

// Cerrar conexión
$conn->close();
?>
