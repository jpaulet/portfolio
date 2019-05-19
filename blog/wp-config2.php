<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'db740698843');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'dbo740698843');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'fjiowej1293015_23asdfoiA');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'db740698843.db.1and1.com');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'pT,`J0$eNbs%:sOg+[l997Qv75BhYb|fn5@!{.F:/DGC53C*qx89[l7AlD]}@8D~');
define('SECURE_AUTH_KEY', 'C.5B_>#(6o6e9h4!aVsC<Rvz3NAY00Zf$q;x}9<lVvJQU|iEXt#MR9YuD$(c2D`r');
define('LOGGED_IN_KEY', '`X[3S5iO2}BtwCy0`%XX5T*U|9^iVsqw7LBv+G2CQ$J3y&<b0:o afRiv2fs)71C');
define('NONCE_KEY', 'PDeNvK>`(qn!hR2&80kyP4K, |1k@@6i8y68^z[u>!j$J`$((gsM2%1JrJ1 j8M]');
define('AUTH_SALT', 'yo;fAU8B0Vs0L{El3a6x6d9)nnWf`52zv@_p`Q>UfC0]m,Q!8)AlS7GnJ3ShACXP');
define('SECURE_AUTH_SALT', '<Y-bKb~k}Znq[W^H6JY2Z-Gz_7>8}D:}Lc4Jc(nC<?k:SvHT<+Xij6Y:0f#8a)(~');
define('LOGGED_IN_SALT', ' ~.h?z#AV.w!jst x0z$y$gnx{Z?=rX^*4}HLs2twT7}^Pol|vuC4;wO<A355Frs');
define('NONCE_SALT', '=}6&RB9{dSD!r7IUF.x_DqC^Otsg>IVr28~D`y9A7<CbXZX$hSjANN-<;MY2^z(W');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


