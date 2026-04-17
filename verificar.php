<?php
session_start();

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "dbouvidoria";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Busca usuário — inclui Matricula na consulta
    $stmt = $conn->prepare("SELECT id_usu, Nome, senha, Matricula FROM tbusuario WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario["senha"])) {

            // Cria sessão com nome E matrícula
            $_SESSION["id"]        = $usuario["id_usu"];
            $_SESSION["nome"]      = $usuario["Nome"];
            $_SESSION["email"]     = $email;
            $_SESSION["matricula"] = $usuario["Matricula"];

            // Redireciona para main.php (com sessão)
            header("Location: main.php");
            exit();

        } else {
            header("Location: login.html?erro=1");
            exit();
        }

    } else {
        header("Location: login.html?erro=1");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>