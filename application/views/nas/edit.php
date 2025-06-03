<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit NAS</title>
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
            <?php $this->load->view('layout/header', ['title' => 'NAS']); ?>
            <?php $this->load->view('layout/navbar'); ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Edit NAS</h2>
                        <a href="<?php echo site_url('nas'); ?>" class="btn btn-secondary mb-3">Kembali</a>
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo site_url('nas/update'); ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $nas->id; ?>">
                            <div class="mb-3">
                                <label for="nasname" class="form-label">Nama NAS</label>
                                <input type="text" class="form-control" id="nasname" name="nasname" value="<?php echo $nas->nasname; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="shortname" class="form-label">Shortname</label>
                                <input type="text" class="form-control" id="shortname" name="shortname" value="<?php echo $nas->shortname; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe</label>
                                <input type="text" class="form-control" id="type" name="type" value="<?php echo $nas->type; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="ports" class="form-label">Ports</label>
                                <input type="number" class="form-control" id="ports" name="ports" value="<?php echo $nas->ports; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="secret" class="form-label">Secret</label>
                                <input type="text" class="form-control" id="secret" name="secret" value="<?php echo $nas->secret; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="server" class="form-label">Server</label>
                                <input type="text" class="form-control" id="server" name="server" value="<?php echo $nas->server; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="community" class="form-label">Community</label>
                                <input type="text" class="form-control" id="community" name="community" value="<?php echo $nas->community; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description"><?php echo $nas->description; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>