import { Page, Product, Order, User, AnalyticsData } from '../../lib/types';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../ui/tabs';
import { Analytics } from './Analytics';
import { ProductManager } from './ProductManager';
import { OrderManager } from './OrderManager';
import { UserManager } from './UserManager';
import { LayoutDashboard, Package, ShoppingCart, Users } from 'lucide-react';

interface AdminDashboardProps {
  products: Product[];
  orders: Order[];
  users: User[];
  analyticsData: AnalyticsData;
  onAddProduct: (product: Product) => void;
  onUpdateProduct: (productId: string, product: Product) => void;
  onDeleteProduct: (productId: string) => void;
  onUpdateOrderStatus: (orderId: string, status: Order['status']) => void;
  activeTab: string;
  onTabChange: (tab: string) => void;
}

export function AdminDashboard({
  products,
  orders,
  users,
  analyticsData,
  onAddProduct,
  onUpdateProduct,
  onDeleteProduct,
  onUpdateOrderStatus,
  activeTab,
  onTabChange,
}: AdminDashboardProps) {
  return (
    <div className="container mx-auto px-4 py-8">
      <div className="mb-8">
        <h1 className="text-3xl md:text-4xl mb-2">Admin Dashboard</h1>
        <p className="text-gray-600">Manage your e-commerce store</p>
      </div>

      <Tabs value={activeTab} onValueChange={onTabChange} className="space-y-6">
        <TabsList className="grid w-full grid-cols-2 md:grid-cols-4">
          <TabsTrigger value="analytics">
            <LayoutDashboard className="h-4 w-4 mr-2" />
            <span className="hidden sm:inline">Analytics</span>
          </TabsTrigger>
          <TabsTrigger value="products">
            <Package className="h-4 w-4 mr-2" />
            <span className="hidden sm:inline">Products</span>
          </TabsTrigger>
          <TabsTrigger value="orders">
            <ShoppingCart className="h-4 w-4 mr-2" />
            <span className="hidden sm:inline">Orders</span>
          </TabsTrigger>
          <TabsTrigger value="users">
            <Users className="h-4 w-4 mr-2" />
            <span className="hidden sm:inline">Users</span>
          </TabsTrigger>
        </TabsList>

        <TabsContent value="analytics">
          <Analytics data={analyticsData} />
        </TabsContent>

        <TabsContent value="products">
          <ProductManager
            products={products}
            onAddProduct={onAddProduct}
            onUpdateProduct={onUpdateProduct}
            onDeleteProduct={onDeleteProduct}
          />
        </TabsContent>

        <TabsContent value="orders">
          <OrderManager
            orders={orders}
            onUpdateOrderStatus={onUpdateOrderStatus}
          />
        </TabsContent>

        <TabsContent value="users">
          <UserManager users={users} />
        </TabsContent>
      </Tabs>
    </div>
  );
}
