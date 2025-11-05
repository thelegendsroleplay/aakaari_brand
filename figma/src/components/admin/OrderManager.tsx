import { useState } from 'react';
import { Order } from '../../lib/types';
import { Card } from '../ui/card';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Input } from '../ui/input';
import { Search } from 'lucide-react';
import { toast } from 'sonner@2.0.3';

interface OrderManagerProps {
  orders: Order[];
  onUpdateOrderStatus: (orderId: string, status: Order['status']) => void;
}

export function OrderManager({ orders, onUpdateOrderStatus }: OrderManagerProps) {
  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState<string>('all');

  const filteredOrders = orders.filter((order) => {
    const matchesSearch = order.id.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         order.shippingAddress.fullName.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = filterStatus === 'all' || order.status === filterStatus;
    return matchesSearch && matchesStatus;
  });

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'delivered':
        return 'bg-green-100 text-green-800';
      case 'shipped':
        return 'bg-blue-100 text-blue-800';
      case 'processing':
        return 'bg-yellow-100 text-yellow-800';
      case 'pending':
        return 'bg-gray-100 text-gray-800';
      case 'cancelled':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const handleStatusChange = (orderId: string, newStatus: Order['status']) => {
    onUpdateOrderStatus(orderId, newStatus);
    toast.success('Order status updated');
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between gap-4">
        <h2 className="text-2xl">Order Management</h2>
        <div className="flex gap-2">
          <div className="relative flex-1 sm:flex-none sm:w-64">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <Input
              placeholder="Search orders..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
          <Select value={filterStatus} onValueChange={setFilterStatus}>
            <SelectTrigger className="w-32">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">All</SelectItem>
              <SelectItem value="pending">Pending</SelectItem>
              <SelectItem value="processing">Processing</SelectItem>
              <SelectItem value="shipped">Shipped</SelectItem>
              <SelectItem value="delivered">Delivered</SelectItem>
              <SelectItem value="cancelled">Cancelled</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div className="space-y-4">
        {filteredOrders.length === 0 ? (
          <Card className="p-8 text-center text-gray-600">
            No orders found
          </Card>
        ) : (
          filteredOrders.map((order) => (
            <Card key={order.id} className="p-6">
              <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
                <div>
                  <div className="flex items-center gap-3 mb-2">
                    <h3>Order #{order.id}</h3>
                    <Badge className={getStatusColor(order.status)}>
                      {order.status}
                    </Badge>
                    <Badge variant="outline" className={
                      order.paymentStatus === 'paid' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700'
                    }>
                      {order.paymentStatus}
                    </Badge>
                  </div>
                  <p className="text-sm text-gray-600 mb-1">
                    Customer: {order.shippingAddress.fullName}
                  </p>
                  <p className="text-sm text-gray-600">
                    Placed: {new Date(order.createdAt).toLocaleDateString()}
                  </p>
                </div>
                <div className="flex items-center gap-3">
                  <div className="text-right mr-4">
                    <p className="text-lg">${order.total.toFixed(2)}</p>
                    <p className="text-sm text-gray-600">{order.items.length} item(s)</p>
                  </div>
                  <Select
                    value={order.status}
                    onValueChange={(value) => handleStatusChange(order.id, value as Order['status'])}
                  >
                    <SelectTrigger className="w-40">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="pending">Pending</SelectItem>
                      <SelectItem value="processing">Processing</SelectItem>
                      <SelectItem value="shipped">Shipped</SelectItem>
                      <SelectItem value="delivered">Delivered</SelectItem>
                      <SelectItem value="cancelled">Cancelled</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="border-t pt-4">
                <h4 className="text-sm mb-2">Order Items:</h4>
                {order.items.map((item, idx) => (
                  <div key={idx} className="flex justify-between text-sm mb-2">
                    <span className="text-gray-600">
                      {item.product.name} - {item.size}/{item.color} (x{item.quantity})
                    </span>
                    <span>${(item.product.price * item.quantity).toFixed(2)}</span>
                  </div>
                ))}
              </div>

              <div className="border-t pt-4 mt-4">
                <h4 className="text-sm mb-2">Shipping Address:</h4>
                <p className="text-sm text-gray-600">
                  {order.shippingAddress.street}, {order.shippingAddress.city}, {order.shippingAddress.state} {order.shippingAddress.zipCode}
                </p>
              </div>

              <div className="flex gap-2 mt-4">
                <Button variant="outline" size="sm">View Details</Button>
                <Button variant="outline" size="sm">Print Invoice</Button>
              </div>
            </Card>
          ))
        )}
      </div>
    </div>
  );
}
