import { LoginPage } from '../../components/auth/LoginPage';
import { Page } from '../../lib/types';

interface LoginPageContainerProps {
  onNavigate: (page: Page) => void;
  onLogin: (email: string, password: string) => void;
}

export function LoginPageContainer({ onNavigate, onLogin }: LoginPageContainerProps) {
  return <LoginPage onNavigate={onNavigate} onLogin={onLogin} />;
}
