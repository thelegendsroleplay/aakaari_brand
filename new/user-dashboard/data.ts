// User Dashboard demo data with images

export const userData = {
  id: 'user-12345',
  firstName: 'John',
  lastName: 'Smith',
  email: 'john.smith@email.com',
  phone: '+1 (555) 123-4567',
  avatar: null,
  joinDate: '2024-03-15',
  tier: 'Gold',
  points: 2450,
  nextTierPoints: 3000,
};

export const dashboardStats = [
  {
    id: 'orders',
    label: 'Total Orders',
    value: '12',
    icon: '📦',
    color: '#000',
  },
  {
    id: 'spent',
    label: 'Total Spent',
    value: '$1,247.89',
    icon: '💳',
    color: '#4338ca',
  },
  {
    id: 'wishlist',
    label: 'Wishlist Items',
    value: '8',
    icon: '❤️',
    color: '#ef4444',
  },
  {
    id: 'points',
    label: 'Reward Points',
    value: '2,450',
    icon: '⭐',
    color: '#f59e0b',
  },
];

export const recentOrders = [
  {
    id: 'ORD-10234',
    date: '2025-11-05',
    status: 'delivered',
    total: 299.97,
    items: 3,
    trackingNumber: 'TRK-789456123',
    products: [
      {
        id: 'prod-1',
        name: 'Classic Black T-Shirt',
        image: 'https://images.unsplash.com/photo-1516082669438-2d2bb5082626?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwdHNoaXJ0fGVufDF8fHx8MTc2MjQxNDMzMHww&ixlib=rb-4.1.0&q=80&w=1080',
        price: 29.99,
        quantity: 2,
      },
      {
        id: 'prod-2',
        name: 'Casual Baseball Cap',
        image: 'https://images.unsplash.com/photo-1558466124-a150281f04cb?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwY2FwfGVufDF8fHx8MTc2MjQxNDMzM3ww&ixlib=rb-4.1.0&q=80&w=1080',
        price: 34.99,
        quantity: 1,
      },
    ],
  },
  {
    id: 'ORD-10201',
    date: '2025-10-28',
    status: 'delivered',
    total: 189.99,
    items: 1,
    trackingNumber: 'TRK-654321987',
    products: [
      {
        id: 'prod-3',
        name: 'Leather Chelsea Boots',
        image: 'https://images.unsplash.com/photo-1585557488780-78b585ba3117?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwYm9vdHN8ZW58MXx8fHwxNzYyNDE0MzMyfDA&ixlib=rb-4.1.0&q=80&w=1080',
        price: 189.99,
        quantity: 1,
      },
    ],
  },
  {
    id: 'ORD-10185',
    date: '2025-10-15',
    status: 'delivered',
    total: 449.97,
    items: 2,
    trackingNumber: 'TRK-321654789',
    products: [
      {
        id: 'prod-4',
        name: 'Wool Blazer',
        image: 'https://images.unsplash.com/photo-1598915850252-fb07ad1e6768?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwYmxhemVyfGVufDF8fHx8MTc2MjM2NTc4NHww&ixlib=rb-4.1.0&q=80&w=1080',
        price: 299.99,
        quantity: 1,
      },
      {
        id: 'prod-5',
        name: 'White Sneakers',
        image: 'https://images.unsplash.com/photo-1672622012959-45355279c93e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc25lYWtlcnN8ZW58MXx8fHwxNzYyNDE0MzMxfDA&ixlib=rb-4.1.0&q=80&w=1080',
        price: 149.99,
        quantity: 1,
      },
    ],
  },
];

export const savedAddresses = [
  {
    id: 'addr-1',
    type: 'home',
    isDefault: true,
    fullName: 'John Smith',
    street: '123 Main Street',
    apartment: 'Apt 4B',
    city: 'New York',
    state: 'NY',
    zipCode: '10001',
    country: 'United States',
    phone: '+1 (555) 123-4567',
  },
  {
    id: 'addr-2',
    type: 'work',
    isDefault: false,
    fullName: 'John Smith',
    street: '456 Business Ave',
    apartment: 'Suite 200',
    city: 'New York',
    state: 'NY',
    zipCode: '10002',
    country: 'United States',
    phone: '+1 (555) 987-6543',
  },
];

export const paymentMethods = [
  {
    id: 'card-1',
    type: 'visa',
    isDefault: true,
    last4: '4242',
    expiryMonth: '12',
    expiryYear: '2026',
    holderName: 'John Smith',
    nickname: 'Personal Card',
  },
  {
    id: 'card-2',
    type: 'mastercard',
    isDefault: false,
    last4: '8888',
    expiryMonth: '08',
    expiryYear: '2027',
    holderName: 'John Smith',
    nickname: 'Business Card',
  },
];

