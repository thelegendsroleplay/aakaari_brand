import React, { useState } from 'react';
import { Package, Users, ShoppingBag, DollarSign, TrendingUp, Eye, Pencil, Trash2, MessageSquare, CheckCircle, Clock, AlertCircle } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Card } from '../../components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../../components/ui/tabs';
import { ProductFormWithVariations } from '../../components/admin/ProductFormWithVariations';
import { CustomerDetailsModal } from '../../components/admin/CustomerDetailsModal';
import { useProducts } from '../../contexts/ProductsContext';
import { useOrders } from '../../contexts/OrdersContext';
import { useTickets } from '../../contexts/TicketContext';
import { Product } from '../../types';
import './admin.css';

interface AdminPageProps {
  onNavigate: (page: string) => void;
}

export const AdminPage: React.FC<AdminPageProps> = ({ onNavigate }) => {
  const { products, addProduct, updateProduct, deleteProduct } = useProducts();
  const { orders } = useOrders();
  const { tickets, updateTicketStatus } = useTickets();
  const [activeTab, setActiveTab] = useState('overview');
  const [showProductForm, setShowProductForm] = useState(false);
  const [editingProduct, setEditingProduct] = useState<Product | undefined>();
  const [orderFilter, setOrderFilter] = useState<string>('all');
  const [selectedCustomer, setSelectedCustomer] = useState<any | null>(null);

  // Mock customer data
  const mockCustomers = [
    { id: '1', name: 'John Doe', email: 'john@example.com', phone: '+1 234-567-8900', address: '123 Main St, New York, NY 10001', joinedDate: '2024-01-15', totalOrders: 5, totalSpent: 1234.56, status: 'active' as const },
    { id: '2', name: 'Jane Smith', email: 'jane@example.com', phone: '+1 234-567-8901', address: '456 Oak Ave, Los Angeles, CA 90001', joinedDate: '2024-02-20', totalOrders: 3, totalSpent: 856.99, status: 'active' as const },
    { id: '3', name: 'Mike Johnson', email: 'mike@example.com', phone: '+1 234-567-8902', address: '789 Pine Rd, Chicago, IL 60601', joinedDate: '2023-12-10', totalOrders: 8, totalSpent: 2145.88, status: 'active' as const },
  ];

  // Calculate stats
  const totalRevenue = orders.reduce((sum, order) => sum + order.total, 0);
  const totalOrders = orders.length;
  const totalProducts = products.length;
  const lowStockProducts = products.filter(p => p.stock < 20).length;

  const stats = [
    { label: 'Total Revenue', value: `$${totalRevenue.toFixed(2)}`, icon: DollarSign, change: '+12.5%', color: 'blue' },
    { label: 'Total Orders', value: totalOrders.toString(), icon: ShoppingBag, change: '+8.2%', color: 'green' },
    { label: 'Total Products', value: totalProducts.toString(), icon: Package, change: `${lowStockProducts} low stock`, color: 'purple' },
    { label: 'Total Customers', value: '3,456', icon: Users, change: '+15.3%', color: 'orange' }
  ];

  const handleAddProduct = () => {
    setEditingProduct(undefined);
    setShowProductForm(true);
  };

  const handleEditProduct = (product: Product) => {
    setEditingProduct(product);
    setShowProductForm(true);
  };

  const handleDeleteProduct = (productId: string) => {
    if (window.confirm('Are you sure you want to delete this product?')) {
      deleteProduct(productId);
    }
  };

  const handleProductSubmit = (productData: any) => {
    if (editingProduct) {
      updateProduct(editingProduct.id, productData);
    } else {
      addProduct(productData);
    }
  };

  const filteredOrders = orderFilter === 'all' 
    ? orders 
    : orders.filter(order => order.status.toLowerCase() === orderFilter.toLowerCase());

  const recentOrders = orders.slice(0, 5);

  return (
    <div className="admin-page">
      <div className="admin-container">
        <div className="admin-header">
          <h1>Admin Dashboard</h1>
          <p>Manage your e-commerce store</p>
        </div>

        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="mb-6">
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="products">Products</TabsTrigger>
            <TabsTrigger value="orders">Orders</TabsTrigger>
            <TabsTrigger value="customers">Customers</TabsTrigger>
            <TabsTrigger value="tickets">
              Support Tickets
              {tickets.filter(t => t.status !== 'closed').length > 0 && (
                <span className="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                  {tickets.filter(t => t.status !== 'closed').length}
                </span>
              )}
            </TabsTrigger>
            <TabsTrigger value="analytics">Analytics</TabsTrigger>
          </TabsList>

          {/* Overview Tab */}
          <TabsContent value="overview">
            {/* Stats Grid */}
            <div className="stats-grid">
              {stats.map((stat, idx) => (
                <Card key={idx} className="stat-card">
                  <div className="stat-header">
                    <div className={`stat-icon ${stat.color}`}>
                      <stat.icon className="w-5 h-5" />
                    </div>
                    <span className="stat-change positive">{stat.change}</span>
                  </div>
                  <h3 className="stat-value">{stat.value}</h3>
                  <p className="stat-label">{stat.label}</p>
                </Card>
              ))}
            </div>

            {/* Recent Orders */}
            <Card className="mt-6">
              <div className="card-header">
                <h2>Recent Orders</h2>
                <Button variant="ghost" size="sm" onClick={() => setActiveTab('orders')}>
                  View All
                </Button>
              </div>
              <div className="orders-table">
                <table>
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Customer</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {recentOrders.map(order => (
                      <tr key={order.id}>
                        <td className="order-id">{order.orderNumber}</td>
                        <td>Customer #{order.userId}</td>
                        <td className="order-total">${order.total.toFixed(2)}</td>
                        <td>
                          <span className={`status-badge ${order.status.toLowerCase()}`}>
                            {order.status}
                          </span>
                        </td>
                        <td className="order-date">{new Date(order.createdAt).toLocaleDateString()}</td>
                        <td>
                          <Button variant="ghost" size="sm">
                            <Eye className="w-4 h-4" />
                          </Button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </Card>
          </TabsContent>

          {/* Products Tab */}
          <TabsContent value="products">
            <Card>
              <div className="card-header">
                <h2>Products Management</h2>
                <Button onClick={handleAddProduct}>Add New Product</Button>
              </div>
              <div className="products-table">
                <table>
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Category</th>
                      <th>Price</th>
                      <th>Stock</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {products.map(product => (
                      <tr key={product.id}>
                        <td>
                          <div className="product-cell">
                            <img src={product.images[0]} alt={product.name} />
                            <span>{product.name}</span>
                          </div>
                        </td>
                        <td>{product.category}</td>
                        <td className="product-price">
                          {product.salePrice ? (
                            <>
                              <span className="line-through text-gray-400 mr-2">${product.price.toFixed(2)}</span>
                              ${product.salePrice.toFixed(2)}
                            </>
                          ) : (
                            `$${product.price.toFixed(2)}`
                          )}
                        </td>
                        <td className={product.stock < 20 ? 'low-stock' : ''}>
                          {product.stock}
                          {product.stock < 20 && ' ⚠️'}
                        </td>
                        <td>
                          <span className="status-badge active">Active</span>
                        </td>
                        <td>
                          <div className="action-buttons">
                            <Button 
                              variant="ghost" 
                              size="sm"
                              onClick={() => handleEditProduct(product)}
                            >
                              <Pencil className="w-4 h-4" />
                            </Button>
                            <Button 
                              variant="ghost" 
                              size="sm"
                              onClick={() => handleDeleteProduct(product.id)}
                            >
                              <Trash2 className="w-4 h-4 text-red-600" />
                            </Button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </Card>
          </TabsContent>

          {/* Orders Tab */}
          <TabsContent value="orders">
            <Card>
              <div className="card-header">
                <h2>All Orders</h2>
                <div className="filter-buttons">
                  <Button 
                    variant={orderFilter === 'all' ? 'default' : 'outline'} 
                    size="sm"
                    onClick={() => setOrderFilter('all')}
                  >
                    All
                  </Button>
                  <Button 
                    variant={orderFilter === 'pending' ? 'default' : 'outline'} 
                    size="sm"
                    onClick={() => setOrderFilter('pending')}
                  >
                    Pending
                  </Button>
                  <Button 
                    variant={orderFilter === 'processing' ? 'default' : 'outline'} 
                    size="sm"
                    onClick={() => setOrderFilter('processing')}
                  >
                    Processing
                  </Button>
                  <Button 
                    variant={orderFilter === 'shipped' ? 'default' : 'outline'} 
                    size="sm"
                    onClick={() => setOrderFilter('shipped')}
                  >
                    Shipped
                  </Button>
                  <Button 
                    variant={orderFilter === 'delivered' ? 'default' : 'outline'} 
                    size="sm"
                    onClick={() => setOrderFilter('delivered')}
                  >
                    Delivered
                  </Button>
                </div>
              </div>
              <div className="orders-table">
                <table>
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Customer</th>
                      <th>Items</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {filteredOrders.length > 0 ? (
                      filteredOrders.map(order => (
                        <tr key={order.id}>
                          <td className="order-id">{order.orderNumber}</td>
                          <td>Customer #{order.userId}</td>
                          <td>{order.items.length} items</td>
                          <td className="order-total">${order.total.toFixed(2)}</td>
                          <td>
                            <span className={`status-badge ${order.status.toLowerCase()}`}>
                              {order.status}
                            </span>
                          </td>
                          <td className="order-date">{new Date(order.createdAt).toLocaleDateString()}</td>
                          <td>
                            <div className="action-buttons">
                              <Button variant="ghost" size="sm">View</Button>
                              <Button variant="ghost" size="sm">Update</Button>
                            </div>
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan={7} className="text-center py-8 text-gray-500">
                          No orders found with status: {orderFilter}
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </Card>
          </TabsContent>

          {/* Customers Tab */}
          <TabsContent value="customers">
            <Card>
              <div className="card-header">
                <h2>Customer Management</h2>
                <Button variant="outline">Export</Button>
              </div>
              <div className="customers-table">
                <table>
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Email</th>
                      <th>Orders</th>
                      <th>Total Spent</th>
                      <th>Joined</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {mockCustomers.map(customer => (
                      <tr key={customer.id}>
                        <td>{customer.name}</td>
                        <td>{customer.email}</td>
                        <td>{customer.totalOrders}</td>
                        <td>${customer.totalSpent.toFixed(2)}</td>
                        <td>{customer.joinedDate}</td>
                        <td>
                          <Button 
                            variant="ghost" 
                            size="sm"
                            onClick={() => setSelectedCustomer(customer)}
                          >
                            View
                          </Button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </Card>
          </TabsContent>

          {/* Tickets Tab */}
          <TabsContent value="tickets">
            <Card>
              <div className="card-header">
                <h2>Support Tickets</h2>
                <div className="flex gap-2">
                  <span className="text-sm text-gray-600">
                    {tickets.filter(t => t.status === 'open').length} Open · 
                    {' '}{tickets.filter(t => t.status === 'in-progress').length} In Progress · 
                    {' '}{tickets.filter(t => t.status === 'closed').length} Closed
                  </span>
                </div>
              </div>
              <div className="orders-table">
                <table>
                  <thead>
                    <tr>
                      <th>Ticket ID</th>
                      <th>Subject</th>
                      <th>Customer</th>
                      <th>Priority</th>
                      <th>Status</th>
                      <th>Created</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {tickets.length > 0 ? (
                      tickets.map(ticket => (
                        <tr key={ticket.id}>
                          <td className="order-id">#{ticket.id.substring(0, 8)}</td>
                          <td>
                            <div className="flex items-center gap-2">
                              <MessageSquare className="w-4 h-4 text-gray-400" />
                              {ticket.subject}
                            </div>
                          </td>
                          <td>{ticket.messages[0]?.userName || 'Unknown'}</td>
                          <td>
                            <span className={`status-badge ${
                              ticket.priority === 'high' ? 'pending' : 
                              ticket.priority === 'medium' ? 'processing' : 'delivered'
                            }`}>
                              {ticket.priority}
                            </span>
                          </td>
                          <td>
                            <span className={`status-badge ${
                              ticket.status === 'open' ? 'pending' : 
                              ticket.status === 'in_progress' ? 'processing' : 
                              ticket.status === 'closed' ? 'delivered' : 'shipped'
                            }`}>
                              {ticket.status === 'in_progress' ? 'In Progress' : ticket.status}
                            </span>
                          </td>
                          <td className="order-date">{new Date(ticket.createdAt).toLocaleDateString()}</td>
                          <td>
                            <div className="action-buttons">
                              <Button 
                                variant="ghost" 
                                size="sm"
                                onClick={() => onNavigate('support')}
                              >
                                View
                              </Button>
                              {ticket.status !== 'closed' && (
                                <Button 
                                  variant="ghost" 
                                  size="sm"
                                  onClick={() => updateTicketStatus(ticket.id, 'closed')}
                                >
                                  Close
                                </Button>
                              )}
                            </div>
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan={7} className="text-center py-12 text-gray-500">
                          <MessageSquare className="w-12 h-12 mx-auto mb-3 text-gray-400" />
                          <p>No support tickets yet</p>
                          <p className="text-sm mt-1">Tickets will appear here when customers reach out for support</p>
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </Card>
          </TabsContent>

          {/* Analytics Tab */}
          <TabsContent value="analytics">
            <div className="analytics-grid">
              <Card className="analytics-card">
                <h3>Sales Overview</h3>
                <div className="chart-placeholder">
                  <TrendingUp className="w-12 h-12 text-gray-300" />
                  <p>Sales chart would be displayed here</p>
                  <p className="text-sm mt-2">Total Revenue: ${totalRevenue.toFixed(2)}</p>
                </div>
              </Card>
              <Card className="analytics-card">
                <h3>Top Products</h3>
                <div className="top-products-list">
                  {products
                    .filter(p => p.bestseller)
                    .slice(0, 5)
                    .map((product, idx) => (
                      <div key={product.id} className="top-product-item">
                        <span className="rank">#{idx + 1}</span>
                        <img src={product.images[0]} alt={product.name} />
                        <div className="product-info">
                          <p>{product.name}</p>
                          <span className="sales-count">{product.reviewCount} reviews</span>
                        </div>
                        <span className="revenue">${product.price.toFixed(0)}</span>
                      </div>
                    ))}
                </div>
              </Card>
            </div>
          </TabsContent>
        </Tabs>
      </div>

      {/* Product Form Modal */}
      {showProductForm && (
        <ProductFormWithVariations
          product={editingProduct}
          onSubmit={handleProductSubmit}
          onClose={() => setShowProductForm(false)}
        />
      )}

      {/* Customer Details Modal */}
      {selectedCustomer && (
        <CustomerDetailsModal
          email={selectedCustomer.email}
          onClose={() => setSelectedCustomer(null)}
        />
      )}
    </div>
  );
};