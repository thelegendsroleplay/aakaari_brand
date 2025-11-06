// Static pages demo data

// About Page Data
export const aboutData = {
  hero: {
    title: 'About Our Brand',
    subtitle: 'Crafting premium men\'s fashion since 2010. We believe in quality, style, and sustainability.',
  },
  story: {
    title: 'Our Story',
    content: `Founded in 2010, we started with a simple mission: to create timeless, high-quality men's fashion 
    that combines classic style with modern sensibility. What began as a small workshop has grown into a global 
    brand trusted by thousands of customers worldwide.`,
  },
  stats: [
    { value: '15+', label: 'Years in Business' },
    { value: '50K+', label: 'Happy Customers' },
    { value: '100+', label: 'Products' },
    { value: '25', label: 'Countries Served' },
  ],
  values: [
    {
      icon: '🎯',
      title: 'Quality First',
      description: 'We use only the finest materials and craftsmanship to ensure every piece meets our high standards.',
    },
    {
      icon: '🌍',
      title: 'Sustainable',
      description: 'Committed to ethical sourcing and environmentally responsible manufacturing practices.',
    },
    {
      icon: '💡',
      title: 'Innovation',
      description: 'Constantly evolving our designs while staying true to timeless style principles.',
    },
    {
      icon: '❤️',
      title: 'Customer Focused',
      description: 'Your satisfaction is our priority. We stand behind every product we sell.',
    },
  ],
  team: [
    {
      name: 'Michael Chen',
      role: 'Founder & CEO',
      bio: '15 years of fashion industry experience',
      image: 'https://images.unsplash.com/photo-1714328564923-d4826427c991?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZm9ybWFsJTIwd2VhcnxlbnwxfHx8fDE3NjIzMjQwNDZ8MA&ixlib=rb-4.1.0&q=80&w=1080',
    },
    {
      name: 'Sarah Johnson',
      role: 'Creative Director',
      bio: 'Award-winning designer with global recognition',
      image: 'https://images.unsplash.com/photo-1656786779124-3eb10b7014a5?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwY2FzdWFsJTIwZmFzaGlvbnxlbnwxfHx8fDE3NjIzMjM1OTJ8MA&ixlib=rb-4.1.0&q=80&w=1080',
    },
    {
      name: 'David Martinez',
      role: 'Head of Production',
      bio: 'Expert in sustainable manufacturing',
      image: 'https://images.unsplash.com/photo-1635650805015-2fa50682873a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc3RyZWV0d2VhcnxlbnwxfHx8fDE3NjI0MTQxMDN8MA&ixlib=rb-4.1.0&q=80&w=1080',
    },
  ],
};

// Contact Page Data
export const contactData = {
  hero: {
    title: 'Get in Touch',
    subtitle: 'Have a question? We\'re here to help. Reach out to our team.',
  },
  info: [
    {
      icon: '📍',
      label: 'Address',
      value: '123 Fashion Avenue, New York, NY 10001, USA',
    },
    {
      icon: '📧',
      label: 'Email',
      value: 'support@mensafashion.com',
    },
    {
      icon: '📞',
      label: 'Phone',
      value: '+1 (555) 123-4567',
    },
    {
      icon: '🕐',
      label: 'Business Hours',
      value: 'Monday - Friday: 9AM - 6PM EST\nSaturday: 10AM - 4PM EST\nSunday: Closed',
    },
  ],
  socialLinks: [
    { platform: 'Facebook', url: '#', icon: 'f' },
    { platform: 'Instagram', url: '#', icon: '📷' },
    { platform: 'Twitter', url: '#', icon: '🐦' },
    { platform: 'LinkedIn', url: '#', icon: 'in' },
  ],
  departments: [
    { name: 'Customer Service', email: 'support@mensafashion.com' },
    { name: 'Sales', email: 'sales@mensafashion.com' },
    { name: 'Press & Media', email: 'press@mensafashion.com' },
    { name: 'Partnerships', email: 'partners@mensafashion.com' },
  ],
};

