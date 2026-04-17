<?php
session_start();

// Verifica se é anônimo (vindo do index) ou logado
$anonimo = isset($_GET['anonimo']) && $_GET['anonimo'] == '1';

if (!$anonimo && !isset($_SESSION["id"])) {
    header("Location: login.html");
    exit();
}

$nomeUsuario      = $anonimo ? "Anônimo" : htmlspecialchars($_SESSION["nome"]);
$matriculaUsuario = $anonimo ? "" : htmlspecialchars($_SESSION["matricula"] ?? "");
$emailUsuario     = $anonimo ? "" : htmlspecialchars($_SESSION["email"] ?? "");
$idUsuario        = $anonimo ? 0 : (int)$_SESSION["id"];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Manifestação - Ouvidoria DW</title>

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
            min-height: calc(100vh - 70px);
        }

        /* Sidebar: só exibe se logado */
        .sidebar {
            width: 250px;
            background: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            flex-shrink: 0;
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

        .btn-voltar svg { width: 16px; height: 16px; }

        .card-painel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease;
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

        .btn-main {
            background-color: var(--accent-color);
            color: white;
            border-radius: 30px;
            padding: 12px;
            font-weight: bold;
            border: none;
        }

        .btn-main:hover { background-color: var(--accent-hover); }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(251,140,0,0.4);
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

        /* MODAL PROTOCOLO */
        .protocolo-box {
            background: linear-gradient(135deg, #1b5e20, #2e7d32);
            color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
        }

        .protocolo-numero {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--accent-color);
            letter-spacing: 3px;
            margin: 10px 0;
        }

        /* Badge anônimo */
        .badge-anonimo {
            background-color: #6c757d;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

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

            <a class="navbar-brand d-flex align-items-center" href="<?php echo $anonimo ? 'index.html' : 'main.php'; ?>">
                <img src="logo2__4_-removebg-preview.png" class="logo">
                <span class="fw-bold">OUVIDORIA</span>&nbsp;DW
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <?php if ($anonimo): ?>
                    <span class="badge-anonimo"><i class="fas fa-user-secret me-1"></i>Modo Anônimo</span>
                    <a href="index.html" class="btn btn-outline-secondary btn-sm rounded-pill">Voltar ao Início</a>
                <?php else: ?>
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
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="dashboard">

        <!-- SIDEBAR (apenas para logados) -->
        <?php if (!$anonimo): ?>
        <div class="sidebar">
            <h6>Menu</h6>
            <a href="criarmanifest.php">Nova Manifestação</a>
            <a href="manifests.php">Minhas Manifestações</a>
            <a href="protocolos.php">Acompanhar Protocolo</a>
            <a href="#">Perfil</a>
        </div>
        <?php endif; ?>

        <div class="content">

            <!-- BOTÃO VOLTAR -->
            <a href="<?php echo $anonimo ? 'index.html' : 'main.php'; ?>" class="btn-voltar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>

            <!-- AVISO ANÔNIMO -->
            <?php if ($anonimo): ?>
            <div class="alert alert-secondary d-flex align-items-center gap-2 mb-3" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
                Você está enviando uma manifestação <strong>&nbsp;anônima</strong>. Seu nome não será registrado.
            </div>
            <?php endif; ?>

            <div class="card-painel">
                <h4 class="titulo">Registrar Manifestação</h4>

                <form id="formManifest" action="salvarmanifest.php" method="post" enctype="multipart/form-data">

                    <!-- Passa o id do usuário (0 se anônimo) -->
                    <input type="hidden" name="id_usu" value="<?php echo $idUsuario; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo de manifestação</label>
                        <select class="form-select" name="id_tipo" required>
                            <option value="">Selecionar</option>
                            <option value="1">Reclamação</option>
                            <option value="2">Sugestão</option>
                            <option value="3">Denúncia</option>
                            <option value="4">Elogio</option>
                            <option value="5">Solicitação</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contato para retorno <small class="text-muted">(opcional)</small></label>
                        <input type="text" class="form-control" name="contato"
                               placeholder="<?php echo $anonimo ? 'E-mail ou telefone (opcional)' : htmlspecialchars($emailUsuario ?: ''); ?>"
                               <?php if (!$anonimo && $emailUsuario) echo 'value="' . $emailUsuario . '"'; ?>>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descrição da manifestação</label>
                        <textarea class="form-control" name="manifest" rows="5"
                                  placeholder="Descreva detalhadamente sua manifestação..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-main w-100">
                        Enviar Manifestação
                    </button>

                </form>
            </div>

        </div>
    </div>

    <!-- MODAL: PROTOCOLO GERADO -->
    <div class="modal fade" id="modalProtocolo" tabindex="-1" data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">

          <div class="modal-body p-4">
            <div class="protocolo-box">
                <div class="mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h5 class="text-white mb-1">Manifestação Enviada!</h5>
                <p class="text-white-50 mb-3 small">Guarde seu número de protocolo:</p>
                <div class="protocolo-numero" id="numeroProtocolo">—</div>
                <p class="text-white-50 small mt-2">Use este número para acompanhar sua manifestação.</p>
            </div>
          </div>

          <div class="modal-footer border-0 justify-content-center pb-4">
            <button class="btn btn-warning rounded-pill px-4 fw-bold"
                    onclick="window.location.href='<?php echo $anonimo ? 'index.html' : 'main.php'; ?>'">
                OK, entendi
            </button>
          </div>

        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Verifica se veio com protocolo na URL (após redirect)
    const params = new URLSearchParams(window.location.search);
    const protocolo = params.get('protocolo');

    if (protocolo) {
        document.getElementById('numeroProtocolo').textContent = '#' + protocolo.padStart(6, '0');
        const modal = new bootstrap.Modal(document.getElementById('modalProtocolo'));
        modal.show();
    }
    </script>

</body>
</html>