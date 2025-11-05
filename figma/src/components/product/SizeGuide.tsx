import { Dialog, DialogContent, DialogHeader, DialogTitle } from '../ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../ui/tabs';

interface SizeGuideProps {
  isOpen: boolean;
  onClose: () => void;
  category?: string;
}

export function SizeGuide({ isOpen, onClose, category = 'shirts' }: SizeGuideProps) {
  const shirtSizes = [
    { size: 'XS', chest: '34-36', waist: '28-30', hips: '34-36' },
    { size: 'S', chest: '36-38', waist: '30-32', hips: '36-38' },
    { size: 'M', chest: '38-40', waist: '32-34', hips: '38-40' },
    { size: 'L', chest: '40-42', waist: '34-36', hips: '40-42' },
    { size: 'XL', chest: '42-44', waist: '36-38', hips: '42-44' },
    { size: 'XXL', chest: '44-46', waist: '38-40', hips: '44-46' },
  ];

  const pantSizes = [
    { size: '28', waist: '28', inseam: '30-34', hips: '34-36' },
    { size: '30', waist: '30', inseam: '30-34', hips: '36-38' },
    { size: '32', waist: '32', inseam: '30-34', hips: '38-40' },
    { size: '34', waist: '34', inseam: '30-34', hips: '40-42' },
    { size: '36', waist: '36', inseam: '30-34', hips: '42-44' },
    { size: '38', waist: '38', inseam: '30-34', hips: '44-46' },
  ];

  const shoeSizes = [
    { us: '7', uk: '6', eu: '40', cm: '25' },
    { us: '8', uk: '7', eu: '41', cm: '26' },
    { us: '9', uk: '8', eu: '42', cm: '27' },
    { us: '10', uk: '9', eu: '43', cm: '28' },
    { us: '11', uk: '10', eu: '44', cm: '29' },
    { us: '12', uk: '11', eu: '45', cm: '30' },
  ];

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="max-w-3xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>Size Guide</DialogTitle>
        </DialogHeader>

        <Tabs defaultValue="shirts" className="mt-4">
          <TabsList className="grid w-full grid-cols-3">
            <TabsTrigger value="shirts">Shirts & Tops</TabsTrigger>
            <TabsTrigger value="pants">Pants</TabsTrigger>
            <TabsTrigger value="shoes">Shoes</TabsTrigger>
          </TabsList>

          <TabsContent value="shirts" className="space-y-4">
            <div>
              <h3 className="mb-2">Shirts & Jackets Size Chart</h3>
              <p className="text-sm text-gray-600 mb-4">
                All measurements are in inches
              </p>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Size</TableHead>
                    <TableHead>Chest</TableHead>
                    <TableHead>Waist</TableHead>
                    <TableHead>Hips</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {shirtSizes.map((row) => (
                    <TableRow key={row.size}>
                      <TableCell>{row.size}</TableCell>
                      <TableCell>{row.chest}"</TableCell>
                      <TableCell>{row.waist}"</TableCell>
                      <TableCell>{row.hips}"</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>

            <div className="bg-gray-50 p-4 rounded-lg">
              <h4 className="mb-2">How to Measure</h4>
              <ul className="space-y-2 text-sm text-gray-600">
                <li>
                  <strong>Chest:</strong> Measure around the fullest part of your chest
                </li>
                <li>
                  <strong>Waist:</strong> Measure around your natural waistline
                </li>
                <li>
                  <strong>Hips:</strong> Measure around the fullest part of your hips
                </li>
              </ul>
            </div>
          </TabsContent>

          <TabsContent value="pants" className="space-y-4">
            <div>
              <h3 className="mb-2">Pants Size Chart</h3>
              <p className="text-sm text-gray-600 mb-4">
                All measurements are in inches
              </p>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Size</TableHead>
                    <TableHead>Waist</TableHead>
                    <TableHead>Inseam</TableHead>
                    <TableHead>Hips</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {pantSizes.map((row) => (
                    <TableRow key={row.size}>
                      <TableCell>{row.size}</TableCell>
                      <TableCell>{row.waist}"</TableCell>
                      <TableCell>{row.inseam}"</TableCell>
                      <TableCell>{row.hips}"</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>

            <div className="bg-gray-50 p-4 rounded-lg">
              <h4 className="mb-2">How to Measure</h4>
              <ul className="space-y-2 text-sm text-gray-600">
                <li>
                  <strong>Waist:</strong> Measure around your natural waistline
                </li>
                <li>
                  <strong>Inseam:</strong> Measure from crotch to ankle
                </li>
                <li>
                  <strong>Hips:</strong> Measure around the fullest part of your hips
                </li>
              </ul>
            </div>
          </TabsContent>

          <TabsContent value="shoes" className="space-y-4">
            <div>
              <h3 className="mb-2">Shoe Size Chart</h3>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>US</TableHead>
                    <TableHead>UK</TableHead>
                    <TableHead>EU</TableHead>
                    <TableHead>CM</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {shoeSizes.map((row) => (
                    <TableRow key={row.us}>
                      <TableCell>{row.us}</TableCell>
                      <TableCell>{row.uk}</TableCell>
                      <TableCell>{row.eu}</TableCell>
                      <TableCell>{row.cm}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>

            <div className="bg-gray-50 p-4 rounded-lg">
              <h4 className="mb-2">How to Measure Your Feet</h4>
              <ul className="space-y-2 text-sm text-gray-600">
                <li>Stand on a piece of paper and trace your foot</li>
                <li>Measure the length from heel to longest toe</li>
                <li>Measure in centimeters for best accuracy</li>
                <li>If between sizes, we recommend sizing up</li>
              </ul>
            </div>
          </TabsContent>
        </Tabs>
      </DialogContent>
    </Dialog>
  );
}