// FAQ Page Data
export const faqData = {
  hero: {
    title: 'Frequently Asked Questions',
    subtitle: 'Find answers to common questions about our products and services.',
  },
  categories: [
    { id: 'all', name: 'All Questions', icon: '📋' },
    { id: 'orders', name: 'Orders & Shipping', icon: '📦' },
    { id: 'returns', name: 'Returns & Exchanges', icon: '↩️' },
    { id: 'products', name: 'Products', icon: '👔' },
    { id: 'account', name: 'Account', icon: '👤' },
    { id: 'payments', name: 'Payments', icon: '💳' },
  ],
  questions: [
    {
      category: 'orders',
      question: 'How long does shipping take?',
      answer: 'Standard shipping typically takes 5-7 business days. Express shipping takes 2-3 business days, and overnight shipping arrives the next business day. All orders are processed within 24 hours of receipt.',
    },
    {
      category: 'orders',
      question: 'Do you ship internationally?',
      answer: 'Yes, we ship to over 25 countries worldwide. International shipping times vary by location but typically take 7-14 business days. Customs fees may apply depending on your country.',
    },
    {
      category: 'orders',
      question: 'How can I track my order?',
      answer: 'Once your order ships, you\'ll receive a tracking number via email. You can also track your order by logging into your account and viewing your order history.',
    },
    {
      category: 'returns',
      question: 'What is your return policy?',
      answer: 'We offer a 30-day return policy for unworn, unwashed items with original tags attached. Returns are free for domestic orders. Simply initiate a return through your account dashboard.',
    },
    {
      category: 'returns',
      question: 'How do I exchange an item?',
      answer: 'To exchange an item, please initiate a return and place a new order for the desired item. This ensures you get your preferred item as quickly as possible.',
    },
    {
      category: 'returns',
      question: 'Can I return customized items?',
      answer: 'Unfortunately, customized items cannot be returned or exchanged unless there is a manufacturing defect. Please review your customization carefully before placing your order.',
    },
    {
      category: 'products',
      question: 'How do I find my size?',
      answer: 'Each product page includes a detailed size guide. Click the "Size Guide" button on any product page to view measurements. We recommend measuring a similar item you own for the best fit.',
    },
    {
      category: 'products',
      question: 'Are your products sustainable?',
      answer: 'Yes, we\'re committed to sustainability. We use ethically sourced materials, environmentally responsible manufacturing processes, and recyclable packaging whenever possible.',
    },
    {
      category: 'products',
      question: 'What is the product customizer?',
      answer: 'Our product customizer allows you to personalize select items with custom text, colors, and other options. Look for the "Customize" option on eligible product pages.',
    },
    {
      category: 'account',
      question: 'How do I create an account?',
      answer: 'Click the "Sign Up" button in the top right corner. Fill in your information to create an account. You can also sign up using your Google, Facebook, or Apple account.',
    },
    {
      category: 'account',
      question: 'What are the benefits of creating an account?',
      answer: 'Account holders enjoy order tracking, wishlist features, faster checkout, exclusive offers, and access to our loyalty rewards program.',
    },
    {
      category: 'account',
      question: 'How do I reset my password?',
      answer: 'Click "Forgot Password" on the login page. Enter your email address and we\'ll send you instructions to reset your password.',
    },
    {
      category: 'payments',
      question: 'What payment methods do you accept?',
      answer: 'We accept all major credit cards (Visa, Mastercard, American Express), PayPal, Apple Pay, and Google Pay. All payments are securely processed.',
    },
    {
      category: 'payments',
      question: 'Is my payment information secure?',
      answer: 'Yes, we use industry-standard SSL encryption to protect your payment information. We never store your full credit card details on our servers.',
    },
    {
      category: 'payments',
      question: 'Do you offer payment plans?',
      answer: 'Currently, we require full payment at checkout. However, you may use services like PayPal Credit if available in your region.',
    },
  ],
};

