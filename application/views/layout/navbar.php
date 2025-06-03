<!-- Navbar Horizontal -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Hotspot Monitoring</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>" href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'profile' ? 'active' : '' ?>" href="<?php echo site_url('profile'); ?>">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'user' ? 'active' : '' ?>" href="<?php echo site_url('user'); ?>">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'nas' ? 'active' : '' ?>" href="<?php echo site_url('nas'); ?>">NAS</a>
                </li>
                <li class="nav-item">
    <a class="nav-link <?= $this->uri->segment(1) == 'settings' && $this->uri->segment(2) == '' ? 'active' : '' ?>" href="<?php echo site_url('settings'); ?>">Pengaturan</a>
</li>
<li class="nav-item">
    <a class="nav-link <?= $this->uri->segment(1) == 'settings' && $this->uri->segment(2) == 'logs' ? 'active' : '' ?>" href="<?php echo site_url('settings/logs'); ?>">Log Aktivitas</a>
</li>

                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'graph' ? 'active' : '' ?>" href="<?php echo site_url('graph'); ?>">Graph</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
