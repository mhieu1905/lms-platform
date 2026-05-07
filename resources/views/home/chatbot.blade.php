<link rel="stylesheet" href="{{ asset('assets/css/home/chatbot.css') }}">

<body>
    <div class="chat-button" onclick="toggleChat()">
        <div class="notification-dot"></div>
        <div class="chat-button-inner">
            <div class="chat-icon"><i class="iconify fs-24" data-icon="ant-design:message-filled"></i></div>
        </div>
    </div>

    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-avatar"><i class="iconify fs-24" data-icon="fluent-emoji-flat:robot"></i></div>
                <div class="chat-header-text">
                    <h3>MTEDU AGENT</h3>
                    <p>Always ready to assist you</p>
                </div>
            </div>
            <button class="close-btn" onclick="toggleChat()">×</button>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-bubble">
                    Hello! How can I assist you today?
                </div>
            </div>
        </div>

        <div class="chat-input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="Type your message..."
                onkeypress="handleKeyPress(event)">
            <button class="send-btn" onclick="sendMessage()">➤</button>
        </div>
    </div>
</body>

<script>
    let chatInitialized = false;
    const chatBroadcast = new BroadcastChannel('chat_sync_channel');
    
    chatBroadcast.onmessage = (event) => {
        const { type, data } = event.data;
        
        switch(type) {
            case 'new_message':
                addMessage(data.text, data.sender, false); 
                break;
            case 'typing_start':
                if (data.sender === 'bot') {
                    showTypingIndicator(false);
                }
                break;
            case 'typing_end':
                hideTypingIndicator(false);
                break;
        }
    };

    async function toggleChat() {
        const chatWindow = document.getElementById('chatWindow');
        chatWindow.classList.toggle('active');

        if (chatWindow.classList.contains('active')) {
            document.getElementById('chatInput').focus();

            chatBroadcast.postMessage({
                type: 'chat_opened',
                data: { timestamp: Date.now() }
            });

            if (!chatInitialized) {
                await initializeChat();
                chatInitialized = true;
            }
        }
    }

    async function initializeChat() {
        try {
            await Promise.all([
                sendSessionToN8N(),

            ]);
        } catch (error) {
            console.error('Error initializing chat:', error);
        }
    }

    function getSessions() {
        return '{{ session()->getId() }}';
    }

    async function sendSessionToN8N() {
        try {
            const response = await fetch('https://n8n.mhieu.io.vn/webhook/postgrest', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                        'content') || '',
                },
                body: JSON.stringify({
                    message_type: 'session_init',
                    user_id: getUserId(),
                    session_id: getSessions(),
                    platform: 'website',
                    action: 'initialize_chat'
                })
            });

            const data = await response.json();
            console.log('Session sent to n8n:', data);

            data.data.forEach(item => {
                if (!item.message || !item.message.type) return;

                if (item.message.type === 'human') {
                    addMessage(item.message.content, 'user', false);
                } else if (item.message.type === 'ai') {
                    addMessage(item.message.content, 'bot', false);
                }
            });


        } catch (error) {
            console.error('Error sending session to n8n:', error);
        }
    }

    function getUserId() {
        @if (Auth::check())
            return '{{ Auth::user()->id }}';
        @else
            let anonymousId = localStorage.getItem('chatbot_user_id');
            if (!anonymousId) {
                anonymousId = 'anonymous_' + Date.now();
                localStorage.setItem('chatbot_user_id', anonymousId);
                console.log('Generated new anonymous user ID: ' + anonymousId);
            }
            return anonymousId;
        @endif
    }

    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();

        if (message) {
            addMessage(message, 'user', true);
            input.value = '';

            showTypingIndicator(true);

            sendToBot(message);
        }
    }

    async function sendToBot(message) {
        try {
            const response = await fetch(
                'https://n8n.mhieu.io.vn/webhook/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || '',
                    },
                    body: JSON.stringify({
                        message_type: 'text',
                        message: message,
                        user_id: getUserId(),
                        session_id: getSessions(),
                        platform: 'website'
                    })
                });

            const data = await response.json();
            console.log(data.output);
            hideTypingIndicator(true);

            if (data.output) {
                addMessage(data.output, 'bot');
            } else {
                addMessage('Hiện đang có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
            }

        } catch (error) {
            console.error('Error sending message to chatbot:', error);
            hideTypingIndicator(true);
            addMessage('Hiện đang có lỗi xảy ra. Vui lòng thử lại sau.', 'bot', true);
        }
    }


    function showTypingIndicator(shouldBroadcast = true) {
        const messagesContainer = document.getElementById('chatMessages');
                if (document.getElementById('typingIndicator')) {
            return;
        }

        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot typing-indicator';
        typingDiv.id = 'typingIndicator';

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';
        bubbleDiv.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';

        typingDiv.appendChild(bubbleDiv);
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        if (shouldBroadcast) {
            chatBroadcast.postMessage({
                type: 'typing_start',
                data: { sender: 'bot' }
            });
        }
    }

    function hideTypingIndicator(shouldBroadcast = true) {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();

            if (shouldBroadcast) {
                chatBroadcast.postMessage({
                    type: 'typing_end',
                    data: {}
                });
            }
        }
    }

    function addMessage(text, sender, shouldBroadcast = true) {
        const messagesContainer = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';

        bubbleDiv.innerHTML = text;

        messageDiv.appendChild(bubbleDiv);
        messagesContainer.appendChild(messageDiv);

        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        if (shouldBroadcast) {
            chatBroadcast.postMessage({
                type: 'new_message',
                data: {
                    text: text,
                    sender: sender,
                    timestamp: Date.now()
                }
            });
        }
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

     window.addEventListener('beforeunload', () => {
        chatBroadcast.close();
    });
</script>

</html>
