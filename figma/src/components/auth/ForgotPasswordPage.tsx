import { useState } from 'react';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Card } from '../ui/card';
import { Page } from '../../lib/types';
import { Mail, ArrowLeft, CheckCircle } from 'lucide-react';

interface ForgotPasswordPageProps {
  onNavigate: (page: Page) => void;
}

export function ForgotPasswordPage({ onNavigate }: ForgotPasswordPageProps) {
  const [email, setEmail] = useState('');
  const [emailSent, setEmailSent] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Simulate sending reset email
    setEmailSent(true);
  };

  if (emailSent) {
    return (
      <div className="min-h-[80vh] flex items-center justify-center px-4 py-12">
        <Card className="w-full max-w-md p-8 text-center">
          <div className="flex justify-center mb-4">
            <div className="bg-green-100 p-3 rounded-full">
              <CheckCircle className="h-12 w-12 text-green-600" />
            </div>
          </div>
          
          <h1 className="text-3xl mb-2">Check Your Email</h1>
          <p className="text-gray-600 mb-6">
            We've sent a password reset link to <strong>{email}</strong>
          </p>
          
          <div className="space-y-3">
            <Button onClick={() => onNavigate('login')} className="w-full">
              Back to Sign In
            </Button>
            <button
              onClick={() => setEmailSent(false)}
              className="text-sm hover:underline"
            >
              Didn't receive the email? Click to resend
            </button>
          </div>
        </Card>
      </div>
    );
  }

  return (
    <div className="min-h-[80vh] flex items-center justify-center px-4 py-12">
      <Card className="w-full max-w-md p-8">
        <button
          onClick={() => onNavigate('login')}
          className="flex items-center text-gray-600 hover:text-black mb-6"
        >
          <ArrowLeft className="h-4 w-4 mr-2" />
          Back to Sign In
        </button>

        <div className="text-center mb-8">
          <h1 className="text-3xl mb-2">Forgot Password?</h1>
          <p className="text-gray-600">
            No worries, we'll send you reset instructions
          </p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <Label htmlFor="email">Email Address</Label>
            <div className="relative mt-1">
              <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
              <Input
                id="email"
                type="email"
                placeholder="your@email.com"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="pl-10"
                required
              />
            </div>
          </div>

          <Button type="submit" className="w-full">
            Reset Password
          </Button>
        </form>
      </Card>
    </div>
  );
}
