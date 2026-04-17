<?php
session_start();

// Protege a página — redireciona se não estiver logado
if (!isset($_SESSION["id"])) {
    header("Location: login.html");
    exit();
}

$nomeUsuario     = htmlspecialchars($_SESSION["nome"]);
$matriculaUsuario = htmlspecialchars($_SESSION["matricula"] ?? "");
$emailUsuario    = htmlspecialchars($_SESSION["email"] ?? "");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Ouvidoria DW</title>

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

        /* NAVBAR */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            margin-right: 10px;
        }

        .navbar-brand img {
            max-height: 45px;
        }

        /* LAYOUT */
        .dashboard {
            display: flex;
            height: calc(100vh - 70px);
        }

        /* MENU LATERAL */
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

        /* CONTEÚDO */
        .content {
            flex: 1;
            padding: 30px;
        }

        .card-painel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            animation: fadeIn 0.6s ease;
        }

        .titulo {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 15px;
        }

        .titulo::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
            margin-top: 5px;
        }

        /* BOTÃO */
        .btn-main {
            background-color: var(--accent-color);
            color: white;
            border-radius: 30px;
            padding: 12px 25px;
            font-weight: bold;
            border: none;
        }

        .btn-main:hover {
            background-color: var(--accent-hover);
        }

        /* TABELA */
        table {
            margin-top: 15px;
        }

        /* AVATAR INICIAL */
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

        /* ANIMAÇÃO */
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

            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="logo2__4_-removebg-preview.png" class="logo">
                <span class="fw-bold">OUVIDORIA</span>&nbsp;DW
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">

                <!-- USUÁRIO (vindo da sessão PHP) -->
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

                <!-- SAIR -->
                <a href="logout.php" class="btn btn-outline-danger">
                    Sair
                </a>

            </div>
        </div>
    </nav>

    <!-- DASHBOARD -->
    <div class="dashboard">

        <!-- MENU LATERAL -->
        <div class="sidebar">
            <h6>Menu</h6>

            <a href="criarmanifest.php">Nova Manifestação</a>
            <a href="manifests.php">Minhas Manifestações</a>
            <a href="protocolos.php">Acompanhar Protocolo</a>
            <a href="#">Perfil</a>
        </div>

        <!-- CONTEÚDO -->
        <div class="content">

            <!-- BOAS VINDAS -->
            <div class="card-painel">
                <h4 class="titulo">Bem-vindo, <?php echo $nomeUsuario; ?>!</h4>

                <a href="criarmanifest.php" class="btn-main text-decoration-none d-inline-block">
                    Registrar Manifestação
                </a>
            </div>

            <!-- ÚLTIMAS MANIFESTAÇÕES -->
            <div class="card-painel">
                <h4 class="titulo">Últimas manifestações</h4>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Protocolo</th>
                            <th>Tipo</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Aqui virão os dados do banco futuramente -->
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Nenhuma manifestação encontrada.
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </div>

</body>
</html>
