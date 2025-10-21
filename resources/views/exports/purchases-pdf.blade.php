<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Summary</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; text-transform: uppercase; font-size: 11px; }
        .total { text-align: right; font-weight: bold; }
        .supplier { background: #e5e7eb; font-weight: bold; padding: 8px; }
    </style>
</head>
<body>
<div class="header">
    <h2>Purchase Summary</h2>
    <p>Generated on {{ now()->format('d/m/Y H:i') }}</p>
</div>

@foreach ($purchasesBySupplier as $supplierGroup)
    <div class="supplier">{{ $supplierGroup['supplier_name'] }}</div>

    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Brand</th>
            <th>Qty</th>
            <th>Unit Price (R$)</th>
            <th>Total (R$)</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($supplierGroup['quotes'] as $quote)
            <tr>
                <td>{{ $quote->item->description ?? '-' }}</td>
                <td>{{ $quote->brand ?? '-' }}</td>
                <td>{{ $quote->quantity }}</td>
                <td>{{ number_format($quote->unit_price, 2, ',', '.') }}</td>
                <td>{{ number_format($quote->total_value, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" class="total">Total Supplier</td>
            <td class="total">R$ {{ number_format($supplierGroup['total'], 2, ',', '.') }}</td>
        </tr>
        </tbody>
    </table>
@endforeach
</body>
</html>
