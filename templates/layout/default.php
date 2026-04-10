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
<html>
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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

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
        <?php
        $identity = $this->request->getAttribute('identity');
        $role = $identity ? $identity->get('role') : null;
        ?>

        <?= $this->Html->link('Home', '/') ?>
        <?= $this->Html->link('Jewelry', '/jewelry') ?>

        <?php if ($role === 'customer' || !$role): ?>
            <?= $this->Html->link('Contact', '/contact') ?>
        <?php else: ?>
            <?= $this->Html->link('Admin', ['controller' => 'Users', 'action' => 'dashboard']) ?>
        <?php endif; ?>
    </nav>

    <div class="navbar-right">
        <?php
        $cart = $this->request->getSession()->read('Cart') ?? [];
        $count = count($cart);
        ?>

        <?= $this->Html->link("Cart ($count)", ['controller' => 'Jewelry', 'action' => 'cart'], ['class' => 'cart']) ?>

        <?php if ($this->Identity->isLoggedIn()): ?>
            <?= $this->Html->link('Logout', '/auth/logout', ['class' => 'btn-login']) ?>
        <?php else: ?>
            <?= $this->Html->link('Login', '/auth/login', ['class' => 'btn-login']) ?>
        <?php endif; ?>
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
    ($_chatController === 'Jewelry'            && $_chatAction === 'view')
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
                    <div class="chat-popup-subtitle">AI Jewelry Advisor</div>
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
            <input
                type="text"
                id="chatInput"
                class="chat-input"
                placeholder="Type a message..."
                autocomplete="off"
                onkeydown="if(event.key==='Enter') sendChatMessage()"
            >
            <button class="chat-send-btn" onclick="sendChatMessage()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
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
    </script>
<?php endif; ?>

</body>
</html>
