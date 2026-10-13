// ── Keep AI Internationalization (i18n) Module ──
// Supports Portuguese (pt) and English (en)

export const I18N = {
  pt: {
    lang_name: 'Português',
    auth: {
      subtitle: 'Sua memória digital. Organize notas, listas e compromissos com total privacidade.',
      login_google: 'Entrar com Conta Google',
      secure_access: 'Acesso seguro e sincronizado com sua Conta Google',
      terms: 'Termos de Uso',
      privacy: 'Privacidade',
      copyright: '© 2026 4U.IA.BR - SOBERANIA DIGITAL'
    },
    nav: {
      section_notes: 'Notas',
      all_notes: 'Todas as notas',
      pinned: 'Fixadas',
      archived: 'Arquivadas',
      trash: 'Lixeira',
      share_app: 'Compartilhar App',
      install_app: 'Instalar App',
      section_categories: 'Categorias',
      checklist: 'Checklist',
      agenda: 'Agenda',
      section_cloud: 'Nuvem & Backup',
      backup_drive: 'Backup Drive',
      section_legal: 'Legal & Ajuda',
      update_app: 'Atualizar App',
      privacy: 'Privacidade',
      tutorial: 'Tutorial',
      terms: 'Termos',
      donate: 'Apoie o Projeto',
      donate_title: 'Apoie o Projeto Keep AI via PayPal (Doação Voluntária)',
      logout: 'Sair',
      loading_user: 'Carregando...'
    },
    header: {
      search_placeholder: 'Pesquisar notas...',
      credits_label: 'créditos',
      drive_sync_title: 'Status de Sincronização com Google Drive',
      install_btn: 'Instalar App',
      toggle_layout: 'Alternar visualização (Grade / Lista)',
      toggle_to_grid: 'Mudar para visualização em grade (2 colunas)',
      toggle_to_list: 'Mudar para visualização em lista (1 coluna)',
      theme_title: 'Trocar Estilo',
      voice_title: 'Comando de voz'
    },
    notes_header: {
      all_title: 'Todas as notas',
      pinned_title: 'Notas Fixadas',
      archived_title: 'Notas Arquivadas',
      trash_title: 'Lixeira',
      checklist_title: 'Checklists',
      agenda_title: 'Agenda & Compromissos',
      count_notes: '{count} nota{s}',
      ask_ai: 'Memória Digital',
      ask_ai_title: 'Pergunte à sua Memória Digital',
      vision: 'Foto OCR',
      vision_title: 'Escanear Foto / Documento',
      elite_ai: 'IA de Elite',
      new_list: 'Checklist',
      new_note: 'Nota',
      new_appointment: 'Agenda',
      empty_trash: 'Esvaziar Lixeira'
    },
    batch: {
      cancel_title: 'Cancelar seleção (Esc)',
      selected_word: 'selecionada(s)',
      select_all: 'Selecionar Tudo',
      pin: 'Fixar / Desafixar',
      color: 'Mudar cor',
      archive: 'Arquivar',
      restore: 'Restaurar',
      trash: 'Mover para a lixeira',
      delete_permanent: 'Excluir definitivamente'
    },
    empty: {
      trash_title: 'A lixeira está vazia.',
      trash_sub: 'Itens excluídos aparecerão aqui.',
      archived_title: 'Nenhuma nota arquivada.',
      archived_sub: 'Notas arquivadas ficam guardadas aqui.',
      pinned_title: 'Nenhuma nota fixada.',
      pinned_sub: 'Fixe notas importantes clicando no ícone de alfinete para vê-las sempre no topo.',
      checklist_title: 'Nenhum checklist encontrado.',
      checklist_sub: 'Crie listas de tarefas ou compras usando o botão Checklist ou IA.',
      agenda_title: 'Nenhum compromisso agendado.',
      agenda_sub: 'Crie notas de agenda ou compromissos com data e hora.',
      search_title: 'Nenhum resultado para a busca.',
      search_sub: 'Tente buscar por outras palavras ou limpe o campo de pesquisa.',
      default_title: 'Nenhuma nota encontrada.',
      default_sub: 'Use o botão <strong>✨ IA</strong> para criar notas inteligentes.'
    },
    card: {
      note_badge: '📝 Nota',
      checklist_badge: '☑️ Checklist',
      agenda_badge: '📅 Agenda',
      items_remaining: '+ {count} itens restantes',
      item_remaining: '+ 1 item restante',
      more_text: 'mais...',
      pin_title: 'Fixar',
      unpin_title: 'Desafixar',
      color_title: 'Mudar cor',
      edit_title: 'Editar',
      cal_title: 'Adicionar ao Google Calendar',
      archive_title: 'Arquivar',
      unarchive_title: 'Restaurar',
      share_title: 'Compartilhar',
      delete_title: 'Excluir',
      restore_title: 'Restaurar Nota',
      delete_perm_title: 'Excluir Definitivamente',
      at_time: 'às'
    },
    modals: {
      edit: {
        title_edit: 'Editar Nota',
        title_new: 'Criar Nota',
        title_new_note: 'Nova Nota',
        title_new_list: 'Novo Checklist',
        title_new_agenda: 'Nova Agenda',
        tab_generic: 'Nota',
        tab_checklist: 'Checklist',
        tab_agenda: 'Agenda',
        title_placeholder: 'Título',
        content_placeholder: 'Escreva algo...',
        add_item: 'Adicionar item',
        item_placeholder: 'Item da lista...',
        ai_actions: 'Ações de IA na Nota',
        ai_improve: 'Melhorar Escrita',
        ai_to_checklist: 'Converter em Checklist',
        ai_summarize: 'Resumir em Tópicos',
        ai_translate_en: 'Traduzir para Inglês',
        ai_translate_es: 'Traduzir para Espanhol',
        color_title: 'Mudar cor',
        toggle_list_title: 'Alternar Lista/Checklist',
        toggle_event_title: 'Definir Data / Compromisso',
        add_image_title: 'Adicionar imagem',
        add_cal_title: 'Adicionar ao Google Agenda',
        share_title: 'Compartilhar',
        delete_title: 'Excluir',
        who_placeholder: 'Quem?',
        save_btn: 'Fechar'
      },
      ai: {
        title: '✨ O que você deseja organizar?',
        mode_text: 'Texto',
        mode_audio: 'Áudio Whisper',
        mode_vision: 'Foto OCR',
        placeholder: 'Ex: Preciso comprar leite e ovos. Marcar reunião amanhã às 15h com o João sobre o projeto Keep.',
        speak: 'Falar',
        listening: 'Ouvindo... fale agora',
        cost_hint: '💡 A IA irá separar automaticamente o que é lista, evento ou compromisso. (Custo: 1 crédito)',
        cancel: 'Cancelar',
        submit: '✨ Criar Notas'
      },
      ask_ai: {
        title: 'Memória Digital',
        subtitle: 'Pergunte sobre qualquer anotação ou compromisso',
        welcome: 'Olá! Sou sua <strong>Memória Digital</strong>. Posso consultar todas as suas anotações, listas e compromissos salvos para responder às suas dúvidas.<br><br><em>Exemplos: "O que tenho agendado para esta semana?", "Quais compras faltam?", "Resuma minhas anotações recentes."</em>',
        sugg_events: '📅 Próximos compromissos',
        sugg_shopping: '🛒 Itens pendentes',
        sugg_summary: '📝 Resumo geral',
        placeholder: 'Pergunte às suas notas...',
        send_title: 'Enviar pergunta',
        cost_hint: '💡 Cada consulta analisa suas notas e consome 1 crédito de IA.'
      },
      vision: {
        title: '📷 Escanear com IA (Visão Computacional)',
        drop_title: 'Tirar foto ou carregar imagem',
        drop_sub: 'Recibos, notas fiscais, lousas, listas de papel ou anotações manuscritas',
        take_photo: 'Tirar Foto',
        upload_image: 'Carregar Imagem',
        goal_label: 'Objetivo do Scanner:',
        goal_auto: '✨ Detectar e Organizar Automaticamente',
        goal_shopping: '🛒 Converter em Lista de Compras / Checklist',
        goal_event: '📅 Extrair Data, Hora e Local para Evento',
        goal_text: '📝 Apenas Transcrever Todo o Texto (OCR puro)',
        cancel: 'Cancelar',
        process: '✨ Processar Imagem (1 crédito)'
      },
      audio: {
        title: '🎙️ Gravador Inteligente (Whisper)',
        status_idle: 'Clique para iniciar a gravação. A IA transcreverá suas ideias e organizará suas notas.',
        cancel: 'Cancelar',
        start_rec: 'Iniciar Gravação',
        stop_rec: 'Parar e Processar'
      },
      drive_backup: {
        title: 'Google Drive Backup',
        desc: 'Salve uma cópia segura e atualizada de todas as suas notas, listas, compromissos e fotos diretamente em uma pasta <strong>Keep AI</strong> no seu Google Drive.',
        total_notes: 'Total de notas a salvar:',
        target_folder: 'Pasta de destino:',
        starting: 'Iniciando...',
        open_folder: 'Abrir pasta no Google Drive',
        save_btn: 'Salvar no Google Drive',
        download_json: 'Baixar JSON Local',
        restore_json: 'Restaurar JSON'
      },
      install_guide: {
        title: 'Instalar Keep AI',
        desc: 'Instale o <strong>Keep AI</strong> no seu dispositivo para acesso instantâneo, modo tela cheia e funcionamento offline!',
        ios_title: '🍎 No iPhone ou iPad (Safari):',
        ios_1: 'Toque no botão de <strong>Compartilhar</strong> na barra inferior do Safari.',
        ios_2: 'Role para baixo e toque em <strong>"Adicionar à Tela de Início"</strong>.',
        ios_3: 'Toque em <strong>"Adicionar"</strong> no canto superior direito.',
        android_title: '🤖 No Android (Chrome):',
        android_1: 'Toque no menu de <strong>3 pontinhos</strong> no topo do navegador.',
        android_2: 'Selecione <strong>"Instalar aplicativo"</strong> ou <strong>"Adicionar à tela inicial"</strong>.',
        android_3: 'Confirme tocando em <strong>"Instalar"</strong>.',
        desktop_title: '💻 No Computador (Chrome / Edge / Brave):',
        desktop_1: 'Clique no ícone de instalação na barra de endereços (lado direito).',
        desktop_2: 'Ou clique nos <strong>3 pontinhos</strong> do navegador > <strong>"Instalar Keep AI..."</strong>.',
        understand: 'Entendi'
      },
      tutorial: {
        title: '📖 Guia Completo do Keep AI',
        subtitle: 'Aprenda a dominar todos os recursos e aumentar sua produtividade',
        btn_start: 'Entendi, vamos começar!',
        items: `
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
                  <strong>💎 Créditos & Recarga PIX / PayPal</strong>
                  <p>Cada geração inteligente consome 1 crédito. Acompanhe seu saldo no topo e recarregue créditos via PIX ou PayPal com aprovação instantânea a qualquer momento.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #ef4444; background: rgba(239, 68, 68, 0.12);"><i data-lucide="undo-2"></i></div>
              <div>
                  <strong>🗑️ Recuperação & Desfazer (Undo)</strong>
                  <p>Excluiu por engano? Clique em <strong>"Desfazer"</strong> no aviso de 5 segundos, ou acesse a <strong>Lixeira</strong> no menu lateral para restaurar ou esvaziar notas permanentemente.</p>
              </div>
          </div>
        `
      },
      privacy: {
        title: 'Políticas de Privacidade',
        content: `
          <p><strong>Última atualização: Março de 2026</strong></p>
          <p>Esta política descreve como o Keep AI coleta e usa seus dados.</p>
          <h4>1. Dados Coletados</h4>
          <p>Coletamos seu nome e e-mail via Google Auth para identificar suas notas e permitir a sincronização segura entre dispositivos.</p>
          <h4>2. Google Agenda</h4>
          <p>Se habilitado, acessamos sua agenda apenas para criar compromissos baseados em suas notas. Não lemos nem editamos outros eventos existentes.</p>
          <h4>3. Proteção de Dados (LGPD)</h4>
          <p>Seus dados são armazenados de forma segura e nunca compartilhados com terceiros para fins publicitários. Você pode solicitar a exclusão de sua conta a qualquer momento.</p>
        `
      },
      terms: {
        title: 'Termos de Uso',
        content: `
          <p>Ao usar o Keep AI, você concorda com estes termos:</p>
          <h4>1. Responsabilidade</h4>
          <p>O Keep AI é uma ferramenta de produtividade. O conteúdo das notas é de responsabilidade exclusiva do usuário.</p>
          <h4>2. Créditos e IA</h4>
          <p>O uso dos recursos de Inteligência Artificial consome créditos. Créditos podem ser adquiridos via pacotes PIX ou PayPal disponíveis no app.</p>
          <h4>3. Disponibilidade</h4>
          <p>Fazemos o melhor para manter o serviço online 24/7, mas não nos responsabilizamos por perdas de dados em caso de falhas catastróficas. Recomendamos backups periódicos de suas notas importantes.</p>
        `
      },
      reminder: {
        title: 'Compromisso Agora!',
        open_cal: 'Abrir no Google Agenda',
        ok: 'OK, Entendi'
      },
      cal_sync: {
        title: 'Lembrete no Celular & Relógio',
        desc: 'Deseja sincronizar este compromisso com a sua <strong>Google Agenda</strong>?<br>Assim você recebe alertas sonoros nativos no celular mesmo com o Keep AI e o navegador <strong>fechados</strong>.',
        sync_btn: 'Sincronizar com Google Agenda',
        not_now: 'Agora Não'
      },
      recharge: {
        title: '💎 Recarregar Créditos',
        subtitle: 'Seus créditos são integrados via <strong>4uLabs</strong>! Disponíveis para uso compartilhado no Keep AI, TubeMind AI e novos lançamentos.',
        tab_pix: '🇧🇷 PIX (R$ BRL)',
        tab_paypal: '🌐 PayPal (US$ USD)',
        credits_label: 'créditos',
        btn_generate_pix: 'Gerar QR Code PIX ({price})',
        btn_generate_pix_loading: 'Gerando PIX...',
        qr_scan_hint: 'Escaneie o QR Code ou copie o código abaixo:',
        copy_code: 'Clique para copiar o código PIX',
        code_copied: 'Código PIX copiado!',
        waiting_payment: 'Aguardando pagamento...',
        pix_success: '🎉 PIX confirmado! +{credits} créditos adicionados.',
        paypal_loading: 'Carregando PayPal e opções de cartão...',
        paypal_confirming: 'Confirmando pagamento no PayPal...',
        paypal_success: '🎉 Pagamento aprovado! +{credits} créditos adicionados.',
        paypal_error: 'Pagamento via PayPal cancelado ou com erro.',
        free_notice: '💎 <strong>100% Gratuito:</strong> Criar notas, checklists, compromissos e backup no Google Drive são 100% gratuitos e ilimitados. Os créditos são necessários apenas para o processamento de Inteligência Artificial.',
        donate_prompt: 'Deseja apenas apoiar o desenvolvedor com uma contribuição voluntária?',
        donate_link: '☕ Fazer Doação Livre via PayPal'
      },
      undo: {
        trash_msg: 'Nota movida para a lixeira.',
        undo_btn: 'Desfazer'
      },
      share: {
        title: 'Compartilhar',
        whatsapp: 'WhatsApp',
        telegram: 'Telegram',
        email: 'E-mail',
        copy: 'Copiar',
        copied: 'Copiado para a área de transferência!'
      }
    },
    install_banner: {
      title: 'Instalar Keep AI',
      desc: 'Acesse mais rápido direto da sua tela inicial.',
      later: 'Depois',
      install: 'Instalar'
    },
    toasts: {
      saved: 'Nota salva!',
      deleted: 'Nota excluída!',
      restored: 'Nota restaurada!',
      archived: 'Nota arquivada!',
      unarchived: 'Nota desarquivada!',
      pinned: 'Nota fixada!',
      unpinned: 'Nota desafixada!',
      copied: 'Copiado para a área de transferência!',
      sync_success: 'Sincronizado com o Google Drive!',
      trash_emptied: 'Lixeira esvaziada!',
      color_updated: 'Cor atualizada!',
      layout_grid: 'Visualização em grade (2 colunas)',
      layout_list: 'Visualização em lista (1 coluna)'
    }
  },

  en: {
    lang_name: 'English',
    auth: {
      subtitle: 'Your digital memory. Organize notes, lists, and appointments with complete privacy.',
      login_google: 'Sign in with Google',
      secure_access: 'Secure access synced with your Google Account',
      terms: 'Terms of Use',
      privacy: 'Privacy Policy',
      copyright: '© 2026 4U.IA.BR - DIGITAL SOVEREIGNTY'
    },
    nav: {
      section_notes: 'Notes',
      all_notes: 'All notes',
      pinned: 'Pinned',
      archived: 'Archived',
      trash: 'Trash',
      share_app: 'Share App',
      install_app: 'Install App',
      section_categories: 'Categories',
      checklist: 'Checklist',
      agenda: 'Calendar',
      section_cloud: 'Cloud & Backup',
      backup_drive: 'Drive Backup',
      section_legal: 'Legal & Help',
      update_app: 'Update App',
      privacy: 'Privacy',
      tutorial: 'Tutorial',
      terms: 'Terms',
      donate: 'Support Project',
      donate_title: 'Support Keep AI via PayPal (Voluntary Donation)',
      logout: 'Sign out',
      loading_user: 'Loading...'
    },
    header: {
      search_placeholder: 'Search notes...',
      credits_label: 'credits',
      drive_sync_title: 'Google Drive Sync Status',
      install_btn: 'Install App',
      toggle_layout: 'Toggle view (Grid / List)',
      toggle_to_grid: 'Switch to grid view (2 columns)',
      toggle_to_list: 'Switch to list view (1 column)',
      theme_title: 'Change Theme',
      voice_title: 'Voice command'
    },
    notes_header: {
      all_title: 'All notes',
      pinned_title: 'Pinned Notes',
      archived_title: 'Archived Notes',
      trash_title: 'Trash',
      checklist_title: 'Checklists',
      agenda_title: 'Calendar & Appointments',
      count_notes: '{count} note{s}',
      ask_ai: 'Digital Memory',
      ask_ai_title: 'Ask your Digital Memory',
      vision: 'Photo OCR',
      vision_title: 'Scan Photo / Document',
      elite_ai: 'Elite AI',
      new_list: 'Checklist',
      new_note: 'Note',
      new_appointment: 'Calendar',
      empty_trash: 'Empty Trash'
    },
    batch: {
      cancel_title: 'Cancel selection (Esc)',
      selected_word: 'selected',
      select_all: 'Select All',
      pin: 'Pin / Unpin',
      color: 'Change color',
      archive: 'Archive',
      restore: 'Restore',
      trash: 'Move to trash',
      delete_permanent: 'Delete permanently'
    },
    empty: {
      trash_title: 'Trash is empty.',
      trash_sub: 'Deleted items will appear here.',
      archived_title: 'No archived notes.',
      archived_sub: 'Archived notes are kept here.',
      pinned_title: 'No pinned notes.',
      pinned_sub: 'Pin important notes by clicking the pin icon to keep them at the top.',
      checklist_title: 'No checklists found.',
      checklist_sub: 'Create task or shopping lists using the Checklist button or AI.',
      agenda_title: 'No scheduled appointments.',
      agenda_sub: 'Create calendar notes or appointments with date and time.',
      search_title: 'No results found.',
      search_sub: 'Try searching for other words or clear the search field.',
      default_title: 'No notes found.',
      default_sub: 'Use the <strong>✨ AI</strong> button to create smart notes.'
    },
    card: {
      note_badge: '📝 Note',
      checklist_badge: '☑️ Checklist',
      agenda_badge: '📅 Calendar',
      items_remaining: '+ {count} items remaining',
      item_remaining: '+ 1 item remaining',
      more_text: 'more...',
      pin_title: 'Pin',
      unpin_title: 'Unpin',
      color_title: 'Change color',
      edit_title: 'Edit',
      cal_title: 'Add to Google Calendar',
      archive_title: 'Archive',
      unarchive_title: 'Restore',
      share_title: 'Share',
      delete_title: 'Delete',
      restore_title: 'Restore Note',
      delete_perm_title: 'Delete Permanently',
      at_time: 'at'
    },
    modals: {
      edit: {
        title_edit: 'Edit Note',
        title_new: 'Create Note',
        title_new_note: 'New Note',
        title_new_list: 'New Checklist',
        title_new_agenda: 'New Calendar',
        tab_generic: 'Note',
        tab_checklist: 'Checklist',
        tab_agenda: 'Calendar',
        title_placeholder: 'Title',
        content_placeholder: 'Write something...',
        add_item: 'Add item',
        item_placeholder: 'List item...',
        ai_actions: 'AI Note Actions',
        ai_improve: 'Improve Writing',
        ai_to_checklist: 'Convert to Checklist',
        ai_summarize: 'Summarize in Bullet Points',
        ai_translate_en: 'Translate to English',
        ai_translate_es: 'Translate to Spanish',
        color_title: 'Change color',
        toggle_list_title: 'Toggle List/Checklist',
        toggle_event_title: 'Set Date / Appointment',
        add_image_title: 'Add image',
        add_cal_title: 'Add to Google Calendar',
        share_title: 'Share',
        delete_title: 'Delete',
        who_placeholder: 'Who?',
        save_btn: 'Close'
      },
      ai: {
        title: '✨ What would you like to organize?',
        mode_text: 'Text',
        mode_audio: 'Whisper Audio',
        mode_vision: 'Photo OCR',
        placeholder: 'E.g.: Need to buy milk and eggs. Schedule meeting tomorrow at 3pm with John about the Keep project.',
        speak: 'Speak',
        listening: 'Listening... speak now',
        cost_hint: '💡 AI will automatically categorize lists, events, or appointments. (Cost: 1 credit)',
        cancel: 'Cancel',
        submit: '✨ Create Notes'
      },
      ask_ai: {
        title: 'Digital Memory',
        subtitle: 'Ask about any note or appointment',
        welcome: 'Hello! I am your <strong>Digital Memory</strong>. I can search all your saved notes, lists, and appointments to answer your questions.<br><br><em>Examples: "What do I have scheduled for this week?", "What groceries are missing?", "Summarize my recent notes."</em>',
        sugg_events: '📅 Upcoming events',
        sugg_shopping: '🛒 Pending items',
        sugg_summary: '📝 General summary',
        placeholder: 'Ask your notes...',
        send_title: 'Send question',
        cost_hint: '💡 Each query analyzes your notes and uses 1 AI credit.'
      },
      vision: {
        title: '📷 Scan with AI (Computer Vision)',
        drop_title: 'Take photo or upload image',
        drop_sub: 'Receipts, invoices, whiteboards, paper lists, or handwritten notes',
        take_photo: 'Take Photo',
        upload_image: 'Upload Image',
        goal_label: 'Scanner Goal:',
        goal_auto: '✨ Automatically Detect and Organize',
        goal_shopping: '🛒 Convert to Shopping List / Checklist',
        goal_event: '📅 Extract Date, Time, and Location for Event',
        goal_text: '📝 Transcribe All Text Only (Pure OCR)',
        cancel: 'Cancel',
        process: '✨ Process Image (1 credit)'
      },
      audio: {
        title: '🎙️ Smart Voice Recorder (Whisper)',
        status_idle: 'Click to start recording. AI will transcribe your ideas and organize your notes.',
        cancel: 'Cancel',
        start_rec: 'Start Recording',
        stop_rec: 'Stop & Process'
      },
      drive_backup: {
        title: 'Google Drive Backup',
        desc: 'Save a secure, up-to-date copy of all your notes, lists, appointments, and photos directly in a <strong>Keep AI</strong> folder in your Google Drive.',
        total_notes: 'Total notes to save:',
        target_folder: 'Destination folder:',
        starting: 'Iniciando...',
        open_folder: 'Open folder in Google Drive',
        save_btn: 'Save to Google Drive',
        download_json: 'Download Local JSON',
        restore_json: 'Restore JSON'
      },
      install_guide: {
        title: 'Install Keep AI',
        desc: 'Install <strong>Keep AI</strong> on your device for instant access, full-screen mode, and offline use!',
        ios_title: '🍎 On iPhone or iPad (Safari):',
        ios_1: 'Tap the <strong>Share</strong> button on Safari\'s bottom bar.',
        ios_2: 'Scroll down and tap <strong>"Add to Home Screen"</strong>.',
        ios_3: 'Tap <strong>"Add"</strong> in the top right corner.',
        android_title: '🤖 On Android (Chrome):',
        android_1: 'Tap the <strong>3 dots</strong> menu at the top of the browser.',
        android_2: 'Select <strong>"Install app"</strong> or <strong>"Add to Home screen"</strong>.',
        android_3: 'Confirm by tapping <strong>"Install"</strong>.',
        desktop_title: '💻 On Computer (Chrome / Edge / Brave):',
        desktop_1: 'Click the install icon in the address bar (right side).',
        desktop_2: 'Or click the browser\'s <strong>3 dots</strong> > <strong>"Install Keep AI..."</strong>.',
        understand: 'Got it'
      },
      tutorial: {
        title: '📖 Keep AI Complete Guide',
        subtitle: 'Master all features and boost your productivity',
        btn_start: 'Got it, let\'s start!',
        items: `
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #f59e0b; background: rgba(245, 158, 11, 0.12);"><i data-lucide="sparkles"></i></div>
              <div>
                  <strong>✨ Elite AI (Text & Whisper Audio)</strong>
                  <p>Speak into the microphone or type what you need to organize. AI detects context and automatically creates simple notes, checklists, or appointments with date and time.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #34d399; background: rgba(52, 211, 153, 0.12);"><i data-lucide="camera"></i></div>
              <div>
                  <strong>📷 Photo OCR (Smart Scanner)</strong>
                  <p>Take pictures of documents, whiteboards, receipts, or handwritten notes. The system extracts text with high-precision OCR and prepares your note instantly.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #818cf8; background: rgba(129, 140, 248, 0.12);"><i data-lucide="message-square"></i></div>
              <div>
                  <strong>💬 Digital Memory (Second Brain)</strong>
                  <p>Chat with AI about anything you've noted down. Ask "How much was the repair?" or "What did I plan for tomorrow?" with direct citations and links to notes.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #38bdf8; background: rgba(56, 189, 248, 0.12);"><i data-lucide="cloud"></i></div>
              <div>
                  <strong>☁️ Zero Server & Google Drive</strong>
                  <p>Your notes are stored in your browser (IndexedDB) in 0ms and synced in the background to your private Google Drive folder (<code>Keep AI/keepai_notes.json</code>).</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #a855f7; background: rgba(168, 85, 247, 0.12);"><i data-lucide="download"></i></div>
              <div>
                  <strong>📲 Installable PWA & 100% Offline</strong>
                  <p>Install Keep AI on your phone or computer via the top button. Use the app normally without internet; data syncs automatically upon reconnecting.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #ec4899; background: rgba(236, 72, 153, 0.12);"><i data-lucide="layout-grid"></i></div>
              <div>
                  <strong>🗂️ Grid (2 Columns) vs List View</strong>
                  <p>Switch between 2 vertical columns (classic mobile Google Keep style) and full-width list view anytime with the header button.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #10b981; background: rgba(16, 185, 129, 0.12);"><i data-lucide="check-square"></i></div>
              <div>
                  <strong>📋 Interactive Checklists</strong>
                  <p>Create to-do or grocery lists with checkboxes. Check off completed items and manage daily tasks swiftly.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #4285f4; background: rgba(66, 133, 244, 0.12);"><i data-lucide="calendar"></i></div>
              <div>
                  <strong>🗓️ Calendar & Google Calendar</strong>
                  <p>Set date, time, and participants. Use the calendar icon inside notes to export appointments directly to your Google Calendar in one tap.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #eab308; background: rgba(234, 179, 8, 0.12);"><i data-lucide="pin"></i></div>
              <div>
                  <strong>👆 Mobile Gestures & Colors</strong>
                  <p>Swipe a card to the <strong>right</strong> to Pin/Unpin, and to the <strong>left</strong> to Archive/Unarchive. Choose custom colors and drag to reorder.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #8b5cf6; background: rgba(139, 92, 246, 0.12);"><i data-lucide="wand-2"></i></div>
              <div>
                  <strong>🪄 In-Note AI (Transform)</strong>
                  <p>When editing any note, click the sparkles icon in the toolbar to: <strong>Improve Writing</strong>, <strong>Convert to Checklist</strong>, <strong>Summarize in Bullet Points</strong>, or <strong>Translate</strong> to English and Spanish.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #06b6d4; background: rgba(6, 182, 212, 0.12);"><i data-lucide="diamond"></i></div>
              <div>
                  <strong>💎 Credits & Recharge (PIX / PayPal)</strong>
                  <p>Each AI generation uses 1 credit. Track your balance at the top and recharge credits via PIX or PayPal with instant delivery at any time.</p>
              </div>
          </div>
          <div class="tutorial-item">
              <div class="tut-icon" style="color: #ef4444; background: rgba(239, 68, 68, 0.12);"><i data-lucide="undo-2"></i></div>
              <div>
                  <strong>🗑️ Recovery & Undo</strong>
                  <p>Deleted by mistake? Click <strong>"Undo"</strong> on the 5-second popup, or access the <strong>Trash</strong> in the sidebar to restore or permanently empty notes.</p>
              </div>
          </div>
        `
      },
      privacy: {
        title: 'Privacy Policy',
        content: `
          <p><strong>Last updated: March 2026</strong></p>
          <p>This policy describes how Keep AI collects and handles your data.</p>
          <h4>1. Data Collected</h4>
          <p>We collect your name and email via Google Auth to identify your account and enable secure syncing across devices.</p>
          <h4>2. Google Calendar</h4>
          <p>If enabled, we only access your calendar to create appointments based on your notes. We do not read or modify other existing events.</p>
          <h4>3. Data Protection (GDPR / Privacy)</h4>
          <p>Your data is stored securely and never shared with third parties for advertising purposes. You can request the deletion of your account at any time.</p>
        `
      },
      terms: {
        title: 'Terms of Use',
        content: `
          <p>By using Keep AI, you agree to these terms:</p>
          <h4>1. Responsibility</h4>
          <p>Keep AI is a productivity tool. The content of your notes is the sole responsibility of the user.</p>
          <h4>2. Credits and AI</h4>
          <p>The use of Artificial Intelligence features consumes credits. Credits can be purchased via PIX or PayPal packages available in the app.</p>
          <h4>3. Availability</h4>
          <p>We do our best to maintain the service online 24/7, but we are not liable for data loss in catastrophic events. We recommend periodic backups of your important notes.</p>
        `
      },
      reminder: {
        title: 'Appointment Now!',
        open_cal: 'Open in Google Calendar',
        ok: 'OK, Got it'
      },
      cal_sync: {
        title: 'Reminder on Phone & Watch',
        desc: 'Would you like to sync this appointment with your <strong>Google Calendar</strong>?<br>This way you receive native audio alerts on your phone even when Keep AI and browser are <strong>closed</strong>.',
        sync_btn: 'Sync with Google Calendar',
        not_now: 'Not Now'
      },
      recharge: {
        title: '💎 Buy AI Credits',
        subtitle: 'Your credits are integrated via <strong>4uLabs</strong>! Available for shared use across Keep AI, TubeMind AI, and upcoming apps.',
        tab_pix: '🇧🇷 PIX (R$ BRL)',
        tab_paypal: '🌐 PayPal (US$ USD)',
        credits_label: 'credits',
        btn_generate_pix: 'Generate PIX QR Code ({price})',
        btn_generate_pix_loading: 'Generating PIX...',
        qr_scan_hint: 'Scan the QR Code or copy the code below:',
        copy_code: 'Click to copy PIX code',
        code_copied: 'PIX code copied!',
        waiting_payment: 'Waiting for payment...',
        pix_success: '🎉 PIX confirmed! +{credits} credits added.',
        paypal_loading: 'Loading PayPal & Card options...',
        paypal_confirming: 'Confirming PayPal payment...',
        paypal_success: '🎉 Payment approved! +{credits} credits added.',
        paypal_error: 'PayPal payment was cancelled or encountered an error.',
        free_notice: '💎 <strong>100% Free:</strong> Notes, checklists, calendar events, and Google Drive backup are 100% free and unlimited. Credits are strictly for AI processing.',
        donate_prompt: 'Want to make a voluntary contribution to support the developer?',
        donate_link: '☕ Make a Voluntary Donation via PayPal'
      },
      undo: {
        trash_msg: 'Note moved to trash.',
        undo_btn: 'Undo'
      },
      share: {
        title: 'Share',
        whatsapp: 'WhatsApp',
        telegram: 'Telegram',
        email: 'Email',
        copy: 'Copy',
        copied: 'Copied to clipboard!'
      }
    },
    install_banner: {
      title: 'Install Keep AI',
      desc: 'Quick access right from your home screen.',
      later: 'Later',
      install: 'Install'
    },
    toasts: {
      saved: 'Note saved!',
      deleted: 'Note deleted!',
      restored: 'Note restored!',
      archived: 'Note archived!',
      unarchived: 'Note unarchived!',
      pinned: 'Note pinned!',
      unpinned: 'Note unpinned!',
      copied: 'Copied to clipboard!',
      sync_success: 'Synced with Google Drive!',
      trash_emptied: 'Trash emptied!',
      color_updated: 'Color updated!',
      layout_grid: 'Grid view (2 columns)',
      layout_list: 'List view (1 column)'
    }
  }
};

