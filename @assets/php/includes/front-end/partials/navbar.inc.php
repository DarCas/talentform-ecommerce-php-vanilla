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
<nav class="navbar navbar-expand bg-body-tertiary shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Vanilla E-Commerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= isCurrentPage('/') ? 'active' : '' ?>"
                       aria-current="page" href="/">Homepage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isCurrentPage('/products') ? 'active' : '' ?>"
                       href="/products">Prodotti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isCurrentPage('/contacts') ? 'active' : '' ?>"
                       href="/contacts">Contatti</a>
                </li>
            </ul>

            <!--a class="btn btn-outline-primary" href="/admin/@action/logout.php">Logout</a-->

            <form action="/products" method="get" class="d-flex" role="search">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Cerca un prodotto"
                           value="<?= htmlentities($_GET['q'] ?? '') ?>"
                           aria-label="Cerca un prodotto"/>
                    <button class="btn btn-success" type="submit"><i class="bi bi-search"></i></button>
                </div>

            </form>
        </div>
    </div>
</nav>
<script>
    const inputSearch = document.querySelector('input[name="q"]');
    inputSearch.addEventListener('input', () => {
        if (inputSearch.value === '') {
            location.href = './<?php

                $queryString = $_GET;
                unset($queryString['q']);

                if (count($queryString) > 0) {
                    echo '?' . http_build_query($queryString);
                }

                ?>';
        }
    })
</script>
