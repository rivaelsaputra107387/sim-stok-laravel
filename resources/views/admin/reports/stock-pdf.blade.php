<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang - {{ $generated_at->format('d/m/Y H:i') }}</title>
    <style>
        @page {
            margin: 15mm 10mm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            background: white;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .header .company-name {
            font-size: 16px;
            color: #34495e;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .header .report-info {
            font-size: 11px;
            color: #7f8c8d;
            font-style: italic;
        }

        /* Filter Information */
        .filter-info {
            background: #ecf0f1;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #3498db;
            border-radius: 0 5px 5px 0;
        }

        .filter-info h3 {
            font-size: 13px;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: bold;
        }

        .filter-grid {
            display: table;
            width: 100%;
        }

        .filter-row {
            display: table-row;
        }

        .filter-item {
            display: table-cell;
            width: 33.33%;
            padding: 3px 15px 3px 0;
            font-size: 10px;
            vertical-align: top;
        }

        .filter-item strong {
            color: #2c3e50;
            display: inline-block;
            min-width: 80px;
        }

        /* Summary Section */
        .summary-section {
            margin-bottom: 25px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #34495e;
        }

        .summary-table th {
            background: #34495e;
            color: white;
            padding: 12px 8px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #2c3e50;
        }

        .summary-table td {
            padding: 12px 8px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #bdc3c7;
        }

        .summary-total { background: #3498db; color: white; }
        .summary-in-stock { background: #27ae60; color: white; }
        .summary-low-stock { background: #f39c12; color: white; }
        .summary-out-stock { background: #e74c3c; color: white; }
        .summary-stock-info { background: #95a5a6; color: white; }

        /* Main Table */
        .table-container {
            width: 100%;
            margin-top: 20px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            border: 2px solid #34495e;
        }

        .main-table th {
            background: #34495e;
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #2c3e50;
            vertical-align: middle;
        }

        .main-table td {
            padding: 8px 6px;
            border: 1px solid #ddd;
            vertical-align: middle;
            font-size: 9px;
        }

        .main-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .main-table tr:nth-child(odd) {
            background: white;
        }

        /* Column widths */
        .col-no { width: 4%; }
        .col-code { width: 10%; }
        .col-name { width: 28%; }
        .col-category { width: 15%; }
        .col-unit { width: 8%; }
        .col-current { width: 10%; }
        .col-minimum { width: 10%; }
        .col-status { width: 12%; }
        .col-alert { width: 3%; }

        /* Utilities */
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .text-left { text-align: left !important; }

        /* Status Badges */
        .stock-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-normal { background: #27ae60; }
        .status-low { background: #f39c12; }
        .status-out { background: #e74c3c; }

        /* Unit badges */
        .unit-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
        }

        .unit-krg { background: #3498db; }
        .unit-dus { background: #9b59b6; }

        /* Product name styling */
        .product-name {
            font-weight: bold;
            font-size: 9px;
            color: #2c3e50;
            line-height: 1.2;
        }

        .product-desc {
            color: #7f8c8d;
            font-size: 7px;
            font-style: italic;
            margin-top: 2px;
            line-height: 1.1;
        }

        /* Stock numbers */
        .stock-number {
            font-weight: bold;
            font-size: 10px;
            color: #2c3e50;
        }

        /* Alert indicators */
        .alert-icon {
            font-size: 12px;
        }

        .alert-danger { color: #e74c3c; }
        .alert-success { color: #27ae60; }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #bdc3c7;
            font-size: 9px;
            color: #7f8c8d;
        }

        .footer-grid {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .footer strong {
            color: #2c3e50;
        }

        /* Legend */
        .legend {
            margin-top: 25px;
            padding: 15px;
            background: #ecf0f1;
            border-left: 5px solid #3498db;
            border-radius: 0 5px 5px 0;
        }

        .legend h4 {
            font-size: 11px;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: bold;
        }

        .legend-content {
            font-size: 9px;
            line-height: 1.4;
        }

        .legend-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
            vertical-align: middle;
        }

        .dot-normal { background: #27ae60; }
        .dot-low { background: #f39c12; }
        .dot-out { background: #e74c3c; }

        /* No data message */
        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #7f8c8d;
            font-style: italic;
        }

        .no-data h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #95a5a6;
        }

        .no-data p {
            font-size: 12px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>LAPORAN STOK BARANG</h1>
        <div class="company-name">GROSIR KK BERSAUDARA</div>
        <div class="report-info">
            Dicetak pada: {{ $generated_at->format('d F Y, H:i:s') }} WIB
        </div>
    </div>

    {{-- Filter Information --}}
    <div class="filter-info">
        <h3>Informasi Filter Laporan:</h3>
        <div class="filter-grid">
            <div class="filter-row">
                <div class="filter-item">
                    <strong>Kategori:</strong>
                    @if(isset($filters['category_id']) && $filters['category_id'])
                        {{ $products->first()->category->name ?? 'Semua Kategori' }}
                    @else
                        Semua Kategori
                    @endif
                </div>
                <div class="filter-item">
                    <strong>Satuan:</strong>
                    @if(isset($filters['unit']) && $filters['unit'])
                        {{ strtoupper($filters['unit']) }}
                    @else
                        Semua Satuan
                    @endif
                </div>
                <div class="filter-item">
                    <strong>Status Stok:</strong>
                    @if(isset($filters['stock_status']) && $filters['stock_status'] && $filters['stock_status'] !== 'all')
                        @switch($filters['stock_status'])
                            @case('normal')
                                Stok Normal
                                @break
                            @case('low_stock')
                                Stok Menipis
                                @break
                            @case('out_of_stock')
                                Stok Habis
                                @break
                            @default
                                Semua Status
                        @endswitch
                    @else
                        Semua Status
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Section --}}
    <div class="summary-section">
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Total Produk</th>
                    <th>Produk Tersedia</th>
                    <th>Stok Menipis</th>
                    <th>Stok Habis</th>
                    <th>Total Stok (Krg)</th>
                    <th>Total Stok (Dus)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="summary-total">{{ number_format($summary['total_products']) }}</td>
                    <td class="summary-in-stock">{{ number_format($summary['products_in_stock']) }}</td>
                    <td class="summary-low-stock">{{ number_format($summary['low_stock_count']) }}</td>
                    <td class="summary-out-stock">{{ number_format($summary['out_of_stock_count']) }}</td>
                    <td class="summary-stock-info">{{ number_format($summary['total_krg_stock']) }}</td>
                    <td class="summary-stock-info">{{ number_format($summary['total_dus_stock']) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Products Table --}}
    <div class="table-container">
        @if($products->count() > 0)
            <table class="main-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-code">Kode Produk</th>
                        <th class="col-name">Nama Produk</th>
                        <th class="col-category">Kategori</th>
                        <th class="col-unit">Satuan</th>
                        <th class="col-current">Stok Saat Ini</th>
                        <th class="col-minimum">Stok Minimum</th>
                        <th class="col-status">Status Stok</th>
                        <th class="col-alert">Alert</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            {{-- Nomor --}}
                            <td class="text-center">{{ $index + 1 }}</td>

                            {{-- Kode Produk --}}
                            <td class="text-center">
                                <strong>{{ $product->code }}</strong>
                            </td>

                            {{-- Nama Produk --}}
                            <td class="text-left">
                                <div class="product-name">{{ $product->name }}</div>
                                @if($product->description)
                                    <div class="product-desc">{{ Str::limit($product->description, 60) }}</div>
                                @endif
                            </td>

                            {{-- Kategori --}}
                            <td class="text-center">{{ $product->category->name }}</td>

                            {{-- Satuan --}}
                            <td class="text-center">
                                <span class="unit-badge {{ $product->unit === 'krg' ? 'unit-krg' : 'unit-dus' }}">
                                    {{ strtoupper($product->unit) }}
                                </span>
                            </td>

                            {{-- Stok Saat Ini --}}
                            <td class="text-center">
                                <span class="stock-number">{{ number_format($product->current_stock) }}</span>
                            </td>

                            {{-- Stok Minimum --}}
                            <td class="text-center">
                                <span class="stock-number">{{ number_format($product->minimum_stock) }}</span>
                            </td>

                            {{-- Status Stok --}}
                            <td class="text-center">
                                @if($product->is_out_of_stock)
                                    <span class="stock-status status-out">Habis</span>
                                @elseif($product->is_low_stock)
                                    <span class="stock-status status-low">Menipis</span>
                                @else
                                    <span class="stock-status status-normal">Normal</span>
                                @endif
                            </td>

                            {{-- Alert Indicator --}}
                            <td class="text-center">
                                @if($product->is_out_of_stock || $product->is_low_stock)
                                    <span class="alert-icon alert-danger">⚠️</span>
                                @else
                                    <span class="alert-icon alert-success">✅</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <h3>Tidak Ada Data</h3>
                <p>Tidak ada data produk yang ditemukan berdasarkan filter yang diterapkan.</p>
                <p>Silakan periksa kembali filter atau tambahkan data produk baru.</p>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-grid">
            <div class="footer-left">
                <strong>GROSIR KK BERSAUDARA</strong><br>
                Sistem Informasi Manajemen Inventaris<br>
                <em>Laporan digenerate otomatis oleh sistem</em>
            </div>
            <div class="footer-right">
                <strong>Halaman 1 dari 1</strong><br>
                Total Produk: {{ number_format($products->count()) }}<br>
                <em>{{ $generated_at->format('d/m/Y H:i:s') }}</em>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="legend">
        <h4>Keterangan Status Stok:</h4>
        <div class="legend-content">
            <div class="legend-item">
                <span class="legend-dot dot-normal"></span>
                <strong>NORMAL:</strong> Stok di atas minimum
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-low"></span>
                <strong>MENIPIS:</strong> Stok pada/di bawah minimum
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-out"></span>
                <strong>HABIS:</strong> Stok kosong (0)
            </div>
        </div>
    </div>
</body>
</html>
