<?php

// conexão com o banco
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "dbouvidoria";
$porta = "3307";

$conn = new mysqli($host, $user, $pass, $db, $porta);

// verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// verifica se veio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // pega dados do form
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $matricula = $_POST["matricula"];
    $serie = $_POST["serie"];
    $curso = $_POST["curso"];
    $senha = $_POST["senha"];

    // 🔐 cria hash da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // verifica se email já existe
    $check = $conn->prepare("SELECT id_usu FROM tbusuario WHERE Email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email já cadastrado!'); window.history.back();</script>";
        exit();
    }

    // insere no banco com TODOS os campos
    $stmt = $conn->prepare("
        INSERT INTO tbusuario (Nome, Serie, Curso, Matricula, Email, senha) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sissss", $nome, $serie, $curso, $matricula, $email, $senhaHash);

    if ($stmt->execute()) {
        // sucesso → volta pro login
        header("Location: login.html");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

