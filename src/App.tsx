import { useState, useEffect } from 'react';
import { Page, Product, CartItem, Order, User, Address } from './lib/types';
import { mockProducts, mockOrders, mockUsers, mockAnalytics } from './lib/mockData';
import { Header } from './components/layout/Header';
import { Footer } from './components/layout/Footer';
import { MobileNav } from './components/layout/MobileNav';
import {
  HomePage,
  ShopPageContainer,
  ProductDetailPage,
  CartPageContainer,
  CheckoutPageContainer,
  UserDashboardPage,
  LoginPageContainer,
  SignUpPageContainer,
  WishlistPageContainer,
  SearchPageContainer,
  AboutPageContainer,
  ContactPageContainer,
  FAQPageContainer,
  AdminDashboardContainer,
} from './pages';
import { ForgotPasswordPage } from './components/auth/ForgotPasswordPage';
import { OrderConfirmation } from './components/order/OrderConfirmation';
import { OrderTracking } from './components/order/OrderTracking';
import { NotFoundPage } from './components/pages/NotFoundPage';
import { TermsPage } from './components/pages/TermsPage';
import { PrivacyPage } from './components/pages/PrivacyPage';
import { ShippingPage } from './components/pages/ShippingPage';
import { QuickView } from './components/product/QuickView';
import { Toaster } from './components/ui/sonner';
import { toast } from 'sonner@2.0.3';

