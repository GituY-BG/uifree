<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php $this->load->view('layout/header', ['title' => 'Profile']); ?>
            <?php $this->load->view('layout/navbar'); ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Profile</h2>
                        <a href="<?php echo site_url('profile/add'); ?>" class="btn btn-primary mb-3">Tambah Profile</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Profile</th>
                                        <th>Rate Limit</th>
                                        <th>Simultan User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($profiles as $profile): ?>
                                        <?php if ($profile->attribute == 'Mikrotik-Rate-Limit'): ?>
                                            <tr>
                                                <td><?php echo $profile->groupname; ?></td>
                                                <td><?php echo $profile->value; ?></td>
                                                <td>
                                                    <?php
                                                    $this->db->where('groupname', $profile->groupname);
                                                    $this->db->where('attribute', 'Simultaneous-Use');
                                                    $simultan = $this->db->get('radgroupreply')->row();
                                                    echo $simultan ? $simultan->value : '-';
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Optional: Bootstrap JS (untuk alert dismissible dll) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>