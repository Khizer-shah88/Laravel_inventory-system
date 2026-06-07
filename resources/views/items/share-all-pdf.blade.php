<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Items PDF - {{ $filterLabel }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            color: #0f172a;
            background: #ffffff;
        }

        .header {
            padding: 18px 24px 12px;
            border-bottom: 2px solid #1a3a6e;
        }

        .header-title {
            font-size: 18px;
            font-weight: 900;
            color: #0d1f3c;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .header-sub {
            margin-top: 4px;
            font-size: 11px;
            color: #475569;
        }

        .meta-row {
            padding: 10px 24px 16px;
            font-size: 10px;
            color: #64748b;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 16px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 10px;
            vertical-align: middle;
        }

        .items-table th {
            background: #0d1f3c;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 9px;
        }

        .thumb {
            width: 72px;
            text-align: center;
        }

        .thumb img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .item-name {
            font-weight: 700;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">Adnan Electronic Store - Items</div>
        <div class="header-sub">{{ $filterLabel }}</div>
    </div>
    <div class="meta-row">Generated: {{ now()->format('d M Y, H:i') }}</div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="thumb">Image</th>
                <th>Item Name</th>
                <th>Item Code</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td class="thumb">
                        <img src="{{ $item->inlineImage }}" alt="{{ $item->ItemName }}">
                    </td>
                    <td class="item-name">{{ $item->ItemName }}</td>
                    <td>{{ $item->ItemCode }}</td>
                    <td>{{ $item->Category ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No items found for this filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
