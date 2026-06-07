<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Items - {{ $filterLabel }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a1628;
            --bg-light: #0f2342;
            --panel: #ffffff;
            --ink: #0f172a;
            --muted: #64748b;
            --brand: #f59e0b;
            --brand-dark: #b45309;
            --accent: #1d4ed8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top, var(--bg-light) 0%, var(--bg-dark) 55%, #081221 100%);
            color: var(--ink);
            min-height: 100vh;
            padding: 32px 20px 48px;
        }

        .page {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .hero {
            background: linear-gradient(135deg, #0b1b33 0%, #13335f 52%, #0b1b33 100%);
            border-radius: 20px;
            padding: 24px 26px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 12% 20%, rgba(245, 158, 11, 0.25), transparent 40%),
                radial-gradient(circle at 88% 15%, rgba(59, 130, 246, 0.2), transparent 45%),
                linear-gradient(120deg, rgba(255, 255, 255, 0.08), transparent 60%);
            pointer-events: none;
        }

        .hero-row {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-badge {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: 0 10px 24px rgba(245, 158, 11, 0.35);
        }

        .brand-title {
            font-size: 1.4rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .brand-title span {
            color: var(--brand);
        }

        .brand-tagline {
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #cbd5f5;
            margin-top: 4px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-whatsapp {
            background: linear-gradient(135deg, #25d366, #128c7e);
            color: #fff;
        }

        .btn-pdf {
            background: #fff;
            color: #0d1f3c;
            border: 2px solid #1a3a6e;
        }

        .meta-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .meta-pill {
            background: var(--panel);
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 8px 16px;
            font-size: 0.85rem;
            color: var(--muted);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        }

        .meta-pill strong {
            color: var(--ink);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .card {
            background: var(--panel);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.12);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .card-image {
            background: #f8fafc;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-image img {
            width: 100%;
            max-height: 220px;
            object-fit: contain;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
        }

        .card-body {
            padding: 16px 18px 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--ink);
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .card-meta {
            font-size: 0.85rem;
            color: var(--muted);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .empty {
            background: var(--panel);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            color: var(--muted);
            border: 1px dashed #cbd5f5;
        }

        @media (max-width: 640px) {
            .hero-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero-actions {
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="hero">
            <div class="hero-row">
                <div class="brand">
                    <div class="brand-badge">⚡</div>
                    <div>
                        <div class="brand-title">ADNAN <span>ELECTRONIC</span> STORE</div>
                        <div class="brand-tagline">Modern Electronics & Electrical Supplies</div>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ $shareWhatsappUrl }}" class="btn btn-whatsapp" target="_blank" rel="noopener">Share on WhatsApp</a>
                    <a href="{{ $sharePdfUrl }}" class="btn btn-pdf" target="_blank" rel="noopener">Download PDF</a>
                </div>
            </div>
        </header>

        <div class="meta-bar">
            <div class="meta-pill"><strong>Filter:</strong> {{ $filterLabel }}</div>
            <div class="meta-pill"><strong>Total:</strong> {{ count($items) }} items</div>
        </div>

        <section class="grid">
            @forelse($items as $item)
                <article class="card">
                    <div class="card-image">
                        <img src="{{ $item->imageUrl }}" alt="{{ $item->ItemName }}" onerror="this.src='{{ $placeholderImage }}'">
                    </div>
                    <div class="card-body">
                        <div class="card-title">{{ $item->ItemName }}</div>
                        <div class="card-meta">
                            <span>Item Code: {{ $item->ItemCode }}</span>
                            <span>Category: {{ $item->Category ?? '-' }}</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty">No items found for this filter.</div>
            @endforelse
        </section>
    </div>
</body>
</html>
