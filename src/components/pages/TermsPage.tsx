import { Card } from '../ui/card';

export function TermsPage() {
  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-4xl mx-auto">
        <h1 className="text-5xl mb-4">Terms of Service</h1>
        <p className="text-gray-600 mb-8">Last updated: November 4, 2025</p>

        <div className="space-y-8">
          <Card className="p-6">
            <h2 className="text-2xl mb-4">1. Agreement to Terms</h2>
            <p className="text-gray-600 mb-4">
              By accessing and using FashionMen's website and services, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">2. Use License</h2>
            <p className="text-gray-600 mb-4">
              Permission is granted to temporarily download one copy of the materials on FashionMen's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>Modify or copy the materials</li>
              <li>Use the materials for any commercial purpose</li>
              <li>Attempt to decompile or reverse engineer any software</li>
              <li>Remove any copyright or proprietary notations</li>
              <li>Transfer the materials to another person</li>
            </ul>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">3. Product Information</h2>
            <p className="text-gray-600 mb-4">
              We strive to ensure that product information, including descriptions, images, and pricing, is accurate. However, we do not warrant that product descriptions or other content is accurate, complete, reliable, current, or error-free. If a product offered by FashionMen is not as described, your sole remedy is to return it in unused condition.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">4. Pricing and Payment</h2>
            <p className="text-gray-600 mb-4">
              All prices are subject to change without notice. We reserve the right to modify or discontinue products without prior notice. We shall not be liable to you or any third party for any modification, price change, suspension, or discontinuance of any product.
            </p>
            <p className="text-gray-600">
              Payment must be received by us before your order is processed. We accept various payment methods as displayed at checkout.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">5. User Accounts</h2>
            <p className="text-gray-600 mb-4">
              When you create an account with us, you must provide information that is accurate, complete, and current at all times. You are responsible for safeguarding the password and for all activities that occur under your account.
            </p>
            <p className="text-gray-600">
              You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">6. Customization Services</h2>
            <p className="text-gray-600 mb-4">
              Products marked as "Customizable" can be personalized according to your specifications. Due to the custom nature of these items:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>Customized products cannot be returned unless defective</li>
              <li>Customization requests must be submitted at time of order</li>
              <li>We are not responsible for errors in customization if the customer provided incorrect information</li>
              <li>Additional processing time is required for customized items</li>
            </ul>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">7. Prohibited Uses</h2>
            <p className="text-gray-600 mb-4">
              You may not use our site:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>For any unlawful purpose or to solicit others to perform unlawful acts</li>
              <li>To violate any international, federal, provincial or state regulations</li>
              <li>To infringe upon or violate our intellectual property rights</li>
              <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate</li>
              <li>To submit false or misleading information</li>
              <li>To upload or transmit viruses or any other type of malicious code</li>
            </ul>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">8. Limitation of Liability</h2>
            <p className="text-gray-600 mb-4">
              In no event shall FashionMen or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on FashionMen's website.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">9. Modifications</h2>
            <p className="text-gray-600 mb-4">
              FashionMen may revise these terms of service at any time without notice. By using this website you are agreeing to be bound by the current version of these Terms of Service.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">10. Governing Law</h2>
            <p className="text-gray-600">
              These terms and conditions are governed by and construed in accordance with the laws of the United States and you irrevocably submit to the exclusive jurisdiction of the courts in that location.
            </p>
          </Card>
        </div>
      </div>
    </div>
  );
}
