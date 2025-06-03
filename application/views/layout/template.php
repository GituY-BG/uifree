<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'RADIUS Admin'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">RADIUS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    $menu = [
                        'dashboard' => 'Dashboard',
                        'profile'   => 'Profile',
                        'user'      => 'User',
                        'nas'       => 'NAS',
                        'settings'  => 'Pengaturan',
                        'graph'     => 'Graph',
                        'auth/logout' => 'Logout',
                    ];
                    $current = $this->uri->segment(1);
                    foreach ($menu as $link => $label) {
                        $active = ($current == $link) ? 'active' : '';
                        echo "<li class='nav-item'><a class='nav-link $active' href='" . site_url($link) . "'>$label</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container mt-4">
        <?= $content ?>
    </div>

    <!-- Optional JS (Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>