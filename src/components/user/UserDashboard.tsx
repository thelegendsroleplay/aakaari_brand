import { useState } from 'react';
import { User, Order } from '../../lib/types';
import { Card } from '../ui/card';
import { Button } from '../ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../ui/tabs';
import { Badge } from '../ui/badge';
import { Package, User as UserIcon, MapPin, Settings, ChevronRight, TrendingUp, ShoppingBag, Clock } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';

interface UserDashboardProps {
  user: User;
  orders: Order[];
}

export function UserDashboard({ user, orders }: UserDashboardProps) {
  const [activeTab, setActiveTab] = useState('orders');

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'delivered':
        return 'bg-white/20 text-white border border-white/30';
      case 'shipped':
        return 'bg-gray-100 text-gray-900 border border-gray-300';
      case 'processing':
        return 'bg-gray-200 text-gray-900 border border-gray-400';
      case 'pending':
        return 'bg-gray-300/50 text-gray-700 border border-gray-400';
      case 'cancelled':
        return 'bg-black/30 text-white border border-white/20';
      default:
        return 'bg-gray-200 text-gray-800 border border-gray-300';
    }
  };

  const stats = [
    {
      icon: ShoppingBag,
      label: 'Total Orders',
      value: orders.length.toString(),
      gradient: 'from-black to-gray-800'
    },
    {
      icon: TrendingUp,
      label: 'Total Spent',
      value: `$${orders.reduce((sum, order) => sum + order.total, 0).toFixed(2)}`,
      gradient: 'from-gray-800 to-gray-600'
    },
    {
      icon: Clock,
      label: 'Member Since',
      value: new Date(user.createdAt).getFullYear().toString(),
      gradient: 'from-gray-600 to-gray-500'
    }
  ];

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
      {/* Hero Section with Glass Morphism */}
      <div className="relative overflow-hidden bg-gradient-to-br from-black via-gray-900 to-black">
        {/* Animated Background Pattern */}
        <div className="absolute inset-0 opacity-10">
          <div className="absolute inset-0" style={{
            backgroundImage: 'linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px)',
            backgroundSize: '50px 50px'
          }} />
        </div>

        <div className="container mx-auto px-4 py-12 md:py-16 relative">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="max-w-4xl"
          >
            <div className="inline-block px-4 py-1 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full mb-4">
              <span className="text-white/80 text-sm">Dashboard</span>
            </div>
            <h1 className="text-white mb-2">Welcome back, {user.name}!</h1>
            <p className="text-white/60 text-lg">Manage your orders, profile, and account settings</p>
          </motion.div>

          {/* Stats Cards */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8"
          >
            {stats.map((stat, idx) => (
              <motion.div
                key={stat.label}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.4, delay: 0.1 * idx }}
                whileHover={{ scale: 1.02, y: -4 }}
                className="group"
              >
                <div className={`relative overflow-hidden bg-gradient-to-br ${stat.gradient} p-6 rounded-2xl border border-white/10`}>
                  <div className="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                  <div className="relative flex items-start justify-between">
                    <div>
                      <p className="text-white/60 text-sm mb-1">{stat.label}</p>
                      <p className="text-white text-2xl">{stat.value}</p>
                    </div>
                    <div className="bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/20">
                      <stat.icon className="h-5 w-5 text-white" />
                    </div>
                  </div>
                </div>
              </motion.div>
            ))}
          </motion.div>
        </div>
      </div>

      {/* Main Content */}
      <div className="container mx-auto px-4 py-8 md:py-12">
        <Tabs value={activeTab} onValueChange={setActiveTab} className="space-y-8">
          {/* Glass Morphism Tab List */}
          <div className="flex justify-center">
            <TabsList className="inline-flex bg-white/60 backdrop-blur-xl border border-gray-200/50 shadow-xl p-1.5 rounded-2xl">
              <TabsTrigger 
                value="orders"
                className="data-[state=active]:bg-gradient-to-br data-[state=active]:from-black data-[state=active]:to-gray-800 data-[state=active]:text-white rounded-xl transition-all duration-300"
              >
                <Package className="h-4 w-4 mr-2" />
                <span className="hidden sm:inline">Orders</span>
              </TabsTrigger>
              <TabsTrigger 
                value="profile"
                className="data-[state=active]:bg-gradient-to-br data-[state=active]:from-black data-[state=active]:to-gray-800 data-[state=active]:text-white rounded-xl transition-all duration-300"
              >
                <UserIcon className="h-4 w-4 mr-2" />
                <span className="hidden sm:inline">Profile</span>
              </TabsTrigger>
              <TabsTrigger 
                value="addresses"
                className="data-[state=active]:bg-gradient-to-br data-[state=active]:from-black data-[state=active]:to-gray-800 data-[state=active]:text-white rounded-xl transition-all duration-300"
              >
                <MapPin className="h-4 w-4 mr-2" />
                <span className="hidden sm:inline">Addresses</span>
              </TabsTrigger>
              <TabsTrigger 
                value="settings"
                className="data-[state=active]:bg-gradient-to-br data-[state=active]:from-black data-[state=active]:to-gray-800 data-[state=active]:text-white rounded-xl transition-all duration-300"
              >
                <Settings className="h-4 w-4 mr-2" />
                <span className="hidden sm:inline">Settings</span>
              </TabsTrigger>
            </TabsList>
          </div>

          {/* Orders Tab */}
          <TabsContent value="orders" className="space-y-6">
            <AnimatePresence mode="wait">
              <motion.div
                key="orders"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -20 }}
                transition={{ duration: 0.3 }}
              >
                <div className="bg-white/60 backdrop-blur-xl border border-gray-200/50 shadow-xl rounded-2xl p-6 md:p-8">
                  <div className="flex items-center justify-between mb-6">
                    <div>
                      <h2 className="mb-1">Order History</h2>
                      <p className="text-gray-600 text-sm">{orders.length} total orders</p>
                    </div>
                  </div>
                  
                  {orders.length === 0 ? (
                    <div className="text-center py-12">
                      <div className="inline-flex p-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl mb-4">
                        <Package className="h-12 w-12 text-gray-400" />
                      </div>
                      <p className="text-gray-600">No orders yet</p>
                      <p className="text-sm text-gray-500 mt-2">Start shopping to see your orders here</p>
                    </div>
                  ) : (
                    <div className="space-y-4">
                      {orders.map((order, idx) => (
                        <motion.div
                          key={order.id}
                          initial={{ opacity: 0, x: -20 }}
                          animate={{ opacity: 1, x: 0 }}
                          transition={{ duration: 0.3, delay: idx * 0.1 }}
                          whileHover={{ scale: 1.01 }}
                          className="group"
                        >
                          <div className="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                              <div>
                                <div className="flex items-center gap-3 mb-2">
                                  <h3>Order #{order.id}</h3>
                                  <Badge className={getStatusColor(order.status)}>
                                    {order.status}
                                  </Badge>
                                </div>
                                <p className="text-sm text-gray-500">
                                  Placed on {new Date(order.createdAt).toLocaleDateString()}
                                </p>
                              </div>
                              <div className="text-left md:text-right">
                                <p className="text-2xl">${order.total.toFixed(2)}</p>
                                <p className="text-sm text-gray-500">{order.items.length} item(s)</p>
                              </div>
                            </div>

                            <div className="border-t border-gray-200 pt-4 mb-4">
                              {order.items.map((item, itemIdx) => (
                                <div key={itemIdx} className="flex justify-between text-sm mb-2 last:mb-0">
                                  <span className="text-gray-600">
                                    {item.product.name} <span className="text-gray-400">(x{item.quantity})</span>
                                  </span>
                                  <span className="text-gray-900">${(item.product.price * item.quantity).toFixed(2)}</span>
                                </div>
                              ))}
                            </div>

                            <div className="flex gap-2">
                              <Button variant="outline" size="sm" className="group-hover:bg-black group-hover:text-white transition-colors">
                                View Details
                                <ChevronRight className="h-3 w-3 ml-1" />
                              </Button>
                              {order.status === 'delivered' && (
                                <Button variant="outline" size="sm">Reorder</Button>
                              )}
                            </div>
                          </div>
                        </motion.div>
                      ))}
                    </div>
                  )}
                </div>
              </motion.div>
            </AnimatePresence>
          </TabsContent>

          {/* Profile Tab */}
          <TabsContent value="profile">
            <AnimatePresence mode="wait">
              <motion.div
                key="profile"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -20 }}
                transition={{ duration: 0.3 }}
                className="bg-white/60 backdrop-blur-xl border border-gray-200/50 shadow-xl rounded-2xl p-6 md:p-8"
              >
                <h2 className="mb-6">Personal Information</h2>
                <div className="space-y-6">
                  <motion.div
                    whileHover={{ x: 4 }}
                    className="p-4 bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl"
                  >
                    <label className="text-sm text-gray-500 mb-1 block">Name</label>
                    <p className="text-lg">{user.name}</p>
                  </motion.div>
                  <motion.div
                    whileHover={{ x: 4 }}
                    className="p-4 bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl"
                  >
                    <label className="text-sm text-gray-500 mb-1 block">Email</label>
                    <p className="text-lg">{user.email}</p>
                  </motion.div>
                  <motion.div
                    whileHover={{ x: 4 }}
                    className="p-4 bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl"
                  >
                    <label className="text-sm text-gray-500 mb-1 block">Member Since</label>
                    <p className="text-lg">{new Date(user.createdAt).toLocaleDateString()}</p>
                  </motion.div>
                  <Button className="w-full md:w-auto bg-gradient-to-br from-black to-gray-800 hover:from-gray-900 hover:to-black">
                    Edit Profile
                    <ChevronRight className="h-4 w-4 ml-2" />
                  </Button>
                </div>
              </motion.div>
            </AnimatePresence>
          </TabsContent>

          {/* Addresses Tab */}
          <TabsContent value="addresses">
            <AnimatePresence mode="wait">
              <motion.div
                key="addresses"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -20 }}
                transition={{ duration: 0.3 }}
                className="bg-white/60 backdrop-blur-xl border border-gray-200/50 shadow-xl rounded-2xl p-6 md:p-8"
              >
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                  <div>
                    <h2 className="mb-1">Saved Addresses</h2>
                    <p className="text-gray-600 text-sm">{user.addresses.length} saved addresses</p>
                  </div>
                  <Button className="bg-gradient-to-br from-black to-gray-800 hover:from-gray-900 hover:to-black">
                    Add New Address
                  </Button>
                </div>
                {user.addresses.length === 0 ? (
                  <div className="text-center py-12">
                    <div className="inline-flex p-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl mb-4">
                      <MapPin className="h-12 w-12 text-gray-400" />
                    </div>
                    <p className="text-gray-600">No saved addresses</p>
                    <p className="text-sm text-gray-500 mt-2">Add an address for faster checkout</p>
                  </div>
                ) : (
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {user.addresses.map((address, idx) => (
                      <motion.div
                        key={idx}
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.3, delay: idx * 0.1 }}
                        whileHover={{ scale: 1.02, y: -4 }}
                        className="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300"
                      >
                        <div className="flex justify-between items-start mb-4">
                          <div className="bg-gradient-to-br from-black to-gray-800 p-2 rounded-lg">
                            <MapPin className="h-4 w-4 text-white" />
                          </div>
                        </div>
                        <h3 className="mb-3">{address.fullName}</h3>
                        <p className="text-sm text-gray-600 leading-relaxed">
                          {address.street}<br />
                          {address.city}, {address.state} {address.zipCode}<br />
                          {address.country}<br />
                          {address.phone}
                        </p>
                        <div className="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                          <Button variant="outline" size="sm" className="flex-1">Edit</Button>
                          <Button variant="outline" size="sm" className="flex-1 text-red-600 hover:text-red-700 hover:bg-red-50">Delete</Button>
                        </div>
                      </motion.div>
                    ))}
                  </div>
                )}
              </motion.div>
            </AnimatePresence>
          </TabsContent>

          {/* Settings Tab */}
          <TabsContent value="settings">
            <AnimatePresence mode="wait">
              <motion.div
                key="settings"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -20 }}
                transition={{ duration: 0.3 }}
                className="bg-white/60 backdrop-blur-xl border border-gray-200/50 shadow-xl rounded-2xl p-6 md:p-8"
              >
                <h2 className="mb-6">Account Settings</h2>
                <div className="space-y-3">
                  {[
                    { label: 'Change Password', icon: Settings },
                    { label: 'Email Preferences', icon: Settings },
                    { label: 'Privacy Settings', icon: Settings },
                  ].map((item, idx) => (
                    <motion.div
                      key={item.label}
                      initial={{ opacity: 0, x: -20 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ duration: 0.3, delay: idx * 0.1 }}
                      whileHover={{ x: 4 }}
                    >
                      <Button 
                        variant="outline" 
                        className="w-full justify-between bg-gradient-to-br from-white to-gray-50 hover:from-gray-50 hover:to-gray-100 border-gray-200 h-auto py-4"
                      >
                        <span className="flex items-center gap-3">
                          <div className="bg-gradient-to-br from-black to-gray-800 p-2 rounded-lg">
                            <item.icon className="h-4 w-4 text-white" />
                          </div>
                          {item.label}
                        </span>
                        <ChevronRight className="h-4 w-4 text-gray-400" />
                      </Button>
                    </motion.div>
                  ))}
                  
                  <motion.div
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ duration: 0.3, delay: 0.3 }}
                    whileHover={{ x: 4 }}
                    className="pt-3"
                  >
                    <Button 
                      variant="outline" 
                      className="w-full justify-between bg-gradient-to-br from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 border-red-200 text-red-600 hover:text-red-700 h-auto py-4"
                    >
                      <span className="flex items-center gap-3">
                        <div className="bg-red-600 p-2 rounded-lg">
                          <Settings className="h-4 w-4 text-white" />
                        </div>
                        Delete Account
                      </span>
                      <ChevronRight className="h-4 w-4" />
                    </Button>
                  </motion.div>
                </div>
              </motion.div>
            </AnimatePresence>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  );
}