export default function App() {
  // Navigation state
  const [currentPage, setCurrentPage] = useState<Page>('home');
  const [isMobileNavOpen, setIsMobileNavOpen] = useState(false);
  const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
  const [adminTab, setAdminTab] = useState('analytics');
  const [lastConfirmedOrder, setLastConfirmedOrder] = useState<Order | null>(null);

  // Data state
  const [products, setProducts] = useState<Product[]>(mockProducts);
  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [orders, setOrders] = useState<Order[]>(mockOrders);
  const [users] = useState<User[]>(mockUsers);
  const [wishlist, setWishlist] = useState<string[]>([]);
  const [recentlyViewed, setRecentlyViewed] = useState<string[]>([]);

  // Quick View state
  const [quickViewProduct, setQuickViewProduct] = useState<Product | null>(null);
  const [isQuickViewOpen, setIsQuickViewOpen] = useState(false);

  // Authentication state
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [currentUser, setCurrentUser] = useState<'customer' | 'admin'>('customer');

  // Load cart from localStorage
  useEffect(() => {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
      setCartItems(JSON.parse(savedCart));
    }
    const savedWishlist = localStorage.getItem('wishlist');
    if (savedWishlist) {
      setWishlist(JSON.parse(savedWishlist));
    }
  }, []);

  // Save cart to localStorage
  useEffect(() => {
    localStorage.setItem('cart', JSON.stringify(cartItems));
  }, [cartItems]);

  // Save wishlist to localStorage
  useEffect(() => {
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
  }, [wishlist]);

  // Handlers
  const handleNavigate = (page: Page) => {
    setCurrentPage(page);
    setIsMobileNavOpen(false);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleProductClick = (productId: string) => {
    setSelectedProductId(productId);
    setCurrentPage('product');
    
    // Add to recently viewed
    setRecentlyViewed(prev => {
      const filtered = prev.filter(id => id !== productId);
      return [productId, ...filtered].slice(0, 10);
    });
  };

  const handleQuickView = (productId: string) => {
    const product = products.find(p => p.id === productId);
    if (product) {
      setQuickViewProduct(product);
      setIsQuickViewOpen(true);
    }
  };

  const handleAddToCart = (item: CartItem) => {
    const existingItemIndex = cartItems.findIndex(
      (cartItem) =>
        cartItem.product.id === item.product.id &&
        cartItem.size === item.size &&
        cartItem.color === item.color &&
        JSON.stringify(cartItem.customization) === JSON.stringify(item.customization)
    );

    if (existingItemIndex >= 0) {
      const updatedCart = [...cartItems];
      updatedCart[existingItemIndex].quantity += item.quantity;
      setCartItems(updatedCart);
    } else {
      setCartItems([...cartItems, item]);
    }

    toast.success('Added to cart!');
  };

  const handleAddToCartFromWishlist = (productId: string) => {
    const product = products.find(p => p.id === productId);
    if (!product || !product.inStock) return;

    const item: CartItem = {
      product,
      quantity: 1,
      size: product.sizes[0] || 'M',
      color: product.colors[0] || 'Black',
    };

    handleAddToCart(item);
  };

  const handleUpdateQuantity = (index: number, quantity: number) => {
    if (quantity <= 0) {
      handleRemoveItem(index);
      return;
    }
    const updatedCart = [...cartItems];
    updatedCart[index].quantity = quantity;
    setCartItems(updatedCart);
  };

  const handleRemoveItem = (index: number) => {
    setCartItems(cartItems.filter((_, i) => i !== index));
    toast.success('Item removed from cart');
  };

  const handlePlaceOrder = (address: Address, paymentMethod: string) => {
    const newOrder: Order = {
      id: `ORD-${String(orders.length + 1).padStart(3, '0')}`,
      userId: isAuthenticated && currentUser === 'customer' ? 'user-1' : 'guest',
      items: cartItems,
      total: cartItems.reduce((total, item) => {
        const customizationPrice = item.product.customizationOptions?.reduce((sum, option) => {
          if (item.customization && item.customization[option.id]) {
            return sum + (option.price || 0);
          }
          return sum;
        }, 0) || 0;
        return total + (item.product.price + customizationPrice) * item.quantity;
      }, 0),
      status: 'pending',
      paymentStatus: 'pending',
      paymentMethod,
      shippingAddress: address,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
    };

    setOrders([newOrder, ...orders]);
    setCartItems([]);
    setLastConfirmedOrder(newOrder);
    handleNavigate('order-confirmation');
  };

  const handleAddProduct = (product: Product) => {
    setProducts([...products, product]);
    toast.success('Product added successfully!');
  };

  const handleUpdateProduct = (productId: string, updatedProduct: Product) => {
    setProducts(products.map(p => p.id === productId ? updatedProduct : p));
    toast.success('Product updated successfully!');
  };

  const handleDeleteProduct = (productId: string) => {
    setProducts(products.filter(p => p.id !== productId));
    toast.success('Product deleted successfully!');
  };

  const handleUpdateOrderStatus = (orderId: string, status: Order['status']) => {
    setOrders(orders.map(order => 
      order.id === orderId ? { ...order, status, updatedAt: new Date().toISOString() } : order
    ));
    toast.success('Order status updated!');
  };

  const handleLogin = (email: string, password: string) => {
    // Mock login - in real app, this would call an API
    const user = users.find(u => u.email === email);
    if (user) {
      setIsAuthenticated(true);
      setCurrentUser(user.role);
      toast.success(`Welcome back, ${user.name}!`);
      handleNavigate(user.role === 'admin' ? 'admin-dashboard' : 'home');
    } else {
      toast.error('Invalid credentials');
    }
  };

  const handleSignUp = (name: string, email: string, password: string) => {
    // Mock sign up - in real app, this would call an API
    setIsAuthenticated(true);
    setCurrentUser('customer');
    toast.success('Account created successfully!');
    handleNavigate('home');
  };

  const handleAddToWishlist = (productId: string) => {
    if (wishlist.includes(productId)) {
      setWishlist(wishlist.filter(id => id !== productId));
      toast.success('Removed from wishlist');
    } else {
      setWishlist([...wishlist, productId]);
      toast.success('Added to wishlist!');
    }
  };

  const handleRemoveFromWishlist = (productId: string) => {
    setWishlist(wishlist.filter(id => id !== productId));
    toast.success('Removed from wishlist');
  };

  // Get current user data
  const currentUserData = users.find(u => u.role === currentUser) || users[0];
  const userOrders = orders.filter(o => o.userId === currentUserData.id);

  // Cart and wishlist counts
  const cartItemsCount = cartItems.reduce((total, item) => total + item.quantity, 0);
  const wishlistCount = wishlist.length;

  // Wishlist products
  const wishlistProducts = products.filter(p => wishlist.includes(p.id));

  // Recently viewed products
  const recentlyViewedProducts = recentlyViewed
    .map(id => products.find(p => p.id === id))
    .filter(Boolean) as Product[];

  // Render current page
  const renderPage = () => {
    switch (currentPage) {
      case 'home':
        return (
          <HomePage
            products={products}
            onProductClick={handleProductClick}
            onNavigate={handleNavigate}
          />
        );

      case 'shop':
        return (
          <ShopPageContainer
            products={products}
            onProductClick={handleProductClick}
            onAddToWishlist={handleAddToWishlist}
            onQuickView={handleQuickView}
            wishlistIds={wishlist}
          />
        );

      case 'product':
        const selectedProduct = products.find(p => p.id === selectedProductId);
        if (!selectedProduct) {
          handleNavigate('shop');
          return null;
        }
        return (
          <ProductDetailPage
            product={selectedProduct}
            onAddToCart={handleAddToCart}
            onBack={() => handleNavigate('shop')}
            relatedProducts={products}
            onProductClick={handleProductClick}
            recentlyViewedProducts={recentlyViewedProducts}
            onAddToWishlist={handleAddToWishlist}
            wishlistIds={wishlist}
          />
        );

      case 'cart':
        return (
          <CartPageContainer
            cartItems={cartItems}
            onUpdateQuantity={handleUpdateQuantity}
            onRemoveItem={handleRemoveItem}
            onNavigate={handleNavigate}
          />
        );

      case 'checkout':
        if (cartItems.length === 0) {
          handleNavigate('cart');
          return null;
        }
        return (
          <CheckoutPageContainer
            cartItems={cartItems}
            onPlaceOrder={handlePlaceOrder}
            onNavigate={handleNavigate}
          />
        );

      case 'user-dashboard':
        if (!isAuthenticated || currentUser === 'admin') {
          handleNavigate('login');
          return null;
        }
        return (
          <UserDashboardPage
            user={currentUserData}
            orders={userOrders}
          />
        );

      case 'admin-dashboard':
      case 'admin-products':
      case 'admin-orders':
      case 'admin-users':
      case 'admin-analytics':
        if (!isAuthenticated || currentUser !== 'admin') {
          handleNavigate('login');
          return null;
        }
        return (
          <AdminDashboardContainer
            products={products}
            orders={orders}
            users={users}
            analyticsData={mockAnalytics}
            onAddProduct={handleAddProduct}
            onUpdateProduct={handleUpdateProduct}
            onDeleteProduct={handleDeleteProduct}
            onUpdateOrderStatus={handleUpdateOrderStatus}
            activeTab={adminTab}
            onTabChange={setAdminTab}
          />
        );

      case 'login':
        return <LoginPageContainer onNavigate={handleNavigate} onLogin={handleLogin} />;

      case 'signup':
        return <SignUpPageContainer onNavigate={handleNavigate} onSignUp={handleSignUp} />;

      case 'forgot-password':
        return <ForgotPasswordPage onNavigate={handleNavigate} />;

      case 'wishlist':
        return (
          <WishlistPageContainer
            wishlistProducts={wishlistProducts}
            onProductClick={handleProductClick}
            onRemoveFromWishlist={handleRemoveFromWishlist}
            onAddToCart={handleAddToCartFromWishlist}
            onNavigate={handleNavigate}
          />
        );

      case 'search':
        return (
          <SearchPageContainer
            products={products}
            onProductClick={handleProductClick}
            onAddToWishlist={handleAddToWishlist}
            wishlistIds={wishlist}
          />
        );

      case 'order-confirmation':
        if (!lastConfirmedOrder) {
          handleNavigate('home');
          return null;
        }
        return (
          <OrderConfirmation
            order={lastConfirmedOrder}
            onNavigate={handleNavigate}
          />
        );

      case 'order-tracking':
        return (
          <OrderTracking
            orders={orders}
            onNavigate={handleNavigate}
          />
        );

      case 'about':
        return <AboutPageContainer onNavigate={handleNavigate} />;

      case 'contact':
        return <ContactPageContainer />;

      case 'faq':
        return <FAQPageContainer onNavigate={handleNavigate} />;

      case 'terms':
        return <TermsPage />;

      case 'privacy':
        return <PrivacyPage />;

      case 'shipping':
        return <ShippingPage />;

      case '404':
        return <NotFoundPage onNavigate={handleNavigate} />;

      default:
        return (
          <HomePage
            products={products}
            onProductClick={handleProductClick}
            onNavigate={handleNavigate}
          />
        );
    }
  };

  return (
    <div className="min-h-screen flex flex-col bg-white">
      <Header
        currentPage={currentPage}
        onNavigate={handleNavigate}
        cartItemsCount={cartItemsCount}
        wishlistCount={wishlistCount}
        onMobileMenuToggle={() => setIsMobileNavOpen(true)}
        currentUser={currentUser}
        isAuthenticated={isAuthenticated}
      />

      <MobileNav
        isOpen={isMobileNavOpen}
        onClose={() => setIsMobileNavOpen(false)}
        onNavigate={handleNavigate}
        currentUser={currentUser}
      />

      <main className="flex-1">
        {renderPage()}
      </main>

      <Footer onNavigate={handleNavigate} />

      {/* Quick View Modal */}
      <QuickView
        product={quickViewProduct}
        isOpen={isQuickViewOpen}
        onClose={() => setIsQuickViewOpen(false)}
        onAddToCart={handleAddToCart}
        onAddToWishlist={handleAddToWishlist}
        onViewDetails={handleProductClick}
      />

      <Toaster />

      {/* Demo Toggle Button - Remove in production */}
      <div className="fixed bottom-4 right-4 flex flex-col gap-2 z-50">
        <button
          onClick={() => {
            setIsAuthenticated(!isAuthenticated);
            toast.success(isAuthenticated ? 'Logged out' : 'Logged in');
          }}
          className="bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition-colors text-sm"
        >
          {isAuthenticated ? '🔓 Logout' : '🔐 Login'}
        </button>
        {isAuthenticated && (
          <button
            onClick={() => {
              setCurrentUser(currentUser === 'customer' ? 'admin' : 'customer');
              toast.success(`Switched to ${currentUser === 'customer' ? 'Admin' : 'Customer'} view`);
            }}
            className="bg-black text-white px-4 py-2 rounded-full shadow-lg hover:bg-gray-800 transition-colors text-sm"
          >
            {currentUser === 'customer' ? '👤 Switch to Admin' : '🔧 Switch to Customer'}
          </button>
        )}
      </div>
    </div>
  );
}
