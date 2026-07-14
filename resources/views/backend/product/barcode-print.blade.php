<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Barcode Print - {{ count($labels) }} labels</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #eef0f2;
            -webkit-print-color-adjust: exact; /* প্রিন্টের কালার ঠিক রাখার জন্য */
            print-color-adjust: exact;
        }

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #212b36;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .toolbar .info { font-size: 14px; }
        .toolbar button {
            background: #fff;
            color: #212b36;
            border: none;
            padding: 8px 22px;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }
        .toolbar button:hover { background: #f1f1f1; }

        .sheet {
            display: grid;
            grid-template-columns: repeat({{ $columns }}, {{ $label_width }}mm);
            gap: {{ $gap }}mm;
            justify-content: center;
            padding: 10mm;
        }

        .label {
            width: {{ $label_width }}mm;
            height: {{ $label_height }}mm;
            border: 1px dashed #bbb;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            padding: 1mm 1.5mm;
            
            /* পেজ ব্রেক ফিক্স - লেবেল অর্ধেক কাটবে না */
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .label .name {
            font-size: 8px;
            font-weight: bold;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .label .barcode-svg {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .label .barcode-svg svg {
            width: 90%;
            height: auto;
            max-height: {{ max($label_height * 0.45, 8) }}mm;
        }

        .label .sku {
            font-size: 7px;
            letter-spacing: 1px;
            margin-top: 0.5mm;
            font-family: 'Courier New', monospace;
        }

        .label .price {
            font-size: 9px;
            font-weight: bold;
            margin-top: 0.5mm;
        }

        @media print {
            .toolbar { display: none !important; }
            body { 
                background: #fff; 
                margin: 0; 
            }
            
            .sheet { 
                padding: {{ $columns == 1 ? '0' : '2mm' }}; 
            }
            
            .label { 
                border: none; 
            }

            @page {
                size: {{ $columns == 1 ? $label_width.'mm '.$label_height.'mm' : 'auto' }};
                margin: 0; 
            }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <div class="info">মোট লেবেল: <strong>{{ count($labels) }}</strong> টি &nbsp;|&nbsp; Label size: {{ $label_width }}mm x {{ $label_height }}mm &nbsp;|&nbsp; Columns: {{ $columns }}</div>
        <button onclick="window.print()">🖨 Print</button>
    </div>

    <div class="sheet">
        @forelse ($labels as $label)
            <div class="label">
                @if($show_name)
                    <div class="name">{{ $label['name'] }}</div>
                @endif

                <div class="barcode-svg">{!! $label['barcode'] !!}</div>

                <div class="sku">{{ $label['sku'] }}</div>

                @if($show_price)
                    <div class="price">৳ {{ number_format($label['price'], 2) }}</div>
                @endif
            </div>
        @empty
            <p>কোনো লেবেল পাওয়া যায়নি।</p>
        @endforelse
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () { window.print(); }, 500);
        };
    </script>
</body>
</html>