<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Footer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sticky Footer dengan Flexbox */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .container, .container-fluid, .container-sm, .container-md, .container-lg, .container-xl, .container-xxl {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
<footer class="bg-light text-center py-3">
    <div class="container">
        <p class="mb-0">Dibuat oleh <a href="https://www.instagram.com/your_instagram_handle" target="_blank">Your Instagram</a> © <?php echo date('Y'); ?></p>
    </div>
</footer>
</body>
</html>