// ── Language Detection & Helper Functions ──

export function detectBrowserLanguage() {
  const saved = localStorage.getItem('keepai_lang');
  if (saved === 'pt' || saved === 'en') return saved;
  const navLang = (navigator.language || navigator.userLanguage || 'en').toLowerCase();
  if (navLang.startsWith('pt')) return 'pt';
  return 'en';
}

let currentLang = detectBrowserLanguage();

export function getLang() {
  return currentLang;
}

export function setLang(lang) {
  if (lang !== 'pt' && lang !== 'en') return;
  currentLang = lang;
  localStorage.setItem('keepai_lang', lang);
  document.documentElement.lang = lang === 'pt' ? 'pt-BR' : 'en';
  applyTranslations();
  updateLangButtonsUI();
}

export function t(path, params = {}) {
  const keys = path.split('.');
  let val = I18N[currentLang];
  for (const k of keys) {
    if (!val || val[k] === undefined) {
      // Fallback to pt then key
      val = I18N['pt'];
      for (const k2 of keys) {
        if (!val || val[k2] === undefined) return path;
        val = val[k2];
      }
      break;
    }
    val = val[k];
  }
  if (typeof val !== 'string') return val;
  let str = val;
  for (const [pKey, pVal] of Object.entries(params)) {
    str = str.replace(new RegExp(`\\{${pKey}\\}`, 'g'), pVal);
  }
  return str;
}

