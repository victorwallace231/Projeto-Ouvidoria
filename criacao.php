
<?php

// conexão com o banco
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "dbouvidoria";

$conn = new mysqli($host, $user, $pass, $db);

// verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// verifica se veio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // pega dados do form
    $nome = $_POST["nome"];
    $email = $_POST["email"];
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

    // insere no banco
    $stmt = $conn->prepare("INSERT INTO tbusuario (Nome, Email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

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

