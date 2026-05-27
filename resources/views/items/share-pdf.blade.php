<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Share - #{{ $item->ItemCode }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            color: #0f172a;
            background: #ffffff;
        }

        /* ── BRAND HEADER ── */
        .brand-header {
            background: linear-gradient(135deg, #0d1f3c 0%, #1a3a6e 100%);
            padding: 18px 24px 14px;
            position: relative;
            overflow: hidden;
        }

        /* subtle grid pattern */
        .brand-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                0deg, transparent, transparent 18px, rgba(255,255,255,0.05) 18px, rgba(255,255,255,0.05) 19px
            ),
            repeating-linear-gradient(
                90deg, transparent, transparent 18px, rgba(255,255,255,0.05) 18px, rgba(255,255,255,0.05) 19px
            );
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 900;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .brand-name span { color: #f59e0b; }

        .brand-tagline {
            font-size: 9px;
            color: #cbd5f5;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* ── CONTACT STRIP ── */
        .contact-strip {
            background: #132b4f;
            padding: 8px 24px;
            display: flex;
            gap: 24px;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
        }

        .phone-badge {
            background: #f59e0b;
            color: #fff;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
        }

        /* ── PRODUCT SECTION ── */
        .product-section {
            padding: 20px 24px;
        }

        .product-name {
            font-size: 15px;
            font-weight: 900;
            color: #0d1f3c;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 14px;
        }

        .image-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            background: #f8fafc;
            text-align: center;
            margin-bottom: 16px;
        }

        .image-wrap img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
        }

        /* ── META TABLE ── */
        .meta {
            width: 100%;
            border-collapse: collapse;
        }

        .meta th, .meta td {
            text-align: left;
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .meta th {
            background: #0d1f3c;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            width: 32%;
            font-size: 10px;
        }

        .meta td {
            font-weight: 700;
            color: #0f172a;
        }

        /* ── FOOTER ── */
        .footer {
            background: #f1f5f9;
            border-top: 2px solid #1a3a6e;
            padding: 10px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-brand {
            font-size: 10px;
            font-weight: 800;
            color: #1a3a6e;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .footer-date {
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>

    {{-- BRAND HEADER --}}
    <div class="brand-header">
        <div class="brand-row">
            <div class="brand-icon">⚡</div>
            <div>
                <div class="brand-name">ADNAN <span>ELECTRONIC</span> STORE</div>
                <div class="brand-tagline">Modern Electronics & Electrical Supplies</div>
            </div>
        </div>
    </div>

    {{-- CONTACT STRIP --}}
    <div class="contact-strip">
        <div class="contact-item">
            <span class="phone-badge">📞</span>
            Adnan: 03002493738
        </div>
        <div class="contact-item">
            <span class="phone-badge">📞</span>
            Ali: 03023057948
        </div>
    </div>

    {{-- PRODUCT SECTION --}}
    <div class="product-section">
        <div class="product-name">{{ $item->ItemName }}</div>

        <div class="image-wrap">
            <img src="{{ $inlineImage }}" alt="{{ $item->ItemName }}">
        </div>

        <table class="meta">
            <tr>
                <th>Item Code</th>
                <td>{{ $item->ItemCode }}</td>
            </tr>
            <tr>
                <th>Category</th>
                <td>{{ $item->Category ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-brand">⚡ Adnan Electronic Store</div>
        <div class="footer-date">{{ now()->format('d M Y, H:i') }}</div>
    </div>

</body>
</html>
