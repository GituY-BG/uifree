<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <?php $this->load->view('layout/header', ['title' => 'User']); ?>
    <?php $this->load->view('layout/navbar'); ?>
    <div class="container mt-4">
        <h2>User</h2>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="<?php echo site_url('user/add'); ?>" class="btn btn-primary">Tambah User</a>
                <a href="<?php echo site_url('user/batch_add'); ?>" class="btn btn-secondary">Batch Add (CSV)</a>
            </div>
            <div class="col-md-6">
                <form action="<?php echo site_url('user'); ?>" method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari username..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                </form>
            </div>
        </div>
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Profile</th>
                        <th>Status Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user->username); ?></td>
                                <td><?php echo htmlspecialchars($user->groupname ? $user->groupname : '-'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user->status == 'active' ? 'success' : 'danger'; ?>">
                                        <?php echo $user->status == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('user/toggle_status/' . urlencode($user->username) . '/' . $user->status); ?>" 
                                       class="btn btn-sm btn-<?php echo $user->status == 'active' ? 'danger' : 'success'; ?>">
                                        <?php echo $user->status == 'active' ? 'Nonaktifkan & Disconnect' : 'Aktifkan'; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo $pagination; ?>
        </div>
    </div>
</body>
<?php $this->load->view('layout/footer'); ?>

</html>