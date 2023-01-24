<?php



/*
 * ----------------------------------------------------------
 * Autoloader
 * ----------------------------------------------------------
 *
 *
 * Execute Autoloader for external libraries
 */
require __DIR__.'/../../vendor/autoload.php';



/*
 * ----------------------------------------------------------
 * Whoops Exception
 * ----------------------------------------------------------
 *
 *
 */
include("WhoopsMsg.php");
$whoops = new WhoopsMsg();
$whoops->initError();


/*
 * ----------------------------------------------------------
 * PATH Helper
 * ----------------------------------------------------------
 *
 *
 */
/**
 *
 * Root path
 *
 */
define('ROOT', realpath(__DIR__.'/../../'));

/**
 *
 * Directory separator
 *
 */
define('DS', DIRECTORY_SEPARATOR);


/*
 * ----------------------------------------------------------
 * DotEnv Helper
 * ----------------------------------------------------------
 *
 *
 */
/**
 * DOTENV Helper
 *
 * @param string $data
 *
 * @return string
 */
if (! function_exists('collectEnv')){
    function collectEnv($data)
    {
        $dotEnvLoader =  \Dotenv\Dotenv::createImmutable(ROOT);
        $dotEnvLoader->load();

        return $_ENV[$data];
    }
}


/**
 * Required DOTENV Helper
 *
 * @return string
 */
if (! function_exists('requiredEnv')){
    function requiredEnv()
    {
        $requiredloaderenv =  \Dotenv\Dotenv::createImmutable(ROOT);
        $requiredloaderenv->load();

        $requiredloaderenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'APP_ENV']);

    }
}


/*
 * ----------------------------------------------------------
 * DB Helper
 * ----------------------------------------------------------
 *
 *
 */
require_once('DB/vendor/autoload.php');




/*
 * ----------------------------------------------------------
 * PAGINATION Helper
 * ----------------------------------------------------------
 *
 *
 */
include('Pagination.php');



/*
 * ----------------------------------------------------------
 * ADMIN PAGINATION Helper
 * ----------------------------------------------------------
 *
 *
 */
include('AdminPagination.php');
