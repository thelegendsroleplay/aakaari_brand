// Shop page specific demo data

export const shopCategories = [
  'Jackets',
  'Shirts', 
  'Pants',
  'Shoes',
  'Accessories'
];

export const shopSizes = [
  'XS', 'S', 'M', 'L', 'XL', 'XXL'
];

export const shopColors = [
  'Black',
  'White',
  'Navy',
  'Grey',
  'Blue',
  'Red',
  'Brown',
  'Green'
];

export const priceRanges = [
  { label: 'Under $50', min: 0, max: 50 },
  { label: '$50 - $100', min: 50, max: 100 },
  { label: '$100 - $200', min: 100, max: 200 },
  { label: '$200 - $500', min: 200, max: 500 },
  { label: 'Over $500', min: 500, max: Infinity },
];

export const sortOptions = [
  { value: 'featured', label: 'Featured' },
  { value: 'price-low', label: 'Price: Low to High' },
  { value: 'price-high', label: 'Price: High to Low' },
  { value: 'newest', label: 'Newest' },
  { value: 'rating', label: 'Top Rated' },
];
