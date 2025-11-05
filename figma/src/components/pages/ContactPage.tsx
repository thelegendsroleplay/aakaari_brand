import { useState } from 'react';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Textarea } from '../ui/textarea';
import { Card } from '../ui/card';
import { Mail, Phone, MapPin, Clock } from 'lucide-react';
import { toast } from 'sonner@2.0.3';

export function ContactPage() {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: '',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    toast.success('Message sent! We\'ll get back to you soon.');
    setFormData({ name: '', email: '', subject: '', message: '' });
  };

  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-6xl mx-auto">
        {/* Header */}
        <div className="text-center mb-12">
          <h1 className="text-5xl mb-4">Contact Us</h1>
          <p className="text-xl text-gray-600">
            We'd love to hear from you. Send us a message and we'll respond as soon as possible.
          </p>
        </div>

        <div className="grid md:grid-cols-2 gap-12">
          {/* Contact Form */}
          <Card className="p-8">
            <h2 className="text-2xl mb-6">Send Us a Message</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <Label htmlFor="name">Name</Label>
                <Input
                  id="name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </div>

              <div>
                <Label htmlFor="email">Email</Label>
                <Input
                  id="email"
                  type="email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  required
                />
              </div>

              <div>
                <Label htmlFor="subject">Subject</Label>
                <Input
                  id="subject"
                  value={formData.subject}
                  onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                  required
                />
              </div>

              <div>
                <Label htmlFor="message">Message</Label>
                <Textarea
                  id="message"
                  rows={5}
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  required
                />
              </div>

              <Button type="submit" className="w-full">
                Send Message
              </Button>
            </form>
          </Card>

          {/* Contact Information */}
          <div className="space-y-8">
            <div>
              <h2 className="text-2xl mb-6">Get in Touch</h2>
              <p className="text-gray-600 mb-8">
                Have a question? We're here to help. Reach out through any of these channels.
              </p>
            </div>

            <Card className="p-6">
              <div className="flex items-start gap-4">
                <div className="bg-black text-white p-3 rounded-lg">
                  <Mail className="h-6 w-6" />
                </div>
                <div>
                  <h3 className="mb-1">Email</h3>
                  <p className="text-gray-600">support@fashionmen.com</p>
                  <p className="text-gray-600">sales@fashionmen.com</p>
                </div>
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-start gap-4">
                <div className="bg-black text-white p-3 rounded-lg">
                  <Phone className="h-6 w-6" />
                </div>
                <div>
                  <h3 className="mb-1">Phone</h3>
                  <p className="text-gray-600">+1 (555) 123-4567</p>
                  <p className="text-gray-600">Mon-Fri: 9AM - 6PM EST</p>
                </div>
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-start gap-4">
                <div className="bg-black text-white p-3 rounded-lg">
                  <MapPin className="h-6 w-6" />
                </div>
                <div>
                  <h3 className="mb-1">Address</h3>
                  <p className="text-gray-600">
                    123 Fashion Street<br />
                    New York, NY 10001<br />
                    United States
                  </p>
                </div>
              </div>
            </Card>

            <Card className="p-6">
              <div className="flex items-start gap-4">
                <div className="bg-black text-white p-3 rounded-lg">
                  <Clock className="h-6 w-6" />
                </div>
                <div>
                  <h3 className="mb-1">Business Hours</h3>
                  <p className="text-gray-600">
                    Monday - Friday: 9AM - 6PM<br />
                    Saturday: 10AM - 4PM<br />
                    Sunday: Closed
                  </p>
                </div>
              </div>
            </Card>
          </div>
        </div>
      </div>
    </div>
  );
}
