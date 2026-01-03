<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKT Artisan — Premium Handcrafted E-commerce</title>
    @vite('resources/css/app.css', 'resources/js/app.js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,600;1,700&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --wood-dark: #3D2B1F;
            --leather-tan: #A67B5B;
            --clay-light: #F2E8DF;
            --copper-accent: #B87333;
            --gold-premium: #D4AF37;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FDFBFA;
            color: var(--wood-dark);
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .bg-clay {
            background-color: var(--clay-light);
        }

        .text-copper {
            color: var(--copper-accent);
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--wood-dark) 0%, #1a1a1a 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            transition: transform 0.7s scale;
        }

        .product-card:hover img {
            transform: scale(1.08);
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased">

    @yield('content')

    @stack('scripts')
</body>

</html>
