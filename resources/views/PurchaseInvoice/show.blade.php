<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->InvNo }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .invoice {
            width: 240px; /* small paper size */
            margin: auto;
        }
        .center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 4px 0; }
        .row { display: flex; justify-content: space-between; }
        .bold { font-weight: bold; }
    </style>
</head>
<body onload="window.print();">

    <div class="invoice">
        <div class="center">
            <h3>Invoice</h3>
        </div>

        <div>
            <div><strong>Inv No:</strong> {{ $invoice->InvNo }}</div>
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->InvDate)->format('d-m-Y') }}</div>
            <div><strong>Account:</strong> {{ $invoice->AccountName }}</div>
        </div>

        <div class="line"></div>

        {{-- Heading only once --}}
        <div class="row bold">
            <div style="width:25%;">Rate</div>
            <div style="width:25%; text-align:center;">Qty</div>
            <div style="width:25%; text-align:center;">Disc</div>
            <div style="width:25%; text-align:right;">Amt</div>
        </div>
        <div class="line"></div>

        {{-- Items --}}
        @php
            $gross = 0;
            $totalDisc = 0;
        @endphp

        @foreach ($items as $item)
            @php
                $amount = ($item->Qty * $item->Rate) - $item->Disc;
                $gross += ($item->Qty * $item->Rate);
                $totalDisc += $item->Disc;
            @endphp

            {{-- Item Name Row --}}
            <div class="bold">{{ $item->ItemName }}</div>

            {{-- Rate, Qty, Disc, Amt row --}}
            <div class="row">
                <div style="width:25%;">{{ rtrim(rtrim(number_format($item->Rate, 2, '.', ''), '0'), '.') }}</div>
                <div style="width:25%; text-align:center;">{{ rtrim(rtrim(number_format($item->Qty, 2, '.', ''), '0'), '.') }}</div>
                <div style="width:25%; text-align:center;">{{ rtrim(rtrim(number_format($item->Disc, 2, '.', ''), '0'), '.') }}</div>
                <div style="width:25%; text-align:right;">{{ rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.') }}</div>
            </div>
            <div class="line"></div>
        @endforeach

        {{-- Totals --}}
        <div class="row">
            <div class="bold">Gross</div>
            <div class="bold">{{ rtrim(rtrim(number_format($gross, 2, '.', ''), '0'), '.') }}</div>
        </div>
        <div class="row">
            <div class="bold">Total Disc</div>
            <div class="bold">{{ rtrim(rtrim(number_format($totalDisc, 2, '.', ''), '0'), '.') }}</div>
        </div>
        <div class="row">
            <div class="bold">Cash Discount</div>
            <div class="bold">{{ rtrim(rtrim(number_format($invoice->CashDiscount, 2, '.', ''), '0'), '.') }}</div>
        </div>
        <div class="row">
            <div class="bold">Net Total</div>
            <div class="bold">
                {{ rtrim(rtrim(number_format($gross - $totalDisc - $invoice->CashDiscount, 2, '.', ''), '0'), '.') }}
            </div>
        </div>

        <div class="center">
            <p>--- Thank You ---</p>
        </div>
    </div>
    <script>
    window.onload = function() {
        window.print();
        window.onafterprint = function() {
            window.close();
        };
    };
</script>

</body>
</html>
