<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Graph</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/chart.umd.min.js'); ?>"></script>
</head>
<body>
    <?php $this->load->view('layout/header', ['title' => 'Graph']); ?>
    <?php $this->load->view('layout/navbar'); ?>
    <div class="container mt-4">
        <h2>Graph</h2>
        <form id="graphForm" method="post">
            <div class="mb-3">
                <label for="type" class="form-label">Tipe</label>
                <select class="form-control" id="type" name="type">
                    <option value="user">Per User</option>
                    <option value="profile">Per Profile</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id" class="form-label">Pilih</label>
                <div id="id-container">
                    <select class="form-control" id="id" name="id">
                        <?php if (empty($profiles)): ?>
                            <option value="">Tidak ada profil tersedia</option>
                        <?php else: ?>
                            <?php foreach ($profiles as $profile): ?>
                                <option value="<?php echo htmlspecialchars($profile->groupname); ?>">
                                    <?php echo htmlspecialchars($profile->groupname); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <button type="button" class="btn btn-secondary" onclick="downloadReport()">Download PDF</button>
        </form>
        <canvas id="bandwidthChart" class="mt-3"></canvas>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Upload (MB)</th>
                    <th>Download (MB)</th>
                    <th>Pengguna Aktif</th>
                </tr>
            </thead>
            <tbody id="graphTable"></tbody>
        </table>
    </div>
    <script>
        // Fungsi untuk mengubah input berdasarkan tipe
        function updateIdInput(type) {
            const container = document.getElementById('id-container');
            if (type === 'user') {
                container.innerHTML = `
                    <input type="text" class="form-control" id="id" name="id" placeholder="Masukkan username">
                `;
            } else {
                container.innerHTML = `
                    <select class="form-control" id="id" name="id">
                        <?php if (empty($profiles)): ?>
                            <option value="">Tidak ada profil tersedia</option>
                        <?php else: ?>
                            <?php foreach ($profiles as $profile): ?>
                                <option value="<?php echo htmlspecialchars($profile->groupname); ?>">
                                    <?php echo htmlspecialchars($profile->groupname); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                `;
            }
        }

        // Inisialisasi input berdasarkan tipe default
        updateIdInput(document.getElementById('type').value);

        // Event listener untuk perubahan tipe
        document.getElementById('type').addEventListener('change', function() {
            updateIdInput(this.value);
        });

        // Event listener untuk form submit dengan validasi
        document.getElementById('graphForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const type = document.getElementById('type').value;
            const id = document.getElementById('id').value;
            const start_date = document.getElementById('start_date').value;
            const end_date = document.getElementById('end_date').value;
            if (!type || !id || !start_date || !end_date) {
                alert('Semua field harus diisi');
                return;
            }
            if (new Date(start_date) > new Date(end_date)) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                return;
            }
            const formData = new FormData(this);
            console.log('Form data:', Object.fromEntries(formData));
            fetch('<?php echo site_url('graph/get_data'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const ctx = document.getElementById('bandwidthChart').getContext('2d');
                if (window.bandwidthChart instanceof Chart) {
                    window.bandwidthChart.destroy();
                }
                window.bandwidthChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.graph_data.map(item => item.date),
                        datasets: [{
                            label: 'Upload (MB)',
                            data: data.graph_data.map(item => item.upload / (1024 * 1024)),
                            borderColor: 'blue',
                            fill: false
                        }, {
                            label: 'Download (MB)',
                            data: data.graph_data.map(item => item.download / (1024 * 1024)),
                            borderColor: 'red',
                            fill: false
                        }, {
                            label: 'Pengguna Aktif',
                            data: data.graph_data.map(item => item.active_users),
                            borderColor: 'green',
                            fill: false
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                enabled: true
                            }
                        }
                    }
                });
                const tableBody = document.getElementById('graphTable');
                tableBody.innerHTML = '';
                data.graph_data.forEach(item => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.date}</td>
                            <td>${(item.upload / (1024 * 1024)).toFixed(2)}</td>
                            <td>${(item.download / (1024 * 1024)).toFixed(2)}</td>
                            <td>${item.active_users}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => console.error('Error fetching graph data:', error));
        });

        // Fungsi untuk download PDF
        function downloadReport() {
            const form = document.getElementById('graphForm');
            const formData = new FormData(form);
            console.log('Download report data:', Object.fromEntries(formData));
            // Validasi sebelum download
            const type = document.getElementById('type').value;
            const id = document.getElementById('id').value;
            const start_date = document.getElementById('start_date').value;
            const end_date = document.getElementById('end_date').value;
            if (!type || !id || !start_date || !end_date) {
                alert('Semua field harus diisi untuk mengunduh laporan');
                return;
            }
            if (new Date(start_date) > new Date(end_date)) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                return;
            }
            form.action = '<?php echo site_url('graph/download_report'); ?>';
            form.submit();
        }
    </script>
</body>
</html>