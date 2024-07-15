<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\d1nle2023;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class QRCodeController extends Controller
{
    public function generateQRCode($id)
    {
        // Fetch QR code data based on ID
        // dd($id);
        $item = d1nle2023::find($id);

        if (!$item) {
            abort(404);
        }

        // Generate QR code for the item
        $qrCode = QrCode::size(300)->generate($item->qrcode_id);

        // Return QR code image as response
        return response($qrCode)->header('Content-Type', 'image/png');
    }

}
