import { AdminDashboard } from '../../components/admin/AdminDashboard';
import { Product, Order, User, AnalyticsData } from '../../lib/types';

interface AdminDashboardContainerProps {
  products: Product[];
  orders: Order[];
  users: User[];
  analyticsData: AnalyticsData;
  onAddProduct: (product: Product) => void;
  onUpdateProduct: (productId: string, updatedProduct: Product) => void;
  onDeleteProduct: (productId: string) => void;
  onUpdateOrderStatus: (orderId: string, status: Order['status']) => void;
  activeTab: string;
  onTabChange: (tab: string) => void;
}

export function AdminDashboardContainer({
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
}: AdminDashboardContainerProps) {
  return (
    <AdminDashboard
      products={products}
      orders={orders}
      users={users}
      analyticsData={analyticsData}
      onAddProduct={onAddProduct}
      onUpdateProduct={onUpdateProduct}
      onDeleteProduct={onDeleteProduct}
      onUpdateOrderStatus={onUpdateOrderStatus}
      activeTab={activeTab}
      onTabChange={onTabChange}
    />
  );
}
