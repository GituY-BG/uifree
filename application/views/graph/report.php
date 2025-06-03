<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bandwidth</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Bandwidth</h2>
        <p>Periode: <?php echo htmlspecialchars($this->input->post('start_date')); ?> s/d <?php echo htmlspecialchars($this->input->post('end_date')); ?></p>
        <p>Tipe: <?php echo htmlspecialchars($this->input->post('type') == 'user' ? 'Per User' : 'Per Profile'); ?></p>
        <p><?php echo htmlspecialchars($this->input->post('type') == 'user' ? 'Username' : 'Profil'); ?>: <?php echo htmlspecialchars($this->input->post('id')); ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Upload (MB)</th>
                <th>Download (MB)</th>
                <th>Pengguna Aktif</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($graph_data)): ?>
                <tr>
                    <td colspan="4">Tidak ada data tersedia</td>
                </tr>
            <?php else: ?>
                <?php foreach ($graph_data as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item->date); ?></td>
                        <td><?php echo number_format($item->upload / (1024 * 1024), 2); ?></td>
                        <td><?php echo number_format($item->download / (1024 * 1024), 2); ?></td>
                        <td><?php echo htmlspecialchars($item->active_users); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>