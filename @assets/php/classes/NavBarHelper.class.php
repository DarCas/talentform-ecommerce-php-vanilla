<?php

abstract class NavBarHelper {
    /**
     * Verifico se la pagina è quella corrente
     *
     * @param string $page
     * @return bool
     */
    static function isCurrentPage(string $page): bool
    {
        $dirname = dirname($_SERVER['SCRIPT_NAME']);

        return ($dirname === $page);
    }
}
