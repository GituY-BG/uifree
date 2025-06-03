<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php $this->load->view('layout/header', ['title' => 'User']); ?>
            <?php $this->load->view('layout/navbar'); ?>

<body>
    <div class="container mt-4">
        <h2>Tambah User</h2>
        <a href="<?php echo site_url('user'); ?>" class="btn btn-secondary mb-3">Kembali</a>
        <form action="<?php echo site_url('user/add'); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="profile" class="form-label">Profile</label>
                <select class="form-control" id="profile" name="profile" required>
                    <option value="">Pilih Profile</option>
                    <?php foreach ($profiles as $profile): ?>
                        <option value="<?php echo $profile->groupname; ?>"><?php echo $profile->groupname; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>
<?php $this->load->view('layout/footer'); ?>

</html>