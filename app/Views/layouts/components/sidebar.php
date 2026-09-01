<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand d-flex align-items-center px-3">
        <a href="<?= base_url() ?>" class="brand-link d-flex align-items-center gap-2 text-decoration-none">
            <i class="bi bi-receipt-cutoff text-primary fs-4 brand-image"></i>
            <span class="brand-text fw-bold fs-6">Facturación App</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= url_is('dashboard*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-muted fs-7 fw-bold mt-2">Gestión</li>

                <?php $isFacturas = url_is('facturas*'); ?>
                <li class="nav-item <?= $isFacturas ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= $isFacturas ? 'active' : '' ?>" aria-expanded="<?= $isFacturas ? 'true' : 'false' ?>">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>
                            Facturación
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('facturas/nueva') ?>" class="nav-link <?= url_is('facturas/nueva') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-plus-circle-dotted"></i>
                                <p>Nueva Factura</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('facturas') ?>" class="nav-link <?= url_is('facturas') && !url_is('facturas/nueva') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>Historial</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>