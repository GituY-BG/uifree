<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
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
                        <?php echo htmlspecialchars($this->session->flashdata('success')); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($this->session->flashdata('error')); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($profile) && $profile && !empty($profile->groupname)): ?>
                    <?php
                    // Query untuk menghitung pengguna aktif
                    $this->db->select('radusergroup.username');
                    $this->db->from('radusergroup');
                    $this->db->join('radacct', 'radusergroup.username = radacct.username', 'left');
                    $this->db->where('radusergroup.groupname', $profile->groupname);
                    $this->db->where('radacct.acctstoptime IS NULL', null, false);
                    $active_users = $this->db->get()->num_rows();
                    if ($active_users > 0): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Perhatian: Profil ini digunakan oleh <?php echo htmlspecialchars($active_users); ?> pengguna aktif. Perubahan mungkin memengaruhi sesi aktif.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <h2>Edit Profil</h2>
                    <a href="<?php echo site_url('settings'); ?>" class="btn btn-secondary mb-3">Kembali</a>
                    <form action="<?php echo site_url('settings/update_profile'); ?>" method="post">
                        <input type="hidden" name="groupname" value="<?php echo htmlspecialchars($profile->groupname); ?>">
                        <div class="mb-3">
                            <label for="groupname_display" class="form-label">Nama Profil</label>
                            <input type="text" class="form-control" id="groupname_display" value="<?php echo htmlspecialchars($profile->groupname); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="rate_limit" class="form-label">Rate Limit (contoh: 2M/2M atau 512k/512k)</label>
                            <input type="text" class="form-control" id="rate_limit" name="rate_limit" value="<?php echo htmlspecialchars($rate_limit); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="simultan" class="form-label">Pengguna Simultan</label>
                            <input type="number" class="form-control" id="simultan" name="simultan" value="<?php echo htmlspecialchars($simultan); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        Profil tidak ditemukan atau data tidak valid.
                    </div>
                    <a href="<?php echo site_url('settings'); ?>" class="btn btn-secondary mb-3">Kembali ke Pengaturan</a>
                <?php endif; ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>