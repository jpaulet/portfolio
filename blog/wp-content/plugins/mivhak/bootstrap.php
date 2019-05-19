<?php
/**
 * Mivhak Syntax Highlighter
 *
 * A lightweight syntax highlighter for WordPress, fully integrated into the TinyMCE rich editor.
 *
 * @package   Mivhak Syntax Highlighter
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/mivhak
 * @copyright 2017 Askupa Software
 *
 * @wordpress-plugin
 * Plugin Name:     Mivhak Syntax Highlighter
 * Plugin URI:      http://products.askupasoftware.com/mivhak
 * Description:     A lightweight syntax highlighter for WordPress, fully integrated into the TinyMCE rich editor.
 * Version:         1.3.9
 * Author:          Askupa Software
 * Author URI:      http://www.askupasoftware.com
 * Text Domain:     mivhak
 * Domain Path:     /languages
 */

if( !function_exists('mivhak_bootstrap') )
{
    function mivhak_bootstrap()
    {
        $validator = require_once 'vendor/askupa-software/amarkal-framework/EnvironmentValidator.php';
        $validator->add_plugin( 'Mivhak Syntax Highlighter', dirname( __FILE__ ).'/includes/Mivhak.php' );
        require dirname( __FILE__ ) . '/vendor/autoload.php';
    }
}
mivhak_bootstrap();

if( !function_exists('mivhak_load_textdomain') )
{
    add_action( 'plugins_loaded', 'mivhak_load_textdomain' );
    function mivhak_load_textdomain() 
    {
        load_plugin_textdomain( 'mivhak', false, plugin_basename( dirname( __FILE__ ) ).'/languages/' );
    }
}
