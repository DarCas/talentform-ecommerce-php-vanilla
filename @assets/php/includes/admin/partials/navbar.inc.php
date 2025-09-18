<?php
/**
 * Con questa funzione verifico quale sezione del pannello di controllo sto usando in modo da attivare il link giusto.
 *
 * @param string $page
 * @return bool
 */
function isCurrentPage(string $page): bool
{
    $dirname = dirname($_SERVER['SCRIPT_NAME']);

    return ($dirname === $page);
}

?>
<nav class="navbar navbar-expand-lg bg-body-tertiary shadow mb-5">
    <div class="container-fluid">
        <a class="navbar-brand" href="/admin">Vanilla E-Commerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= isCurrentPage('/admin/products') ? 'active' : '' ?>"
                       aria-current="page" href="/admin/products">Prodotti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isCurrentPage('/admin/customers') ? 'active' : '' ?>"
                       href="/admin/customers">Clienti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isCurrentPage('/admin/carts') ? 'active' : '' ?>"
                       href="/admin/carts">Ordini</a>
                </li>
            </ul>

            <a class="btn btn-outline-primary" href="/admin/@action/logout.php">Logout</a>
        </div>
    </div>
</nav>
