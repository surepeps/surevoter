<?php
/**
 * essential-master
 * Created by SureCoder
 * FILE NAME: configuration.php
 * YEAR: 2022
 */

// get all Application values
$gconfig = array();

/*
 * ----------------------------------------------------------
 * DB PARAMETERS
 * ----------------------------------------------------------
 *
 *
 */
$sql_db_host = collectEnv('DB_HOST');
$sql_db_user = collectEnv('DB_USER');
$sql_db_pass = collectEnv('DB_PASS');
$sql_db_name = collectEnv('DB_NAME');
$sql_db_port = collectEnv('DB_PORT');

/*
 * ----------------------------------------------------------
 * APP PARAMETERS
 * ----------------------------------------------------------
 *
 *
 */
$site_url = collectEnv('BASE_URL');

/*
 *
 * ------------------------------------------------------------
 * APPLICATION MODE (installed / yet to be installed)
 * ------------------------------------------------------------
 *
 */

$softwareMode = collectEnv('APP_MODE');;