export const wishlistPreview = [
  {
    id: 'wish-1',
    name: 'Premium Denim Jeans',
    price: 119.99,
    image: 'https://images.unsplash.com/photo-1714143136372-ddaf8b606da7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZGVuaW0lMjBqZWFuc3xlbnwxfHx8fDE3NjIzMTczNjh8MA&ixlib=rb-4.1.0&q=80&w=1080',
    inStock: true,
  },
  {
    id: 'wish-2',
    name: 'Leather Wallet',
    price: 79.99,
    image: 'https://images.unsplash.com/photo-1675582122314-cabef1d757ec?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwd2FsbGV0fGVufDF8fHx8MTc2MjQxNDMzM3ww&ixlib=rb-4.1.0&q=80&w=1080',
    inStock: true,
  },
  {
    id: 'wish-3',
    name: 'Grey Hoodie',
    price: 89.99,
    image: 'https://images.unsplash.com/photo-1688111421205-a0a85415b224?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwaG9vZGllfGVufDF8fHx8MTc2MjQxNDMzMnww&ixlib=rb-4.1.0&q=80&w=1080',
    inStock: false,
  },
  {
    id: 'wish-4',
    name: 'Formal Suit',
    price: 499.99,
    image: 'https://images.unsplash.com/photo-1679101893310-9b9adb4b733b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc3VpdHxlbnwxfHx8fDE3NjI0MTQxMDR8MA&ixlib=rb-4.1.0&q=80&w=1080',
    inStock: true,
  },
];

export const loyaltyProgram = {
  currentTier: 'Gold',
  currentPoints: 2450,
  nextTier: 'Platinum',
  pointsToNextTier: 550,
  totalPointsNeeded: 3000,
  benefits: {
    bronze: ['5% off all orders', 'Early sale access'],
    silver: ['10% off all orders', 'Free standard shipping', 'Birthday gift'],
    gold: ['15% off all orders', 'Free express shipping', 'Exclusive products', 'Priority support'],
    platinum: ['20% off all orders', 'Free overnight shipping', 'Personal stylist', 'VIP events'],
  },
};

export const availableRewards = [
  {
    id: 'reward-1',
    name: '$10 Off Coupon',
    points: 500,
    description: '$10 off your next purchase',
    category: 'discount',
    expiryDays: 30,
  },
  {
    id: 'reward-2',
    name: 'Free Shipping',
    points: 300,
    description: 'Free shipping on your next order',
    category: 'shipping',
    expiryDays: 60,
  },
  {
    id: 'reward-3',
    name: '$25 Off Coupon',
    points: 1000,
    description: '$25 off orders over $100',
    category: 'discount',
    expiryDays: 30,
  },
  {
    id: 'reward-4',
    name: 'Exclusive Product Access',
    points: 1500,
    description: 'Early access to new collections',
    category: 'exclusive',
    expiryDays: 90,
  },
];

export const pointsHistory = [
  {
    id: 'points-1',
    date: '2025-11-05',
    description: 'Order #ORD-10234',
    points: +300,
    type: 'earned',
  },
  {
    id: 'points-2',
    date: '2025-11-04',
    description: 'Product review',
    points: +50,
    type: 'earned',
  },
  {
    id: 'points-3',
    date: '2025-11-01',
    description: 'Redeemed: $10 off coupon',
    points: -500,
    type: 'redeemed',
  },
  {
    id: 'points-4',
    date: '2025-10-28',
    description: 'Order #ORD-10201',
    points: +190,
    type: 'earned',
  },
  {
    id: 'points-5',
    date: '2025-10-25',
    description: 'Referral bonus',
    points: +100,
    type: 'earned',
  },
];

export const notifications = [
  {
    id: 'notif-1',
    type: 'order',
    title: 'Order Delivered',
    message: 'Your order #ORD-10234 has been delivered',
    date: '2025-11-05',
    read: false,
    icon: '📦',
  },
  {
    id: 'notif-2',
    type: 'promo',
    title: 'Flash Sale Alert',
    message: '24-hour flash sale on winter collection - Up to 50% off!',
    date: '2025-11-04',
    read: false,
    icon: '🔥',
  },
  {
    id: 'notif-3',
    type: 'wishlist',
    title: 'Price Drop',
    message: 'Leather Wallet is now 20% off',
    date: '2025-11-03',
    read: true,
    icon: '💰',
  },
  {
    id: 'notif-4',
    type: 'points',
    title: 'Points Earned',
    message: 'You earned 300 points from your recent order',
    date: '2025-11-05',
    read: true,
    icon: '⭐',
  },
];

export const accountSettings = {
  email: {
    current: 'john.smith@email.com',
    verified: true,
  },
  phone: {
    current: '+1 (555) 123-4567',
    verified: true,
  },
  newsletter: true,
  smsNotifications: true,
  orderUpdates: true,
  promotions: true,
  priceAlerts: true,
  twoFactorAuth: false,
};

export const orderHistory = {
  total: 12,
  delivered: 9,
  processing: 2,
  cancelled: 1,
  totalSpent: 1247.89,
  averageOrderValue: 103.99,
};

export const recentlyViewed = [
  {
    id: 'viewed-1',
    name: 'Aviator Sunglasses',
    price: 129.99,
    image: 'https://images.unsplash.com/photo-1708702101923-f06afcb4e2b7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc3VuZ2xhc3Nlc3xlbnwxfHx8fDE3NjI0MTQxMDR8MA&ixlib=rb-4.1.0&q=80&w=1080',
  },
  {
    id: 'viewed-2',
    name: 'Leather Belt',
    price: 59.99,
    image: 'https://images.unsplash.com/photo-1684510334550-0c4fa8aaffd1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwYmVsdHxlbnwxfHx8fDE3NjI0MTQzMzN8MA&ixlib=rb-4.1.0&q=80&w=1080',
  },
  {
    id: 'viewed-3',
    name: 'Luxury Watch',
    price: 899.99,
    image: 'https://images.unsplash.com/photo-1751437797070-54ac95740dac?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwbHV4dXJ5JTIwd2F0Y2h8ZW58MXx8fHwxNzYyNDE0MTAzfDA&ixlib=rb-4.1.0&q=80&w=1080',
  },
];
