import { Card } from '../ui/card';
import { Package, Truck, Globe, Clock } from 'lucide-react';

export function ShippingPage() {
  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-4xl mx-auto">
        <h1 className="text-5xl mb-4">Shipping & Returns</h1>
        <p className="text-xl text-gray-600 mb-12">
          Everything you need to know about shipping, delivery, and returns
        </p>

        <div className="space-y-8">
          {/* Shipping Methods */}
          <Card className="p-6">
            <div className="flex items-center gap-3 mb-4">
              <div className="bg-black text-white p-2 rounded-lg">
                <Truck className="h-6 w-6" />
              </div>
              <h2 className="text-2xl">Shipping Methods</h2>
            </div>
            
            <div className="space-y-4">
              <div className="border-l-4 border-black pl-4">
                <h3 className="mb-1">Standard Shipping</h3>
                <p className="text-gray-600">
                  Delivery in 3-5 business days. <strong>Free on orders over $100</strong>, otherwise $5.99
                </p>
              </div>

              <div className="border-l-4 border-gray-300 pl-4">
                <h3 className="mb-1">Express Shipping</h3>
                <p className="text-gray-600">
                  Delivery in 1-2 business days - $14.99
                </p>
              </div>

              <div className="border-l-4 border-gray-300 pl-4">
                <h3 className="mb-1">Overnight Shipping</h3>
                <p className="text-gray-600">
                  Next business day delivery - $24.99
                </p>
              </div>
            </div>
          </Card>

          {/* International Shipping */}
          <Card className="p-6">
            <div className="flex items-center gap-3 mb-4">
              <div className="bg-black text-white p-2 rounded-lg">
                <Globe className="h-6 w-6" />
              </div>
              <h2 className="text-2xl">International Shipping</h2>
            </div>
            
            <p className="text-gray-600 mb-4">
              We ship to over 25 countries worldwide. International shipping rates and delivery times vary by destination:
            </p>

            <div className="grid md:grid-cols-2 gap-4">
              <div className="bg-gray-50 p-4 rounded-lg">
                <p className="mb-1">Canada & Mexico</p>
                <p className="text-sm text-gray-600">5-7 business days | Starting at $12.99</p>
              </div>
              <div className="bg-gray-50 p-4 rounded-lg">
                <p className="mb-1">Europe</p>
                <p className="text-sm text-gray-600">7-10 business days | Starting at $19.99</p>
              </div>
              <div className="bg-gray-50 p-4 rounded-lg">
                <p className="mb-1">Asia Pacific</p>
                <p className="text-sm text-gray-600">10-14 business days | Starting at $24.99</p>
              </div>
              <div className="bg-gray-50 p-4 rounded-lg">
                <p className="mb-1">Rest of World</p>
                <p className="text-sm text-gray-600">14-21 business days | Starting at $29.99</p>
              </div>
            </div>

            <p className="text-sm text-gray-600 mt-4">
              * International orders may be subject to customs duties and import taxes. These charges are the responsibility of the recipient.
            </p>
          </Card>

          {/* Processing Time */}
          <Card className="p-6">
            <div className="flex items-center gap-3 mb-4">
              <div className="bg-black text-white p-2 rounded-lg">
                <Clock className="h-6 w-6" />
              </div>
              <h2 className="text-2xl">Processing Time</h2>
            </div>
            
            <ul className="space-y-2 text-gray-600">
              <li className="flex items-start gap-2">
                <span className="mt-1">•</span>
                <span>Standard orders: 1-2 business days processing</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="mt-1">•</span>
                <span>Customized items: 3-5 business days processing (additional time required for personalization)</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="mt-1">•</span>
                <span>Orders placed after 2 PM EST will be processed the next business day</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="mt-1">•</span>
                <span>We do not process orders on weekends or holidays</span>
              </li>
            </ul>
          </Card>

          {/* Order Tracking */}
          <Card className="p-6">
            <div className="flex items-center gap-3 mb-4">
              <div className="bg-black text-white p-2 rounded-lg">
                <Package className="h-6 w-6" />
              </div>
              <h2 className="text-2xl">Order Tracking</h2>
            </div>
            
            <p className="text-gray-600 mb-4">
              Once your order ships, you'll receive an email with tracking information. You can also track your order by:
            </p>

            <ul className="space-y-2 text-gray-600">
              <li className="flex items-start gap-2">
                <span>1.</span>
                <span>Logging into your account and viewing your order history</span>
              </li>
              <li className="flex items-start gap-2">
                <span>2.</span>
                <span>Using the tracking number provided in your shipping confirmation email</span>
              </li>
              <li className="flex items-start gap-2">
                <span>3.</span>
                <span>Visiting our Order Tracking page and entering your order number</span>
              </li>
            </ul>
          </Card>

          {/* Returns */}
          <Card className="p-6">
            <h2 className="text-2xl mb-4">Return Policy</h2>
            
            <div className="space-y-4">
              <div>
                <h3 className="mb-2">30-Day Returns</h3>
                <p className="text-gray-600">
                  We offer a 30-day return policy from the date of delivery. Items must be unworn, unwashed, and in original condition with all tags attached.
                </p>
              </div>

              <div>
                <h3 className="mb-2">Free Returns</h3>
                <p className="text-gray-600">
                  Returns are free for US customers. International return shipping costs are the responsibility of the customer.
                </p>
              </div>

              <div>
                <h3 className="mb-2">How to Return</h3>
                <ol className="list-decimal list-inside text-gray-600 space-y-2">
                  <li>Log into your account and go to Order History</li>
                  <li>Select the item(s) you wish to return</li>
                  <li>Choose your return reason and preferred resolution (refund or exchange)</li>
                  <li>Print the prepaid return label</li>
                  <li>Package your item(s) securely and attach the label</li>
                  <li>Drop off at any authorized shipping location</li>
                </ol>
              </div>

              <div>
                <h3 className="mb-2">Refund Processing</h3>
                <p className="text-gray-600">
                  Once we receive your return, please allow 3-5 business days for inspection and processing. Refunds will be issued to your original payment method within 5-7 business days.
                </p>
              </div>

              <div className="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <h3 className="mb-2">Non-Returnable Items</h3>
                <ul className="text-gray-600 space-y-1">
                  <li>• Customized or personalized items (unless defective)</li>
                  <li>• Final sale items</li>
                  <li>• Gift cards</li>
                  <li>• Underwear and intimate apparel (for hygiene reasons)</li>
                </ul>
              </div>
            </div>
          </Card>

          {/* Exchanges */}
          <Card className="p-6">
            <h2 className="text-2xl mb-4">Exchanges</h2>
            <p className="text-gray-600 mb-4">
              Need a different size or color? We offer free exchanges for:
            </p>
            <ul className="space-y-2 text-gray-600">
              <li className="flex items-start gap-2">
                <span>•</span>
                <span>Different sizes of the same item</span>
              </li>
              <li className="flex items-start gap-2">
                <span>•</span>
                <span>Different colors of the same item</span>
              </li>
            </ul>
            <p className="text-gray-600 mt-4">
              Exchange processing is typically faster than returns. Your new item will be shipped as soon as we receive your original item back.
            </p>
          </Card>

          {/* Contact */}
          <Card className="p-6 bg-gray-50">
            <h2 className="text-2xl mb-4">Questions?</h2>
            <p className="text-gray-600">
              If you have any questions about shipping or returns, please contact our customer service team:
            </p>
            <p className="text-gray-600 mt-4">
              Email: support@fashionmen.com<br />
              Phone: +1 (555) 123-4567<br />
              Hours: Monday-Friday, 9 AM - 6 PM EST
            </p>
          </Card>
        </div>
      </div>
    </div>
  );
}
