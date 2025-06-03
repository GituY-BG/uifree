<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            height: 100vh;
        }
        .nav-link.active {
            background-color: #0d6efd;
            font-weight: 500;
        }
        #activeUsersChart {
            max-height: 300px;
            width: 100%;
            display: block;
            border: 1px solid #ccc; /* Debugging: Pastikan canvas terlihat */
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body data-theme="<?php echo $this->session->userdata('theme') ?: 'light'; ?>">
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('layout/header', ['title' => 'Dashboard']); ?>
            <?php $this->load->view('layout/navbar'); ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
                <h2 class="mb-4">Dashboard</h2>

                <!-- Dropdown Profil -->
                <div class="mb-3">
                    <form action="<?php echo site_url('dashboard/index'); ?>" method="get">
                        <div class="input-group">
                            <select name="profile" class="form-select">
                                <option value="">Semua Profil</option>
                                <?php if (empty($profiles)): ?>
                                    <option value="">Tidak ada profil tersedia</option>
                                <?php else: ?>
                                    <?php foreach ($profiles as $p): ?>
                                        <option value="<?php echo htmlspecialchars($p['groupname']); ?>" <?php echo $selected_profile == $p['groupname'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($p['groupname']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo site_url('dashboard/index'); ?>'">Reset</button>
                        </div>
                    </form>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pengguna Aktif</h5>
                                <p class="card-text"><?php echo $active_users; ?> Pengguna</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Upload</h5>
                                <p class="card-text"><?php echo number_format($total_upload, 2); ?> GB</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Download</h5>
                                <p class="card-text"><?php echo number_format($total_download, 2); ?> GB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Pengguna Aktif per Profil -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Pengguna Aktif per Profil</h4>
                        <canvas id="activeUsersChart"></canvas>
                        <div id="chart-error" class="error-message"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Sesi Aktif</h4>
                        <form action="<?php echo site_url('dashboard/search_user' . ($selected_profile ? '?profile=' . urlencode($selected_profile) : '')); ?>" method="post" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="username" placeholder="Cari pengguna...">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>

                        <?php if (isset($user_status)): ?>
                            <p>Status: <?php echo htmlspecialchars($user_status['status']); ?></p>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Username</th>
                                        <th>IP Address</th>
                                        <th>Waktu Sesi</th>
                                        <th>Upload (MB)</th>
                                        <th>Download (MB)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sessions as $session): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($session->username); ?></td>
                                            <td><?php echo htmlspecialchars($session->framedipaddress); ?></td>
                                            <td><?php echo htmlspecialchars($session->acctstarttime); ?></td>
                                            <td><?php echo number_format($session->acctinputoctets / (1024 * 1024), 2); ?></td>
                                            <td><?php echo number_format($session->acctoutputoctets / (1024 * 1024), 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery and Profile Selection Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Debugging: Log data profil ke konsol
        const profiles = <?php echo json_encode($profiles); ?>;
        console.log('Profiles:', profiles);

        // Debugging: Log data untuk grafik
        const chartLabels = [<?php foreach ($profiles as $p) { echo '"' . htmlspecialchars($p['groupname']) . '",'; } ?>];
        const chartData = [<?php foreach ($profiles as $p) { echo $this->Dashboard_model->get_active_users_count($p['groupname']) . ','; } ?>];
        console.log('Chart Labels:', chartLabels);
        console.log('Chart Data:', chartData);

        // Periksa apakah Chart.js tersedia
        const errorDiv = document.getElementById('chart-error');
        if (typeof Chart === 'undefined') {
            console.error('Chart.js tidak ter-load. Pastikan file assets/js/chart.min.js ada dan dapat diakses.');
            errorDiv.innerHTML = 'Gagal memuat grafik: Chart.js tidak tersedia. Pastikan file lokal tersedia.';
            return;
        }

        // Periksa apakah canvas ada
        const canvas = document.getElementById('activeUsersChart');
        if (!canvas) {
            console.error('Canvas #activeUsersChart tidak ditemukan.');
            errorDiv.innerHTML = 'Gagal memuat grafik: Elemen canvas tidak ditemukan.';
            return;
        }

        // Periksa apakah data valid
        if (chartLabels.length === 0 || chartData.length === 0) {
            console.warn('Data grafik kosong. Tidak ada profil atau pengguna aktif.');
            errorDiv.innerHTML = 'Tidak ada data untuk ditampilkan.';
            return;
        }

        // Render grafik
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pengguna Aktif per Profil',
                    data: chartData,
                    borderColor: '#36A2EB',
                    backgroundColor: '#36A2EB',
                    pointBackgroundColor: '#36A2EB',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Pengguna'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Profil'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        // Skrip untuk dropdown profil
        $('select[name="profile"]').change(function() {
            console.log('Dropdown changed:', $(this).val());
            window.location.href = '<?php echo site_url('dashboard/index'); ?>?profile=' + encodeURIComponent($(this).val());
        });
    });
    </script>
</body>
<?php $this->load->view('layout/footer'); ?>
</html>