/**
 * Custom Live Chat Widget
 * Provides real-time customer support chat functionality
 */

(function($) {
    'use strict';

    let chatWindow = null;
    let messagesContainer = null;
    let messageInput = null;
    let imageInput = null;
    let imagePreview = null;
    let activeChatId = null;
    let sessionKey = null;
    let selectedImage = null;
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
                    <div class="header-actions">
                        <button class="chat-end-btn" id="chat-end-btn" aria-label="End chat" title="End chat and receive transcript">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
                        </button>
                        <button class="chat-minimize-btn" aria-label="Minimize chat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </button>
                    </div>
                </div>
                <div class="chat-messages" id="chat-messages"></div>
                <div class="chat-input-area">
                    <input type="file" id="chat-image-input" accept="image/*" style="display:none;" />
                    <button id="chat-attach-btn" class="chat-attach-btn" title="Attach image">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                    </button>
                    <textarea id="chat-message-input" placeholder="Type your message..." rows="2"></textarea>
                    <button id="chat-send-btn" class="chat-send-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
                <div id="chat-image-preview" class="chat-image-preview hidden"></div>
                <div class="chat-welcome hidden">
                    <div class="welcome-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <h3>Welcome to Live Support</h3>
                    <p>Start chatting with us!</p>
                    <div id="chat-disclaimer" class="chat-disclaimer"></div>
                    <button id="chat-start-btn" class="btn-primary">Start Chatting</button>
                </div>
                <div class="chat-closed hidden">
                    <div class="closed-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <h3>Chat Closed</h3>
                    <p>This chat has been closed. A transcript has been sent to your email.</p>
                    <button id="chat-new-btn" class="btn-primary">Start New Chat</button>
                </div>
            `
        });

        // Append to body
        $('body').append(chatBubble);
        $('body').append(chatWindow);

        // Cache elements
        messagesContainer = $('#chat-messages');
        messageInput = $('#chat-message-input');
        imageInput = $('#chat-image-input');
        imagePreview = $('#chat-image-preview');

        // Bind events
        chatBubble.on('click', toggleChatWindow);
        $('.chat-minimize-btn').on('click', toggleChatWindow);
        $('#chat-end-btn').on('click', endChat);
        $('#chat-send-btn').on('click', sendMessage);
        $('#chat-start-btn').on('click', startNewChat);
        $('#chat-new-btn').on('click', function() {
            // Reset and show welcome screen
            activeChatId = null;
            sessionKey = null;
            localStorage.removeItem('aakaari_chat_session');
            localStorage.removeItem('aakaari_active_chat_id');
            showWelcomeScreen();
        });
        $('#chat-attach-btn').on('click', function() {
            imageInput.click();
        });

        imageInput.on('change', handleImageSelect);

        messageInput.on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Load chat settings (disclaimer)
        loadChatSettings();

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
                $('#chat-start-btn').focus();
            }
        }
    }

    /**
     * Load chat settings (disclaimer)
     */
    function loadChatSettings() {
        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_get_chat_settings',
                nonce: aakaari_chat.nonce
            },
            success: function(response) {
                if (response.success && response.data.disclaimer) {
                    $('#chat-disclaimer').html('<div class="disclaimer-box"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg><span>' + response.data.disclaimer + '</span></div>');
                }
            }
        });
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
                if (response.success && response.data.has_chat && response.data.chat_id) {
                    activeChatId = response.data.chat_id;
                    sessionKey = response.data.session_key;
                    // Store in localStorage for persistence
                    localStorage.setItem('aakaari_chat_session', sessionKey);
                    localStorage.setItem('aakaari_active_chat_id', activeChatId);
                    showChatInterface();
                    loadMessages();
                    startPolling();
                } else {
                    // No active chat found
                    showWelcomeScreen();
                }
            }
        });
    }

    /**
     * Start new chat conversation
     */
    function startNewChat() {
        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_start_chat',
                nonce: aakaari_chat.nonce,
                guest_name: 'Guest',
                guest_email: '',
                subject: 'Live Chat Support',
                message: 'Customer has started a live chat'
            },
            success: function(response) {
                if (response.success) {
                    activeChatId = response.data.chat_id;
                    sessionKey = response.data.session_key;
                    // Store session in localStorage for persistence
                    localStorage.setItem('aakaari_chat_session', sessionKey);
                    localStorage.setItem('aakaari_active_chat_id', activeChatId);
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
        if (!message && !selectedImage) return;

        const formData = new FormData();
        formData.append('action', 'aakaari_send_chat_message');
        formData.append('nonce', aakaari_chat.nonce);
        formData.append('chat_id', activeChatId);
        formData.append('session_key', sessionKey);
        formData.append('message', message);

        if (selectedImage) {
            formData.append('image', selectedImage);
        }

        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    messageInput.val('');
                    clearImagePreview();
                    loadMessages();
                } else {
                    alert(response.data || 'Failed to send message.');
                }
            }
        });
    }

    /**
     * Handle image selection
     */
    function handleImageSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5MB.');
            return;
        }

        selectedImage = file;

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.html(`
                <div class="image-preview-container">
                    <img src="${e.target.result}" alt="Preview" />
                    <button class="remove-image-btn" onclick="clearImagePreview()">×</button>
                </div>
            `);
            imagePreview.removeClass('hidden');
        };
        reader.readAsDataURL(file);
    }

    /**
     * Clear image preview
     */
    function clearImagePreview() {
        selectedImage = null;
        imageInput.val('');
        imagePreview.html('');
        imagePreview.addClass('hidden');
    }

    // Make clearImagePreview globally accessible
    window.clearImagePreview = clearImagePreview;

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
                    const status = response.data.status;
                    const closeReason = response.data.close_reason;

                    // Check if admin closed the chat
                    if (status === 'closed' && closeReason === 'admin_closed') {
                        // Stop polling
                        if (pollingInterval) {
                            clearInterval(pollingInterval);
                        }

                        // Show notification popup
                        alert('The support team has closed this chat. A transcript has been sent to your email. Thank you for contacting us!');

                        // Show closed screen
                        showClosedScreen();

                        // Clear session
                        localStorage.removeItem('aakaari_chat_session');
                        localStorage.removeItem('aakaari_active_chat_id');

                        activeChatId = null;
                        return;
                    }

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
            let contentHtml = '';

            // Add text message if present
            if (msg.message) {
                contentHtml += `<div class="message-text">${escapeHtml(msg.message)}</div>`;
            }

            // Add image if present
            if (msg.image) {
                contentHtml += `<div class="message-image"><img src="${msg.image}" alt="Attached image" /></div>`;
            }

            const messageHtml = `
                <div class="message ${messageClass}">
                    <div class="message-header">
                        <span class="message-author">${msg.user_name}</span>
                        <span class="message-time">${formatTime(msg.timestamp)}</span>
                    </div>
                    <div class="message-content">${contentHtml}</div>
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

        // Poll every 1 second for real-time updates (like WhatsApp)
        pollingInterval = setInterval(function() {
            if (!chatWindow.hasClass('hidden') && activeChatId) {
                loadMessages();
            }
        }, 1000);
    }

    /**
     * Show chat interface
     */
    function showChatInterface() {
        $('.chat-welcome').addClass('hidden');
        $('.chat-closed').addClass('hidden');
        messagesContainer.removeClass('hidden');
        $('.chat-input-area').removeClass('hidden');
        $('#chat-end-btn').show().removeClass('hidden');
    }

    /**
     * Show welcome screen
     */
    function showWelcomeScreen() {
        $('.chat-welcome').removeClass('hidden');
        $('.chat-closed').addClass('hidden');
        messagesContainer.addClass('hidden');
        $('.chat-input-area').addClass('hidden');
        $('#chat-end-btn').hide().addClass('hidden');
    }

    /**
     * Show closed screen
     */
    function showClosedScreen() {
        $('.chat-closed').removeClass('hidden');
        $('.chat-welcome').addClass('hidden');
        messagesContainer.addClass('hidden');
        $('.chat-input-area').addClass('hidden');
        $('#chat-end-btn').hide().addClass('hidden');
    }

    /**
     * End chat (customer closes)
     */
    function endChat() {
        if (!activeChatId) return;

        if (!confirm('Are you sure you want to end this chat? A transcript will be sent to your email.')) {
            return;
        }

        $.ajax({
            url: aakaari_chat.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_customer_close_chat',
                nonce: aakaari_chat.nonce,
                chat_id: activeChatId,
                session_key: sessionKey
            },
            success: function(response) {
                if (response.success) {
                    // Stop polling
                    if (pollingInterval) {
                        clearInterval(pollingInterval);
                    }

                    // Show closed screen
                    showClosedScreen();

                    // Clear session
                    localStorage.removeItem('aakaari_chat_session');
                    localStorage.removeItem('aakaari_active_chat_id');
                } else {
                    alert(response.data || 'Failed to close chat.');
                }
            }
        });
    }

    /**
     * Format timestamp to readable time in IST (Indian Standard Time)
     */
    function formatTime(timestamp) {
        // Parse the timestamp - WordPress sends "YYYY-MM-DD HH:MM:SS" format
        const date = new Date(timestamp.replace(' ', 'T'));
        const now = new Date();

        // Calculate difference in milliseconds
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        // Return relative time for recent messages
        if (minutes < 1) return 'Just now';
        if (minutes < 60) return minutes + ' min ago';
        if (hours < 24) return hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
        if (days < 7) return days + ' day' + (days > 1 ? 's' : '') + ' ago';

        // For older messages, show full date and time in IST
        // Convert to IST by adding 5:30 hours offset
        const istOffset = 5.5 * 60 * 60 * 1000; // IST offset in milliseconds
        const utcTime = date.getTime() + (date.getTimezoneOffset() * 60000);
        const istTime = new Date(utcTime + istOffset);

        const istHours = istTime.getHours();
        const istMinutes = istTime.getMinutes().toString().padStart(2, '0');
        const ampm = istHours >= 12 ? 'PM' : 'AM';
        const displayHours = istHours % 12 || 12;

        return istTime.toLocaleDateString('en-IN') + ' ' + displayHours + ':' + istMinutes + ' ' + ampm + ' IST';
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
