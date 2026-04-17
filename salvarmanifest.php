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

$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit();
}

$id_usu   = isset($_POST["id_usu"])   ? (int)$_POST["id_usu"]   : 0;
$id_tipo  = isset($_POST["id_tipo"])  ? (int)$_POST["id_tipo"]  : 0;
$contato  = isset($_POST["contato"])  ? trim($_POST["contato"])  : "";
$manifest = isset($_POST["manifest"]) ? trim($_POST["manifest"]) : "";

if ($id_tipo === 0 || $manifest === "") {
    $anonimo = ($id_usu === 0) ? "?anonimo=1&erro=campos" : "?erro=campos";
    header("Location: criarmanifest.php" . $anonimo);
    exit();
}

// Busca nome real da tabela (evita problema de acento no código-fonte)
$res = $conn->query("
    SELECT TABLE_NAME FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = 'dbouvidoria' AND TABLE_NAME LIKE 'tbmanifest%'
    LIMIT 1
");

if (!$res || $res->num_rows === 0) {
    die("Erro: tabela tbmanifest* não encontrada.");
}

$tabela = $res->fetch_assoc()['TABLE_NAME'];

$sql  = "INSERT INTO `$tabela` (id_usu, id_tipo, Contato, Manifest) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar query: " . $conn->error);
}

$stmt->bind_param("iiss", $id_usu, $id_tipo, $contato, $manifest);

if ($stmt->execute()) {
    $protocolo = $stmt->insert_id;
    $stmt->close();
    $conn->close();

    $anonimo_param = ($id_usu === 0) ? "&anonimo=1" : "";
    header("Location: criarmanifest.php?protocolo=" . $protocolo . $anonimo_param);
    exit();

} else {
    $erro = urlencode("Erro ao salvar: " . $stmt->error);
    $stmt->close();
    $conn->close();

    $anonimo_param = ($id_usu === 0) ? "?anonimo=1&erro=" . $erro : "?erro=" . $erro;
    header("Location: criarmanifest.php" . $anonimo_param);
    exit();
}
?>