// Shipping Page Data
export const shippingData = {
  hero: {
    title: 'Shipping Information',
    subtitle: 'Fast, reliable shipping to your doorstep.',
  },
  methods: [
    {
      name: 'Standard Shipping',
      price: 'Free on orders over $50',
      duration: '5-7 business days',
      details: 'Our most economical option. Perfect for non-urgent orders. Tracking included.',
    },
    {
      name: 'Express Shipping',
      price: '$15.00',
      duration: '2-3 business days',
      details: 'Faster delivery for when you need your order sooner. Full tracking and insurance included.',
    },
    {
      name: 'Overnight Shipping',
      price: '$25.00',
      duration: 'Next business day',
      details: 'Get your order as fast as possible. Orders placed before 2PM EST ship same day.',
    },
  ],
  international: {
    title: 'International Shipping',
    content: 'We ship to over 25 countries worldwide. International shipping times vary by location but typically take 7-14 business days. Customs fees and import duties may apply and are the responsibility of the customer.',
    countries: ['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Italy', 'Spain', 'Japan', '+ 16 more countries'],
  },
  policies: [
    {
      title: 'Processing Time',
      content: 'All orders are processed within 24 hours of receipt. Orders placed on weekends or holidays will be processed the next business day.',
    },
    {
      title: 'Tracking',
      content: 'Once your order ships, you\'ll receive a tracking number via email. You can also track your order through your account dashboard.',
    },
    {
      title: 'Lost or Damaged Items',
      content: 'If your order arrives damaged or goes missing in transit, please contact us immediately. We\'ll work with the carrier and send you a replacement or refund.',
    },
  ],
};

// Privacy Policy Data
export const privacyData = {
  lastUpdated: 'November 6, 2025',
  sections: [
    {
      title: 'Information We Collect',
      content: 'We collect information you provide directly, such as name, email, shipping address, and payment information. We also collect usage data through cookies and analytics tools.',
    },
    {
      title: 'How We Use Your Information',
      content: 'We use your information to process orders, provide customer service, send marketing communications (with your consent), and improve our services.',
    },
    {
      title: 'Information Sharing',
      content: 'We do not sell your personal information. We only share data with service providers necessary to operate our business, such as payment processors and shipping carriers.',
    },
    {
      title: 'Data Security',
      content: 'We implement industry-standard security measures to protect your information, including SSL encryption and secure payment processing.',
    },
    {
      title: 'Your Rights',
      content: 'You have the right to access, correct, or delete your personal information. Contact us to exercise these rights.',
    },
  ],
};

// Terms of Service Data
export const termsData = {
  lastUpdated: 'November 6, 2025',
  sections: [
    {
      title: 'Acceptance of Terms',
      content: 'By accessing and using this website, you accept and agree to be bound by these terms and conditions.',
    },
    {
      title: 'Products and Pricing',
      content: 'We strive to ensure all product descriptions and prices are accurate. However, errors may occur and we reserve the right to correct any inaccuracies.',
    },
    {
      title: 'Orders and Payment',
      content: 'All orders are subject to acceptance. We reserve the right to refuse or cancel any order. Payment must be received in full before orders are shipped.',
    },
    {
      title: 'Intellectual Property',
      content: 'All content on this website, including images, text, and logos, is the property of our company and protected by copyright laws.',
    },
    {
      title: 'Limitation of Liability',
      content: 'We are not liable for any indirect, incidental, or consequential damages arising from the use of our website or products.',
    },
  ],
};

// General CTA
export const ctaData = {
  title: 'Ready to Upgrade Your Wardrobe?',
  message: 'Discover our latest collection of premium men\'s fashion.',
  buttonText: 'Shop Now',
  buttonLink: '/shop',
};
