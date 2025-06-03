<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .table-container table {
            width: 100%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body data-theme="<?php echo $this->session->userdata('theme') ?: 'light'; ?>">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php $this->load->view('layout/header', ['title' => 'Pengaturan']); ?>
            <?php $this->load->view('layout/navbar'); ?>
            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 mt-4">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <h2>Pengaturan</h2>
                <h4>Data User</h4>
                <form action="<?php echo site_url('settings'); ?>" method="get" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search_user" placeholder="Cari username..." value="<?php echo $this->input->get('search_user'); ?>">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="active" <?php echo ($this->input->get('status') == 'active') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="inactive" <?php echo ($this->input->get('status') == 'inactive') ? 'selected' : ''; ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="table-container">
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Profile</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $user_count = 0;
                            foreach ($users as $user): 
                                if ($user_count < 10):
                                    $user_count++;
                            ?>
                                <tr>
                                    <td><?php echo $user->username; ?></td>
                                    <td>
                                        <?php
                                        $this->db->where('username', $user->username);
                                        $group = $this->db->get('radusergroup')->row();
                                        echo $group ? $group->groupname : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $this->db->where('username', $user->username);
                                        $this->db->where('acctstoptime IS NULL', null, false);
                                        $status = $this->db->get('radacct')->num_rows() > 0 ? 'Aktif' : 'Tidak Aktif';
                                        echo $status;
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('settings/edit_user/' . $user->username); ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                        <a href="<?php echo site_url('settings/delete_user/' . $user->username); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?');"><i class="bi bi-trash"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <h4>Data Profile</h4>
                <form action="<?php echo site_url('settings'); ?>" method="get" class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search_profile" placeholder="Cari nama profile..." value="<?php echo $this->input->get('search_profile'); ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Profile</th>
                                <th>Rate Limit</th>
                                <th>Simultan User</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $profile_count = 0;
                            $displayed_groups = []; // Track displayed groupnames
                            foreach ($profiles as $profile): 
                                if (!in_array($profile->groupname, $displayed_groups) && $profile_count < 10):
                                    $profile_count++;
                                    $displayed_groups[] = $profile->groupname;
                            ?>
                                <tr>
                                    <td><?php echo $profile->groupname; ?></td>
                                    <td>
                                        <?php
                                        echo isset($profile->rate_limit) ? $profile->rate_limit : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo isset($profile->simultan) ? $profile->simultan : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('settings/edit_profile/' . $profile->groupname); ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                        <a href="<?php echo site_url('settings/delete_profile/' . $profile->groupname); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus profile ini? Semua user dengan profile ini juga akan dihapus.');"><i class="bi bi-trash"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>