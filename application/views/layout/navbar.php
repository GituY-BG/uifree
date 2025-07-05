<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo site_url(); ?>" id="navbarBrand" data-brand-text="Hotspot Monitoring">|</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->uri->segment(1) == 'dashboard' ? 'active' : ''; ?>" href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->uri->segment(1) == 'profile' ? 'active' : ''; ?>" href="<?php echo site_url('profile'); ?>">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->uri->segment(1) == 'user' ? 'active' : ''; ?>" href="<?php echo site_url('user'); ?>">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->uri->segment(1) == 'nas' ? 'active' : ''; ?>" href="<?php echo site_url('nas'); ?>">NAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->uri->segment(1) == 'settings' && $this->uri->segment(2) == '' ? 'active' : ''; ?>" href="<?php echo site_url('settings'); ?>">Pengaturan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->uri->segment(1) == 'settings' && $this->uri->segment(2) == 'logs' ? 'active' : ''; ?>" href="<?php echo site_url('settings/logs'); ?>">Log Aktivitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $this->uri->segment(1) == 'graph' ? 'active' : ''; ?>" href="<?php echo site_url('graph'); ?>">Graph</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Logout</a>
                </li>
                <li class="nav-item">
                    <button class="btn ms-2" id="themeToggle">
                        <?php echo $this->session->userdata('theme') === 'dark' ? '☀️' : '🌙'; ?>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Opsional: Tambahkan efek kursor berkedip */
#navbarBrand::after {
    content: "|";
    animation: blink 1s infinite;
    margin-left: 2px;
}

@keyframes blink {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0; }
}
</style>

<script>
    function typeAnimation() {
        const brand = document.getElementById('navbarBrand');
        const fullText = brand.getAttribute('data-brand-text') || 'Hotspot Monitoring';
        brand.textContent = '';
        let i = 0;
        function type() {
            if (i < fullText.length) {
                brand.textContent = fullText.substring(0, i + 1);
                i++;
                setTimeout(type, 100);
            }
        }
        type();
    }

    document.addEventListener('DOMContentLoaded', typeAnimation);

    document.getElementById('themeToggle').addEventListener('click', function () {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        this.innerHTML = newTheme === 'light' ? '🌙' : '☀️';

        fetch('<?php echo site_url('settings/switch_theme'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'theme=' + newTheme
        });

        if (window.activeUsersChart) {
            window.activeUsersChart.destroy();
            renderChart(newTheme);
        }

        // Opsional: ulangi animasi saat ganti tema
        typeAnimation();
    });
</script>
