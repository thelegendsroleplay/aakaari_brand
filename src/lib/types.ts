// Type definitions for the e-commerce application

export interface Product {
  id: string;
  name: string;
  description: string;
  price: number;
  category: string;
  image: string;
  images: string[];
  sizes: string[];
  colors: string[];
  inStock: boolean;
  isCustomizable: boolean;
  customizationOptions?: CustomizationOption[];
  rating: number;
  reviews: number;
}

export interface CustomizationOption {
  id: string;
  name: string;
  type: 'text' | 'select' | 'color';
  options?: string[];
  price?: number;
}

export interface CartItem {
  product: Product;
  quantity: number;
  size: string;
  color: string;
  customization?: Record<string, string>;
}

export interface Order {
  id: string;
  userId: string;
  items: CartItem[];
  total: number;
  status: 'pending' | 'processing' | 'shipped' | 'delivered' | 'cancelled';
  paymentStatus: 'pending' | 'paid' | 'failed';
  paymentMethod: string;
  shippingAddress: Address;
  createdAt: string;
  updatedAt: string;
}

export interface Address {
  fullName: string;
  street: string;
  city: string;
  state: string;
  zipCode: string;
  country: string;
  phone: string;
}

export interface User {
  id: string;
  email: string;
  name: string;
  role: 'customer' | 'admin';
  avatar?: string;
  addresses: Address[];
  createdAt: string;
}

export interface AnalyticsData {
  totalRevenue: number;
  totalOrders: number;
  totalCustomers: number;
  totalProducts: number;
  revenueByMonth: { month: string; revenue: number }[];
  topProducts: { product: Product; sales: number }[];
  ordersByStatus: { status: string; count: number }[];
}

export interface Coupon {
  id: string;
  code: string;
  discountType: 'percentage' | 'fixed';
  discountValue: number;
  minPurchase?: number;
  maxDiscount?: number;
  validFrom: string;
  validUntil: string;
  isActive: boolean;
}

export interface Review {
  id: string;
  productId: string;
  userId: string;
  userName: string;
  rating: number;
  comment: string;
  images?: string[];
  createdAt: string;
  helpful: number;
}

export type Page = 
  | 'home' 
  | 'shop' 
  | 'product' 
  | 'cart' 
  | 'checkout' 
  | 'user-dashboard' 
  | 'admin-dashboard'
  | 'admin-products'
  | 'admin-orders'
  | 'admin-users'
  | 'admin-analytics'
  | 'login'
  | 'signup'
  | 'forgot-password'
  | 'wishlist'
  | 'search'
  | 'order-confirmation'
  | 'order-tracking'
  | 'about'
  | 'contact'
  | 'faq'
  | 'terms'
  | 'privacy'
  | 'shipping'
  | '404';
