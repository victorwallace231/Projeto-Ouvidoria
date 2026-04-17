<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.html");
    exit();
}

$nomeUsuario      = htmlspecialchars($_SESSION["nome"]);
$matriculaUsuario = htmlspecialchars($_SESSION["matricula"] ?? "");
$emailUsuario     = htmlspecialchars($_SESSION["email"] ?? "");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhar Protocolo - Ouvidoria DW</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1b5e20;
            --primary-light: #2e7d32;
            --accent-color: #fb8c00;
            --accent-hover: #ef6c00;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo { margin-right: 10px; }
        .navbar-brand img { max-height: 45px; }

        .dashboard {
            display: flex;
            height: calc(100vh - 70px);
        }

        .sidebar {
            width: 250px;
            background: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar h6 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            color: #555;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: var(--accent-color);
            color: white;
        }

        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* BOTÃO VOLTAR */
        .btn-voltar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .btn-voltar:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-voltar svg {
            width: 16px;
            height: 16px;
        }

        .card-painel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease;
            margin-bottom: 20px;
        }

        .titulo {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .titulo::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
            margin-top: 5px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--accent-color);
            color: white;
            font-weight: bold;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* CAMPO DE BUSCA */
        .input-group .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(251,140,0,0.4);
        }

        .btn-buscar {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 0 30px 30px 0;
            padding: 10px 20px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-buscar:hover { background-color: var(--accent-hover); }

        .input-group .form-control {
            border-radius: 30px 0 0 30px;
        }

        /* RESULTADO */
        .resultado-box {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border-radius: 12px;
            border-left: 5px solid var(--accent-color);
            background-color: #fffbf5;
        }

        .resultado-box.visivel { display: block; }

        .resultado-box h6 {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #f0e8d8;
            font-size: 0.95rem;
        }

        .info-row:last-child { border-bottom: none; }
        .info-label { color: #777; }
        .info-value { font-weight: 600; color: #333; }

        .nao-encontrado {
            display: none;
            margin-top: 15px;
            color: #c0392b;
            font-weight: 500;
            text-align: center;
        }

        .nao-encontrado.visivel { display: block; }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(15px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-3">

            <a class="navbar-brand d-flex align-items-center" href="main.php">
                <img src="logo2__4_-removebg-preview.png" class="logo">
                <span class="fw-bold">OUVIDORIA</span>&nbsp;DW
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="user-avatar">
                        <?php echo mb_strtoupper(mb_substr($nomeUsuario, 0, 1, 'UTF-8'), 'UTF-8'); ?>
                    </div>
                    <div class="text-end">
                        <div><strong><?php echo $nomeUsuario; ?></strong></div>
                        <?php if ($matriculaUsuario): ?>
                            <small class="text-muted"><?php echo $matriculaUsuario; ?></small>
                        <?php else: ?>
                            <small class="text-muted"><?php echo $emailUsuario; ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
        </div>
    </nav>

    <div class="dashboard">

        <div class="sidebar">
            <h6>Menu</h6>
            <a href="criarmanifest.php">Nova Manifestação</a>
            <a href="manifests.php">Minhas Manifestações</a>
            <a href="protocolos.php">Acompanhar Protocolo</a>
            <a href="#">Perfil</a>
        </div>

        <div class="content">

            <!-- BOTÃO VOLTAR -->
            <a href="main.php" class="btn-voltar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>

            <div class="card-painel">
                <h4 class="titulo">Acompanhar Protocolo</h4>

                <p class="text-muted mb-4">Digite o número do protocolo para consultar o status da sua manifestação.</p>

                <!-- CAMPO DE BUSCA -->
                <div class="input-group">
                    <input type="text" class="form-control" id="inputProtocolo" placeholder="Ex: 2024001234">
                    <button class="btn-buscar" onclick="buscarProtocolo()">
                        Consultar
                    </button>
                </div>

                <!-- RESULTADO -->
                <div class="resultado-box" id="resultadoBox">
                    <h6>Resultado da consulta</h6>
                    <div class="info-row">
                        <span class="info-label">Protocolo</span>
                        <span class="info-value" id="resProtocolo">—</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tipo</span>
                        <span class="info-value" id="resTipo">—</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Assunto</span>
                        <span class="info-value" id="resAssunto">—</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Data de Envio</span>
                        <span class="info-value" id="resData">—</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value" id="resStatus">—</span>
                    </div>
                </div>

                <div class="nao-encontrado" id="naoEncontrado">
                    ⚠️ Protocolo não encontrado. Verifique o número e tente novamente.
                </div>

            </div>
        </div>
    </div>

    <script>
        function buscarProtocolo() {
            const protocolo = document.getElementById("inputProtocolo").value.trim();
            const resultadoBox  = document.getElementById("resultadoBox");
            const naoEncontrado = document.getElementById("naoEncontrado");

            // Limpa estado anterior
            resultadoBox.classList.remove("visivel");
            naoEncontrado.classList.remove("visivel");

            if (!protocolo) return;

            // Futuramente: fetch para um buscarprotocolo.php?protocolo=XXX
            // Por ora, exibe mensagem de não encontrado como placeholder
            naoEncontrado.classList.add("visivel");
        }

        // Permitir busca com Enter
        document.getElementById("inputProtocolo").addEventListener("keydown", function(e) {
            if (e.key === "Enter") buscarProtocolo();
        });
    </script>

</body>
</html>