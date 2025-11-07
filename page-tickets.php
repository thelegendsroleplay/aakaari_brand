<?php
/**
 * Template Name: Support Tickets
 * Description: Support ticket system with real-time updates
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_user_logged_in() ) {
    wp_redirect( wc_get_page_permalink( 'myaccount' ) );
    exit;
}

get_header();
?>

<div class="tickets-page">
    <div class="tickets-container">
        <div class="tickets-header">
            <h1>Support Tickets</h1>
            <button class="btn-primary" onclick="openNewTicketModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Ticket
            </button>
        </div>

        <!-- Filter Tabs -->
        <div class="tickets-tabs">
            <button class="tab-btn active" data-filter="all">All Tickets</button>
            <button class="tab-btn" data-filter="open">Open</button>
            <button class="tab-btn" data-filter="in-progress">In Progress</button>
            <button class="tab-btn" data-filter="resolved">Resolved</button>
            <button class="tab-btn" data-filter="closed">Closed</button>
        </div>

        <!-- Tickets List -->
        <div id="tickets-list" class="tickets-list">
            <div class="loading-spinner">Loading tickets...</div>
        </div>

        <!-- No Tickets Message -->
        <div id="no-tickets" class="no-tickets" style="display: none;">
            <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            <h3>No tickets yet</h3>
            <p>Create your first support ticket to get help</p>
            <button class="btn-primary" onclick="openNewTicketModal()">Create Ticket</button>
        </div>
    </div>
</div>

<!-- New Ticket Modal -->
<div id="new-ticket-modal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeNewTicketModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create New Ticket</h2>
            <button class="modal-close" onclick="closeNewTicketModal()">×</button>
        </div>
        <form id="new-ticket-form">
            <div class="form-group">
                <label>Subject *</label>
                <input type="text" name="subject" id="ticket-subject" required placeholder="Brief description of your issue" />
            </div>
            <div class="form-group">
                <label>Priority *</label>
                <select name="priority" id="ticket-priority" required>
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            <div class="form-group">
                <label>Message *</label>
                <textarea name="message" id="ticket-message" required rows="6" placeholder="Describe your issue in detail..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-outline" onclick="closeNewTicketModal()">Cancel</button>
                <button type="submit" class="btn-primary">Create Ticket</button>
            </div>
        </form>
    </div>
</div>

<!-- Ticket Detail Modal -->
<div id="ticket-detail-modal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeTicketDetailModal()"></div>
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2 id="ticket-detail-subject"></h2>
            <button class="modal-close" onclick="closeTicketDetailModal()">×</button>
        </div>
        <div class="ticket-detail-body">
            <div class="ticket-meta">
                <span class="ticket-status-badge" id="ticket-detail-status"></span>
                <span class="ticket-priority-badge" id="ticket-detail-priority"></span>
                <span class="ticket-date" id="ticket-detail-date"></span>
            </div>
            <div class="ticket-content">
                <p id="ticket-detail-message"></p>
            </div>
            <div class="ticket-replies" id="ticket-replies">
                <!-- Replies will be loaded here -->
            </div>
            <div class="ticket-reply-form">
                <textarea id="ticket-reply-message" placeholder="Type your reply..." rows="3"></textarea>
                <button class="btn-primary" onclick="addTicketReply()">Send Reply</button>
            </div>
            <div class="ticket-actions">
                <label>Change Status:</label>
                <select id="ticket-status-select">
                    <option value="open">Open</option>
                    <option value="in-progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                <button class="btn-outline" onclick="updateTicketStatus()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentTickets = [];
let currentFilter = 'all';
let currentTicketId = null;
let updateInterval = null;

// Auto-refresh tickets every 10 seconds
document.addEventListener('DOMContentLoaded', function() {
    loadTickets();
    updateInterval = setInterval(loadTickets, 10000); // 10 seconds

    // Tab filtering
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            renderTickets();
        });
    });

    // New ticket form submission
    document.getElementById('new-ticket-form').addEventListener('submit', function(e) {
        e.preventDefault();
        createTicket();
    });
});

function loadTickets() {
    fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'aakaari_get_tickets',
            nonce: '<?php echo wp_create_nonce( 'aakaari_tickets_nonce' ); ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentTickets = data.data.tickets;
            renderTickets();
        }
    });
}

function renderTickets() {
    const listEl = document.getElementById('tickets-list');
    const noTicketsEl = document.getElementById('no-tickets');

    let filtered = currentTickets;
    if (currentFilter !== 'all') {
        filtered = currentTickets.filter(t => t.status.toLowerCase().replace(' ', '-') === currentFilter);
    }

    if (filtered.length === 0) {
        listEl.innerHTML = '';
        noTicketsEl.style.display = 'block';
        return;
    }

    noTicketsEl.style.display = 'none';

    listEl.innerHTML = filtered.map(ticket => `
        <div class="ticket-card" onclick="openTicketDetail(${ticket.id})">
            <div class="ticket-card-header">
                <h3>${ticket.subject}</h3>
                <div class="ticket-badges">
                    <span class="status-badge ${ticket.status.toLowerCase().replace(' ', '-')}">${ticket.status}</span>
                    <span class="priority-badge ${ticket.priority.toLowerCase()}">${ticket.priority}</span>
                </div>
            </div>
            <p class="ticket-preview">${ticket.message.substring(0, 100)}${ticket.message.length > 100 ? '...' : ''}</p>
            <div class="ticket-card-footer">
                <span class="ticket-date">${ticket.created}</span>
                ${ticket.reply_count > 0 ? `<span class="ticket-replies-count">${ticket.reply_count} ${ticket.reply_count === 1 ? 'reply' : 'replies'}</span>` : ''}
            </div>
        </div>
    `).join('');
}

function openNewTicketModal() {
    document.getElementById('new-ticket-modal').style.display = 'flex';
}

function closeNewTicketModal() {
    document.getElementById('new-ticket-modal').style.display = 'none';
    document.getElementById('new-ticket-form').reset();
}

function createTicket() {
    const form = document.getElementById('new-ticket-form');
    const formData = new FormData(form);

    fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'aakaari_create_ticket',
            nonce: '<?php echo wp_create_nonce( 'aakaari_tickets_nonce' ); ?>',
            subject: formData.get('subject'),
            message: formData.get('message'),
            priority: formData.get('priority')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeNewTicketModal();
            loadTickets();
            alert('Ticket created successfully!');
        } else {
            alert('Error: ' + data.data.message);
        }
    });
}

function openTicketDetail(ticketId) {
    currentTicketId = ticketId;
    const ticket = currentTickets.find(t => t.id === ticketId);

    if (!ticket) return;

    document.getElementById('ticket-detail-subject').textContent = ticket.subject;
    document.getElementById('ticket-detail-status').textContent = ticket.status;
    document.getElementById('ticket-detail-status').className = 'ticket-status-badge ' + ticket.status.toLowerCase().replace(' ', '-');
    document.getElementById('ticket-detail-priority').textContent = ticket.priority;
    document.getElementById('ticket-detail-priority').className = 'ticket-priority-badge ' + ticket.priority.toLowerCase();
    document.getElementById('ticket-detail-date').textContent = ticket.created;
    document.getElementById('ticket-detail-message').textContent = ticket.message;
    document.getElementById('ticket-status-select').value = ticket.status.toLowerCase().replace(' ', '-');

    // Render replies
    const repliesEl = document.getElementById('ticket-replies');
    if (ticket.replies && ticket.replies.length > 0) {
        repliesEl.innerHTML = '<h4>Replies</h4>' + ticket.replies.map(reply => `
            <div class="ticket-reply ${reply.is_admin ? 'admin-reply' : ''}">
                <div class="reply-header">
                    <strong>${reply.user_name}${reply.is_admin ? ' (Support Team)' : ''}</strong>
                    <span class="reply-date">${reply.timestamp}</span>
                </div>
                <p>${reply.message}</p>
            </div>
        `).join('');
    } else {
        repliesEl.innerHTML = '';
    }

    document.getElementById('ticket-detail-modal').style.display = 'flex';
}

function closeTicketDetailModal() {
    document.getElementById('ticket-detail-modal').style.display = 'none';
    currentTicketId = null;
}

function addTicketReply() {
    const message = document.getElementById('ticket-reply-message').value.trim();

    if (!message) {
        alert('Please enter a message');
        return;
    }

    fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'aakaari_add_ticket_reply',
            nonce: '<?php echo wp_create_nonce( 'aakaari_tickets_nonce' ); ?>',
            ticket_id: currentTicketId,
            reply: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('ticket-reply-message').value = '';
            loadTickets();
            setTimeout(() => openTicketDetail(currentTicketId), 500);
        } else {
            alert('Error: ' + data.data.message);
        }
    });
}

function updateTicketStatus() {
    const status = document.getElementById('ticket-status-select').value;

    fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'aakaari_update_ticket_status',
            nonce: '<?php echo wp_create_nonce( 'aakaari_tickets_nonce' ); ?>',
            ticket_id: currentTicketId,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadTickets();
            setTimeout(() => openTicketDetail(currentTicketId), 500);
            alert('Status updated successfully!');
        } else {
            alert('Error: ' + data.data.message);
        }
    });
}

// Clear interval on page unload
window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});
</script>

<?php get_footer(); ?>
