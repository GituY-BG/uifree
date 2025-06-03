<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body data-theme="<?php echo $this->session->userdata('theme') ?: 'light'; ?>">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php $this->load->view('layout/header', ['title' => 'Log']); ?>
            <?php $this->load->view('layout/navbar'); ?>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 mt-4">
                <h2>Log Aktivitas</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Username</th>
                                <th>Aksi</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo $log->created_at; ?></td>
                                    <td><?php echo $log->username; ?></td>
                                    <td><?php echo $log->action; ?></td>
                                    <td><?php echo $log->details; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>