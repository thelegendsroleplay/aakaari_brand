// Checkout page specific demo data

export const checkoutSteps = [
  { id: 1, name: 'Shipping', description: 'Enter your shipping address' },
  { id: 2, name: 'Payment', description: 'Choose your payment method' },
  { id: 3, name: 'Review', description: 'Review your order' },
];

export const shippingMethods = [
  {
    id: 'standard',
    name: 'Standard Shipping',
    description: '5-7 business days',
    price: 0,
    estimatedDays: '5-7',
  },
  {
    id: 'express',
    name: 'Express Shipping',
    description: '2-3 business days',
    price: 15.00,
    estimatedDays: '2-3',
  },
  {
    id: 'overnight',
    name: 'Overnight Shipping',
    description: 'Next business day',
    price: 25.00,
    estimatedDays: '1',
  },
];

export const paymentMethods = [
  {
    id: 'credit-card',
    name: 'Credit Card',
    description: 'Pay with Visa, Mastercard, or Amex',
    icon: '💳',
  },
  {
    id: 'paypal',
    name: 'PayPal',
    description: 'Pay securely with your PayPal account',
    icon: '🅿️',
  },
  {
    id: 'apple-pay',
    name: 'Apple Pay',
    description: 'Fast and secure payment with Apple Pay',
    icon: '🍎',
  },
  {
    id: 'google-pay',
    name: 'Google Pay',
    description: 'Quick checkout with Google Pay',
    icon: 'G',
  },
];

export const countries = [
  'United States',
  'Canada',
  'United Kingdom',
  'Australia',
  'Germany',
  'France',
  'Italy',
  'Spain',
  'Japan',
  'Other'
];

export const usStates = [
  'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
  'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
  'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
  'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
  'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
];

export const securityBadges = [
  { name: 'SSL Secured', icon: '🔒' },
  { name: 'PCI Compliant', icon: '✓' },
  { name: 'Money Back Guarantee', icon: '💰' },
];
