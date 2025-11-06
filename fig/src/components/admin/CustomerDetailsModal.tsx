import React, { useState } from 'react';
import { X, Mail, Phone, MapPin, Calendar, ShoppingBag, DollarSign, Ban, Unlock, Trash2, Key } from 'lucide-react';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../ui/tabs';

interface Customer {
  id: string;
  name: string;
  email: string;
  phone?: string;
  address?: string;
  joinedDate: string;
  totalOrders: number;
  totalSpent: number;
  status: 'active' | 'banned';
}

interface CustomerDetailsModalProps {
  email: string;
  onClose: () => void;
}

export const CustomerDetailsModal: React.FC<CustomerDetailsModalProps> = ({
  email,
  onClose
}) => {
  // Mock customer data based on email
  const customerData = {
    '1': { id: '1', name: 'John Doe', email: 'john@example.com', phone: '+1 234-567-8900', address: '123 Main St, New York, NY 10001', joinedDate: '2024-01-15', totalOrders: 5, totalSpent: 1234.56, status: 'active' as const },
    '2': { id: '2', name: 'Jane Smith', email: 'jane@example.com', phone: '+1 234-567-8901', address: '456 Oak Ave, Los Angeles, CA 90001', joinedDate: '2024-02-20', totalOrders: 3, totalSpent: 856.99, status: 'active' as const },
    '3': { id: '3', name: 'Mike Johnson', email: 'mike@example.com', phone: '+1 234-567-8902', address: '789 Pine Rd, Chicago, IL 60601', joinedDate: '2023-12-10', totalOrders: 8, totalSpent: 2145.88, status: 'active' as const },
  };
  
  const customer = Object.values(customerData).find(c => c.email === email) || customerData['1'];
  
  const [activeTab, setActiveTab] = useState('details');
  const [editedCustomer, setEditedCustomer] = useState(customer);
  const [isEditing, setIsEditing] = useState(false);

  const handleSave = () => {
    // onUpdate would be implemented here
    setIsEditing(false);
  };

  const handleDelete = () => {
    if (window.confirm(`Are you sure you want to delete ${customer.name}? This action cannot be undone.`)) {
      // onDelete would be implemented here
      onClose();
    }
  };

  const handleBanToggle = () => {
    const action = customer.status === 'active' ? 'ban' : 'unban';
    if (window.confirm(`Are you sure you want to ${action} ${customer.name}?`)) {
      // onBanToggle would be implemented here
    }
  };

  const handleResetPassword = () => {
    if (window.confirm(`Send password reset email to ${customer.email}?`)) {
      // onResetPassword would be implemented here
      alert('Password reset email sent!');
    }
  };

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        {/* Header */}
        <div className="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
          <div>
            <h2 className="text-xl font-semibold">Customer Details</h2>
            <p className="text-sm text-gray-600">ID: {customer.id}</p>
          </div>
          <Button variant="ghost" size="icon" onClick={onClose}>
            <X className="w-5 h-5" />
          </Button>
        </div>

        {/* Content */}
        <div className="flex-1 overflow-y-auto">
          <Tabs value={activeTab} onValueChange={setActiveTab}>
            <div className="px-6 pt-4">
              <TabsList>
                <TabsTrigger value="details">Details</TabsTrigger>
                <TabsTrigger value="orders">Orders</TabsTrigger>
                <TabsTrigger value="activity">Activity</TabsTrigger>
              </TabsList>
            </div>

            {/* Details Tab */}
            <TabsContent value="details" className="p-6 space-y-6">
              {/* Status Badge */}
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className={`px-3 py-1 rounded-full text-sm font-medium ${
                    customer.status === 'active' 
                      ? 'bg-green-100 text-green-700' 
                      : 'bg-red-100 text-red-700'
                  }`}>
                    {customer.status === 'active' ? 'Active' : 'Banned'}
                  </div>
                </div>
                <Button 
                  variant="outline" 
                  size="sm"
                  onClick={() => setIsEditing(!isEditing)}
                >
                  {isEditing ? 'Cancel' : 'Edit Details'}
                </Button>
              </div>

              {/* Customer Info */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <Label htmlFor="name">Full Name</Label>
                  <Input
                    id="name"
                    value={editedCustomer.name}
                    onChange={(e) => setEditedCustomer({ ...editedCustomer, name: e.target.value })}
                    disabled={!isEditing}
                  />
                </div>

                <div>
                  <Label htmlFor="email">Email Address</Label>
                  <div className="flex gap-2">
                    <Input
                      id="email"
                      type="email"
                      value={editedCustomer.email}
                      onChange={(e) => setEditedCustomer({ ...editedCustomer, email: e.target.value })}
                      disabled={!isEditing}
                    />
                    <Mail className="w-5 h-5 text-gray-400 self-center" />
                  </div>
                </div>

                <div>
                  <Label htmlFor="phone">Phone Number</Label>
                  <div className="flex gap-2">
                    <Input
                      id="phone"
                      value={editedCustomer.phone || ''}
                      onChange={(e) => setEditedCustomer({ ...editedCustomer, phone: e.target.value })}
                      disabled={!isEditing}
                      placeholder="Not provided"
                    />
                    <Phone className="w-5 h-5 text-gray-400 self-center" />
                  </div>
                </div>

                <div>
                  <Label htmlFor="joinedDate">Joined Date</Label>
                  <div className="flex gap-2">
                    <Input
                      id="joinedDate"
                      value={editedCustomer.joinedDate}
                      disabled
                    />
                    <Calendar className="w-5 h-5 text-gray-400 self-center" />
                  </div>
                </div>
              </div>

              {/* Address */}
              <div>
                <Label htmlFor="address">Address</Label>
                <div className="flex gap-2">
                  <Input
                    id="address"
                    value={editedCustomer.address || ''}
                    onChange={(e) => setEditedCustomer({ ...editedCustomer, address: e.target.value })}
                    disabled={!isEditing}
                    placeholder="Not provided"
                  />
                  <MapPin className="w-5 h-5 text-gray-400 self-center" />
                </div>
              </div>

              {/* Stats */}
              <div className="grid grid-cols-2 gap-4 pt-4 border-t">
                <div className="bg-blue-50 rounded-lg p-4">
                  <div className="flex items-center gap-2 text-blue-600 mb-1">
                    <ShoppingBag className="w-4 h-4" />
                    <span className="text-sm font-medium">Total Orders</span>
                  </div>
                  <p className="text-2xl font-bold text-gray-900">{customer.totalOrders}</p>
                </div>
                <div className="bg-green-50 rounded-lg p-4">
                  <div className="flex items-center gap-2 text-green-600 mb-1">
                    <DollarSign className="w-4 h-4" />
                    <span className="text-sm font-medium">Total Spent</span>
                  </div>
                  <p className="text-2xl font-bold text-gray-900">${customer.totalSpent.toFixed(2)}</p>
                </div>
              </div>

              {/* Save Changes */}
              {isEditing && (
                <div className="flex gap-2">
                  <Button onClick={handleSave} className="flex-1">
                    Save Changes
                  </Button>
                  <Button variant="outline" onClick={() => setIsEditing(false)}>
                    Cancel
                  </Button>
                </div>
              )}
            </TabsContent>

            {/* Orders Tab */}
            <TabsContent value="orders" className="p-6">
              <div className="text-center py-12 text-gray-500">
                <ShoppingBag className="w-12 h-12 mx-auto mb-3 text-gray-400" />
                <p>Order history will be displayed here</p>
                <p className="text-sm mt-1">Customer has {customer.totalOrders} orders</p>
              </div>
            </TabsContent>

            {/* Activity Tab */}
            <TabsContent value="activity" className="p-6">
              <div className="space-y-3">
                <div className="border-l-2 border-blue-500 pl-4 py-2">
                  <p className="text-sm font-medium">Account Created</p>
                  <p className="text-xs text-gray-500">{customer.joinedDate}</p>
                </div>
                <div className="border-l-2 border-gray-300 pl-4 py-2">
                  <p className="text-sm font-medium">Last Login</p>
                  <p className="text-xs text-gray-500">2 hours ago</p>
                </div>
                <div className="border-l-2 border-gray-300 pl-4 py-2">
                  <p className="text-sm font-medium">Last Order</p>
                  <p className="text-xs text-gray-500">5 days ago</p>
                </div>
              </div>
            </TabsContent>
          </Tabs>
        </div>

        {/* Actions Footer */}
        <div className="border-t border-gray-200 px-6 py-4 bg-gray-50">
          <div className="flex flex-wrap gap-2">
            <Button 
              variant="outline" 
              size="sm"
              onClick={handleResetPassword}
            >
              <Key className="w-4 h-4 mr-2" />
              Reset Password
            </Button>
            <Button 
              variant="outline" 
              size="sm"
              onClick={handleBanToggle}
              className={customer.status === 'active' ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700'}
            >
              {customer.status === 'active' ? (
                <>
                  <Ban className="w-4 h-4 mr-2" />
                  Ban Customer
                </>
              ) : (
                <>
                  <Unlock className="w-4 h-4 mr-2" />
                  Unban Customer
                </>
              )}
            </Button>
            <Button 
              variant="outline" 
              size="sm"
              onClick={handleDelete}
              className="text-red-600 hover:text-red-700 ml-auto"
            >
              <Trash2 className="w-4 h-4 mr-2" />
              Delete Customer
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
};