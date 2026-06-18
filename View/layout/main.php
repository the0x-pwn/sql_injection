<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title><?= env('APP_NAME') ?></title>
    <style>
        .flash-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
            text-align: center;
        }

        .flash-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #4ade80;
            text-align: center;
        }

        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --card: #16161f;
            --border: rgba(255, 255, 255, 0.06);
            --accent: #7c3aed;
            --accent2: #06b6d4;
            --text: #e2e8f0;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(124, 58, 237, 0.12) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 80% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Navbar */
        .navbar-custom {
            background: rgba(10, 10, 15, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            font-size: 1.4rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .nav-badge {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: white;
            font-size: 0.7rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 700;
        }

        /* Animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── index.php ── */
        .hero {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 80px 20px 60px;
        }

        .hero-label {
            display: inline-block;
            border: 1px solid rgba(124, 58, 237, 0.4);
            background: rgba(124, 58, 237, 0.08);
            color: #a78bfa;
            font-size: 0.78rem;
            padding: 5px 18px;
            border-radius: 20px;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            color: var(--muted);
            font-size: 1.05rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .search-wrap {
            position: relative;
            z-index: 1;
            max-width: 480px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }

        .search-input-row {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-wrap input {
            flex: 1;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 50px 14px 20px;
            color: var(--text);
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .search-wrap input:focus {
            border-color: rgba(124, 58, 237, 0.6);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .search-wrap input::placeholder {
            color: var(--muted);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 1rem;
            pointer-events: none;
        }

        .btn-search {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.3);
        }

        .btn-search:hover {
            opacity: 0.88;
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(124, 58, 237, 0.4);
        }

        .btn-search:active {
            transform: translateY(0);
        }

        .search-hint {
            text-align: center;
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 10px;
        }

        .search-hint span {
            color: #a78bfa;
            font-weight: 600;
        }

        .products-section {
            position: relative;
            z-index: 1;
            padding: 0 20px 80px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-6px);
            border-color: rgba(124, 58, 237, 0.35);
            box-shadow: 0 20px 60px rgba(124, 58, 237, 0.12);
        }

        .product-img-wrap {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
            padding: 20px;
        }

        .product-card:hover .product-img-wrap img {
            transform: scale(1.06);
        }

        .product-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .product-body {
            padding: 22px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 0.72rem;
            color: #a78bfa;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-desc {
            font-size: 0.85rem;
            color: var(--muted);
            flex: 1;
            margin-bottom: 18px;
            line-height: 1.6;
        }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-view {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 22px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
            text-decoration: none;
        }

        .btn-view:hover {
            opacity: 0.88;
            transform: scale(1.03);
            color: white;
        }

        .product-col {
            animation: fadeUp 0.5s ease both;
        }

        .product-col:nth-child(1) {
            animation-delay: 0.05s;
        }

        .product-col:nth-child(2) {
            animation-delay: 0.12s;
        }

        .product-col:nth-child(3) {
            animation-delay: 0.19s;
        }

        .product-col:nth-child(4) {
            animation-delay: 0.26s;
        }

        .product-col:nth-child(5) {
            animation-delay: 0.33s;
        }

        .product-col:nth-child(6) {
            animation-delay: 0.40s;
        }

        /* ── product.php ── */
        .product-page {
            position: relative;
            z-index: 1;
            padding: 60px 20px 100px;
        }

        .breadcrumb-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 48px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .breadcrumb-wrap a {
            color: #a78bfa;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-wrap a:hover {
            color: var(--accent2);
        }

        .product-wrap {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: start;
        }

        @media (max-width: 768px) {
            .product-wrap {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }

        .product-img-box {
            position: relative;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            height: 460px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.3s, box-shadow 0.3s;
            animation: fadeUp 0.5s ease both;
            animation-delay: 0.05s;
        }

        .product-img-box:hover {
            border-color: rgba(124, 58, 237, 0.35);
            box-shadow: 0 20px 60px rgba(124, 58, 237, 0.12);
        }

        .product-img-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 48px;
            transition: transform 0.5s ease;
        }

        .product-img-box:hover img {
            transform: scale(1.04);
        }

        .product-details {
            display: flex;
            flex-direction: column;
            animation: fadeUp 0.5s ease both;
            animation-delay: 0.15s;
        }

        .product-category-label {
            font-size: 0.72rem;
            color: #a78bfa;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .product-title {
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 900;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .product-price-big {
            font-size: 2.6rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 24px;
        }

        .product-description {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.8;
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid var(--border);
        }

        .product-meta {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 36px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 18px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: border-color 0.2s;
        }

        .meta-item:hover {
            border-color: rgba(124, 58, 237, 0.25);
        }

        .meta-label {
            font-size: 0.82rem;
            color: var(--muted);
            font-weight: 600;
        }

        .meta-value {
            font-size: 0.88rem;
            color: var(--text);
            font-weight: 700;
        }

        .status-in {
            color: #4ade80;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border: 1px solid var(--border);
            border-radius: 14px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
            width: fit-content;
        }

        .btn-back:hover {
            border-color: rgba(124, 58, 237, 0.4);
            color: #a78bfa;
            background: rgba(124, 58, 237, 0.05);
        }
    </style>
</head>

<body>
    <div class="container">
        {{content}}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
