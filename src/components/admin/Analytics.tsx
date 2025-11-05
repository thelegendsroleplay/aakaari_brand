import { AnalyticsData } from '../../lib/types';
import { Card } from '../ui/card';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';
import { DollarSign, ShoppingCart, Users, Package } from 'lucide-react';

interface AnalyticsProps {
  data: AnalyticsData;
}

const COLORS = ['#10b981', '#3b82f6', '#f59e0b', '#6b7280'];

export function Analytics({ data }: AnalyticsProps) {
  return (
    <div className="space-y-6">
      <h2 className="text-2xl">Analytics Dashboard</h2>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <Card className="p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-600 mb-1">Total Revenue</p>
              <p className="text-2xl">${data.totalRevenue.toLocaleString()}</p>
            </div>
            <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
              <DollarSign className="h-6 w-6 text-green-600" />
            </div>
          </div>
        </Card>

        <Card className="p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-600 mb-1">Total Orders</p>
              <p className="text-2xl">{data.totalOrders}</p>
            </div>
            <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
              <ShoppingCart className="h-6 w-6 text-blue-600" />
            </div>
          </div>
        </Card>

        <Card className="p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-600 mb-1">Total Customers</p>
              <p className="text-2xl">{data.totalCustomers}</p>
            </div>
            <div className="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
              <Users className="h-6 w-6 text-purple-600" />
            </div>
          </div>
        </Card>

        <Card className="p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-600 mb-1">Total Products</p>
              <p className="text-2xl">{data.totalProducts}</p>
            </div>
            <div className="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
              <Package className="h-6 w-6 text-orange-600" />
            </div>
          </div>
        </Card>
      </div>

      {/* Charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Revenue Chart */}
        <Card className="p-6">
          <h3 className="text-lg mb-4">Monthly Revenue</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={data.revenueByMonth}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="month" />
              <YAxis />
              <Tooltip />
              <Bar dataKey="revenue" fill="#3b82f6" />
            </BarChart>
          </ResponsiveContainer>
        </Card>

        {/* Orders by Status */}
        <Card className="p-6">
          <h3 className="text-lg mb-4">Orders by Status</h3>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie
                data={data.ordersByStatus}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={({ status, count }) => `${status}: ${count}`}
                outerRadius={100}
                fill="#8884d8"
                dataKey="count"
              >
                {data.ordersByStatus.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
        </Card>
      </div>

      {/* Top Products */}
      <Card className="p-6">
        <h3 className="text-lg mb-4">Top Selling Products</h3>
        <div className="space-y-4">
          {data.topProducts.map((item, index) => (
            <div key={item.product.id} className="flex items-center justify-between">
              <div className="flex items-center gap-4">
                <span className="text-2xl text-gray-400">{index + 1}</span>
                <div>
                  <p>{item.product.name}</p>
                  <p className="text-sm text-gray-600">{item.product.category}</p>
                </div>
              </div>
              <div className="text-right">
                <p>{item.sales} sales</p>
                <p className="text-sm text-gray-600">${(item.product.price * item.sales).toFixed(2)}</p>
              </div>
            </div>
          ))}
        </div>
      </Card>
    </div>
  );
}
