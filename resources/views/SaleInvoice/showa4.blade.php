<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sale Invoice #{{ $invoice->InvNo }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            margin: 20px;
            color: #000;
        }
        h2 {
            margin: 0;
            font-weight: 600;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
        }
        .sub-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 12px;
        }
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            text-align: left;
            padding: 5px;
            background: #f2f2f2;
        }
        td {
            padding: 5px;
        }
        .totals {
            margin-top: 15px;
            width: 100%;
        }
        .totals td {
            padding: 5px;
        }
        .totals .label {
            text-align: right;
            font-weight: 600;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
        .signature {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
        }
        .muted {
            color: #777;
            font-size: 11px;
        }
    </style>
</head>
<body>

            <strong>Adnan Electric Store</strong></br>
        <span>Phone: 0315-3485950 / 0300-8311175</span>
    <!-- Header -->
    <div class="header">


        <h2>Sale Invoice</h2>
        <div class="muted">Printed Time: {{ now()->format('d-m-Y h:i A') }}</div>
    </div>
    <hr>

    <!-- Invoice Info -->
    <div class="sub-header">
        <div>
            <strong>Account Name:</strong> {{ $invoice->AccountName }}
        </div>
        <div>
            <strong>Invoice No:</strong> {{ $invoice->InvNo }}
        </div>
    </div>

    <!-- Invoice Items -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; $gross = 0; @endphp
            @foreach ($items as $item)
                @php
                    $amount = $item->Qty * $item->Rate;
                    $gross += $amount;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->ItemName }}</td>
                    <td>{{ $item->Qty }}</td>
                    <td>{{ number_format($item->Rate, 2) }}</td>
                    <td>{{ number_format($amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table class="totals">
        <tr>
            <td class="label">Gross:</td>
            <td>{{ number_format($gross, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Cash Discount:</td>
            <td>{{ number_format($invoice->CashDiscount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Net Amount:</td>
            <td>
                {{ number_format($gross - ($invoice->CashDiscount ?? 0), 2) }}
            </td>
        </tr>
    </table>
</br>
</br>

    

    
</body>
</html>
