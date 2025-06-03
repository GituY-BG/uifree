<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Batch Add Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php $this->load->view('layout/header', ['title' => 'User']); ?>
            <?php $this->load->view('layout/navbar'); ?>

<body>
    <div class="container mt-4">
        <h2>Batch Add Users (CSV)</h2>
        <a href="<?php echo site_url('user'); ?>" class="btn btn-secondary mb-3">Kembali</a>
        <form action="<?php echo site_url('user/batch_add'); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="profile" class="form-label">Profile</label>
                <select class="form-control" id="profile" name="profile" required>
                    <option value="">Pilih Profile</option>
                    <?php foreach ($profiles as $profile): ?>
                        <option value="<?php echo $profile->groupname; ?>"><?php echo $profile->groupname; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="csv_file" class="form-label">File CSV (Format: username,password)</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Unggah</button>
        </form>
    </div>
</body>
<?php $this->load->view('layout/footer'); ?>

</html>