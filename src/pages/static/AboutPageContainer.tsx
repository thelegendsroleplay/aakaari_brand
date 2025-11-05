import { AboutPage } from '../../components/pages/AboutPage';
import { Page } from '../../lib/types';

interface AboutPageContainerProps {
  onNavigate: (page: Page) => void;
}

export function AboutPageContainer({ onNavigate }: AboutPageContainerProps) {
  return <AboutPage onNavigate={onNavigate} />;
}
