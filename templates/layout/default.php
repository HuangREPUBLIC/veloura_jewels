<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Veloura Jewels';
?>
<!DOCTYPE html>
<html lang="">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', '/img/icon.png') ?>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'fonts', 'default-styles', 'cake', 'login', 'live-chat']) ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<header class="navbar">
    <div class="navbar-brand">
        <?= $this->Html->link(
            $this->Html->image('logo.png', ['alt' => 'Veloura Jewels', 'class' => 'navbar-logo']),
            '/',
            ['escape' => false]
        ) ?>
    </div>

    <nav class="navbar-links">
        <?= $this->Html->link('Home', '/') ?>
        <?= $this->Html->link('Jewelry', '/jewelry') ?>
        <?= $this->Html->link('HomeDecor', '/home-decor') ?>
        <?= $this->Html->link('Contact', '/contact') ?>

    </nav>

    <div class="navbar-right">
        <?php
        $cart = $this->request->getSession()->read('Cart') ?? [];
        $count = count($cart);
        $identity = $this->request->getAttribute('identity');
        $role = $identity ? $identity->get('role') : null;
        ?>

        <!-- Search icon (no function yet) -->
        <button class="nav-icon-btn" title="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
            </svg>
        </button>

        <!-- Cart icon -->
        <?= $this->Html->link(
            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>'
            . ($count > 0 ? '<span class="nav-cart-badge">' . $count . '</span>' : ''),
            ['controller' => 'Jewelry', 'action' => 'cart'],
            ['class' => 'nav-icon-btn nav-cart-wrap', 'escape' => false, 'title' => 'Cart']
        ) ?>

        <!-- User dropdown -->
        <div class="nav-dropdown-wrap">
            <button class="nav-icon-btn" id="navUserBtn" onclick="toggleNavDropdown()" title="Account">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </button>

            <div class="nav-dropdown" id="navDropdown">
                <?php if (!$this->Identity->isLoggedIn()): ?>
                    <?= $this->Html->link(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg> Login',
                        '/auth/login',
                        ['escape' => false]
                    ) ?>

                <?php elseif ($role === 'customer'): ?>
                    <span class="nav-dropdown-label">My Account</span>
                    <?= $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> Profile',
                    ['controller' => 'Profile', 'action' => 'index'],
                    ['escape' => false]
                ) ?>
                    <div class="nav-dropdown-divider"></div>
                    <?= $this->Html->link(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Logout',
                        '/auth/logout',
                        ['escape' => false, 'class' => 'nav-dropdown-danger']
                    ) ?>

                <?php else: ?>
                    <span class="nav-dropdown-label">Admin</span>
                    <?= $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg> Dashboard',
                    ['controller' => 'Users', 'action' => 'dashboard'],
                    ['escape' => false]
                ) ?>
                    <?= $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> Profile',
                    ['controller' => 'Profile', 'action' => 'index'],
                    ['escape' => false]
                ) ?>
                    <div class="nav-dropdown-divider"></div>
                    <?= $this->Html->link(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Logout',
                        '/auth/logout',
                        ['escape' => false, 'class' => 'nav-dropdown-danger']
                    ) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main class="main-content">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>

<footer class="footer">
    <div class="footer-brand">
        <h3>Veloura Jewels</h3>
        <p>Opening hours: 10:00AM - 6:00PM</p>
        <p>123 456 7890</p>
        <p>veloura.jewels@gmail.com</p>
        <p>88 Elizabeth Road, Melbourne, VIC 3000</p>
    </div>
    <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms &amp; Conditions</a>
        <a href="#">Refund Policy</a>
        <a href="#">Shipping Policy</a>
    </div>
</footer>

