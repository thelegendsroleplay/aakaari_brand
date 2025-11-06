import React, { useState } from 'react';
import { CartProvider } from './contexts/CartContext';
import { WishlistProvider } from './contexts/WishlistContext';
import { AuthProvider } from './contexts/AuthContext';
import { OrdersProvider } from './contexts/OrdersContext';
import { ProductsProvider } from './contexts/ProductsContext';
import { TicketProvider } from './contexts/TicketContext';
import { Header } from './components/header/Header';
import { Footer } from './components/footer/Footer';
import { HomePage } from './pages/home/HomePage';
import { ProductsPage } from './pages/products/ProductsPage';
import { CartPage } from './pages/cart/CartPage';
import { CheckoutPage } from './pages/checkout/CheckoutPage';
import { AdminPage } from './pages/admin/AdminPage';
import { AuthPage } from './pages/auth/AuthPage';
import { ProductDetailPage } from './pages/product-detail/ProductDetailPage';
import { AboutPage } from './pages/about/AboutPage';
import { ContactPage } from './pages/contact/ContactPage';
import { AccountPage } from './pages/account/AccountPage';
import { OrdersPage } from './pages/orders/OrdersPage';
import { SupportPage } from './pages/support/SupportPage';
import { Product } from './types';
import { Toaster } from './components/ui/sonner';
import './styles/globals.css';

export default function App() {
  const [currentPage, setCurrentPage] = useState('home');
  const [selectedProduct, setSelectedProduct] = useState<Product | null>(null);

  const handleNavigate = (page: string, productId?: string) => {
    setCurrentPage(page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleViewProduct = (product: Product) => {
    setSelectedProduct(product);
    setCurrentPage('product-detail');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const renderPage = () => {
    switch (currentPage) {
      case 'home':
        return <HomePage onNavigate={handleNavigate} onViewProduct={handleViewProduct} />;
      case 'products':
      case 'hoodies':
      case 'new-arrivals':
      case 'sale':
      case 'bestsellers':
        return <ProductsPage onNavigate={handleNavigate} onViewProduct={handleViewProduct} pageType={currentPage} />;
      case 'product-detail':
        return selectedProduct ? (
          <ProductDetailPage product={selectedProduct} onNavigate={handleNavigate} onViewProduct={handleViewProduct} />
        ) : (
          <HomePage onNavigate={handleNavigate} onViewProduct={handleViewProduct} />
        );
      case 'cart':
        return <CartPage onNavigate={handleNavigate} />;
      case 'checkout':
        return <CheckoutPage onNavigate={handleNavigate} />;
      case 'auth':
        return <AuthPage onNavigate={handleNavigate} />;
      case 'about':
        return <AboutPage onNavigate={handleNavigate} />;
      case 'contact':
        return <ContactPage onNavigate={handleNavigate} />;
      case 'admin':
        return <AdminPage onNavigate={handleNavigate} />;
      case 'wishlist':
        // We'll create this page soon
        return <div className="container mx-auto px-4 py-8">
          <h1 className="mb-6">Wishlist</h1>
          <p>Your wishlist is empty</p>
        </div>;
      case 'orders':
        return <OrdersPage onNavigate={handleNavigate} />;
      case 'account':
        return <AccountPage onNavigate={handleNavigate} />;
      case 'support':
        return <SupportPage onNavigate={handleNavigate} />;
      case 'track-order':
        return <div className="container mx-auto px-4 py-8">
          <h1 className="mb-6">Track Your Order</h1>
          <p>Enter your order number to track your shipment</p>
        </div>;
      default:
        return <HomePage onNavigate={handleNavigate} onViewProduct={handleViewProduct} />;
    }
  };

  return (
    <AuthProvider>
      <CartProvider>
        <WishlistProvider>
          <OrdersProvider>
            <ProductsProvider>
              <TicketProvider>
                <div className="min-h-screen flex flex-col">
                  <Header onNavigate={handleNavigate} currentPage={currentPage} />
                  <main className="flex-1">
                    {renderPage()}
                  </main>
                  <Footer onNavigate={handleNavigate} />
                  <Toaster />
                </div>
              </TicketProvider>
            </ProductsProvider>
          </OrdersProvider>
        </WishlistProvider>
      </CartProvider>
    </AuthProvider>
  );
}