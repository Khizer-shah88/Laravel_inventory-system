<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Invoice #{{ $invoice->InvNo }}</title>
    <style>
        /* Screen styles - BlackCopper theme */
        body {
            background-color: #1e1e1e;
            color: #c0a080;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            font-size: 14px;
        }

        .invoice-container {
            max-width: 480px;
            margin: auto;
            background-color: #2a2a2a;
            border: 1px solid #c0a080;
            padding: 20px;
            border-radius: 6px;
        }

        h2 {
            color: #e0c080;
            margin-bottom: 12px;
            font-size: 20px;
        }

        .info {
            margin-bottom: 12px;
        }

        .info div {
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table thead th {
            border-bottom: 1px solid #c0a080;
            padding: 6px 8px;
            text-align: right;
            font-weight: 600;
            font-size: 12px;
            color: #e0c080;
        }

        table tbody td {
            border-bottom: 1px solid #444;
            padding: 6px 8px;
            font-size: 12px;
            text-align: right;
        }

        /* Make the item name row stand out and left-aligned */
        tbody tr:first-child td {
            border-bottom: none !important;
            text-align: left !important;
            font-weight: 600;
            padding: 8px 8px 2px 8px !important;
            font-size: 13px;
            color: #f0d38c;
        }

        .totals {
            margin-top: 16px;
            border-top: 1px solid #c0a080;
            padding-top: 12px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
        }

        /* PRINT styles for 48mm thermal paper */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white !important;
                color: black !important;
                font-size: 9pt !important;
                font-family: monospace, monospace !important;
            }

            .invoice-container {
                width: 48mm !important;
                max-width: 48mm !important;
                padding: 5px !important;
                border: none !important;
                background: white !important;
                color: black !important;
                font-size: 9pt !important;
                box-sizing: border-box !important;
                margin: 0 auto !important;
            }

            h2 {
                font-size: 12pt !important;
                margin-bottom: 8px !important;
                text-align: center;
            }

            .info div {
                margin-bottom: 3px !important;
                font-size: 8.5pt !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 8pt !important;
            }

            table thead th,
            table tbody td {
                border: 1px solid black !important;
                padding: 2px 4px !important;
                word-wrap: break-word !important;
                text-align: right !important;
            }

            /* Left-align the item name row */
            tbody tr:first-child td {
                text-align: left !important;
                font-weight: 600 !important;
                padding: 4px 4px 2px 4px !important;
                font-size: 9pt !important;
                border: none !important;
                border-bottom: 1px solid black !important;
                color: black !important;
            }

            .totals {
                margin-top: 10px !important;
                border-top: 1px solid black !important;
                padding-top: 8px !important;
                font-size: 9pt !important;
                font-weight: 600 !important;
                gap: 4px !important;
                flex-direction: column !important;
            }

            .totals div {
                justify-content: space-between !important;
            }
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <h2>Invoice #{{ $invoice->InvNo }}</h2>

    <div class="info">
        <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->InvDate)->format('d M, Y') }}</div>
        <div><strong>Customer:</strong> {{ $invoice->AccountName }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Pkt Sz</th>
                <th>Pkt Qty</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Tot Qty</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td colspan="6">{{ $item->ItemName }}</td>
            </tr>
            <tr>
                <td>{{ $item->PacketSize }}</td>
                <td>{{ $item->PacketQty }}</td>
                <td>{{ $item->Qty }}</td>
                <td>{{ number_format($item->Rate, 2) }}</td>
                <td>{{ $item->TotalQty }}</td>
                <td>{{ number_format($item->Rate * $item->TotalQty, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div><span>Gross Total:</span> <span>{{ number_format($gross, 2) }}</span></div>
        <div><span>Total Discount:</span> <span>{{ number_format($discount, 2) }}</span></div>
        <div><span>Cash Discount:</span> <span>{{ number_format($cashDiscount, 2) }}</span></div>
        <div><span>Net Total:</span> <span>{{ number_format($net, 2) }}</span></div>
    </div>
</div>

</body>
</html>
