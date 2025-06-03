<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Profile</ Filling</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php $this->load->view('layout/header', ['title' => 'Tambah Profile']); ?>
            <?php $this->load->view('layout/navbar'); ?>

<body>
    <div class="container mt-4">
        <h2>Tambah Profile</h2>
        <a href="<?php echo site_url('profile'); ?>" class="btn btn-secondary mb-3">Kembali</a>
        <form action="<?php echo site_url('profile/add'); ?>" method="post">
            <div class="mb-3">
                <label for="groupname" class="form-label">Nama Profile</label>
                <input type="text" class="form-control" id="groupname" name="groupname" required>
            </div>
            <div class="mb-3">
                <label for="rate_limit" class="form-label">Rate Limit (contoh: 2M/2M atau 512k/512k)</label>
                <input type="text" class="form-control" id="rate_limit" name="rate_limit" required>
            </div>
            <div class="mb-3">
                <label for="simultan" class="form-label">Simultan User</label>
                <input type="number" class="form-control" id="simultan" name="simultan" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>

</html>