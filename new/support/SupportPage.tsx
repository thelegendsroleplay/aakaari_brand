import React, { useState } from 'react';
import { MessageSquare, Plus, Search, Filter } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Input } from '../../components/ui/input';
import { Label } from '../../components/ui/label';
import { Card } from '../../components/ui/card';
import { useTickets } from '../../contexts/TicketContext';
import { useAuth } from '../../contexts/AuthContext';
import { SupportTicket } from '../../types';
import './support.css';

interface SupportPageProps {
  onNavigate: (page: string) => void;
}

export const SupportPage: React.FC<SupportPageProps> = ({ onNavigate }) => {
  const { user } = useAuth();
  const { tickets, createTicket, getTicketsByUser, addTicketMessage, updateTicketStatus } = useTickets();
  const [showCreateForm, setShowCreateForm] = useState(false);
  const [selectedTicket, setSelectedTicket] = useState<SupportTicket | null>(null);
  const [newTicketData, setNewTicketData] = useState({
    subject: '',
    category: 'General',
    priority: 'medium',
    message: '',
  });
  const [replyMessage, setReplyMessage] = useState('');
  const [filterStatus, setFilterStatus] = useState<string>('all');

  const userTickets = user ? getTicketsByUser(user.id) : [];
  
  const filteredTickets = filterStatus === 'all' 
    ? userTickets 
    : userTickets.filter(t => t.status === filterStatus);

  const handleCreateTicket = (e: React.FormEvent) => {
    e.preventDefault();
    if (!user) return;
    
    createTicket(
      newTicketData.subject,
      newTicketData.category,
      newTicketData.priority,
      newTicketData.message,
      user.id
    );
    
    setNewTicketData({ subject: '', category: 'General', priority: 'medium', message: '' });
    setShowCreateForm(false);
  };

  const handleReply = (e: React.FormEvent) => {
    e.preventDefault();
    if (!user || !selectedTicket) return;
    
    addTicketMessage(selectedTicket.id, user.id, user.name, replyMessage);
    setReplyMessage('');
  };

  const handleCloseTicket = () => {
    if (!selectedTicket) return;
    
    if (window.confirm('Are you sure you want to close this ticket? You can reopen it later if needed.')) {
      updateTicketStatus(selectedTicket.id, 'closed');
      // Update the selected ticket to reflect the change
      setSelectedTicket({ ...selectedTicket, status: 'closed' });
    }
  };

  const handleReopenTicket = () => {
    if (!selectedTicket) return;
    
    updateTicketStatus(selectedTicket.id, 'open');
    setSelectedTicket({ ...selectedTicket, status: 'open' });
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'open': return 'blue';
      case 'in_progress': return 'yellow';
      case 'resolved': return 'green';
      case 'closed': return 'gray';
      default: return 'gray';
    }
  };

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high': return 'red';
      case 'medium': return 'yellow';
      case 'low': return 'green';
      default: return 'gray';
    }
  };

  if (!user) {
    return (
      <div className="support-page">
        <div className="container mx-auto px-4 py-12 text-center">
          <MessageSquare className="w-16 h-16 mx-auto mb-4 text-gray-300" />
          <h1 className="text-2xl font-semibold mb-2">Support Center</h1>
          <p className="text-gray-600 mb-6">Please login to view and create support tickets</p>
          <Button onClick={() => onNavigate('auth')}>Login</Button>
        </div>
      </div>
    );
  }

  return (
    <div className="support-page">
      <div className="support-container">
        <div className="support-header">
          <div>
            <h1>Support Center</h1>
            <p>Get help with your orders and questions</p>
          </div>
          <Button onClick={() => setShowCreateForm(true)}>
            <Plus className="w-4 h-4 mr-2" />
            New Ticket
          </Button>
        </div>

        <div className="support-content">
          {/* Sidebar - Ticket List */}
          <div className="tickets-sidebar">
            <div className="sidebar-header">
              <h2>Your Tickets</h2>
              <div className="filter-buttons">
                <button
                  className={filterStatus === 'all' ? 'active' : ''}
                  onClick={() => setFilterStatus('all')}
                >
                  All
                </button>
                <button
                  className={filterStatus === 'open' ? 'active' : ''}
                  onClick={() => setFilterStatus('open')}
                >
                  Open
                </button>
                <button
                  className={filterStatus === 'in_progress' ? 'active' : ''}
                  onClick={() => setFilterStatus('in_progress')}
                >
                  In Progress
                </button>
                <button
                  className={filterStatus === 'resolved' ? 'active' : ''}
                  onClick={() => setFilterStatus('resolved')}
                >
                  Resolved
                </button>
              </div>
            </div>

            <div className="tickets-list">
              {filteredTickets.length > 0 ? (
                filteredTickets.map(ticket => (
                  <div
                    key={ticket.id}
                    className={`ticket-item ${selectedTicket?.id === ticket.id ? 'active' : ''}`}
                    onClick={() => setSelectedTicket(ticket)}
                  >
                    <div className="ticket-item-header">
                      <h3>{ticket.subject}</h3>
                      <span className={`status-badge ${getStatusColor(ticket.status)}`}>
                        {ticket.status.replace('_', ' ')}
                      </span>
                    </div>
                    <p className="ticket-category">{ticket.category}</p>
                    <div className="ticket-meta">
                      <span className={`priority-badge ${getPriorityColor(ticket.priority)}`}>
                        {ticket.priority}
                      </span>
                      <span className="ticket-date">
                        {new Date(ticket.createdAt).toLocaleDateString()}
                      </span>
                    </div>
                  </div>
                ))
              ) : (
                <div className="empty-state">
                  <MessageSquare className="w-12 h-12 text-gray-300 mx-auto mb-3" />
                  <p>No tickets found</p>
                </div>
              )}
            </div>
          </div>

          {/* Main Content - Ticket Detail */}
          <div className="ticket-detail">
            {selectedTicket ? (
              <>
                <div className="ticket-detail-header">
                  <div>
                    <h2>{selectedTicket.subject}</h2>
                    <div className="ticket-detail-meta">
                      <span className={`status-badge ${getStatusColor(selectedTicket.status)}`}>
                        {selectedTicket.status.replace('_', ' ')}
                      </span>
                      <span className={`priority-badge ${getPriorityColor(selectedTicket.priority)}`}>
                        {selectedTicket.priority} priority
                      </span>
                      <span className="ticket-id">Ticket #{selectedTicket.id}</span>
                    </div>
                  </div>
                  {selectedTicket.status === 'closed' ? (
                    <Button type="button" variant="outline" onClick={handleReopenTicket}>
                      Reopen Ticket
                    </Button>
                  ) : (
                    <Button type="button" variant="outline" onClick={handleCloseTicket}>
                      Close Ticket
                    </Button>
                  )}
                </div>

                <div className="messages-container">
                  {selectedTicket.messages.map((message) => (
                    <div
                      key={message.id}
                      className={`message ${message.userId === user.id ? 'user-message' : 'support-message'}`}
                    >
                      <div className="message-header">
                        <span className="message-author">{message.userName}</span>
                        <span className="message-time">
                          {new Date(message.createdAt).toLocaleString()}
                        </span>
                      </div>
                      <div className="message-content">{message.message}</div>
                    </div>
                  ))}
                </div>

                {selectedTicket.status !== 'closed' && (
                  <form onSubmit={handleReply} className="reply-form">
                    <textarea
                      className="reply-textarea"
                      placeholder="Type your message..."
                      value={replyMessage}
                      onChange={(e) => setReplyMessage(e.target.value)}
                      required
                    />
                    <Button type="submit">Send Reply</Button>
                  </form>
                )}
              </>
            ) : (
              <div className="empty-state">
                <MessageSquare className="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3>Select a ticket</h3>
                <p>Choose a ticket from the list to view details</p>
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Create Ticket Modal */}
      {showCreateForm && (
        <div className="modal-overlay" onClick={() => setShowCreateForm(false)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2>Create Support Ticket</h2>
              <button onClick={() => setShowCreateForm(false)} className="close-button">
                ×
              </button>
            </div>
            <form onSubmit={handleCreateTicket} className="create-ticket-form">
              <div className="form-field">
                <Label htmlFor="subject">Subject *</Label>
                <Input
                  id="subject"
                  value={newTicketData.subject}
                  onChange={(e) => setNewTicketData({ ...newTicketData, subject: e.target.value })}
                  required
                />
              </div>

              <div className="form-row">
                <div className="form-field">
                  <Label htmlFor="category">Category *</Label>
                  <select
                    id="category"
                    className="select-input"
                    value={newTicketData.category}
                    onChange={(e) => setNewTicketData({ ...newTicketData, category: e.target.value })}
                  >
                    <option value="General">General</option>
                    <option value="Orders">Orders</option>
                    <option value="Product Issues">Product Issues</option>
                    <option value="Shipping">Shipping</option>
                    <option value="Returns">Returns & Refunds</option>
                    <option value="Technical">Technical Support</option>
                  </select>
                </div>

                <div className="form-field">
                  <Label htmlFor="priority">Priority *</Label>
                  <select
                    id="priority"
                    className="select-input"
                    value={newTicketData.priority}
                    onChange={(e) => setNewTicketData({ ...newTicketData, priority: e.target.value })}
                  >
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                  </select>
                </div>
              </div>

              <div className="form-field">
                <Label htmlFor="message">Message *</Label>
                <textarea
                  id="message"
                  className="textarea-input"
                  value={newTicketData.message}
                  onChange={(e) => setNewTicketData({ ...newTicketData, message: e.target.value })}
                  required
                  rows={5}
                />
              </div>

              <div className="form-actions">
                <Button type="submit">Create Ticket</Button>
                <Button type="button" variant="outline" onClick={() => setShowCreateForm(false)}>
                  Cancel
                </Button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};