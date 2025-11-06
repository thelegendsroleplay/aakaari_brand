// Product page specific demo data

export const productTabs = [
  'Description',
  'Specifications',
  'Shipping & Returns',
  'Reviews'
];

export const sizeGuideData = {
  'Shirts': [
    { size: 'S', chest: '36-38', waist: '30-32', length: '28' },
    { size: 'M', chest: '38-40', waist: '32-34', length: '29' },
    { size: 'L', chest: '40-42', waist: '34-36', length: '30' },
    { size: 'XL', chest: '42-44', waist: '36-38', length: '31' },
    { size: 'XXL', chest: '44-46', waist: '38-40', length: '32' },
  ],
  'Pants': [
    { size: '28', waist: '28', hip: '36', inseam: '30' },
    { size: '30', waist: '30', hip: '38', inseam: '30' },
    { size: '32', waist: '32', hip: '40', inseam: '32' },
    { size: '34', waist: '34', hip: '42', inseam: '32' },
    { size: '36', waist: '36', hip: '44', inseam: '32' },
  ],
  'Shoes': [
    { size: '7', us: '7', uk: '6', eu: '40', cm: '25' },
    { size: '8', us: '8', uk: '7', eu: '41', cm: '26' },
    { size: '9', us: '9', uk: '8', eu: '42', cm: '27' },
    { size: '10', us: '10', uk: '9', eu: '43', cm: '28' },
    { size: '11', us: '11', uk: '10', eu: '44', cm: '29' },
    { size: '12', us: '12', uk: '11', eu: '45', cm: '30' },
  ],
};

export const shippingInfo = {
  standard: {
    name: 'Standard Shipping',
    duration: '5-7 business days',
    cost: 'Free on orders over $50',
  },
  express: {
    name: 'Express Shipping',
    duration: '2-3 business days',
    cost: '$15.00',
  },
  overnight: {
    name: 'Overnight Shipping',
    duration: '1 business day',
    cost: '$25.00',
  },
};

export const returnPolicy = {
  duration: '30 days',
  conditions: [
    'Items must be unworn and unwashed',
    'Original tags must be attached',
    'Original packaging required',
    'Proof of purchase needed'
  ],
  nonReturnable: [
    'Customized items',
    'Final sale items',
    'Underwear and swimwear'
  ],
};
