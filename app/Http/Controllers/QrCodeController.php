<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\Ecommerce\Product;


class QrCodeController extends Controller
{
    public function generate_file_qr(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        // Retrieve the file_url parameter from the request
        $file_url = $product->file_url;

        // Decode the URL parameter
        $file_url = urldecode($file_url);

        // For debugging: Log the file_url to ensure it's received correctly
        \Log::info('Generating QR code for URL: ' . $file_url);

        // Generate the QR code as an SVG
        $qrCode = QrCode::size(300)->generate(env('APP_URL') . '/public/' . $file_url);

        // Pass the QR code to the view
        return view('admin.ecommerce.products.file-qr', compact('qrCode', 'product'));
    }
    
    public function generate_product_qr(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();

        $page = new Page();
        $page->name = $product->name  . ' QR Code';

        // Retrieve the file_url parameter from the request
        $file_url = $product->file_url;

        // Decode the URL parameter
        $file_url = urldecode($file_url);

        // For debugging: Log the file_url to ensure it's received correctly
        \Log::info('Generating QR code for URL: ' . $file_url);

        // Generate the QR code as an SVG
        $qrCode = QrCode::size(300)->generate(route('product.series'));

        // Pass the QR code to the view
        return view('theme.pages.ecommerce.product-qr', compact('qrCode', 'product', 'page'));
    }

    public function product_series(){

        $page = new Page();
        $page->name = 'Product Series';
        
        return view('theme.pages.ecommerce.product-series', compact('page'));
    }
}

