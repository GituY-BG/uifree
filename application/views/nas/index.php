<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>NAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .table-container table {
            width: 100%;
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
                        <h2 class="card-title">NAS</h2>
                        <a href="<?php echo site_url('nas/add'); ?>" class="btn btn-primary mb-3">Tambah NAS</a>
                        <form action="<?php echo site_url('nas'); ?>" method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search_nas" placeholder="Cari nama NAS atau shortname..." value="<?php echo $this->input->get('search_nas'); ?>">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>
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
                        <div class="table-container">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama NAS</th>
                                        <th>Shortname</th>
                                        <th>Tipe</th>
                                        <th>Ports</th>
                                        <th>Secret</th>
                                        <th>Server</th>
                                        <th>Community</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $nas_count = 0;
                                    foreach ($nas_list as $nas): 
                                        if ($nas_count < 10):
                                            $nas_count++;
                                    ?>
                                        <tr>
                                            <td><?php echo $nas->nasname; ?></td>
                                            <td><?php echo $nas->shortname; ?></td>
                                            <td><?php echo $nas->type; ?></td>
                                            <td><?php echo $nas->ports; ?></td>
                                            <td><?php echo $nas->secret; ?></td>
                                            <td><?php echo $nas->server ?: '-'; ?></td>
                                            <td><?php echo $nas->community ?: '-'; ?></td>
                                            <td><?php echo $nas->description ?: '-'; ?></td>
                                            <td>
                                                <a href="<?php echo site_url('nas/edit/' . $nas->id); ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                                <a href="<?php echo site_url('nas/delete/' . $nas->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus NAS ini?');"><i class="bi bi-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>