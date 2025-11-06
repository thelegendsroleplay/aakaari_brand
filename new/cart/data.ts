// Cart page specific demo data

export const cartMessages = {
  empty: {
    title: 'Your cart is empty',
    message: 'Add some items to get started!',
    buttonText: 'Continue Shopping',
  },
  freeShipping: {
    threshold: 50,
    message: 'You qualify for FREE shipping!',
  },
  almostFreeShipping: (remaining: number) => 
    `Add $${remaining.toFixed(2)} more to get FREE shipping!`,
};

export const cartCoupons = [
  {
    id: 'SAVE10',
    description: '10% off your first order',
    minAmount: 0,
  },
  {
    id: 'SAVE25',
    description: '$25 off orders over $100',
    minAmount: 100,
  },
  {
    id: 'FREESHIP',
    description: 'Free shipping on any order',
    minAmount: 0,
  },
];

export const paymentMethods = [
  {
    id: 'card',
    name: 'Credit/Debit Card',
    icon: '💳',
  },
  {
    id: 'paypal',
    name: 'PayPal',
    icon: '🅿️',
  },
  {
    id: 'apple',
    name: 'Apple Pay',
    icon: '🍎',
  },
  {
    id: 'google',
    name: 'Google Pay',
    icon: 'G',
  },
];
