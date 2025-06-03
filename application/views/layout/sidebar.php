<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar p-3">
    <div class="position-sticky">
        <h5 class="text-white">Menu</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>" href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $this->uri->segment(1) == 'profile' ? 'active' : '' ?>" href="<?php echo site_url('profile'); ?>">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $this->uri->segment(1) == 'user' ? 'active' : '' ?>" href="<?php echo site_url('user'); ?>">User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $this->uri->segment(1) == 'nas' ? 'active' : '' ?>" href="<?php echo site_url('nas'); ?>">NAS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $this->uri->segment(1) == 'settings' ? 'active' : '' ?>" href="<?php echo site_url('settings'); ?>">Pengaturan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $this->uri->segment(1) == 'graph' ? 'active' : '' ?>" href="<?php echo site_url('graph'); ?>">Graph</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo site_url('auth/logout'); ?>">Logout</a>
            </li>
        </ul>
    </div>
</nav>