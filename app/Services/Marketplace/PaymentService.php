<?php

namespace App\Services\Marketplace;

use App\Models\MarketplaceSell;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PaypalSdk;
use Stripe\Stripe;
use Stripe\Charge;
use Exception;

class PaymentService
{
    protected $paypalProvider;

    public function __construct()
    {
        $this->initializePayPal();
    }

    protected function initializePayPal()
    {
        $this->paypalProvider = new PaypalSdk;
        $this->paypalProvider->setApiCredentials(config('paypal'));
        $this->paypalProvider->getAccessToken();
    }

    // Create marketplace order record
    public function createOrder(array $data, string $gateway): MarketplaceSell
    {
        return MarketplaceSell::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? auth()->user()->email,
            'email_amount' => $data['email_amount'],
            'country' => $data['country'],
            'price' => str_replace(['$', ','], '', $data['price']),
            'type' => $data['type'],
            'status' => 'pending',
            'gateway' => $gateway,
        ]);
    }

    // PayPal payment processing
    public function processPayPalPayment(MarketplaceSell $order)
    {
        Session::put('sale_id', $order->id);

        $response = $this->paypalProvider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('getPaymentStatusMarketplace'),
                'cancel_url' => route('payment.cancel'),
            ],
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $order->price,
                    ],
                    'description' => "{$order->country} contacts purchase"
                ],
            ],
        ]);

        if (!isset($response['links'])) {
            throw new Exception('Failed to initialize PayPal payment');
        }

        $approveLink = collect($response['links'])->where('rel', 'approve')->first();
        return redirect()->away($approveLink['href']);
    }

    // PayPal payment callback handling
    public function handlePayPalCallback(Request $request)
    {
        $saleId = Session::get('sale_id');
        if (!$saleId) {
            throw new Exception('Order session expired');
        }

        $order = MarketplaceSell::findOrFail($saleId);
        $response = $this->paypalProvider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $filePath = $this->generateCsvFile($order);
            $this->sendEmailWithAttachment($order, $filePath);
            $this->updateOrderStatus($order->id, 'paid', $filePath);
            return true;
        }

        return false;
    }

    // Stripe payment processing
    public function processStripePayment(MarketplaceSell $order, string $token)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $charge = Charge::create([
            'amount' => (int)($order->price * 100),
            'currency' => 'usd',
            'source' => $token,
            'description' => "{$order->country} contacts purchase",
        ]);

        if ($charge->paid) {
            $filePath = $this->generateCsvFile($order);
            $this->sendEmailWithAttachment($order, $filePath);
            $this->updateOrderStatus($order->id, 'paid', $filePath);
            return true;
        }

        return false;
    }

    // Generate CSV file for purchased contacts
    public function generateCsvFile(MarketplaceSell $order): string
    {
        $countryCode = $order->country;
        $amount = $order->email_amount;

        // Get local file path instead of URL
        $csvPath = public_path('uploads/marketplace/csv/email/' . $countryCode . '.csv');

        if (!file_exists($csvPath)) {
            throw new Exception("CSV file not found for {$countryCode}");
        }

        $outputDir = public_path('marketplace/output/');
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $generatedName = $countryCode . '-emails-' . rand(1000, 10000);
        $outputFile = $outputDir . $generatedName;

        $in = fopen($csvPath, 'r');
        if (!$in) {
            throw new Exception("Failed to open CSV file: $csvPath");
        }

        $out = null;
        $fileCount = 1;
        $rowsProcessed = 0;
        $fileName = $outputFile . sprintf('%04d', $fileCount) . '.csv';
        $out = fopen($fileName, 'w');

        // Read and process the CSV
        while (($data = fgetcsv($in)) !== false) {
            if ($rowsProcessed >= $amount) break;

            fputcsv($out, $data);
            $rowsProcessed++;
        }

        fclose($out);
        fclose($in);

        return $fileName;
    }

    // Send email with attachment
    protected function sendEmailWithAttachment(MarketplaceSell $order, string $filePath)
    {
        $data = [
            'email' => $order->email,
            'title' => 'Your Purchased Contacts - ' . config('app.name')
        ];

        Mail::send('emails.marketplace_contacts', $data, function ($message) use ($data, $filePath) {
            $message->to($data['email'])
                ->subject($data['title'])
                ->attach($filePath);
        });
    }

    // Update order status
    protected function updateOrderStatus(int $orderId, string $status, ?string $filePath = null)
    {
        $updateData = ['status' => $status];
        if ($filePath) $updateData['file_path'] = $filePath;

        MarketplaceSell::where('id', $orderId)->update($updateData);
    }
}