<?php
$_chatController = $this->request->getParam('controller');
$_chatAction     = $this->request->getParam('action');
$_showChat = (
    ($_chatController === 'Pages'              && $_chatAction === 'display') ||
    ($_chatController === 'ContactSubmissions' && $_chatAction === 'add')     ||
    ($_chatController === 'Jewelry'            && $_chatAction === 'index')   ||
    ($_chatController === 'Jewelry'            && $_chatAction === 'cart')    ||
    ($_chatController === 'Jewelry'            && $_chatAction === 'view')    ||
    ($_chatController === 'Jewelry'            && $_chatAction === 'checkout')    ||
    ($_chatController === 'Jewelry'            && $_chatAction === 'success')    ||
    ($_chatController === 'Jewelry'            && $_chatAction === 'cancel')    ||
    ($_chatController === 'Profile'            && $_chatAction === 'index')    ||
    ($_chatController === 'Profile'            && $_chatAction === 'edit')    ||
    ($_chatController === 'Profile'            && $_chatAction === 'orders')    ||
    ($_chatController === 'Auth'               && $_chatAction === 'changePassword')
);
?>
<?php if ($_showChat): ?>
    <button class="live-chat-btn" onclick="toggleChat()">
        <svg class="chat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span class="chat-label">Live Chat</span>
        <span class="chat-dot"></span>
    </button>

    <div class="live-chat-popup" id="chatPopup">
        <div class="chat-popup-header">
            <div class="chat-popup-header-left">
                <div class="chat-popup-avatar">
                    <img src="<?= $this->Url->image('icon.png') ?>" alt="Veloura Jewels" style="width:28px;height:28px;object-fit:contain;border-radius:50%;">
                </div>
                <div>
                    <div class="chat-popup-title">Veloura Jewels</div>
                    <div class="chat-popup-subtitle">Jewelry Advisor</div>
                </div>
            </div>
            <button class="chat-popup-close" onclick="toggleChat()">✕</button>
        </div>

        <div class="chat-popup-body" id="chatMessages">
            <div class="chat-bubble chat-bubble-bot">
                👋 Hi there! Welcome to Veloura Jewels. How can I help you today?
            </div>
        </div>

        <div class="chat-popup-input-area">
            <input type="file" id="chatFileInput" style="display:none" onchange="handleFileAttach(this)">
            <input type="text" id="chatInput" class="chat-input" placeholder="Type a message..."
                   autocomplete="off" onkeydown="if(event.key==='Enter') sendChatMessage()">
            <button class="chat-send-btn" onclick="sendChatMessage()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
            <button class="chat-attach-btn" onclick="document.getElementById('chatFileInput').click()" title="Attach file">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
        var _chatHistory = [];
        var _chatEndpoint = '<?= $this->Url->build(['controller' => 'Chat', 'action' => 'message']) ?>';
        var _csrfToken = '<?= $this->request->getAttribute('csrfToken') ?>';

        function toggleChat() {
            document.getElementById('chatPopup').classList.toggle('open');
            if (document.getElementById('chatPopup').classList.contains('open')) {
                document.getElementById('chatInput').focus();
            }
        }

        function appendBubble(text, role) {
            var body = document.getElementById('chatMessages');
            var div = document.createElement('div');
            div.className = 'chat-bubble ' + (role === 'user' ? 'chat-bubble-user' : 'chat-bubble-bot');
            div.textContent = text;
            body.appendChild(div);
            body.scrollTop = body.scrollHeight;
            return div;
        }

        function sendChatMessage() {
            var input = document.getElementById('chatInput');
            var message = input.value.trim();
            if (!message) return;

            input.value = '';
            input.disabled = true;
            document.querySelector('.chat-send-btn').disabled = true;

            appendBubble(message, 'user');
            var typing = appendBubble('...', 'bot');
            typing.classList.add('chat-bubble-typing');

            fetch(_chatEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': _csrfToken
                },
                body: JSON.stringify({ message: message, history: _chatHistory })
            })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    typing.remove();
                    var reply = data.reply || 'Sorry, something went wrong. Please try again.';
                    appendBubble(reply, 'bot');
                    _chatHistory.push({ role: 'user', content: message });
                    _chatHistory.push({ role: 'assistant', content: reply });
                    if (_chatHistory.length > 20) _chatHistory = _chatHistory.slice(-20);
                })
                .catch(function() {
                    typing.remove();
                    appendBubble('Connection error. Please try again.', 'bot');
                })
                .finally(function() {
                    input.disabled = false;
                    document.querySelector('.chat-send-btn').disabled = false;
                    input.focus();
                });

        }
        function handleFileAttach(input) {
            if (!input.files || !input.files[0]) return;
            var file = input.files[0];
            appendBubble('📎 ' + file.name, 'user');

            input.value = '';
        }
    </script>
<?php endif; ?>

<script>
    function toggleNavDropdown() {
        document.getElementById('navDropdown').classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
        var wrap = document.querySelector('.nav-dropdown-wrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('navDropdown').classList.remove('open');
        }
    });
</script>

</body>
</html>
