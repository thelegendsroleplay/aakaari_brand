/**
 * Custom Live Chat Widget
 * Provides real-time customer support chat functionality
 */

(function($) {
    'use strict';

    let chatWindow = null;
    let messagesContainer = null;
    let messageInput = null;
    let activeChatId = null;
    let pollingInterval = null;
    let lastMessageCount = 0;

    /**
     * Initialize chat widget
     */
    function initChatWidget() {
        // Create chat bubble button
        const chatBubble = $('<div>', {
            id: 'aakaari-chat-bubble',
            class: 'chat-bubble',
            html: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>'
        });

        // Create chat window
        chatWindow = $('<div>', {
            id: 'aakaari-chat-window',
            class: 'chat-window hidden',
            html: `
                <div class="chat-window-header">
                    <div class="header-info">
                        <h3>Live Support</h3>
                        <p class="header-status">Online</p>
                    </div>
                    <button class="chat-close-btn" aria-label="Close chat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="chat-messages" id="chat-messages"></div>
                <div class="chat-input-area">
                    <textarea id="chat-message-input" placeholder="Type your message..." rows="2"></textarea>
                    <button id="chat-send-btn" class="chat-send-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
                <div class="chat-welcome hidden">
                    <div class="welcome-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <h3>Welcome to Live Support</h3>
                    <p>How can we help you today?</p>
                    <input type="text" id="chat-subject-input" placeholder="Brief subject of your query..." />
                    <textarea id="chat-initial-message" placeholder="Describe your issue..." rows="4"></textarea>
                    <button id="chat-start-btn" class="btn-primary">Start Chat</button>
                </div>
            `
        });

        // Append to body
        $('body').append(chatBubble);
        $('body').append(chatWindow);

        // Cache elements
        messagesContainer = $('#chat-messages');
        messageInput = $('#chat-message-input');

        // Bind events
        chatBubble.on('click', toggleChatWindow);
        $('.chat-close-btn').on('click', toggleChatWindow);
        $('#chat-send-btn').on('click', sendMessage);
        $('#chat-start-btn').on('click', startNewChat);

        messageInput.on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Check for active chat
        checkActiveChat();
    }

    /**
     * Toggle chat window visibility
     */
    function toggleChatWindow() {
        chatWindow.toggleClass('hidden');

        if (!chatWindow.hasClass('hidden')) {
            // Focus input when opening
            if (activeChatId) {
                messageInput.focus();
            } else {
                $('#chat-subject-input').focus();
            }
        }
    }

    /**
     * Check if user has an active chat
     */
    function checkActiveChat() {
        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_get_active_chat',
                nonce: aakaari_chat.nonce
            },
            success: function(response) {
                if (response.success && response.data.chat_id) {
                    activeChatId = response.data.chat_id;
                    showChatInterface();
                    loadMessages();
                    startPolling();
                } else {
                    showWelcomeScreen();
                }
            }
        });
    }

    /**
     * Start new chat conversation
     */
    function startNewChat() {
        const subject = $('#chat-subject-input').val().trim();
        const message = $('#chat-initial-message').val().trim();

        if (!subject || !message) {
            alert('Please provide both a subject and message to start the chat.');
            return;
        }

        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_start_chat',
                nonce: aakaari_chat.nonce,
                subject: subject,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    activeChatId = response.data.chat_id;
                    showChatInterface();
                    loadMessages();
                    startPolling();
                } else {
                    alert(response.data || 'Failed to start chat. Please try again.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    }

    /**
     * Send message in active chat
     */
    function sendMessage() {
        if (!activeChatId) return;

        const message = messageInput.val().trim();
        if (!message) return;

        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_send_chat_message',
                nonce: aakaari_chat.nonce,
                chat_id: activeChatId,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    messageInput.val('');
                    loadMessages();
                } else {
                    alert(response.data || 'Failed to send message.');
                }
            }
        });
    }

    /**
     * Load chat messages
     */
    function loadMessages() {
        if (!activeChatId) return;

        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_get_chat_messages',
                nonce: aakaari_chat.nonce,
                chat_id: activeChatId
            },
            success: function(response) {
                if (response.success) {
                    const messages = response.data.messages || [];

                    // Only update if message count changed
                    if (messages.length !== lastMessageCount) {
                        displayMessages(messages);
                        lastMessageCount = messages.length;
                    }
                }
            }
        });
    }

    /**
     * Display messages in chat window
     */
    function displayMessages(messages) {
        messagesContainer.empty();

        messages.forEach(function(msg) {
            const messageClass = msg.is_admin ? 'support-message' : 'user-message';
            const messageHtml = `
                <div class="message ${messageClass}">
                    <div class="message-header">
                        <span class="message-author">${msg.user_name}</span>
                        <span class="message-time">${formatTime(msg.timestamp)}</span>
                    </div>
                    <div class="message-content">${escapeHtml(msg.message)}</div>
                </div>
            `;
            messagesContainer.append(messageHtml);
        });

        // Scroll to bottom
        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
    }

    /**
     * Start polling for new messages
     */
    function startPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }

        // Poll every 3 seconds
        pollingInterval = setInterval(function() {
            if (!chatWindow.hasClass('hidden') && activeChatId) {
                loadMessages();
            }
        }, 3000);
    }

    /**
     * Show chat interface
     */
    function showChatInterface() {
        $('.chat-welcome').addClass('hidden');
        messagesContainer.removeClass('hidden');
        $('.chat-input-area').removeClass('hidden');
    }

    /**
     * Show welcome screen
     */
    function showWelcomeScreen() {
        $('.chat-welcome').removeClass('hidden');
        messagesContainer.addClass('hidden');
        $('.chat-input-area').addClass('hidden');
    }

    /**
     * Format timestamp to readable time
     */
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return minutes + ' min ago';
        if (hours < 24) return hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
        if (days < 7) return days + ' day' + (days > 1 ? 's' : '') + ' ago';

        return date.toLocaleDateString();
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initChatWidget();
    });

})(jQuery);
