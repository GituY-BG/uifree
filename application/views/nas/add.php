<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah NAS</title>
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

            <?php $this->load->view('layout/header', ['title' => 'NAS']); ?>
            <?php $this->load->view('layout/navbar'); ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Tambah NAS</h2>
                        <a href="<?php echo site_url('nas'); ?>" class="btn btn-secondary mb-3">Kembali</a>
                        <form action="<?php echo site_url('nas/add'); ?>" method="post">
                            <div class="mb-3">
                                <label for="nasname" class="form-label">Nama NAS</label>
                                <input type="text" class="form-control" id="nasname" name="nasname" required>
                            </div>
                            <div class="mb-3">
                                <label for="shortname" class="form-label">Shortname</label>
                                <input type="text" class="form-control" id="shortname" name="shortname" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe</label>
                                <input type="text" class="form-control" id="type" name="type" value="other" required>
                            </div>
                            <div class="mb-3">
                                <label for="ports" class="form-label">Ports</label>
                                <input type="number" class="form-control" id="ports" name="ports" value="1812" required>
                            </div>
                            <div class="mb-3">
                                <label for="secret" class="form-label">Secret</label>
                                <input type="text" class="form-control" id="secret" name="secret" required>
                            </div>
                            <div class="mb-3">
                                <label for="server" class="form-label">Server</label>
                                <input type="text" class="form-control" id="server" name="server">
                            </div>
                            <div class="mb-3">
                                <label for="community" class="form-label">Community</label>
                                <input type="text" class="form-control" id="community" name="community">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS (Opsional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>