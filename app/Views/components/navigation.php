<nav class="navbar navbar-expand-lg bg-success shadow">
    <div class="container-md">
        <a href="" class="fs-3 lead text-white text-decoration-none me-lg-3">SIDC System</a>
        <button class="btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#menu-item">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </span>
        </button>
        <div class="collapse navbar-collapse d-lg-flex justify-content-between" id="menu-item">

            <ul class="navbar-nav">

                <?php if(in_group('admin')): ?>

                    <li class="nav-item">
                        <a href="<?= base_url('admin') ?>" class="nav-link text-white fw-light <?= url_is('admin*') ? 'active' : '' ?>">Admin Settings</a>
                    </li>

                <?php endif; ?>

                <?php if(!in_group('admin')): ?>

                    <li class="nav-item">
                        <a href="<?= base_url('stock') ?>" class="nav-link text-white fw-light <?= url_is('stock*') ? 'active' : '' ?>">Stock Request</a>
                    </li>

                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?= base_url('leave') ?>" class="nav-link text-white fw-light <?= url_is('leave') ? 'active' : '' ?>">Leave Request</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('overtime') ?>" class="nav-link text-white fw-light <?= url_is('overtime') ? 'active' : '' ?>">Overtime Request</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('ob') ?>" class="nav-link text-white fw-light <?= url_is('ob*') ? 'active' : '' ?>">OB Request</a>
                </li>
            </ul>

            <hr class="text-white">

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?= session()->get('name') ?? 'Default User' ?></strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="<?= base_url('settings') ?>">Account Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Sign out</a></li>
                </ul>
            </div>

        </div>
    </div>
</nav>
