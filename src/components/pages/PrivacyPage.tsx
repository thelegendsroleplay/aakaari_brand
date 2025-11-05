import { Card } from '../ui/card';

export function PrivacyPage() {
  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-4xl mx-auto">
        <h1 className="text-5xl mb-4">Privacy Policy</h1>
        <p className="text-gray-600 mb-8">Last updated: November 4, 2025</p>

        <div className="space-y-8">
          <Card className="p-6">
            <h2 className="text-2xl mb-4">1. Information We Collect</h2>
            <p className="text-gray-600 mb-4">
              We collect information that you provide directly to us, including:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>Name, email address, postal address, and phone number</li>
              <li>Payment information (processed securely through our payment providers)</li>
              <li>Account credentials</li>
              <li>Order history and preferences</li>
              <li>Communication preferences</li>
              <li>Product reviews and ratings</li>
              <li>Customization preferences and specifications</li>
            </ul>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">2. How We Use Your Information</h2>
            <p className="text-gray-600 mb-4">
              We use the information we collect to:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>Process and fulfill your orders</li>
              <li>Communicate with you about your orders and account</li>
              <li>Provide customer support</li>
              <li>Send you marketing communications (with your consent)</li>
              <li>Improve our products and services</li>
              <li>Prevent fraud and enhance security</li>
              <li>Comply with legal obligations</li>
              <li>Process customization requests</li>
            </ul>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">3. Information Sharing</h2>
            <p className="text-gray-600 mb-4">
              We do not sell your personal information. We may share your information with:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>Service providers who assist in our operations (shipping, payment processing, etc.)</li>
              <li>Law enforcement when required by law</li>
              <li>Business partners for joint marketing efforts (with your consent)</li>
              <li>Third parties in connection with a business transfer or sale</li>
            </ul>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">4. Cookies and Tracking Technologies</h2>
            <p className="text-gray-600 mb-4">
              We use cookies and similar tracking technologies to:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>Remember your preferences and settings</li>
              <li>Understand how you use our website</li>
              <li>Improve website performance</li>
              <li>Deliver personalized content and advertisements</li>
              <li>Analyze trends and user behavior</li>
            </ul>
            <p className="text-gray-600">
              You can control cookies through your browser settings, but disabling cookies may limit your ability to use certain features.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">5. Data Security</h2>
            <p className="text-gray-600 mb-4">
              We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>SSL encryption for data transmission</li>
              <li>Secure payment processing through PCI-compliant providers</li>
              <li>Regular security audits and updates</li>
              <li>Limited access to personal information</li>
              <li>Employee training on data protection</li>
            </ul>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">6. Your Rights</h2>
            <p className="text-gray-600 mb-4">
              Depending on your location, you may have the following rights:
            </p>
            <ul className="list-disc list-inside text-gray-600 space-y-2 mb-4">
              <li>Access to your personal information</li>
              <li>Correction of inaccurate information</li>
              <li>Deletion of your information</li>
              <li>Restriction of processing</li>
              <li>Data portability</li>
              <li>Object to processing</li>
              <li>Withdraw consent</li>
            </ul>
            <p className="text-gray-600">
              To exercise these rights, please contact us at privacy@fashionmen.com
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">7. Children's Privacy</h2>
            <p className="text-gray-600">
              Our services are not directed to children under 13. We do not knowingly collect personal information from children under 13. If you become aware that a child has provided us with personal information, please contact us.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">8. International Data Transfers</h2>
            <p className="text-gray-600">
              Your information may be transferred to and processed in countries other than your own. We ensure that such transfers comply with applicable data protection laws and implement appropriate safeguards.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">9. Changes to This Policy</h2>
            <p className="text-gray-600 mb-4">
              We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date.
            </p>
          </Card>

          <Card className="p-6">
            <h2 className="text-2xl mb-4">10. Contact Us</h2>
            <p className="text-gray-600">
              If you have any questions about this Privacy Policy, please contact us at:
            </p>
            <p className="text-gray-600 mt-4">
              Email: privacy@fashionmen.com<br />
              Phone: +1 (555) 123-4567<br />
              Address: 123 Fashion Street, New York, NY 10001
            </p>
          </Card>
        </div>
      </div>
    </div>
  );
}
