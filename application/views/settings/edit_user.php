<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php $this->load->view('layout/header', ['title' => 'Pengaturan']); ?>
            <?php $this->load->view('layout/navbar'); ?>
            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 mt-4">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php
                $this->db->where('username', $user->username);
                $this->db->where('acctstoptime IS NULL', null, false);
                $is_active = $this->db->get('radacct')->num_rows() > 0;
                if ($is_active): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Perhatian: User ini sedang aktif. Perubahan profil mungkin memengaruhi sesi aktif.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <h2>Edit User</h2>
                <a href="<?php echo site_url('settings'); ?>" class="btn btn-secondary mb-3">Kembali</a>
                <form action="<?php echo site_url('settings/update_user'); ?>" method="post">
                    <input type="hidden" name="username" value="<?php echo $user->username; ?>">
                    <div class="mb-3">
                        <label for="username_display" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username_display" value="<?php echo $user->username; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="profile" class="form-label">Profile</label>
                        <select class="form-control" id="profile" name="profile" required>
                            <option value="">Pilih Profile</option>
                            <?php foreach ($profiles as $profile): ?>
                                <option value="<?php echo $profile->groupname; ?>" <?php echo ($current_profile == $profile->groupname) ? 'selected' : ''; ?>>
                                    <?php echo $profile->groupname; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>