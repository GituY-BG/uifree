<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">Register Admin</h2>
                <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                <form action="<?php echo site_url('auth/store_register'); ?>" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (min 6 karakter)</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Register</button>
                </form>
                <div class="mt-2 text-center">
                    <a href="<?php echo site_url('auth'); ?>">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>