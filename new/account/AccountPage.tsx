import React, { useState } from 'react';
import { User, Package, Heart, MapPin, CreditCard, Settings, LogOut } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Card } from '../../components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../../components/ui/tabs';
import { Input } from '../../components/ui/input';
import { Label } from '../../components/ui/label';
import { useAuth } from '../../contexts/AuthContext';
import { useOrders } from '../../contexts/OrdersContext';
import { useWishlist } from '../../contexts/WishlistContext';
import './account.css';

interface AccountPageProps {
  onNavigate: (page: string) => void;
}

export const AccountPage: React.FC<AccountPageProps> = ({ onNavigate }) => {
  const { user, logout } = useAuth();
  const { orders } = useOrders();
  const { wishlist } = useWishlist();
  const [activeTab, setActiveTab] = useState('overview');

  const stats = [
    { label: 'Total Orders', value: orders.length.toString(), icon: Package },
    { label: 'Wishlist Items', value: wishlist.length.toString(), icon: Heart },
    { label: 'Saved Addresses', value: '2', icon: MapPin },
  ];

  const recentOrders = orders.slice(0, 5);

  return (
    <div className="account-page">
      <div className="account-container">
        {/* Header */}
        <div className="account-header">
          <div>
            <h1>My Account</h1>
            <p className="text-gray-600">Welcome back, {user?.name || 'Guest'}</p>
          </div>
          <Button variant="outline" onClick={logout}>
            <LogOut className="w-4 h-4 mr-2" />
            Logout
          </Button>
        </div>

        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="mb-6">
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="orders">Orders</TabsTrigger>
            <TabsTrigger value="wishlist">Wishlist</TabsTrigger>
            <TabsTrigger value="support">Support</TabsTrigger>
            <TabsTrigger value="profile">Profile</TabsTrigger>
            <TabsTrigger value="addresses">Addresses</TabsTrigger>
          </TabsList>

          {/* Overview Tab */}
          <TabsContent value="overview">
            {/* Stats */}
            <div className="stats-grid">
              {stats.map((stat, idx) => (
                <Card key={idx} className="stat-card">
                  <div className="stat-icon-wrapper">
                    <stat.icon className="w-5 h-5" />
                  </div>
                  <h3 className="stat-value">{stat.value}</h3>
                  <p className="stat-label">{stat.label}</p>
                </Card>
              ))}
            </div>

            {/* Recent Orders */}
            <div className="section-spacing">
              <div className="section-header">
                <h2>Recent Orders</h2>
                <Button variant="ghost" onClick={() => setActiveTab('orders')}>
                  View All
                </Button>
              </div>
              
              {recentOrders.length > 0 ? (
                <div className="orders-list">
                  {recentOrders.map(order => (
                    <Card key={order.id} className="order-card">
                      <div className="order-header">
                        <div>
                          <p className="order-id">Order #{order.id}</p>
                          <p className="order-date">{new Date(order.createdAt).toLocaleDateString()}</p>
                        </div>
                        <span className={`status-badge ${order.status.toLowerCase()}`}>
                          {order.status}
                        </span>
                      </div>
                      <div className="order-items">
                        {order.items.slice(0, 3).map(item => (
                          <div key={item.id} className="order-item">
                            <img src={item.product.images[0]} alt={item.product.name} />
                            <div className="item-details">
                              <p>{item.product.name}</p>
                              <p className="text-sm text-gray-500">Qty: {item.quantity}</p>
                            </div>
                            <p className="item-price">${item.price.toFixed(2)}</p>
                          </div>
                        ))}
                      </div>
                      <div className="order-footer">
                        <p className="order-total">Total: ${order.total.toFixed(2)}</p>
                        <Button variant="outline" size="sm">Track Order</Button>
                      </div>
                    </Card>
                  ))}
                </div>
              ) : (
                <Card className="empty-state">
                  <Package className="w-12 h-12 text-gray-300 mb-3" />
                  <p className="text-gray-600">No orders yet</p>
                  <Button onClick={() => onNavigate('products')} className="mt-4">
                    Start Shopping
                  </Button>
                </Card>
              )}
            </div>
          </TabsContent>

          {/* Orders Tab */}
          <TabsContent value="orders">
            <Card>
              <div className="card-header">
                <h2>All Orders</h2>
                <div className="filter-buttons">
                  <Button variant="outline" size="sm">All</Button>
                  <Button variant="outline" size="sm">Processing</Button>
                  <Button variant="outline" size="sm">Shipped</Button>
                  <Button variant="outline" size="sm">Delivered</Button>
                </div>
              </div>
              
              {orders.length > 0 ? (
                <div className="orders-table-wrapper">
                  <table className="orders-table">
                    <thead>
                      <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {orders.map(order => (
                        <tr key={order.id}>
                          <td className="order-id">#{order.id}</td>
                          <td>{new Date(order.createdAt).toLocaleDateString()}</td>
                          <td>{order.items.length} items</td>
                          <td className="order-total">${order.total.toFixed(2)}</td>
                          <td>
                            <span className={`status-badge ${order.status.toLowerCase()}`}>
                              {order.status}
                            </span>
                          </td>
                          <td>
                            <Button variant="ghost" size="sm">View Details</Button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <div className="empty-state">
                  <Package className="w-12 h-12 text-gray-300 mb-3" />
                  <p className="text-gray-600">No orders yet</p>
                  <Button onClick={() => onNavigate('products')} className="mt-4">
                    Start Shopping
                  </Button>
                </div>
              )}
            </Card>
          </TabsContent>

          {/* Wishlist Tab */}
          <TabsContent value="wishlist">
            <Card>
              <div className="card-header">
                <h2>My Wishlist</h2>
                <p className="text-sm text-gray-600">{wishlist.length} items</p>
              </div>
              
              {wishlist.length > 0 ? (
                <div className="wishlist-grid">
                  {wishlist.map((item) => (
                    <div key={item.product.id} className="wishlist-item">
                      <img src={item.product.images[0]} alt={item.product.name} />
                      <div className="wishlist-details">
                        <h3>{item.product.name}</h3>
                        <p className="price">${item.product.price.toFixed(2)}</p>
                        <div className="wishlist-actions">
                          <Button size="sm" onClick={() => onNavigate('product-detail')}>
                            View Product
                          </Button>
                          <Button variant="outline" size="sm">Remove</Button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="empty-state">
                  <Heart className="w-12 h-12 text-gray-300 mb-3" />
                  <p className="text-gray-600">Your wishlist is empty</p>
                  <Button onClick={() => onNavigate('products')} className="mt-4">
                    Browse Products
                  </Button>
                </div>
              )}
            </Card>
          </TabsContent>

          {/* Support Tab */}
          <TabsContent value="support">
            <div className="support-section">
              <div className="section-header">
                <h2>Customer Support</h2>
                <Button onClick={() => onNavigate('support')}>
                  View All Tickets
                </Button>
              </div>

              <Card className="mb-6">
                <div className="card-header">
                  <h3>Need Help?</h3>
                </div>
                <div className="p-6">
                  <p className="text-gray-600 mb-4">
                    Have a question or issue? Our support team is here to help! Create a support ticket and we'll get back to you as soon as possible.
                  </p>
                  <Button onClick={() => onNavigate('support')}>
                    Create New Ticket
                  </Button>
                </div>
              </Card>

              <Card>
                <div className="card-header">
                  <h3>Quick Links</h3>
                </div>
                <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">
                    <Package className="w-6 h-6 mb-2 text-blue-600" />
                    <h4 className="font-semibold mb-1">Track Order</h4>
                    <p className="text-sm text-gray-600">Check your order status and delivery</p>
                  </div>
                  <div className="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">
                    <Heart className="w-6 h-6 mb-2 text-red-600" />
                    <h4 className="font-semibold mb-1">Returns & Refunds</h4>
                    <p className="text-sm text-gray-600">Learn about our return policy</p>
                  </div>
                  <div className="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">
                    <MapPin className="w-6 h-6 mb-2 text-green-600" />
                    <h4 className="font-semibold mb-1">Shipping Info</h4>
                    <p className="text-sm text-gray-600">View shipping rates and times</p>
                  </div>
                  <div className="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">
                    <Settings className="w-6 h-6 mb-2 text-purple-600" />
                    <h4 className="font-semibold mb-1">Account Help</h4>
                    <p className="text-sm text-gray-600">Manage your account settings</p>
                  </div>
                </div>
              </Card>
            </div>
          </TabsContent>

          {/* Profile Tab */}
          <TabsContent value="profile">
            <Card>
              <div className="card-header">
                <h2>Profile Information</h2>
              </div>
              <div className="profile-form">
                <div className="form-grid">
                  <div>
                    <Label htmlFor="firstName">First Name</Label>
                    <Input id="firstName" defaultValue={user?.name?.split(' ')[0] || ''} />
                  </div>
                  <div>
                    <Label htmlFor="lastName">Last Name</Label>
                    <Input id="lastName" defaultValue={user?.name?.split(' ')[1] || ''} />
                  </div>
                  <div>
                    <Label htmlFor="email">Email</Label>
                    <Input id="email" type="email" defaultValue={user?.email || ''} />
                  </div>
                  <div>
                    <Label htmlFor="phone">Phone</Label>
                    <Input id="phone" type="tel" placeholder="+1 (555) 000-0000" />
                  </div>
                </div>
                
                <div className="form-section">
                  <h3>Change Password</h3>
                  <div className="form-grid">
                    <div>
                      <Label htmlFor="currentPassword">Current Password</Label>
                      <Input id="currentPassword" type="password" />
                    </div>
                    <div>
                      <Label htmlFor="newPassword">New Password</Label>
                      <Input id="newPassword" type="password" />
                    </div>
                    <div>
                      <Label htmlFor="confirmPassword">Confirm Password</Label>
                      <Input id="confirmPassword" type="password" />
                    </div>
                  </div>
                </div>

                <div className="form-actions">
                  <Button>Save Changes</Button>
                  <Button variant="outline">Cancel</Button>
                </div>
              </div>
            </Card>
          </TabsContent>

          {/* Addresses Tab */}
          <TabsContent value="addresses">
            <div className="addresses-section">
              <div className="section-header">
                <h2>Saved Addresses</h2>
                <Button>Add New Address</Button>
              </div>
              
              <div className="addresses-grid">
                <Card className="address-card">
                  <div className="address-header">
                    <h3>Home</h3>
                    <span className="default-badge">Default</span>
                  </div>
                  <div className="address-details">
                    <p>John Doe</p>
                    <p>123 Main Street</p>
                    <p>Apt 4B</p>
                    <p>New York, NY 10001</p>
                    <p>United States</p>
                    <p>+1 (555) 123-4567</p>
                  </div>
                  <div className="address-actions">
                    <Button variant="ghost" size="sm">Edit</Button>
                    <Button variant="ghost" size="sm">Remove</Button>
                  </div>
                </Card>

                <Card className="address-card">
                  <div className="address-header">
                    <h3>Work</h3>
                  </div>
                  <div className="address-details">
                    <p>John Doe</p>
                    <p>456 Business Ave</p>
                    <p>Suite 100</p>
                    <p>New York, NY 10002</p>
                    <p>United States</p>
                    <p>+1 (555) 987-6543</p>
                  </div>
                  <div className="address-actions">
                    <Button variant="ghost" size="sm">Edit</Button>
                    <Button variant="ghost" size="sm">Remove</Button>
                    <Button variant="ghost" size="sm">Set as Default</Button>
                  </div>
                </Card>
              </div>
            </div>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  );
};