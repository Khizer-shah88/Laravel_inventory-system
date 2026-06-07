<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Share - #{{ $item->ItemCode }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: radial-gradient(circle at top, #0f2342 0%, #0a1628 48%, #081221 100%);
            font-family: 'Outfit', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ── BANNER CARD ─────────────────────────────────────── */
        .banner-card {
            width: 100%;
            max-width: 640px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
            background: #0b1b33;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        /* ── TOP BANNER HEADER ──────────────────────────────── */
        .banner-header {
            position: relative;
            background: linear-gradient(135deg, #0b1b33 0%, #13335f 52%, #0b1b33 100%);
            padding: 30px 28px 24px;
            overflow: hidden;
        }

        /* Circuit / geometric SVG background */
        .banner-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='160'%3E%3Cdefs%3E%3Cstyle%3E.l%7Bstroke:%23ffffff18;stroke-width:1;fill:none%7D.d%7Bfill:%23ffffff12%7D%3C/style%3E%3C/defs%3E%3C!-- horizontal lines --%3E%3Cline class='l' x1='0' y1='30' x2='600' y2='30'/%3E%3Cline class='l' x1='0' y1='80' x2='600' y2='80'/%3E%3Cline class='l' x1='0' y1='130' x2='600' y2='130'/%3E%3C!-- vertical lines --%3E%3Cline class='l' x1='60' y1='0' x2='60' y2='160'/%3E%3Cline class='l' x1='160' y1='0' x2='160' y2='160'/%3E%3Cline class='l' x1='280' y1='0' x2='280' y2='160'/%3E%3Cline class='l' x1='420' y1='0' x2='420' y2='160'/%3E%3Cline class='l' x1='530' y1='0' x2='530' y2='160'/%3E%3C!-- nodes --%3E%3Ccircle class='d' cx='60' cy='30' r='4'/%3E%3Ccircle class='d' cx='160' cy='80' r='4'/%3E%3Ccircle class='d' cx='280' cy='30' r='5'/%3E%3Ccircle class='d' cx='420' cy='130' r='4'/%3E%3Ccircle class='d' cx='530' cy='80' r='4'/%3E%3Ccircle class='d' cx='60' cy='130' r='3'/%3E%3Ccircle class='d' cx='530' cy='30' r='3'/%3E%3C!-- diagonal traces --%3E%3Cline class='l' x1='160' y1='80' x2='280' y2='30'/%3E%3Cline class='l' x1='280' y1='30' x2='420' y2='130'/%3E%3Cline class='l' x1='60' y1='30' x2='160' y2='80'/%3E%3Cline class='l' x1='420' y1='130' x2='530' y2='80'/%3E%3C/svg%3E");
            background-size: cover;
            background-repeat: no-repeat;
            pointer-events: none;
        }

        .banner-logo-row {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 2;
        }

        .banner-icon {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
            box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35);
        }

        .banner-title-block {
            flex: 1;
        }

        .banner-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.04em;
            line-height: 1.12;
            text-transform: uppercase;
        }

        .banner-title span {
            color: #f59e0b;
        }

        .banner-tagline {
            font-size: 0.7rem;
            color: #cbd5f5;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* ── CONTACT STRIP ──────────────────────────────────── */
        .contact-strip {
            background: #132b4f;
            border-top: 1px solid rgba(255,255,255,0.12);
            padding: 12px 28px;
            display: flex;
            gap: 18px;
            align-items: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .contact-item .phone-icon {
            background: #f59e0b;
            color: #fff;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .contact-divider {
            width: 1px;
            height: 18px;
            background: rgba(255,255,255,0.25);
        }

        /* ── PRODUCT IMAGE ──────────────────────────────────── */
        .product-image-wrap {
            background: linear-gradient(135deg, #eaf0f8 0%, #f8fbff 60%, #ffffff 100%);
            padding: 28px;
            text-align: center;
            position: relative;
        }

        .image-frame {
            position: relative;
            padding: 18px;
            border-radius: 22px;
            background: linear-gradient(135deg, #f9e08a 0%, #f6c453 28%, #e9a31d 55%, #ffd57a 78%, #b87900 100%);
            border: 1px solid rgba(120, 74, 0, 0.35);
            box-shadow: 0 26px 50px rgba(13, 31, 60, 0.24), inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .image-frame::before {
            content: '';
            position: absolute;
            inset: 10px;
            border-radius: 18px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            pointer-events: none;
        }

        .image-frame::after {
            content: '';
            position: absolute;
            inset: 6px;
            border-radius: 20px;
            background:
                linear-gradient(#fff1c2, #fff1c2) 12px 12px/44px 2px no-repeat,
                linear-gradient(#fff1c2, #fff1c2) 12px 12px/2px 44px no-repeat,
                linear-gradient(#d08a10, #d08a10) calc(100% - 12px) 12px/44px 2px no-repeat,
                linear-gradient(#d08a10, #d08a10) calc(100% - 12px) 12px/2px 44px no-repeat,
                linear-gradient(#d08a10, #d08a10) 12px calc(100% - 12px)/44px 2px no-repeat,
                linear-gradient(#d08a10, #d08a10) 12px calc(100% - 12px)/2px 44px no-repeat,
                linear-gradient(#fff1c2, #fff1c2) calc(100% - 12px) calc(100% - 12px)/44px 2px no-repeat,
                linear-gradient(#fff1c2, #fff1c2) calc(100% - 12px) calc(100% - 12px)/2px 44px no-repeat;
            opacity: 0.85;
            pointer-events: none;
        }

        .frame-ornament {
            position: absolute;
            z-index: 2;
            background: linear-gradient(180deg, #fff1c2 0%, #f6c453 45%, #e09a19 70%, #ffd57a 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65), inset 0 -1px 0 rgba(120, 74, 0, 0.35);
        }

        .frame-ornament.left,
        .frame-ornament.right {
            top: 18%;
            width: 36px;
            height: 64%;
            border-radius: 16px;
            background-image:
                linear-gradient(180deg, #fff1c2 0%, #f6c453 45%, #e09a19 70%, #ffd57a 100%),
                repeating-linear-gradient(180deg, rgba(120, 74, 0, 0.18) 0 4px, transparent 4px 10px);
            background-blend-mode: multiply;
        }

        .frame-ornament.left {
            left: 8px;
        }

        .frame-ornament.right {
            right: 8px;
        }

        .frame-ornament.bottom {
            left: 20%;
            right: 20%;
            bottom: 6px;
            height: 26px;
            border-radius: 0 0 18px 18px;
            background-image:
                linear-gradient(180deg, #fff1c2 0%, #f6c453 60%, #e09a19 100%),
                linear-gradient(90deg, rgba(120, 74, 0, 0.25), transparent 35%, transparent 65%, rgba(120, 74, 0, 0.25));
            background-blend-mode: multiply;
        }

        .image-frame-inner {
            position: relative;
            background: #ffffff;
            padding: 16px;
            border-radius: 14px;
            border: 1px solid rgba(120, 74, 0, 0.2);
            box-shadow: inset 0 0 0 1px rgba(255, 233, 173, 0.55);
        }

        .image-frame-inner img {
            width: 100%;
            max-height: 360px;
            object-fit: contain;
            border-radius: 10px;
            display: block;
            background: #f8fafc;
        }

        /* ── PRODUCT INFO ───────────────────────────────────── */
        .product-info {
            background: #ffffff;
            padding: 22px 24px 24px;
            border-top: 1px solid #e2e8f0;
        }

        .product-name {
            font-size: 1.18rem;
            font-weight: 800;
            color: #0d1f3c;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 4px;
        }

        .product-meta-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .product-meta-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .meta-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: 600;
        }

        .meta-value {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
        }

        /* ── ACTION BAR ─────────────────────────────────────── */
        .action-bar {
            background: #f1f5f9;
            border-top: 1px solid #e2e8f0;
            padding: 14px 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn-whatsapp {
            background: linear-gradient(135deg, #25d366, #128c7e);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s;
        }
        .btn-whatsapp:hover { opacity: 0.88; color: #fff; }

        .btn-category {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s;
        }
        .btn-category:hover { opacity: 0.9; color: #fff; }

        .btn-pdf {
            background: #fff;
            color: #0d1f3c;
            border: 2px solid #1a3a6e;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-pdf:hover { background: #e8f0fe; }

        .btn-close-custom {
            background: linear-gradient(135deg, #1a3a6e, #0d1f3c);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-close-custom:hover { opacity: 0.85; }

        @media print {
            body { background: #fff; padding: 0; }
            .action-bar { display: none; }
        }

        @media (max-width: 480px) {
            .banner-title { font-size: 1.12rem; }
            .contact-strip { gap: 10px; }
            .product-image-wrap { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="banner-card">

    {{-- ── BRAND HEADER ── --}}
    <div class="banner-header">
        <div class="banner-logo-row">
            <div class="banner-icon">⚡</div>
            <div class="banner-title-block">
                <div class="banner-title">ADNAN <span>ELECTRONIC</span> STORE</div>
                <div class="banner-tagline">Modern Electronic & Electrical Supplies</div>
            </div>
        </div>
    </div>

    {{-- ── CONTACT STRIP ── --}}
    <div class="contact-strip">
        <div class="contact-item">
            <div class="phone-icon">📞</div>
            <span>Adnan: 03002493738</span>
        </div>
        <div class="contact-divider"></div>
        <div class="contact-item">
            <div class="phone-icon">📞</div>
            <span>Ali: 03023057948</span>
        </div>
         <div class="contact-item">
            <span class="phone-badge">📍</span>
            Location: KN SHAH
        </div>
    </div>

    {{-- ── PRODUCT IMAGE ── --}}
    <div class="product-image-wrap">
        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif
        <div class="image-frame">
            <span class="frame-ornament left" aria-hidden="true"></span>
            <span class="frame-ornament right" aria-hidden="true"></span>
            <span class="frame-ornament bottom" aria-hidden="true"></span>
            <div class="image-frame-inner">
                <img src="{{ $shareImage }}" alt="{{ $item->ItemName }}" onerror="this.src='{{ $placeholderImage }}'">
            </div>
        </div>
    </div>

    {{-- ── PRODUCT DETAILS ── --}}
    <div class="product-info">
        <div class="product-name">{{ $item->ItemName }}</div>
        <div class="product-meta-row">
            <div class="product-meta-item">
                <div class="meta-label">Item Code</div>
                <div class="meta-value">{{ $item->ItemCode }}</div>
            </div>
            <div class="product-meta-item">
                <div class="meta-label">Category</div>
                <div class="meta-value">{{ $item->Category ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- ── ACTION BUTTONS ── --}}
    <div class="action-bar">
        @if(!empty($item->Category))
            <a href="{{ route('items.shareAll', ['search_column' => 'category', 'search_value' => $item->Category]) }}"
               class="btn-category"
               target="_blank" rel="noopener">
                🧾 Share Category
            </a>
        @endif
        <a href="{{ route('items.sharePdf', $item->ItemCode) }}" class="btn-whatsapp" target="_blank" rel="noopener">
            📲 Share on WhatsApp
        </a>
        <button class="btn-pdf" onclick="window.print()">💾 Save as PDF</button>
        <button class="btn-close-custom" onclick="window.close()">✕ Close</button>
    </div>

</div>

</body>
</html>
