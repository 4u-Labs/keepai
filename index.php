<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

$vJs = filemtime(__DIR__ . '/app-v46.js') ?: time();
$vCss = filemtime(__DIR__ . '/style-v46.css') ?: time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- Atomic Sync (Bypass Cache) -->
<script>
    (function() {
        const V = "46.5.0";
        if (localStorage.getItem('keepai_v') !== V) {
            localStorage.setItem('keepai_v', V);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(rs => {
                    for (let r of rs) r.unregister();
                });
            }
            caches.keys().then(ns => {
                for (let n of ns) caches.delete(n);
            });
            setTimeout(() => { window.location.reload(true); }, 500);
        }
    })();
</script>
<head>
    <script>
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPrompt = e;
            if (typeof window.showInstallBanner === 'function') window.showInstallBanner();
            if (typeof window.updatePwaInstallUI === 'function') window.updatePwaInstallUI();
        });
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    
    <!-- Open Graph (Prioritized) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Keep AI — Notas Inteligentes">
    <meta property="og:description" content="Organize suas ideias com o poder da Inteligência Artificial.">
    <meta property="og:image" content="https://4u.ia.br/app/keepai/512.png?v=46">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">
    <meta property="og:url" content="https://4u.ia.br/app/keepai/">
    <meta itemprop="image" content="https://4u.ia.br/app/keepai/512.png?v=46">

    <title>Keep AI — Notas Inteligentes</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="192x192" href="192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="stylesheet" href="style-v46.css?v=<?= $vCss ?>">
    <link rel="manifest" href="manifest.json?v=46.4.2">
    <meta name="theme-color" content="#fbbc04">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="description" content="Keep AI — Notas Inteligentes">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <!-- ── Loading Overlay ── -->
    <div id="loading-overlay">
        <div class="big-spinner"></div>
        <p>Iniciando Keep AI...</p>
    </div>

    <!-- ── Auth Screen ── -->
    <section id="auth-screen">
        <div class="auth-card">
            <div class="lang-switcher auth-lang-switcher" title="Idioma / Language">
                <button type="button" class="btn-lang active" data-lang="pt" onclick="window.setLang('pt')">PT</button>
                <button type="button" class="btn-lang" data-lang="en" onclick="window.setLang('en')">EN</button>
            </div>
            <div class="auth-logo">
                <img src="512.png" alt="logo" style="width:80px; height:80px; border-radius:20px; box-shadow: 0 8px 16px rgba(0,0,0,0.2);">
            </div>
            <h1>Keep <span>AI</span></h1>
            <p id="auth-subtitle" data-i18n="auth.subtitle">Sua memória digital. Organize notas, listas e compromissos com total privacidade.</p>
            
            <div class="auth-google-container" style="margin-top: 28px; display: flex; flex-direction: column; align-items: center; width: 100%;">
                <button id="btn-google-login" onclick="conectarGoogle()" class="google-login-btn">
                    <svg viewBox="0 0 24 24" width="22" height="22" style="flex-shrink: 0;">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span data-i18n="auth.login_google">Entrar com Conta Google</span>
                </button>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 14px; text-align: center;" data-i18n="auth.secure_access">
                    Acesso seguro e sincronizado com sua Conta Google
                </p>
                <div style="margin-top: 14px; display: flex; gap: 12px; font-size: 0.75rem; color: var(--text-muted); justify-content: center;">
                    <a href="#" onclick="event.preventDefault(); openModal('modal-terms');" style="color: var(--text-dim); text-decoration: underline; cursor: pointer;" data-i18n="auth.terms">Termos de Uso</a>
                    <span>•</span>
                    <a href="#" onclick="event.preventDefault(); openModal('modal-privacy');" style="color: var(--text-dim); text-decoration: underline; cursor: pointer;" data-i18n="auth.privacy">Privacidade</a>
                </div>
            </div>

            <footer class="footer-clean" style="margin-top: 32px; padding: 0; opacity: 0.6;">
                <p style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em;" data-i18n="auth.copyright">&copy; 2026 4U.IA.BR - Soberania Digital</p>
            </footer>
        </div>
    </section>

    <!-- ── Main App Layout ── -->
    <div id="app">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand" id="sidebar-logo-click" title="5 cliques para o menu admin">
                <div class="sidebar-logo">
                    <img src="192.png" alt="logo" style="width:32px; height:32px; border-radius:8px;">
                </div>
                <h2>Keep <span>AI</span></h2>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-label" data-i18n="nav.section_notes">Notas</div>
                    <div class="nav-item active" data-filter="all">
                        <span class="nav-icon"><i data-lucide="layers"></i></span>
                        <span data-i18n="nav.all_notes">Todas as notas</span>
                    </div>
                    <div class="nav-item" data-filter="pinned">
                        <span class="nav-icon"><i data-lucide="pin"></i></span>
                        <span data-i18n="nav.pinned">Fixadas</span>
                    </div>
                    <div class="nav-item" data-filter="archived">
                        <span class="nav-icon"><i data-lucide="archive"></i></span>
                        <span data-i18n="nav.archived">Arquivadas</span>
                    </div>
                    <div class="nav-item" data-filter="trash">
                        <span class="nav-icon"><i data-lucide="trash-2"></i></span>
                        <span data-i18n="nav.trash">Lixeira</span>
                    </div>
                    <div class="nav-item" onclick="shareApp()">
                        <span class="nav-icon"><i data-lucide="share-2"></i></span>
                        <span data-i18n="nav.share_app">Compartilhar App</span>
                    </div>
                    <div class="nav-item" id="btn-sidebar-install" onclick="handleInstallPwa()" title="Instalar aplicativo Keep AI" data-i18n-title="nav.install_app">
                        <span class="nav-icon"><i data-lucide="download"></i></span>
                        <span id="sidebar-install-text" data-i18n="nav.install_app">Instalar App</span>
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-label" data-i18n="nav.section_categories">Categorias</div>
                    <div class="nav-item" data-filter="shopping-list">
                        <span class="nav-icon"><i data-lucide="check-square"></i></span>
                        <span data-i18n="nav.checklist">Checklist</span>
                    </div>
                    <div class="nav-item" data-filter="agenda">
                        <span class="nav-icon"><i data-lucide="calendar"></i></span>
                        <span data-i18n="nav.agenda">Agenda</span>
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-label" data-i18n="nav.section_cloud">Nuvem & Backup</div>
                    <div class="nav-item" id="btn-sidebar-drive-backup" title="Fazer backup no Google Drive" data-i18n-title="nav.backup_drive">
                        <span class="nav-icon"><i data-lucide="cloud"></i></span>
                        <span data-i18n="nav.backup_drive">Backup Drive</span>
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-label" data-i18n="nav.section_legal">Legal & Ajuda</div>
                    <div class="nav-item" id="btn-force-update">
                        <i data-lucide="refresh-cw"></i>
                        <span data-i18n="nav.update_app">Atualizar App</span>
                    </div>
                    <div class="nav-item" id="btn-open-privacy">
                        <span class="nav-icon"><i data-lucide="shield-check"></i></span>
                        <span data-i18n="nav.privacy">Privacidade</span>
                    </div>
                    <div class="nav-item" id="btn-open-tutorial" onclick="openModal('tutorial-modal')" title="Como usar o App" data-i18n-title="nav.tutorial">
                        <span class="nav-icon"><i data-lucide="book-open"></i></span>
                        <span data-i18n="nav.tutorial">Tutorial</span>
                    </div>
                    <div class="nav-item" id="btn-open-terms">
                        <span class="nav-icon"><i data-lucide="file-text"></i></span>
                        <span data-i18n="nav.terms">Termos</span>
                    </div>
                    <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" rel="noopener noreferrer" class="nav-item donate-nav-item" style="color: #fbbf24; text-decoration: none;" title="Apoie o Projeto Keep AI via PayPal" data-i18n-title="nav.donate_title">
                        <span class="nav-icon" style="color: #fbbf24;"><i data-lucide="coffee"></i></span>
                        <span style="color: #fbbf24; font-weight: 600;" data-i18n="nav.donate">Apoie o Projeto</span>
                    </a>
                    <div class="sidebar-social-links">
                        <a href="https://github.com/4u-Labs" target="_blank" rel="noopener noreferrer" class="social-link-btn" title="GitHub 4u-Labs">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/in/fbr4g4/" target="_blank" rel="noopener noreferrer" class="social-link-btn" title="LinkedIn Fabiano Braga">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/fbr4g4/" target="_blank" rel="noopener noreferrer" class="social-link-btn" title="Facebook Fabiano Braga">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/fbr4g4/" target="_blank" rel="noopener noreferrer" class="social-link-btn" title="Instagram @fbr4g4">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                            </svg>
                        </a>
                        <a href="https://alternativeto.net/user/4u-ia-br/added/" target="_blank" rel="noopener noreferrer" class="social-link-btn" title="AlternativeTo 4u.ia.br">
                            <svg viewBox="0 0 64 64" width="18" height="18">
                                <rect width="64" height="64" rx="14" fill="#1b95e0"/>
                                <path d="M22.7 19c-1 0-2.2-.5-2.2-2.1v-2.3C20.5 11 25.8 9.7 31 9.7c7.1 0 16.3 1.8 16.3 13.7v27.5c0 1.9-.9 2.8-2.7 2.8h-3.9c-1.7 0-2.6-.8-2.8-2.2-.2.3-2.8 3-7.6 3-7.9 0-13.4-6.1-13.4-13.9 0-13.1 9.8-169 19.9-18-.2-2.9-1.3-5-5.9-5-4.7 0-6.5 1.3-8.2 1.4zm9.3 26.2c3 0 4.6-2.5 4.6-5.7v-9c-4.7 1.1-9.3 2.7-9.3 9 .1 3.1 1.5 5.7 4.7 5.7z" fill="#fff"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </nav>

            <div class="sidebar-user">
                <div class="user-card">
                    <div class="user-avatar" id="user-avatar">?</div>
                    <div class="user-info">
                        <div class="user-name" id="user-name" data-i18n="nav.loading_user">Carregando...</div>
                        <div class="user-email" id="user-email">...</div>
                    </div>
                    <button class="btn-logout" id="btn-logout" title="Sair" data-i18n-title="nav.logout">
                        <i data-lucide="log-out"></i>
                    </button>
                </div>
            </div>

            <div class="sidebar-footer">
                <div class="lang-switcher sidebar-lang-switcher" title="Idioma / Language">
                    <button type="button" class="btn-lang active" data-lang="pt" onclick="window.setLang('pt')">PT</button>
                    <button type="button" class="btn-lang" data-lang="en" onclick="window.setLang('en')">EN</button>
                </div>
                <div style="font-size: 10px; opacity: 0.4; margin-bottom: 8px; color: #ef4444;">Keep AI v46.2.14 (Ecosystem Portal Support)</div>
                <p class="text-[10px] uppercase tracking-[0.1em] opacity-50">&copy; <span class="curr-year">2026</span> 4U.IA.BR - Todos os direitos reservados</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <!-- Batch Selection Action Bar -->
            <div id="batch-action-bar" class="batch-action-bar hidden">
                <div class="batch-left">
                    <button class="btn-icon batch-btn-close" id="btn-batch-close" title="Cancelar seleção (Esc)" data-i18n-title="batch.cancel_title">
                        <i data-lucide="x"></i>
                    </button>
                    <span class="batch-count" id="batch-count"><span id="batch-count-num">0</span> <span class="batch-count-word" data-i18n="batch.selected_word">selecionada(s)</span></span>
                </div>
                <div class="batch-actions">
                    <button class="btn-icon batch-action-btn" id="btn-batch-select-all" title="Selecionar Tudo" data-i18n-title="batch.select_all">
                        <i data-lucide="check-check"></i>
                    </button>
                    <button class="btn-icon batch-action-btn" id="btn-batch-pin" title="Fixar / Desafixar" data-i18n-title="batch.pin">
                        <i data-lucide="pin"></i>
                    </button>
                    <button class="btn-icon batch-action-btn" id="btn-batch-color" title="Mudar cor" data-i18n-title="batch.color">
                        <i data-lucide="palette"></i>
                    </button>
                    <button class="btn-icon batch-action-btn" id="btn-batch-archive" title="Arquivar" data-i18n-title="batch.archive">
                        <i data-lucide="archive"></i>
                    </button>
                    <button class="btn-icon batch-action-btn" id="btn-batch-restore" title="Restaurar" data-i18n-title="batch.restore" style="display:none;">
                        <i data-lucide="rotate-ccw"></i>
                    </button>
                    <button class="btn-icon batch-action-btn batch-btn-delete" id="btn-batch-trash" title="Mover para a lixeira" data-i18n-title="batch.trash">
                        <i data-lucide="trash-2"></i>
                    </button>
                    <button class="btn-icon batch-action-btn batch-btn-delete" id="btn-batch-delete-permanent" title="Excluir definitivamente" data-i18n-title="batch.delete_permanent" style="display:none;">
                        <i data-lucide="trash"></i>
                    </button>
                </div>
            </div>

            <!-- Header -->
            <header class="header">
                <button class="btn-menu" id="btn-menu">
                    <i data-lucide="menu"></i>
                </button>
                <div class="search-bar">
                    <i data-lucide="search"></i>
                    <input type="text" id="search-input" placeholder="Pesquisar notas..." data-i18n-placeholder="header.search_placeholder">
                </div>

                <div class="header-actions">
                    <div class="lang-switcher header-lang-switcher" title="Idioma / Language">
                        <button type="button" class="btn-lang active" data-lang="pt" onclick="window.setLang('pt')">PT</button>
                        <button type="button" class="btn-lang" data-lang="en" onclick="window.setLang('en')">EN</button>
                    </div>

                    <div class="credits-badge" id="credits-badge" title="Recarregar créditos">
                        <span class="icon">💎</span>
                        <span id="credits-count">0</span>
                        <span class="cred-label" style="margin-left:4px; font-weight:400; opacity:0.7" data-i18n="header.credits_label">créditos</span>
                    </div>

                    <div class="drive-sync-badge" id="drive-sync-badge" onclick="GoogleDriveService.syncWithDrive(true)" title="Status de Sincronização com Google Drive" data-i18n-title="header.drive_sync_title" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; cursor: pointer; background: var(--surface); border: 1px solid var(--border); color: var(--text-muted); transition: all 0.2s;">
                        <i data-lucide="cloud" style="width: 15px; height: 15px; color: #4285F4;"></i>
                        <span id="drive-sync-text">Drive</span>
                    </div>

                    <button class="btn-install-pwa" id="btn-header-install" onclick="handleInstallPwa()" title="Instalar aplicativo Keep AI" data-i18n-title="header.install_btn">
                        <i data-lucide="download"></i>
                        <span class="install-btn-text" data-i18n="header.install_btn">Instalar App</span>
                    </button>

                    <button class="btn-icon" id="btn-toggle-layout" title="Alternar visualização (Grade / Lista)" data-i18n-title="header.toggle_layout">
                        <i data-lucide="rows-3"></i>
                    </button>

                    <button class="btn-icon" id="btn-theme" title="Trocar Estilo" data-i18n-title="header.theme_title">
                        <i data-lucide="palette"></i>
                    </button>
                    <button class="btn-icon" id="btn-voice" title="Comando de voz" data-i18n-title="header.voice_title">
                        <i data-lucide="mic"></i>
                    </button>
                </div>
            </header>

            <!-- Notes Grid Area -->
            <section class="notes-area">
                <div class="notes-header">
                    <div>
                        <h2 class="notes-title" id="filter-title" data-i18n="notes_header.all_title">Todas as notas</h2>
                        <p class="notes-subtitle" id="notes-count">Carregando...</p>
                    </div>
                    <div class="notes-header-actions">
                        <div class="header-action-row row-ai-tools">
                            <button class="btn-ask-ai" id="btn-open-ask-ai" title="Pergunte à sua Memória Digital" data-i18n-title="notes_header.ask_ai_title">
                                <i data-lucide="message-square"></i>
                                <span data-i18n="notes_header.ask_ai">Memória Digital</span>
                            </button>
                            <button class="btn-vision" id="btn-open-vision" title="Escanear Foto / Documento" data-i18n-title="notes_header.vision_title">
                                <i data-lucide="camera"></i>
                                <span data-i18n="notes_header.vision">Foto OCR</span>
                            </button>
                        </div>
                        <button class="btn-ai" id="btn-open-ai">
                            <i data-lucide="sparkles"></i>
                            <span data-i18n="notes_header.elite_ai">IA de Elite</span>
                        </button>
                        <div class="header-action-row row-create-notes">
                            <button class="btn-primary btn-create-type" id="btn-new-list" style="background: var(--surface); border-color: var(--border); color: var(--text);" title="Novo Checklist" data-i18n-title="notes_header.new_list">
                                <i data-lucide="check-square"></i>
                                <span data-i18n="notes_header.new_list">Checklist</span>
                            </button>
                            <button class="btn-primary btn-create-type" id="btn-new-note" style="background: var(--surface); border-color: var(--border); color: var(--text);" title="Nova Nota Simples" data-i18n-title="notes_header.new_note">
                                <i data-lucide="plus"></i>
                                <span data-i18n="notes_header.new_note">Nota</span>
                            </button>
                            <button class="btn-primary btn-create-type" id="btn-new-appointment" style="background: var(--surface); border-color: var(--border); color: var(--text);" title="Nova Agenda / Evento" data-i18n-title="notes_header.new_appointment">
                                <i data-lucide="calendar"></i>
                                <span data-i18n="notes_header.new_appointment">Agenda</span>
                            </button>
                        </div>
                        <button class="btn-danger" id="btn-empty-trash" style="display:none; background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 8px 14px; font-weight: 600; font-size: 0.85rem; cursor: pointer; align-items: center; gap: 6px;">
                            <i data-lucide="trash-2"></i>
                            <span data-i18n="notes_header.empty_trash">Esvaziar Lixeira</span>
                        </button>
                    </div>
                </div>

                <div class="notes-grid" id="notes-grid">
                    <!-- Cards are rendered here -->
                </div>
            </section>
        </main>
    </div>

    <!-- ── MODALS ── -->

    <!-- AI Input Modal -->
    <div class="modal-overlay" id="ai-modal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title" data-i18n="modals.ai.title">✨ O que você deseja organizar?</h3>
                <button class="modal-close" id="btn-close-ai"><i data-lucide="x"></i></button>
            </div>
            
            <div class="ai-modal-quick-modes" style="display: flex; gap: 8px; margin-bottom: 12px;">
                <button type="button" class="btn-quick-mode active" id="btn-mode-text" style="flex: 1; padding: 8px; border-radius: 8px; font-size: 0.8rem; border: 1px solid var(--border); background: var(--surface); color: var(--text); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i data-lucide="type"></i> <span data-i18n="modals.ai.mode_text">Texto</span>
                </button>
                <button type="button" class="btn-quick-mode" id="btn-mode-audio" style="flex: 1; padding: 8px; border-radius: 8px; font-size: 0.8rem; border: 1px solid var(--border); background: var(--surface); color: var(--text); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i data-lucide="mic"></i> <span data-i18n="modals.ai.mode_audio">Áudio Whisper</span>
                </button>
                <button type="button" class="btn-quick-mode" id="btn-mode-vision" style="flex: 1; padding: 8px; border-radius: 8px; font-size: 0.8rem; border: 1px solid var(--border); background: var(--surface); color: var(--text); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i data-lucide="camera"></i> <span data-i18n="modals.ai.mode_vision">Foto OCR</span>
                </button>
            </div>

            <div class="modal-textarea-wrapper">
                <textarea id="ai-input" class="modal-textarea" placeholder="Ex: Preciso comprar leite e ovos. Marcar reunião amanhã às 15h com o João sobre o projeto Keep." data-i18n-placeholder="modals.ai.placeholder"></textarea>
                <button class="btn-voice-modal" id="btn-voice-modal" title="Falar" data-i18n-title="modals.ai.speak">
                    <i data-lucide="mic"></i>
                </button>
            </div>
            
            <div class="voice-indicator" id="voice-indicator">
                <div class="voice-dot"></div>
                <span data-i18n="modals.ai.listening">Ouvindo... fale agora</span>
            </div>

            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 12px;" data-i18n="modals.ai.cost_hint">
                💡 A IA irá separar automaticamente o que é lista, evento ou compromisso. (Custo: 1 crédito)
            </p>

            <div class="modal-actions">
                <button class="btn-secondary" id="btn-close-ai-alt" data-i18n="modals.ai.cancel">Cancelar</button>
                <button class="btn-primary" id="btn-submit-ai" data-i18n="modals.ai.submit">✨ Criar Notas</button>
            </div>
        </div>
    </div>

    <!-- Recharge Modal (PIX & PayPal) -->
    <div class="modal-overlay" id="pix-modal">
        <div class="modal" style="max-width: 440px;">
            <div class="modal-header" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                    <h3 class="modal-title" data-i18n="modals.recharge.title">💎 Recarregar Créditos</h3>
                    <button class="modal-close" id="btn-close-pix" style="margin: 0;"><i data-lucide="x"></i></button>
                </div>
                <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; color: #00c6ff; font-weight: 700; background: rgba(0, 198, 255, 0.1); padding: 2px 8px; border-radius: 4px;">Portal Unificado 4uLabs</span>
            </div>
            
            <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 16px; line-height: 1.45;" data-i18n-html="modals.recharge.subtitle">
                Seus créditos são integrados via <strong>4uLabs</strong>! Ao recarregar, eles ficam disponíveis para uso compartilhado em todo o ecossistema de ferramentas (Keep AI, TubeMind AI e futuros lançamentos).
            </p>

            <!-- Payment Method Tabs -->
            <div class="recharge-method-tabs">
                <button type="button" class="tab-recharge-method active" id="tab-recharge-pix" onclick="setRechargeMethod('pix')">
                    <span data-i18n="modals.recharge.tab_pix">🇧🇷 PIX (R$ BRL)</span>
                </button>
                <button type="button" class="tab-recharge-method" id="tab-recharge-paypal" onclick="setRechargeMethod('paypal')">
                    <span data-i18n="modals.recharge.tab_paypal">🌐 PayPal (US$ USD)</span>
                </button>
            </div>

            <!-- Packages -->
            <div class="pix-packages" id="recharge-packages">
                <div class="pix-pkg" data-index="0" onclick="selectRechargePackage(0)">
                    <div class="pix-pkg-credits">10</div>
                    <div class="pix-pkg-label" data-i18n="modals.recharge.credits_label">créditos</div>
                    <div class="pix-pkg-price" id="pkg-price-0">R$ 4,90</div>
                </div>
                <div class="pix-pkg selected" data-index="1" onclick="selectRechargePackage(1)" style="position: relative;">
                    <span class="pkg-badge-top">Top</span>
                    <div class="pix-pkg-credits">50</div>
                    <div class="pix-pkg-label" data-i18n="modals.recharge.credits_label">créditos</div>
                    <div class="pix-pkg-price" id="pkg-price-1">R$ 19,90</div>
                </div>
                <div class="pix-pkg" data-index="2" onclick="selectRechargePackage(2)">
                    <div class="pix-pkg-credits">100</div>
                    <div class="pix-pkg-label" data-i18n="modals.recharge.credits_label">créditos</div>
                    <div class="pix-pkg-price" id="pkg-price-2">R$ 34,90</div>
                </div>
            </div>

            <!-- PIX Container -->
            <div id="pix-payment-section">
                <div id="qr-area" class="qr-area">
                    <!-- QR Code appears here after generation -->
                </div>

                <div class="modal-actions" style="margin-top: 14px;">
                    <button id="btn-gerar-pix" class="btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-weight: 700; border-radius: 12px;">
                        Gerar QR Code PIX (R$ 19,90)
                    </button>
                </div>
            </div>

            <!-- PayPal Container -->
            <div id="paypal-payment-section" style="display: none; margin-top: 14px;">
                <div id="paypal-button-container" style="min-height: 48px;">
                    <!-- PayPal SDK Buttons appear here -->
                </div>
            </div>

            <div class="recharge-free-notice" style="margin-top: 16px; font-size: 0.74rem; color: var(--text-muted); text-align: center; line-height: 1.45; background: rgba(59, 130, 246, 0.07); padding: 10px 14px; border-radius: 10px; border: 1px solid rgba(59, 130, 246, 0.15);" data-i18n-html="modals.recharge.free_notice">
                💎 <strong>100% Gratuito:</strong> Criar notas, checklists, compromissos e backup no Google Drive são 100% gratuitos e ilimitados. Os créditos são necessários apenas para o processamento de Inteligência Artificial.
            </div>

            <div style="margin-top: 14px; text-align: center; font-size: 0.78rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 12px;">
                <span style="color: var(--text-muted);" data-i18n="modals.recharge.donate_prompt">Deseja apenas apoiar o desenvolvedor com uma contribuição voluntária?</span><br>
                <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" rel="noopener noreferrer" style="color: #fbbf24; font-weight: 700; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px; margin-top: 6px;" data-i18n="modals.recharge.donate_link">
                    ☕ Fazer Doação Livre via PayPal
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Note Modal (Premium Upgrade) -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal modal-premium" id="edit-modal-content">
            <div class="modal-header">
                <div class="modal-header-left">
                    <button class="modal-icon-btn" id="btn-edit-pin"><i data-lucide="pin"></i></button>
                    <div id="edit-image-preview" class="modal-image-preview hidden"></div>
                    <h3 class="modal-title" data-i18n="modals.edit.title_edit">Editar Nota</h3>
                </div>
                <button class="modal-close" id="btn-close-edit"><i data-lucide="x"></i></button>
            </div>
            
            <div class="modal-body-scroll">
                <!-- Note Type Selector Pills -->
                <div class="note-type-pills" style="display: flex; gap: 8px; margin-bottom: 14px; flex-wrap: wrap;">
                    <button type="button" class="type-pill-btn active" id="pill-type-generic" onclick="switchModalNoteType('generic')">
                        <i data-lucide="file-text" style="width:14px;height:14px;"></i> <span data-i18n="modals.edit.tab_generic">Nota</span>
                    </button>
                    <button type="button" class="type-pill-btn" id="pill-type-list" onclick="switchModalNoteType('shopping-list')">
                        <i data-lucide="check-square" style="width:14px;height:14px;"></i> <span data-i18n="modals.edit.tab_checklist">Checklist</span>
                    </button>
                    <button type="button" class="type-pill-btn" id="pill-type-event" onclick="switchModalNoteType('appointment')">
                        <i data-lucide="calendar" style="width:14px;height:14px;"></i> <span data-i18n="modals.edit.tab_agenda">Agenda</span>
                    </button>
                </div>

                <input type="text" id="edit-title" class="modal-input-premium" placeholder="Título" data-i18n-placeholder="modals.edit.title_placeholder">
                
                <!-- Text Content (Generic/Events) -->
                <textarea id="edit-content" class="modal-textarea-premium" placeholder="Escreva algo..." data-i18n-placeholder="modals.edit.content_placeholder"></textarea>
                
                <!-- List Editor (Shopping List) -->
                <div id="list-editor-container" class="list-editor-container hidden">
                    <div id="modal-list-items" class="modal-list-items">
                        <!-- Items will be added here dynamically -->
                    </div>
                    <button class="btn-add-item" id="btn-add-list-item">
                        <i data-lucide="plus"></i> <span data-i18n="modals.edit.add_item">Adicionar item</span>
                    </button>
                </div>
                
            </div>

            <div class="modal-footer-premium">
                <div class="modal-toolbar">
                    <div class="ai-dropdown-wrapper" style="position:relative; display:inline-block;">
                        <button class="toolbar-btn" id="btn-edit-ai-transform" title="Ações de IA na Nota" data-i18n-title="modals.edit.ai_actions">
                            <i data-lucide="sparkles"></i>
                        </button>
                        <div class="ai-dropdown-menu hidden" id="ai-transform-menu">
                            <button type="button" class="ai-menu-item" data-action="improve"><i data-lucide="wand-2"></i> <span data-i18n="modals.edit.ai_improve">Melhorar Escrita</span></button>
                            <button type="button" class="ai-menu-item" data-action="to_checklist"><i data-lucide="check-square"></i> <span data-i18n="modals.edit.ai_to_checklist">Converter em Checklist</span></button>
                            <button type="button" class="ai-menu-item" data-action="summarize"><i data-lucide="list"></i> <span data-i18n="modals.edit.ai_summarize">Resumir em Tópicos</span></button>
                            <button type="button" class="ai-menu-item" data-action="translate" data-lang="en"><i data-lucide="globe"></i> <span data-i18n="modals.edit.ai_translate_en">Traduzir para Inglês</span></button>
                            <button type="button" class="ai-menu-item" data-action="translate" data-lang="es"><i data-lucide="languages"></i> <span data-i18n="modals.edit.ai_translate_es">Traduzir para Espanhol</span></button>
                        </div>
                    </div>
                    <button class="toolbar-btn" id="btn-edit-palette" title="Mudar cor" data-i18n-title="modals.edit.color_title"><i data-lucide="palette"></i></button>
                    <button class="toolbar-btn" id="btn-toggle-list" title="Alternar Lista/Checklist" data-i18n-title="modals.edit.toggle_list_title"><i data-lucide="list-checks"></i></button>
                    <button class="toolbar-btn" id="btn-toggle-event" title="Definir Data / Compromisso" data-i18n-title="modals.edit.toggle_event_title"><i data-lucide="calendar"></i></button>
                    <button class="toolbar-btn" id="btn-edit-image" title="Adicionar imagem" data-i18n-title="modals.edit.add_image_title"><i data-lucide="image"></i></button>
                    <button class="toolbar-btn hidden" id="btn-edit-calendar" title="Adicionar ao Google Agenda" data-i18n-title="modals.edit.add_cal_title"><i data-lucide="calendar-plus"></i></button>
                    <button class="toolbar-btn" id="btn-edit-share" title="Compartilhar" data-i18n-title="modals.edit.share_title"><i data-lucide="share-2"></i></button>
                    <button class="toolbar-btn delete" id="btn-edit-delete" title="Excluir" data-i18n-title="modals.edit.delete_title"><i data-lucide="trash-2"></i></button>
                </div>

                <!-- Metadata Editor (Moved to Footer) -->
                <div id="metadata-editor-container" class="metadata-editor-footer hidden">
                    <div class="meta-field-f">
                        <input type="date" id="edit-date" title="Data">
                    </div>
                    <div class="meta-field-f">
                        <input type="time" id="edit-time" title="Hora">
                    </div>
                    <div class="meta-field-f person-f">
                        <input type="text" id="edit-person" placeholder="Quem?" data-i18n-placeholder="modals.edit.who_placeholder">
                    </div>
                </div>

                <div class="modal-actions-right">
                    <button class="btn-save-premium" id="btn-save-edit" data-i18n="modals.edit.save_btn">Fechar</button>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Memória Digital (Chat com suas Notas) Modal -->
    <div class="modal-overlay" id="ask-ai-modal">
        <div class="modal modal-chat">
            <div class="modal-header">
                <div class="chat-header-info">
                    <span class="chat-avatar-icon">🧠</span>
                    <div>
                        <h3 class="modal-title" data-i18n="modals.ask_ai.title">Memória Digital</h3>
                        <p style="font-size: 0.72rem; color: var(--text-muted);" data-i18n="modals.ask_ai.subtitle">Pergunte sobre qualquer anotação ou compromisso</p>
                    </div>
                </div>
                <button class="modal-close" id="btn-close-ask-ai"><i data-lucide="x"></i></button>
            </div>
            
            <div class="chat-body" id="ask-ai-messages">
                <div class="chat-msg assistant">
                    <div class="msg-bubble" data-i18n-html="modals.ask_ai.welcome">
                        Olá! Sou sua <strong>Memória Digital</strong>. Posso consultar todas as suas anotações, listas e compromissos salvos para responder às suas dúvidas.<br><br>
                        <em>Exemplos: "O que tenho agendado para esta semana?", "Quais compras faltam?", "Resuma minhas anotações recentes."</em>
                    </div>
                </div>
            </div>

            <div class="chat-suggestions" id="chat-suggestions">
                <button class="chat-sugg-btn" data-query="O que tenho agendado para os próximos dias?" data-i18n="modals.ask_ai.sugg_events">📅 Próximos compromissos</button>
                <button class="chat-sugg-btn" data-query="Quais itens estão pendentes na minha lista de compras?" data-i18n="modals.ask_ai.sugg_shopping">🛒 Itens pendentes</button>
                <button class="chat-sugg-btn" data-query="Faça um resumo de todas as minhas notas recentes." data-i18n="modals.ask_ai.sugg_summary">📝 Resumo geral</button>
            </div>

            <div class="chat-input-wrapper">
                <input type="text" id="ask-ai-input" placeholder="Pergunte às suas notas..." autocomplete="off" data-i18n-placeholder="modals.ask_ai.placeholder">
                <button id="btn-send-ask-ai" class="btn-send-chat" title="Enviar pergunta" data-i18n-title="modals.ask_ai.send_title">
                    <i data-lucide="send"></i>
                </button>
            </div>
            <p style="font-size: 0.7rem; color: var(--text-muted); text-align: center; margin-top: 8px;" data-i18n="modals.ask_ai.cost_hint">
                💡 Cada consulta analisa suas notas e consome 1 crédito de IA.
            </p>
        </div>
    </div>

    <!-- Vision / OCR Modal -->
    <div class="modal-overlay" id="vision-modal">
        <div class="modal modal-vision">
            <div class="modal-header">
                <h3 class="modal-title" data-i18n="modals.vision.title">📷 Escanear com IA (Visão Computacional)</h3>
                <button class="modal-close" id="btn-close-vision"><i data-lucide="x"></i></button>
            </div>
            
            <div class="vision-upload-area" id="vision-drop-area">
                <input type="file" id="vision-file-input" accept="image/*" style="display:none;">
                <input type="file" id="vision-camera-input" accept="image/*" capture="environment" style="display:none;">
                <div id="vision-placeholder" class="vision-placeholder">
                    <i data-lucide="camera" style="width:48px; height:48px; margin-bottom:8px; color:var(--accent);"></i>
                    <strong style="font-size:1.05rem; display:block; margin-bottom:4px;" data-i18n="modals.vision.drop_title">Tirar foto ou carregar imagem</strong>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 12px;" data-i18n="modals.vision.drop_sub">Recibos, notas fiscais, lousas, listas de papel ou anotações manuscritas</p>
                    <div class="vision-btn-row" style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                        <button type="button" class="btn-primary" id="btn-trigger-camera" style="font-size:0.85rem; padding:8px 14px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;">
                            <i data-lucide="camera" style="width:16px; height:16px;"></i> <span data-i18n="modals.vision.take_photo">Tirar Foto</span>
                        </button>
                        <button type="button" class="btn-secondary" id="btn-trigger-gallery" style="font-size:0.85rem; padding:8px 14px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;">
                            <i data-lucide="image" style="width:16px; height:16px;"></i> <span data-i18n="modals.vision.upload_image">Carregar Imagem</span>
                        </button>
                    </div>
                </div>
                <img id="vision-img-preview" class="vision-img-preview hidden" alt="Preview da Imagem">
            </div>

            <div class="vision-prompt-options" style="margin-top: 14px;">
                <label style="font-size: 0.78rem; font-weight: 600; color: var(--text-dim); display:block; margin-bottom:6px;" data-i18n="modals.vision.goal_label">Objetivo do Scanner:</label>
                <select id="vision-mode-select" class="modal-input" style="height:42px; margin-bottom:12px;">
                    <option value="auto" data-i18n="modals.vision.goal_auto">✨ Detectar e Organizar Automaticamente</option>
                    <option value="shopping" data-i18n="modals.vision.goal_shopping">🛒 Converter em Lista de Compras / Checklist</option>
                    <option value="event" data-i18n="modals.vision.goal_event">📅 Extrair Data, Hora e Local para Evento</option>
                    <option value="text" data-i18n="modals.vision.goal_text">📝 Apenas Transcrever Todo o Texto (OCR puro)</option>
                </select>
            </div>

            <div class="modal-actions">
                <button class="btn-secondary" id="btn-close-vision-alt" data-i18n="modals.vision.cancel">Cancelar</button>
                <button class="btn-primary" id="btn-process-vision" disabled data-i18n="modals.vision.process">✨ Processar Imagem (1 crédito)</button>
            </div>
        </div>
    </div>

    <!-- Audio / Whisper Modal -->
    <div class="modal-overlay" id="audio-modal">
        <div class="modal modal-audio" style="text-align: center;">
            <div class="modal-header">
                <h3 class="modal-title" data-i18n="modals.audio.title">🎙️ Gravador Inteligente (Whisper)</h3>
                <button class="modal-close" id="btn-close-audio"><i data-lucide="x"></i></button>
            </div>
            
            <div class="audio-record-box" style="padding: 24px 12px;">
                <div class="audio-wave-container" id="audio-wave">
                    <div class="audio-pulse-circle"></div>
                    <i data-lucide="mic" style="width: 48px; height: 48px; color: #fff; position: relative; z-index: 2;"></i>
                </div>
                <div class="audio-timer" id="audio-timer" style="font-size: 2rem; font-weight: 700; margin-top: 16px; font-family: monospace;">00:00</div>
                <p id="audio-status-text" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 8px;" data-i18n="modals.audio.status_idle">
                    Clique para iniciar a gravação. A IA transcreverá suas ideias e organizará suas notas.
                </p>
            </div>

            <div class="modal-actions" style="justify-content: center; margin-top: 24px;">
                <button class="btn-secondary" id="btn-cancel-audio" data-i18n="modals.audio.cancel">Cancelar</button>
                <button class="btn-primary" id="btn-toggle-record">
                    <i data-lucide="circle-dot"></i>
                    <span id="btn-record-label" data-i18n="modals.audio.start_rec">Iniciar Gravação</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Undo Soft Delete Toast -->
    <div id="undo-toast" class="undo-toast hidden">
        <div class="undo-toast-content">
            <span id="undo-toast-text" data-i18n="modals.undo.trash_msg">Nota movida para a lixeira.</span>
            <button id="btn-undo-action" class="btn-undo" data-i18n="modals.undo.undo_btn">Desfazer</button>
        </div>
        <div class="undo-progress-bar" id="undo-progress-bar"></div>
    </div>

    <!-- Hidden File Input for Images -->
    <input type="file" id="image-upload-input" accept="image/*" style="display:none;">

    <div id="toast-container"></div>
    
    <!-- Install App Banner -->
    <div id="install-banner" class="install-banner">
        <div class="install-content">
            <img src="192.png" alt="logo" class="install-logo">
            <div>
                <strong data-i18n="install_banner.title">Instalar Keep AI</strong>
                <p data-i18n="install_banner.desc">Acesse mais rápido direto da sua tela inicial.</p>
            </div>
        </div>
        <div class="install-actions">
            <button class="btn-secondary btn-sm" id="btn-install-later" data-i18n="install_banner.later">Depois</button>
            <button class="btn-primary btn-sm" id="btn-install-now" data-i18n="install_banner.install">Instalar</button>
        </div>
    </div>
    
    <!-- Share Modal (Desktop Custom) -->
    <div class="modal-overlay" id="share-modal">
        <div class="modal share-modal-content">
            <div class="modal-header">
                <h3 class="modal-title" data-i18n="modals.share.title">Compartilhar</h3>
                <button class="modal-close" id="btn-close-share"><i data-lucide="x"></i></button>
            </div>
            <div class="share-options-grid">
                <a href="#" id="share-whatsapp" target="_blank" class="share-option">
                    <div class="share-icon-circle whatsapp"><i data-lucide="message-circle"></i></div>
                    <span data-i18n="modals.share.whatsapp">WhatsApp</span>
                </a>
                <a href="#" id="share-telegram" target="_blank" class="share-option">
                    <div class="share-icon-circle telegram"><i data-lucide="send"></i></div>
                    <span data-i18n="modals.share.telegram">Telegram</span>
                </a>
                <a href="#" id="share-email" target="_blank" class="share-option">
                    <div class="share-icon-circle email"><i data-lucide="mail"></i></div>
                    <span data-i18n="modals.share.email">E-mail</span>
                </a>
                <div id="share-copy" class="share-option cursor-pointer">
                    <div class="share-icon-circle copy"><i data-lucide="copy"></i></div>
                    <span data-i18n="modals.share.copy">Copiar</span>
                </div>
            </div>
            <div class="share-url-preview">
                <input type="text" id="share-url-input" readonly>
            </div>
        </div>
    </div>
    
    <!-- PWA Install Guide Modal -->
    <div class="modal-overlay" id="modal-install-guide">
        <div class="modal modal-install-guide" style="max-width: 480px; text-align: left;">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="192.png" alt="Keep AI" style="width: 32px; height: 32px; border-radius: 8px;">
                    <h3 class="modal-title" data-i18n="modals.install_guide.title">Instalar Keep AI</h3>
                </div>
                <button class="modal-close" onclick="closeModal('modal-install-guide')"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-body" style="padding: 16px 0;">
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 16px; line-height: 1.5;" data-i18n-html="modals.install_guide.desc">
                    Instale o <strong>Keep AI</strong> no seu dispositivo para acesso instantâneo, modo tela cheia e funcionamento offline!
                </p>

                <div id="install-guide-ios" class="install-guide-box" style="display: none; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 12px;">
                    <strong style="display: flex; align-items: center; gap: 8px; font-size: 0.92rem; margin-bottom: 12px; color: var(--text);" data-i18n="modals.install_guide.ios_title">
                        🍎 No iPhone ou iPad (Safari):
                    </strong>
                    <ol style="margin: 0; padding-left: 20px; font-size: 0.85rem; color: var(--text-dim); line-height: 1.6;">
                        <li data-i18n-html="modals.install_guide.ios_1">Toque no botão de <strong>Compartilhar</strong> na barra inferior do Safari.</li>
                        <li data-i18n-html="modals.install_guide.ios_2">Role para baixo e toque em <strong>"Adicionar à Tela de Início"</strong>.</li>
                        <li data-i18n-html="modals.install_guide.ios_3">Toque em <strong>"Adicionar"</strong> no canto superior direito.</li>
                    </ol>
                </div>

                <div id="install-guide-android" class="install-guide-box" style="display: none; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 12px;">
                    <strong style="display: flex; align-items: center; gap: 8px; font-size: 0.92rem; margin-bottom: 12px; color: var(--text);" data-i18n="modals.install_guide.android_title">
                        🤖 No Android (Chrome):
                    </strong>
                    <ol style="margin: 0; padding-left: 20px; font-size: 0.85rem; color: var(--text-dim); line-height: 1.6;">
                        <li data-i18n-html="modals.install_guide.android_1">Toque no menu de <strong>3 pontinhos</strong> no topo do navegador.</li>
                        <li data-i18n-html="modals.install_guide.android_2">Selecione <strong>"Instalar aplicativo"</strong> ou <strong>"Adicionar à tela inicial"</strong>.</li>
                        <li data-i18n-html="modals.install_guide.android_3">Confirme tocando em <strong>"Instalar"</strong>.</li>
                    </ol>
                </div>

                <div id="install-guide-desktop" class="install-guide-box" style="display: none; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 12px;">
                    <strong style="display: flex; align-items: center; gap: 8px; font-size: 0.92rem; margin-bottom: 12px; color: var(--text);" data-i18n="modals.install_guide.desktop_title">
                        💻 No Computador (Chrome / Edge / Brave):
                    </strong>
                    <ol style="margin: 0; padding-left: 20px; font-size: 0.85rem; color: var(--text-dim); line-height: 1.6;">
                        <li data-i18n-html="modals.install_guide.desktop_1">Clique no ícone de instalação na barra de endereços (lado direito).</li>
                        <li data-i18n-html="modals.install_guide.desktop_2">Ou clique nos <strong>3 pontinhos</strong> do navegador &gt; <strong>"Instalar Keep AI..."</strong>.</li>
                    </ol>
                </div>
            </div>
            <div class="modal-actions" style="justify-content: flex-end; margin-top: 10px;">
                <button class="btn-primary btn-sm" onclick="closeModal('modal-install-guide')" data-i18n="modals.install_guide.understand">Entendi</button>
            </div>
        </div>
    </div>
    
    <!-- Tutorial Modal (Guia Completo) -->
    <div class="modal-overlay" id="tutorial-modal">
        <div class="modal tutorial-modal-content" style="max-width: 740px; max-height: 88vh; overflow-y: auto;">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" data-i18n="modals.tutorial.title">📖 Guia Completo do Keep AI</h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;" data-i18n="modals.tutorial.subtitle">Aprenda a dominar todos os recursos e aumentar sua produtividade</p>
                </div>
                <button class="modal-close" id="btn-close-tutorial" onclick="closeModal('tutorial-modal')"><i data-lucide="x"></i></button>
            </div>
            
            <div class="tutorial-grid" data-i18n-html="modals.tutorial.items">
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #f59e0b; background: rgba(245, 158, 11, 0.12);"><i data-lucide="sparkles"></i></div>
                    <div>
                        <strong>✨ IA de Elite (Texto & Áudio Whisper)</strong>
                        <p>Dite no microfone ou escreva o que precisa organizar. A IA detecta o contexto e cria notas simples, listas de tarefas ou compromissos com data e hora automaticamente.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #34d399; background: rgba(52, 211, 153, 0.12);"><i data-lucide="camera"></i></div>
                    <div>
                        <strong>📷 Foto OCR (Scanner Inteligente)</strong>
                        <p>Fotografe documentos, quadros, comprovantes ou notas manuscritas. O sistema extrai o texto com OCR de alta precisão e gera sua anotação pronta para usar.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #818cf8; background: rgba(129, 140, 248, 0.12);"><i data-lucide="message-square"></i></div>
                    <div>
                        <strong>💬 Memória Digital (Segunda Mente)</strong>
                        <p>Converse com a IA sobre tudo o que você já anotou. Pergunte "Qual foi o valor do conserto?" ou "O que planejei para amanhã?" com citações e links diretos para a nota.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #38bdf8; background: rgba(56, 189, 248, 0.12);"><i data-lucide="cloud"></i></div>
                    <div>
                        <strong>☁️ Zero Servidor & Google Drive</strong>
                        <p>Suas notas ficam salvas no navegador (IndexedDB) em 0ms e sincronizadas em segundo plano na sua pasta privada do Google Drive (<code>Keep AI/keepai_notes.json</code>).</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #a855f7; background: rgba(168, 85, 247, 0.12);"><i data-lucide="download"></i></div>
                    <div>
                        <strong>📲 PWA Instalável & 100% Offline</strong>
                        <p>Instale o Keep AI no seu celular ou computador pelo botão no topo. Use o aplicativo normalmente sem sinal de internet; os dados sincronizam ao reconectar.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #ec4899; background: rgba(236, 72, 153, 0.12);"><i data-lucide="layout-grid"></i></div>
                    <div>
                        <strong>🗂️ Grade (2 Colunas) vs Lista</strong>
                        <p>Alterne entre a visualização em 2 colunas verticais (estilo clássico Google Keep no celular) e lista corrida de largura total pelo botão no cabeçalho.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #10b981; background: rgba(16, 185, 129, 0.12);"><i data-lucide="check-square"></i></div>
                    <div>
                        <strong>📋 Checklists Interativos</strong>
                        <p>Crie listas de afazeres ou compras com checkboxes. Risque tarefas concluídas e organize suas pendências do dia a dia com agilidade.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #4285f4; background: rgba(66, 133, 244, 0.12);"><i data-lucide="calendar"></i></div>
                    <div>
                        <strong>🗓️ Agenda & Google Calendar</strong>
                        <p>Defina data, hora e participantes. Use o ícone de calendário dentro da nota para enviar o compromisso direto para seu Google Agenda com um toque.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #eab308; background: rgba(234, 179, 8, 0.12);"><i data-lucide="pin"></i></div>
                    <div>
                        <strong>👆 Gestos & Cores no Celular</strong>
                        <p>Deslize o card para a <strong>direita</strong> para Fixar/Desafixar e para a <strong>esquerda</strong> para Arquivar/Desarquivar. Escolha cores e reordene arrastando.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #8b5cf6; background: rgba(139, 92, 246, 0.12);"><i data-lucide="wand-2"></i></div>
                    <div>
                        <strong>🪄 IA dentro da Nota (Transformar)</strong>
                        <p>Ao editar qualquer nota, clique no ícone de brilho no rodapé para: <strong>Melhorar Escrita</strong>, <strong>Converter em Checklist</strong>, <strong>Resumir em Tópicos</strong> ou <strong>Traduzir</strong> para Inglês e Espanhol.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #06b6d4; background: rgba(6, 182, 212, 0.12);"><i data-lucide="diamond"></i></div>
                    <div>
                        <strong>💎 Créditos & Recarga PIX</strong>
                        <p>Cada geração inteligente consome 1 crédito. Acompanhe seu saldo no topo e recarregue créditos via PIX com aprovação automática e instantânea a qualquer momento.</p>
                    </div>
                </div>
                <div class="tutorial-item">
                    <div class="tut-icon" style="color: #ef4444; background: rgba(239, 68, 68, 0.12);"><i data-lucide="undo-2"></i></div>
                    <div>
                        <strong>🗑️ Recuperação & Desfazer (Undo)</strong>
                        <p>Excluiu por engano? Clique em <strong>"Desfazer"</strong> no aviso de 5 segundos, ou acesse a <strong>Lixeira</strong> no menu lateral para restaurar ou esvaziar notas permanentemente.</p>
                    </div>
                </div>
            </div>

            <div class="modal-actions" style="margin-top: 18px;">
                <button class="btn-primary" id="btn-close-tutorial-alt" onclick="closeModal('tutorial-modal')" style="width: 100%; justify-content: center; padding: 10px;" data-i18n="modals.tutorial.btn_start">Entendi, vamos começar!</button>
            </div>
        </div>
    </div>

    <!-- Modal Privacidade -->
    <div class="modal-overlay" id="modal-privacy">
        <div class="modal legal-modal">
            <div class="modal-header">
                <i data-lucide="shield-check"></i>
                <h3 data-i18n="auth.privacy">Políticas de Privacidade</h3>
                <button class="modal-close" onclick="closeModal('modal-privacy')" title="Fechar"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-body" data-i18n-html="modals.privacy.content">
                <p><strong>Última atualização: Março de 2026</strong></p>
                <p>Esta política descreve como o Keep AI coleta e usa seus dados.</p>
                <h4>1. Dados Coletados</h4>
                <p>Coletamos seu nome e e-mail via Google Auth para identificar suas notas e permitir a sincronização segura entre dispositivos.</p>
                <h4>2. Google Agenda</h4>
                <p>Se habilitado, acessamos sua agenda apenas para criar compromissos baseados em suas notas. Não lemos nem editamos outros eventos existentes.</p>
                <h4>3. Proteção de Dados (LGPD)</h4>
                <p>Seus dados são armazenados de forma segura e nunca compartilhados com terceiros para fins publicitários. Você pode solicitar a exclusão de sua conta a qualquer momento.</p>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="closeModal('modal-privacy')" data-i18n="modals.install_guide.understand">Entendi!</button>
            </div>
        </div>
    </div>

    <!-- Modal Termos -->
    <div class="modal-overlay" id="modal-terms">
        <div class="modal legal-modal">
            <div class="modal-header">
                <i data-lucide="file-text"></i>
                <h3 data-i18n="auth.terms">Termos de Uso</h3>
                <button class="modal-close" onclick="closeModal('modal-terms')" title="Fechar"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-body" data-i18n-html="modals.terms.content">
                <p>Ao usar o Keep AI, você concorda com estes termos:</p>
                <h4>1. Responsabilidade</h4>
                <p>O Keep AI é uma ferramenta de produtividade. O conteúdo das notas é de responsabilidade exclusiva do usuário.</p>
                <h4>2. Créditos e IA</h4>
                <p>O uso dos recursos de Inteligência Artificial consome créditos. Créditos podem ser adquiridos via pacotes PIX disponíveis no app.</p>
                <h4>3. Disponibilidade</h4>
                <p>Fazemos o melhor para manter o serviço online 24/7, mas não nos responsabilizamos por perdas de dados em caso de falhas catastróficas. Recomendamos backups periódicos de suas notas importantes.</p>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" onclick="closeModal('modal-terms')" data-i18n="modals.edit.save_btn">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Modal Lembrete de Compromisso (Alarme Ativo) -->
    <div class="modal-overlay" id="modal-reminder">
        <div class="modal" style="max-width: 420px; text-align: center; padding: 26px; border-radius: 20px;">
            <div style="font-size: 3.5rem; margin-bottom: 12px; filter: drop-shadow(0 4px 12px rgba(255, 187, 0, 0.4));">⏰</div>
            <h3 id="reminder-modal-title" style="margin-bottom: 8px; font-size: 1.35rem; font-weight: 700;" data-i18n="modals.reminder.title">Compromisso Agora!</h3>
            <p id="reminder-modal-desc" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 22px; line-height: 1.5; white-space: pre-line;"></p>
            <div class="modal-actions" style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <button class="btn-primary" id="btn-reminder-google-cal" style="background: #4285F4; border: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 600;">
                    <i data-lucide="calendar"></i> <span data-i18n="modals.reminder.open_cal">Abrir no Google Agenda</span>
                </button>
                <button class="btn-secondary" onclick="closeModal('modal-reminder')" data-i18n="modals.reminder.ok">OK, Entendi</button>
            </div>
        </div>
    </div>

    <!-- Modal Perguntar se quer sincronizar com Google Agenda após salvar -->
    <div class="modal-overlay" id="modal-calendar-sync">
        <div class="modal" style="max-width: 440px; text-align: center; padding: 26px; border-radius: 20px;">
            <div style="font-size: 3rem; margin-bottom: 10px; filter: drop-shadow(0 4px 12px rgba(66, 133, 244, 0.4));">📅</div>
            <h3 style="margin-bottom: 8px; font-size: 1.25rem; font-weight: 700;" data-i18n="modals.cal_sync.title">Lembrete no Celular & Relógio</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 22px; line-height: 1.5;" data-i18n-html="modals.cal_sync.desc">
                Deseja sincronizar este compromisso com a sua <strong>Google Agenda</strong>?<br>
                Assim você recebe alertas sonoros nativos no celular mesmo com o Keep AI e o navegador <strong>fechados</strong>.
            </p>
            <div class="modal-actions" style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <button class="btn-primary" id="btn-sync-now-google" style="background: #4285F4; border: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 600;">
                    <i data-lucide="calendar-plus"></i> <span data-i18n="modals.cal_sync.sync_btn">Sincronizar com Google Agenda</span>
                </button>
                <button class="btn-secondary" onclick="closeModal('modal-calendar-sync')" data-i18n="modals.cal_sync.not_now">Agora Não</button>
            </div>
        </div>
    </div>

    <!-- Modal Google Drive Backup -->
    <div class="modal-overlay" id="modal-drive-backup">
        <div class="modal" style="max-width: 480px; padding: 26px; border-radius: 20px;">
            <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="background: rgba(66, 133, 244, 0.15); color: #4285F4; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="cloud"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem;" data-i18n="modals.drive_backup.title">Google Drive Backup</h3>
                </div>
                <button class="modal-close" onclick="closeModal('modal-drive-backup')" title="Fechar"><i data-lucide="x"></i></button>
            </div>
            
            <div class="modal-body" style="text-align: left;">
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 16px;" data-i18n-html="modals.drive_backup.desc">
                    Salve uma cópia segura e atualizada de todas as suas notas, listas, compromissos e fotos diretamente em uma pasta <strong>Keep AI</strong> no seu Google Drive.
                </p>

                <div style="background: var(--surface-hover, rgba(66, 133, 244, 0.06)); border: 1px solid var(--border, rgba(0,0,0,0.1)); border-radius: 14px; padding: 14px 18px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px;">
                        <span data-i18n="modals.drive_backup.total_notes">Total de notas a salvar:</span>
                        <strong id="drive-backup-count" style="color: var(--text); font-size: 1.1rem; font-weight: 700;">0</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; color: var(--text-muted);">
                        <span data-i18n="modals.drive_backup.target_folder">Pasta de destino:</span>
                        <strong style="color: #1a73e8; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                            <i data-lucide="folder" style="width:15px;height:15px;"></i> Keep AI
                        </strong>
                    </div>
                </div>

                <!-- Progress / Status Area -->
                <div id="drive-backup-status-area" class="hidden" style="margin-bottom: 18px; padding: 12px; border-radius: 10px; background: rgba(66, 133, 244, 0.1); border: 1px solid rgba(66, 133, 244, 0.2); font-size: 0.85rem; color: var(--text);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span id="drive-backup-spinner" class="spinner" style="width: 16px; height: 16px;"></span>
                        <span id="drive-backup-status-text" data-i18n="modals.drive_backup.starting">Iniciando...</span>
                    </div>
                    <div id="drive-backup-link-container" class="hidden" style="margin-top: 10px;">
                        <a id="drive-backup-link" href="#" target="_blank" style="color: #4285F4; text-decoration: underline; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="external-link" style="width:14px;height:14px;"></i> <span data-i18n="modals.drive_backup.open_folder">Abrir pasta no Google Drive</span>
                        </a>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button class="btn-primary" id="btn-start-drive-backup" style="background: #4285F4; border: none; padding: 12px; font-size: 0.95rem; font-weight: 600; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="upload-cloud"></i> <span data-i18n="modals.drive_backup.save_btn">Salvar no Google Drive</span>
                    </button>
                    <div style="display: flex; gap: 8px;">
                        <button class="btn-secondary" id="btn-download-local-backup" style="flex: 1; padding: 10px; font-size: 0.82rem; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <i data-lucide="download"></i> <span data-i18n="modals.drive_backup.download_json">Baixar JSON Local</span>
                        </button>
                        <button class="btn-secondary" id="btn-import-local-backup" style="flex: 1; padding: 10px; font-size: 0.82rem; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <i data-lucide="upload"></i> <span data-i18n="modals.drive_backup.restore_json">Restaurar JSON</span>
                        </button>
                    </div>
                    <input type="file" id="input-restore-backup" accept=".json" style="display: none;">
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=BAAsoqPW8MlsLqTNKrQMoPEeqyfKafERMBvspk51nt_y9eSMEKqFSOMNfzgMlg7ru7TOYtvj_FOtx5mFf0&currency=USD" async></script>
    <script id="app-js" type="module" src="app-v46.js?v=<?= $vJs ?>"></script>
    <script>
        // Service Worker registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('./sw.js?v=<?= $vJs ?>').catch(err => console.error(err));
            });
            // Auto-reload when new worker takes over
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                window.location.reload();
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const spans = document.querySelectorAll('.curr-year');
            spans.forEach(s => s.textContent = '2026');
            if (window.lucide) lucide.createIcons();
        });

        // Simple bypass for cancel buttons in modals
        document.querySelectorAll('.btn-secondary').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal-overlay');
                if (modal) modal.classList.remove('open');
            });
        });
    </script>
</body>
</html>
