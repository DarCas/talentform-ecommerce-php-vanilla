<!doctype html>
<html lang="it" style="height: 100dvh;">
<head>
    <title>Admin ~ Login</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/header.inc.php"; ?>
</head>
<body style="height: 100dvh;">
<main class="container" style="height: 100dvh;align-content: center;">
    <div class="card shadow mx-auto" style="width: 50%;">
        <div class="card-body">
            <h5 class="card-title">Login</h5>
            <?php
            if (isset($_GET['error'])) {
                ?>
                <div class="alert alert-danger" role="alert">
                    Accesso non autorizzato
                </div>
                <?php
            }
            ?>
            <form action="/admin/@action/login.php" method="post" class="card-text">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="inputUsernm" class="form-label">Username</label>
                        <input type="email" class="form-control" id="inputUsernm"
                               required name="usernm">
                        <?php
                        if (isset($_GET['usernm'])) {
                            ?>
                            <div class="form-text text-danger">
                                <?= $_GET['usernm']; ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="inputPasswd" class="form-label">Password</label>
                        <input type="password" class="form-control" id="inputPasswd"
                               required name="passwd">
                        <?php
                        if (isset($_GET['passwd'])) {
                            ?>
                            <div class="form-text text-danger">
                                <?= $_GET['passwd']; ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn w-100 block btn-success">Accedi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/footer.inc.php"; ?>
</body>
</html>
