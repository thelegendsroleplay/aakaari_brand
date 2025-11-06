import { FAQPage } from '../../components/pages/FAQPage';
import { Page } from '../../lib/types';

interface FAQPageContainerProps {
  onNavigate: (page: Page) => void;
}

export function FAQPageContainer({ onNavigate }: FAQPageContainerProps) {
  return <FAQPage onNavigate={onNavigate} />;
}