export function applyTranslations() {
  // 1. Text elements
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    const txt = t(key);
    if (txt && typeof txt === 'string' && txt !== key) el.textContent = txt;
  });

  // 2. HTML elements (with spans or formatting)
  document.querySelectorAll('[data-i18n-html]').forEach(el => {
    const key = el.getAttribute('data-i18n-html');
    const html = t(key);
    if (html && typeof html === 'string' && html !== key) el.innerHTML = html;
  });

  // 3. Placeholder attributes
  document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
    const key = el.getAttribute('data-i18n-placeholder');
    const ph = t(key);
    if (ph && typeof ph === 'string' && ph !== key) el.placeholder = ph;
  });

  // 4. Title / Tooltip attributes
  document.querySelectorAll('[data-i18n-title]').forEach(el => {
    const key = el.getAttribute('data-i18n-title');
    const title = t(key);
    if (title && typeof title === 'string' && title !== key) el.title = title;
  });

  // 5. Re-run Lucide icons for any freshly injected HTML (e.g., tutorial items)
  if (window.lucide && typeof window.lucide.createIcons === 'function') {
    window.lucide.createIcons();
  }

  // Trigger app-level re-renders if available
  if (typeof window.onLanguageChange === 'function') {
    window.onLanguageChange(currentLang);
  }
}

export function updateLangButtonsUI() {
  document.querySelectorAll('.btn-lang').forEach(btn => {
    const bLang = btn.getAttribute('data-lang');
    if (bLang === currentLang) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });
}

// Global exposure
if (typeof window !== 'undefined') {
  window.I18N = I18N;
  window.t = t;
  window.getLang = getLang;
  window.setLang = setLang;
  window.applyTranslations = applyTranslations;
  window.updateLangButtonsUI = updateLangButtonsUI;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      applyTranslations();
      updateLangButtonsUI();
    });
  } else {
    applyTranslations();
    updateLangButtonsUI();
  }
}
