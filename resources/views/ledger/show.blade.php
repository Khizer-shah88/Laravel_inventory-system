<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ledger Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 4px 6px;
            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .no-border td {
            border: none;
        }
        .criteria-table td {
            padding: 3px 6px;
        }
        .btn-print {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: flex-start;">
    <div style="text-align: left; line-height: 1.4; margin: 0; padding: 0;">
        <h2 style="margin: 0; line-height: 1.4;">Ledger Report</h2>
        <h3 style="margin: 0; line-height: 1.4;">Adnan Electric Store</h3>
        <p style="margin: 0; line-height: 1.4;">071-5625022 / 0315-3485950</p>
    </div>

    <div style="text-align: right; line-height: 1.4; margin: 0; padding: 0;">
        <h3 style="margin: 0; line-height: 1.4;">{{ $headerCode. ' - ' .$AccountId}}</h3>
        <h3 style="margin: 0; line-height: 1.4;">{{ $accountName }}</h3>
        <h3 style="margin: 0; line-height: 1.4;">Start Date: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}</h3>
        <h3 style="margin: 0; line-height: 1.4;">End Date: {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</h3>
    </div>
</div>
    <!-- Ledger Table -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Ref No</th>
                <th class="text-end">Debit</th>
                <th class="text-end">Credit</th>
                <th class="text-end">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ledgerData as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['VDate'])->format('d-m-Y') }}</td>
                    <td>{{ $row['Description'] }}</td>
                    <td>{{ $row['RefNo'] }}</td>
                    <td class="text-end">{{ number_format($row['Debit'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['Credit'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['Balance'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Totals:</strong></td>
                <td class="text-end"><strong>{{ number_format($ledgerTotals['totalDebit'], 2) }}</strong></td>
                <td class="text-end"><strong>{{ number_format($ledgerTotals['totalCredit'], 2) }}</strong></td>
                <td class="text-end"><strong>{{ number_format($ledgerTotals['closingBalance'], 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
