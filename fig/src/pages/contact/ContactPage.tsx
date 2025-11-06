import React from 'react';
import { Mail, Phone, MapPin } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Input } from '../../components/ui/input';
import { Label } from '../../components/ui/label';
import { Textarea } from '../../components/ui/textarea';
import './contact.css';

export const ContactPage: React.FC = () => {
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
  };

  return (
    <div className="contact-page">
      <div className="contact-container">
        <h1>Contact Us</h1>
        <p className="subtitle">Get in touch with our team</p>

        <div className="contact-grid">
          <div className="contact-info">
            <h2>Get In Touch</h2>
            <div className="info-items">
              <div className="info-item">
                <Mail className="w-5 h-5" />
                <div>
                  <p>Email</p>
                  <span>support@mensfashion.com</span>
                </div>
              </div>
              <div className="info-item">
                <Phone className="w-5 h-5" />
                <div>
                  <p>Phone</p>
                  <span>+1 (555) 123-4567</span>
                </div>
              </div>
              <div className="info-item">
                <MapPin className="w-5 h-5" />
                <div>
                  <p>Address</p>
                  <span>123 Fashion Street, NY 10001</span>
                </div>
              </div>
            </div>
          </div>

          <form onSubmit={handleSubmit} className="contact-form">
            <div className="form-field">
              <Label>Name</Label>
              <Input placeholder="Your name" />
            </div>
            <div className="form-field">
              <Label>Email</Label>
              <Input type="email" placeholder="your@email.com" />
            </div>
            <div className="form-field">
              <Label>Message</Label>
              <Textarea placeholder="How can we help you?" rows={5} />
            </div>
            <Button type="submit" className="w-full">Send Message</Button>
          </form>
        </div>
      </div>
    </div>
  );
};
