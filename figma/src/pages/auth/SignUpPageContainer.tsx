import { SignUpPage } from '../../components/auth/SignUpPage';
import { Page } from '../../lib/types';

interface SignUpPageContainerProps {
  onNavigate: (page: Page) => void;
  onSignUp: (name: string, email: string, password: string) => void;
}

export function SignUpPageContainer({ onNavigate, onSignUp }: SignUpPageContainerProps) {
  return <SignUpPage onNavigate={onNavigate} onSignUp={onSignUp} />;
}
