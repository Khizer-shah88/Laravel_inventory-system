<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Book Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            width: 210mm;
            min-height: 297mm;
            margin: auto;
            padding: 20px;
            background: #fff;
        }
        @media print {
            body {
                margin: 0;
                padding: 10mm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h3 class="fw-bold">Cash Book Voucher</h3>
        </div>

        <!-- Voucher Info -->
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Voucher No:</strong> {{ $cashbook->CBID }}</p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($cashbook->CBDate)->format('d-m-Y') }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>Header Code</th>
                    <th>Account ID</th>
                    <th>Account Name</th>
                    <th>Description</th>
                    <th class="text-end">Debit</th>
                    <th class="text-end">Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subs as $sub)
                    <tr>
                        <td>{{ $sub->HeaderCode }}</td>
                        <td>{{ $sub->AccountId }}</td>
                        <td>{{ $sub->AccountName }}</td>
                        <td>{{ $sub->Description }}</td>
                        <td class="text-end">{{ number_format($sub->Debit, 2) }}</td>
                        <td class="text-end">{{ number_format($sub->Credit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="4" class="text-end">Total</td>
                    <td class="text-end">{{ number_format($subs->sum('Debit'), 2) }}</td>
                    <td class="text-end">{{ number_format($subs->sum('Credit'), 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Previous Balance</td>
                    <td colspan="2" class="text-end">{{ number_format($previousBalance, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Cash In Hand</td>
                    <td colspan="2" class="text-end">
                        {{ number_format($previousBalance + $subs->sum('Credit') - $subs->sum('Debit'), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Print Button -->
        <div class="text-center mt-4 no-print">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    <!-- Bootstrap Icons (for printer icon) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
