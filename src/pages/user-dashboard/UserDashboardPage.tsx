import { User, Order } from '../../lib/types';
import { Card } from '../../components/ui/card';
import { Button } from '../../components/ui/button';
import { Badge } from '../../components/ui/badge';
import { motion } from 'motion/react';
import { 
  Package, 
  User as UserIcon, 
  MapPin, 
  Settings, 
  Heart, 
  CreditCard,
  Bell,
  Shield,
  TrendingUp,
  Award,
  Clock,
  CheckCircle2,
  XCircle,
  Truck,
  Box
} from 'lucide-react';
import { useState } from 'react';

interface UserDashboardPageProps {
  user: User;
  orders: Order[];
}

export function UserDashboardPage({ user, orders }: UserDashboardPageProps) {
  const [activeTab, setActiveTab] = useState('overview');

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'delivered':
        return 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20';
      case 'shipped':
        return 'bg-blue-500/10 text-blue-500 border-blue-500/20';
      case 'processing':
        return 'bg-amber-500/10 text-amber-500 border-amber-500/20';
      case 'pending':
        return 'bg-gray-500/10 text-gray-500 border-gray-500/20';
      case 'cancelled':
        return 'bg-red-500/10 text-red-500 border-red-500/20';
      default:
        return 'bg-gray-500/10 text-gray-500 border-gray-500/20';
    }
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'delivered':
        return <CheckCircle2 className="h-4 w-4" />;
      case 'shipped':
        return <Truck className="h-4 w-4" />;
      case 'processing':
        return <Box className="h-4 w-4" />;
      case 'pending':
        return <Clock className="h-4 w-4" />;
      case 'cancelled':
        return <XCircle className="h-4 w-4" />;
      default:
        return <Package className="h-4 w-4" />;
    }
  };

  const stats = [
    {
      label: 'Total Orders',
      value: orders.length,
      icon: Package,
      color: 'from-blue-500 to-cyan-500',
      bgColor: 'bg-blue-500/10',
    },
    {
      label: 'Total Spent',
      value: `$${orders.reduce((sum, order) => sum + order.total, 0).toFixed(2)}`,
      icon: TrendingUp,
      color: 'from-emerald-500 to-teal-500',
      bgColor: 'bg-emerald-500/10',
    },
    {
      label: 'Rewards Points',
      value: Math.floor(orders.reduce((sum, order) => sum + order.total, 0) * 10),
      icon: Award,
      color: 'from-amber-500 to-orange-500',
      bgColor: 'bg-amber-500/10',
    },
    {
      label: 'Wishlist Items',
      value: 12,
      icon: Heart,
      color: 'from-pink-500 to-rose-500',
      bgColor: 'bg-pink-500/10',
    },
  ];

  const tabs = [
    { id: 'overview', label: 'Overview', icon: TrendingUp },
    { id: 'orders', label: 'Orders', icon: Package },
    { id: 'profile', label: 'Profile', icon: UserIcon },
    { id: 'addresses', label: 'Addresses', icon: MapPin },
    { id: 'payment', label: 'Payment', icon: CreditCard },
    { id: 'settings', label: 'Settings', icon: Settings },
  ];

  const recentOrders = orders.slice(0, 3);

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
      {/* Header Section */}
      <div className="relative overflow-hidden bg-gradient-to-r from-gray-900 via-black to-gray-900">
        <div className="absolute inset-0 bg-grid-white/[0.02] bg-[size:20px_20px]" />
        <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />
        
        <div className="container mx-auto px-4 py-12 relative">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="flex flex-col md:flex-row items-start md:items-center justify-between gap-6"
          >
            <div className="flex items-center gap-6">
              <div className="relative">
                <div className="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur-xl opacity-50" />
                <div className="relative h-20 w-20 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white text-2xl">
                  {user.name.charAt(0)}
                </div>
              </div>
              <div>
                <h1 className="text-3xl md:text-4xl text-white mb-2">
                  Welcome back, {user.name.split(' ')[0]}
                </h1>
                <p className="text-gray-400">Member since {new Date(user.createdAt).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}</p>
              </div>
            </div>
            
            <div className="flex gap-3">
              <Button variant="outline" className="bg-white/10 border-white/20 text-white hover:bg-white/20">
                <Bell className="h-4 w-4 mr-2" />
                Notifications
              </Button>
              <Button className="bg-gradient-to-r from-blue-500 to-purple-500 text-white border-0 hover:from-blue-600 hover:to-purple-600">
                <Shield className="h-4 w-4 mr-2" />
                Verified
              </Button>
            </div>
          </motion.div>

          {/* Stats Grid */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            {stats.map((stat, index) => {
              const Icon = stat.icon;
              return (
                <motion.div
                  key={stat.label}
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.5, delay: index * 0.1 }}
                >
                  <Card className="bg-white/10 backdrop-blur-lg border-white/20 p-4 hover:bg-white/20 transition-all duration-300">
                    <div className={`inline-flex p-2 rounded-lg ${stat.bgColor} mb-3`}>
                      <Icon className={`h-5 w-5 bg-gradient-to-r ${stat.color} bg-clip-text text-transparent`} style={{ WebkitTextFillColor: 'transparent' }} />
                    </div>
                    <p className="text-2xl text-white mb-1">{stat.value}</p>
                    <p className="text-sm text-gray-400">{stat.label}</p>
                  </Card>
                </motion.div>
              );
            })}
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="container mx-auto px-4 py-8">
        {/* Tab Navigation */}
        <div className="flex gap-2 overflow-x-auto pb-4 mb-8 scrollbar-hide">
          {tabs.map((tab) => {
            const Icon = tab.icon;
            return (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`flex items-center gap-2 px-6 py-3 rounded-full transition-all duration-300 whitespace-nowrap ${
                  activeTab === tab.id
                    ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg shadow-blue-500/30'
                    : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
                }`}
              >
                <Icon className="h-4 w-4" />
                {tab.label}
              </button>
            );
          })}
        </div>

        {/* Tab Content */}
        <motion.div
          key={activeTab}
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.3 }}
        >
          {/* Overview Tab */}
          {activeTab === 'overview' && (
            <div className="grid lg:grid-cols-3 gap-6">
              {/* Recent Orders */}
              <div className="lg:col-span-2">
                <Card className="p-6 border-0 shadow-lg">
                  <div className="flex items-center justify-between mb-6">
                    <h2 className="text-2xl">Recent Orders</h2>
                    <Button 
                      variant="ghost" 
                      onClick={() => setActiveTab('orders')}
                      className="text-blue-500 hover:text-blue-600"
                    >
                      View All
                    </Button>
                  </div>

                  {recentOrders.length === 0 ? (
                    <div className="text-center py-12">
                      <Package className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                      <p className="text-gray-600">No orders yet</p>
                      <p className="text-sm text-gray-500 mt-2">Start shopping to see your orders here</p>
                    </div>
                  ) : (
                    <div className="space-y-4">
                      {recentOrders.map((order) => (
                        <motion.div
                          key={order.id}
                          whileHover={{ scale: 1.01 }}
                          className="border border-gray-100 rounded-2xl p-4 hover:border-blue-200 hover:shadow-md transition-all duration-300"
                        >
                          <div className="flex items-start justify-between mb-4">
                            <div className="flex items-center gap-3">
                              <div className="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white">
                                {getStatusIcon(order.status)}
                              </div>
                              <div>
                                <h3 className="mb-1">Order #{order.id}</h3>
                                <p className="text-sm text-gray-500">
                                  {new Date(order.createdAt).toLocaleDateString('en-US', { 
                                    month: 'short', 
                                    day: 'numeric',
                                    year: 'numeric'
                                  })}
                                </p>
                              </div>
                            </div>
                            <div className="text-right">
                              <p className="text-lg mb-1">${order.total.toFixed(2)}</p>
                              <Badge className={`border ${getStatusColor(order.status)}`}>
                                {order.status}
                              </Badge>
                            </div>
                          </div>

                          <div className="flex items-center gap-2 pt-4 border-t border-gray-100">
                            {order.items.slice(0, 3).map((item, idx) => (
                              <div
                                key={idx}
                                className="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-xs"
                              >
                                {item.product.name.charAt(0)}
                              </div>
                            ))}
                            {order.items.length > 3 && (
                              <div className="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-xs">
                                +{order.items.length - 3}
                              </div>
                            )}
                            <span className="text-sm text-gray-500 ml-2">
                              {order.items.length} item{order.items.length !== 1 ? 's' : ''}
                            </span>
                          </div>
                        </motion.div>
                      ))}
                    </div>
                  )}
                </Card>
              </div>

              {/* Quick Actions */}
              <div className="space-y-6">
                <Card className="p-6 border-0 shadow-lg">
                  <h2 className="text-xl mb-4">Quick Actions</h2>
                  <div className="space-y-3">
                    <Button className="w-full justify-start bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:from-blue-600 hover:to-purple-600">
                      <Package className="h-4 w-4 mr-2" />
                      Track Order
                    </Button>
                    <Button variant="outline" className="w-full justify-start">
                      <Heart className="h-4 w-4 mr-2" />
                      View Wishlist
                    </Button>
                    <Button variant="outline" className="w-full justify-start">
                      <MapPin className="h-4 w-4 mr-2" />
                      Manage Addresses
                    </Button>
                    <Button variant="outline" className="w-full justify-start">
                      <CreditCard className="h-4 w-4 mr-2" />
                      Payment Methods
                    </Button>
                  </div>
                </Card>

                <Card className="p-6 border-0 shadow-lg bg-gradient-to-br from-amber-50 to-orange-50">
                  <div className="flex items-start gap-4">
                    <div className="h-12 w-12 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center flex-shrink-0">
                      <Award className="h-6 w-6 text-white" />
                    </div>
                    <div>
                      <h3 className="mb-2">Rewards Program</h3>
                      <p className="text-sm text-gray-600 mb-3">
                        You have {Math.floor(orders.reduce((sum, order) => sum + order.total, 0) * 10)} points
                      </p>
                      <Button size="sm" className="bg-gradient-to-r from-amber-500 to-orange-500 text-white hover:from-amber-600 hover:to-orange-600">
                        Redeem Points
                      </Button>
                    </div>
                  </div>
                </Card>
              </div>
            </div>
          )}

          {/* Orders Tab */}
          {activeTab === 'orders' && (
            <Card className="p-6 border-0 shadow-lg">
              <h2 className="text-2xl mb-6">All Orders</h2>
              {orders.length === 0 ? (
                <div className="text-center py-12">
                  <Package className="h-16 w-16 text-gray-400 mx-auto mb-4" />
                  <p className="text-gray-600 mb-2">No orders yet</p>
                  <p className="text-sm text-gray-500">Your order history will appear here</p>
                </div>
              ) : (
                <div className="space-y-4">
                  {orders.map((order) => (
                    <motion.div
                      key={order.id}
                      whileHover={{ scale: 1.01 }}
                      className="border border-gray-100 rounded-2xl p-6 hover:border-blue-200 hover:shadow-md transition-all duration-300"
                    >
                      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <div className="flex items-center gap-4">
                          <div className="h-16 w-16 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white text-xl">
                            {getStatusIcon(order.status)}
                          </div>
                          <div>
                            <div className="flex items-center gap-3 mb-2">
                              <h3>Order #{order.id}</h3>
                              <Badge className={`border ${getStatusColor(order.status)}`}>
                                {order.status}
                              </Badge>
                            </div>
                            <p className="text-sm text-gray-600">
                              {new Date(order.createdAt).toLocaleDateString('en-US', { 
                                month: 'long', 
                                day: 'numeric',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                              })}
                            </p>
                          </div>
                        </div>
                        <div className="text-left md:text-right">
                          <p className="text-2xl mb-1">${order.total.toFixed(2)}</p>
                          <p className="text-sm text-gray-600">{order.items.length} item(s)</p>
                        </div>
                      </div>

                      <div className="border-t border-gray-100 pt-4">
                        {order.items.map((item, idx) => (
                          <div key={idx} className="flex justify-between text-sm mb-2 py-2">
                            <span className="text-gray-700">
                              {item.product.name} <span className="text-gray-500">(x{item.quantity})</span>
                            </span>
                            <span className="font-medium">${(item.product.price * item.quantity).toFixed(2)}</span>
                          </div>
                        ))}
                      </div>

                      <div className="flex gap-3 mt-4 pt-4 border-t border-gray-100">
                        <Button variant="outline" size="sm" className="flex-1">
                          View Details
                        </Button>
                        {order.status === 'delivered' && (
                          <Button variant="outline" size="sm" className="flex-1">
                            Reorder
                          </Button>
                        )}
                        {order.status === 'shipped' && (
                          <Button size="sm" className="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:from-blue-600 hover:to-purple-600">
                            Track Package
                          </Button>
                        )}
                      </div>
                    </motion.div>
                  ))}
                </div>
              )}
            </Card>
          )}

          {/* Profile Tab */}
          {activeTab === 'profile' && (
            <Card className="p-6 border-0 shadow-lg max-w-2xl">
              <h2 className="text-2xl mb-6">Personal Information</h2>
              <div className="space-y-6">
                <div className="grid md:grid-cols-2 gap-6">
                  <div>
                    <label className="text-sm text-gray-600 mb-2 block">Full Name</label>
                    <div className="p-4 bg-gray-50 rounded-xl border border-gray-200">
                      <p>{user.name}</p>
                    </div>
                  </div>
                  <div>
                    <label className="text-sm text-gray-600 mb-2 block">Email Address</label>
                    <div className="p-4 bg-gray-50 rounded-xl border border-gray-200">
                      <p>{user.email}</p>
                    </div>
                  </div>
                </div>
                <div>
                  <label className="text-sm text-gray-600 mb-2 block">Member Since</label>
                  <div className="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p>{new Date(user.createdAt).toLocaleDateString('en-US', { 
                      month: 'long', 
                      day: 'numeric',
                      year: 'numeric'
                    })}</p>
                  </div>
                </div>
                <div className="flex gap-3 pt-4">
                  <Button className="bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:from-blue-600 hover:to-purple-600">
                    <UserIcon className="h-4 w-4 mr-2" />
                    Edit Profile
                  </Button>
                  <Button variant="outline">
                    Cancel
                  </Button>
                </div>
              </div>
            </Card>
          )}

          {/* Addresses Tab */}
          {activeTab === 'addresses' && (
            <Card className="p-6 border-0 shadow-lg">
              <div className="flex justify-between items-center mb-6">
                <h2 className="text-2xl">Saved Addresses</h2>
                <Button className="bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:from-blue-600 hover:to-purple-600">
                  <MapPin className="h-4 w-4 mr-2" />
                  Add New Address
                </Button>
              </div>
              {user.addresses.length === 0 ? (
                <div className="text-center py-12">
                  <MapPin className="h-16 w-16 text-gray-400 mx-auto mb-4" />
                  <p className="text-gray-600 mb-2">No saved addresses</p>
                  <p className="text-sm text-gray-500">Add an address to speed up checkout</p>
                </div>
              ) : (
                <div className="grid md:grid-cols-2 gap-4">
                  {user.addresses.map((address, idx) => (
                    <motion.div
                      key={idx}
                      whileHover={{ scale: 1.02 }}
                      className="border border-gray-100 rounded-2xl p-6 hover:border-blue-200 hover:shadow-md transition-all duration-300"
                    >
                      <div className="flex items-start gap-4 mb-4">
                        <div className="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center flex-shrink-0">
                          <MapPin className="h-6 w-6 text-white" />
                        </div>
                        <div className="flex-1">
                          <h3 className="mb-2">{address.fullName}</h3>
                          <p className="text-sm text-gray-600 leading-relaxed">
                            {address.street}<br />
                            {address.city}, {address.state} {address.zipCode}<br />
                            {address.country}
                          </p>
                          <p className="text-sm text-gray-600 mt-2">{address.phone}</p>
                        </div>
                      </div>
                      <div className="flex gap-2 pt-4 border-t border-gray-100">
                        <Button variant="outline" size="sm" className="flex-1">
                          Edit
                        </Button>
                        <Button variant="outline" size="sm" className="flex-1 text-red-500 hover:text-red-600">
                          Delete
                        </Button>
                      </div>
                    </motion.div>
                  ))}
                </div>
              )}
            </Card>
          )}

          {/* Payment Tab */}
          {activeTab === 'payment' && (
            <Card className="p-6 border-0 shadow-lg">
              <div className="flex justify-between items-center mb-6">
                <h2 className="text-2xl">Payment Methods</h2>
                <Button className="bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:from-blue-600 hover:to-purple-600">
                  <CreditCard className="h-4 w-4 mr-2" />
                  Add Card
                </Button>
              </div>
              <div className="text-center py-12">
                <CreditCard className="h-16 w-16 text-gray-400 mx-auto mb-4" />
                <p className="text-gray-600 mb-2">No payment methods saved</p>
                <p className="text-sm text-gray-500">Add a payment method for faster checkout</p>
              </div>
            </Card>
          )}

          {/* Settings Tab */}
          {activeTab === 'settings' && (
            <Card className="p-6 border-0 shadow-lg max-w-2xl">
              <h2 className="text-2xl mb-6">Account Settings</h2>
              <div className="space-y-4">
                <button className="w-full p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300 flex items-center justify-between group">
                  <div className="flex items-center gap-4">
                    <div className="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                      <Settings className="h-6 w-6 text-blue-600" />
                    </div>
                    <div className="text-left">
                      <p className="font-medium">Change Password</p>
                      <p className="text-sm text-gray-500">Update your account password</p>
                    </div>
                  </div>
                </button>

                <button className="w-full p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300 flex items-center justify-between group">
                  <div className="flex items-center gap-4">
                    <div className="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                      <Bell className="h-6 w-6 text-blue-600" />
                    </div>
                    <div className="text-left">
                      <p className="font-medium">Email Preferences</p>
                      <p className="text-sm text-gray-500">Manage your email notifications</p>
                    </div>
                  </div>
                </button>

                <button className="w-full p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300 flex items-center justify-between group">
                  <div className="flex items-center gap-4">
                    <div className="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                      <Shield className="h-6 w-6 text-blue-600" />
                    </div>
                    <div className="text-left">
                      <p className="font-medium">Privacy Settings</p>
                      <p className="text-sm text-gray-500">Control your data and privacy</p>
                    </div>
                  </div>
                </button>

                <button className="w-full p-4 rounded-xl border border-red-200 hover:border-red-300 hover:bg-red-50 transition-all duration-300 flex items-center justify-between group">
                  <div className="flex items-center gap-4">
                    <div className="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                      <XCircle className="h-6 w-6 text-red-600" />
                    </div>
                    <div className="text-left">
                      <p className="font-medium text-red-600">Delete Account</p>
                      <p className="text-sm text-gray-500">Permanently delete your account</p>
                    </div>
                  </div>
                </button>
              </div>
            </Card>
          )}
        </motion.div>
      </div>
    </div>
  );
}
