export interface Product {
  id: string;
  name: string;
  slug: string;
  description: string;
  price: number;
  salePrice?: number;
  category: string;
  subcategory?: string;
  images: string[];
  sizes: string[];
  colors: string[];
  materials: string[];
  stock: number;
  rating: number;
  reviewCount: number;
  featured: boolean;
  newArrival: boolean;
  bestseller: boolean;
  tags: string[];
  sku: string;
  productType: 'simple' | 'variable'; // Like WooCommerce
  variations?: ProductVariation[]; // For variable products
  attributes?: ProductAttribute[]; // Define variation attributes
}

export interface ProductAttribute {
  name: string; // e.g., "Color", "Size"
  values: string[]; // e.g., ["Red", "Blue"], ["S", "M", "L"]
  variation: boolean; // Used for variations
  visible: boolean; // Visible on product page
}

export interface ProductVariation {
  id: string;
  attributes: { [key: string]: string }; // e.g., { "Color": "Red", "Size": "M" }
  sku: string;
  price: number;
  salePrice?: number;
  stock: number;
  image?: string; // Variation-specific image
  enabled: boolean;
}

export interface ProductVariant {
  productId: string;
  size: string;
  color: string;
  stock: number;
  sku: string;
}

export interface CartItem {
  product: Product;
  quantity: number;
  size: string;
  color: string;
  customization?: {
    monogram?: string;
    customText?: string;
  };
}

export interface WishlistItem {
  product: Product;
  addedAt: Date;
}

export interface User {
  id: string;
  email: string;
  name: string;
  phone?: string;
  avatar?: string;
  role: 'customer' | 'admin';
  emailVerified: boolean;
  phoneVerified: boolean;
  twoFactorEnabled: boolean;
}

export interface Address {
  id: string;
  userId: string;
  type: 'shipping' | 'billing';
  name: string;
  addressLine1: string;
  addressLine2?: string;
  city: string;
  state: string;
  zipCode: string;
  country: string;
  phone: string;
  isDefault: boolean;
}

export interface Order {
  id: string;
  userId: string;
  orderNumber: string;
  items: CartItem[];
  subtotal: number;
  tax: number;
  shippingCost: number;
  discount: number;
  total: number;
  status: 'pending' | 'processing' | 'shipped' | 'delivered' | 'cancelled' | 'refunded';
  paymentMethod: string;
  paymentStatus: 'pending' | 'completed' | 'failed';
  shippingAddress: Address;
  billingAddress: Address;
  trackingNumber?: string;
  createdAt: Date;
  updatedAt: Date;
  estimatedDelivery?: Date;
}

export interface Review {
  id: string;
  productId: string;
  userId: string;
  userName: string;
  userAvatar?: string;
  rating: number;
  title: string;
  comment: string;
  images?: string[];
  verified: boolean;
  helpful: number;
  createdAt: Date;
}

export interface Coupon {
  id: string;
  code: string;
  type: 'percentage' | 'fixed' | 'free_shipping';
  value: number;
  minPurchase?: number;
  maxDiscount?: number;
  expiresAt?: Date;
  usageLimit?: number;
  usageCount: number;
  active: boolean;
}

export interface SupportTicket {
  id: string;
  userId: string;
  subject: string;
  category: string;
  priority: 'low' | 'medium' | 'high';
  status: 'open' | 'in_progress' | 'resolved' | 'closed';
  messages: TicketMessage[];
  createdAt: Date;
  updatedAt: Date;
}

export interface TicketMessage {
  id: string;
  ticketId: string;
  userId: string;
  userName: string;
  message: string;
  attachments?: string[];
  createdAt: Date;
}

export interface FilterOptions {
  categories: string[];
  priceRange: [number, number];
  sizes: string[];
  colors: string[];
  materials: string[];
  rating: number;
  sortBy: 'popularity' | 'price-low' | 'price-high' | 'newest' | 'rating';
}