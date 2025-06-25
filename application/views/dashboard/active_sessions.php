<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Top User</title>
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
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('layout/header', ['title' => 'Top User']); ?>
            <?php $this->load->view('layout/navbar'); ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
                <h2 class="mb-4">Top User</h2>

                <!-- Tombol Kembali ke Dashboard -->
                <div class="mb-3">
                    <a href="<?php echo site_url('dashboard/index'); ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>

                <!-- Form Filter -->
                <h4 class="mt-4">Filter Top User</h4>
                <form action="<?php echo site_url('dashboard/active_sessions'); ?>" method="get" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="profile" class="form-label">Profil</label>
                            <select class="form-control" name="profile" id="profile">
                                <option value="">Semua Profil</option>
                                <?php foreach ($profiles as $p): ?>
                                    <option value="<?php echo htmlspecialchars($p['groupname']); ?>" <?php echo ($selected_profile == $p['groupname']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($p['groupname']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Tabel Ringkasan Profil (hanya muncul jika profil dipilih) -->
                <?php if (!empty($selected_profile) && !empty($profile_summary)): ?>
                    <h4 class="mt-4">Ringkasan Profil</h4>
                    <div class="table-container">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Profil</th>
                                    <th>Jumlah Pengguna</th>
                                    <th>Upload (GB)</th>
                                    <th>Download (GB)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlspecialchars($profile_summary['profile']); ?></td>
                                    <td><?php echo htmlspecialchars($profile_summary['user_count']); ?></td>
                                    <td><?php echo number_format($profile_summary['total_upload'], 2); ?></td>
                                    <td><?php echo number_format($profile_summary['total_download'], 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Tabel Top User -->
                <h4 class="mt-4"><?php echo empty($selected_profile) ? 'Top User Berdasarkan Bandwidth' : 'Top User pada Profil ' . htmlspecialchars($selected_profile); ?></h4>
                <div class="table-container">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Username</th>
                                <th>IP Address</th>
                                <th>MAC Address</th>
                                <th>Upload (GB)</th>
                                <th>Download (GB)</th>
                                <th>Profil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sessions)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sessions as $session): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($session->username); ?></td>
                                        <td><?php echo htmlspecialchars($session->framedipaddress); ?></td>
                                        <td><?php echo htmlspecialchars($session->callingstationid); ?></td>
                                        <td><?php echo number_format($session->total_upload, 2); ?></td>
                                        <td><?php echo number_format($session->total_download, 2); ?></td>
                                        <td><?php echo htmlspecialchars($session->profile); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if (!empty($selected_profile) && !empty($total_bandwidth)): ?>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total:</th>
                                    <th><?php echo number_format($total_bandwidth['total_upload'], 2); ?> GB</th>
                                    <th><?php echo number_format($total_bandwidth['total_download'], 2); ?> GB</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo $pagination; ?>
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