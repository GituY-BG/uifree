<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sesi Aktif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('layout/header', ['title' => 'Sesi Aktif']); ?>
            <?php $this->load->view('layout/navbar'); ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
                <h2 class="mb-4">Sesi Aktif</h2>

                <!-- Tombol Kembali ke Dashboard -->
                <div class="mb-3">
                    <a href="<?php echo site_url('dashboard/index'); ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>

                <!-- Tampilkan pesan error dari flashdata -->
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Sesi Aktif</h4>
                        <form action="<?php echo site_url('dashboard/active_sessions'); ?>" method="post" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="username" placeholder="Cari pengguna...">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>

                        <?php if (isset($user_status) && $user_status['status'] != 'Tidak Ditemukan'): ?>
                            <p>Status: <?php echo htmlspecialchars($user_status['status']); ?></p>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Username</th>
                                        <th>IP Address</th>
                                        <th>Macaddress</th>
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
                                            <td><?php echo htmlspecialchars($session->callingstationid); ?></td>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
<?php $this->load->view('layout/footer'); ?>
</html>