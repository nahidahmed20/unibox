<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductDemoExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'name', 'category', 'subcategory', 'brand', 'unit', 
            'purchase_price', 'selling_price', 'main_price', 'discount_type', 
            'alert_quantity', 'sku', 'barcode', 'sizes', 'colors', 
            'main_image', 'short_description', 'description', 'status'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Premium Cotton T-Shirt', 'Mens Fashion', 'T-Shirts', 'Easy', 'Piece',
                '500', '800', '1000', 'fixed', '10', 'TSH-1001', '890123456789', 
                'S, M, L, XL', 'Red, Black, White', 'tshirt-main.jpg', 
                'High quality cotton t-shirt', 'Full description goes here...', '1'
            ],
            [
                'Slim Fit Jeans', 'Mens Fashion', 'Pants', 'Leads', 'Piece',
                '800', '1200', '1500', 'percent', '5', 'JNS-2002', '', 
                '30, 32, 34', 'Blue, Grey', '', 
                'Stretchable slim fit jeans', 'Full description...', '1'
            ]
        ];
    }

    // হেডার বোল্ড করার জন্য
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}