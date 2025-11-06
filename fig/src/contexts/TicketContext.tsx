import React, { createContext, useContext, useState } from 'react';
import { SupportTicket, TicketMessage } from '../types';
import { toast } from 'sonner@2.0.3';

interface TicketContextType {
  tickets: SupportTicket[];
  createTicket: (subject: string, category: string, priority: string, message: string, userId: string) => void;
  updateTicketStatus: (ticketId: string, status: SupportTicket['status']) => void;
  addTicketMessage: (ticketId: string, userId: string, userName: string, message: string) => void;
  getTicketsByUser: (userId: string) => SupportTicket[];
  getTicketById: (ticketId: string) => SupportTicket | undefined;
}

const TicketContext = createContext<TicketContextType | undefined>(undefined);

export const TicketProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [tickets, setTickets] = useState<SupportTicket[]>([
    {
      id: '1',
      userId: '1',
      subject: 'Order not received',
      category: 'Orders',
      priority: 'high',
      status: 'in_progress',
      messages: [
        {
          id: 'm1',
          ticketId: '1',
          userId: '1',
          userName: 'John Doe',
          message: 'I placed an order 2 weeks ago (Order #12345) but haven\'t received it yet. Can you help?',
          createdAt: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000),
        },
        {
          id: 'm2',
          ticketId: '1',
          userId: 'admin',
          userName: 'Support Team',
          message: 'Hi John, we\'re looking into your order. It appears there was a delay with the shipping carrier. We\'ll update you soon.',
          createdAt: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000),
        },
      ],
      createdAt: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000),
      updatedAt: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000),
    },
    {
      id: '2',
      userId: '1',
      subject: 'Product defect',
      category: 'Product Issues',
      priority: 'medium',
      status: 'resolved',
      messages: [
        {
          id: 'm3',
          ticketId: '2',
          userId: '1',
          userName: 'John Doe',
          message: 'The t-shirt I received has a small tear. Can I get a replacement?',
          createdAt: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000),
        },
        {
          id: 'm4',
          ticketId: '2',
          userId: 'admin',
          userName: 'Support Team',
          message: 'We\'re sorry to hear that. We\'ll send you a replacement right away. You should receive it within 3-5 business days.',
          createdAt: new Date(Date.now() - 4 * 24 * 60 * 60 * 1000),
        },
      ],
      createdAt: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000),
      updatedAt: new Date(Date.now() - 4 * 24 * 60 * 60 * 1000),
    },
  ]);

  const createTicket = (subject: string, category: string, priority: string, message: string, userId: string) => {
    const newTicket: SupportTicket = {
      id: Math.random().toString(36).substr(2, 9),
      userId,
      subject,
      category,
      priority: priority as SupportTicket['priority'],
      status: 'open',
      messages: [
        {
          id: Math.random().toString(36).substr(2, 9),
          ticketId: '',
          userId,
          userName: 'You',
          message,
          createdAt: new Date(),
        },
      ],
      createdAt: new Date(),
      updatedAt: new Date(),
    };
    newTicket.messages[0].ticketId = newTicket.id;
    setTickets(prev => [newTicket, ...prev]);
    toast.success('Support ticket created successfully');
  };

  const updateTicketStatus = (ticketId: string, status: SupportTicket['status']) => {
    setTickets(prev =>
      prev.map(ticket =>
        ticket.id === ticketId
          ? { ...ticket, status, updatedAt: new Date() }
          : ticket
      )
    );
    toast.success('Ticket status updated');
  };

  const addTicketMessage = (ticketId: string, userId: string, userName: string, message: string) => {
    const newMessage: TicketMessage = {
      id: Math.random().toString(36).substr(2, 9),
      ticketId,
      userId,
      userName,
      message,
      createdAt: new Date(),
    };

    setTickets(prev =>
      prev.map(ticket =>
        ticket.id === ticketId
          ? {
              ...ticket,
              messages: [...ticket.messages, newMessage],
              updatedAt: new Date(),
            }
          : ticket
      )
    );
    toast.success('Message sent');
  };

  const getTicketsByUser = (userId: string) => {
    return tickets.filter(ticket => ticket.userId === userId);
  };

  const getTicketById = (ticketId: string) => {
    return tickets.find(ticket => ticket.id === ticketId);
  };

  return (
    <TicketContext.Provider
      value={{
        tickets,
        createTicket,
        updateTicketStatus,
        addTicketMessage,
        getTicketsByUser,
        getTicketById,
      }}
    >
      {children}
    </TicketContext.Provider>
  );
};

export const useTickets = () => {
  const context = useContext(TicketContext);
  if (!context) throw new Error('useTickets must be used within TicketProvider');
  return context;
};