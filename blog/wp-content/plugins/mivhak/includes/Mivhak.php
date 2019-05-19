<?php
/**
 * @package   Mivhak Syntax Highlighter
 * @date      2016-06-21
 * @version   1.3.6
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/mivhak
 * @copyright 2016 Askupa Software
 */

namespace Mivhak;

use Amarkal\Extensions\WordPress\Plugin;
use Amarkal\Extensions\WordPress\Editor;
use Amarkal\Extensions\WordPress\Options;
use Amarkal\Loaders;

class Mivhak extends Plugin\AbstractPlugin 
{    
    private static $options;
    
    public function __construct() 
    {
        parent::__construct( dirname( __DIR__ ).'/bootstrap.php' );
        
        $this->generate_defines();

        // Register an options page
        self::$options = new Options\OptionsPage( include('configs/options.php') );
        self::$options->register();
        
        // Add a popup button to the rich editor
        Editor\Editor::add_button( include('configs/editor.php') );
        
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_public_assets' ) );
        $this->add_filters();
    }
    
    public function generate_defines()
    {
        $basepath = dirname( __FILE__ );
        define( __NAMESPACE__.'\PLUGIN_DIR', $basepath );
        define( __NAMESPACE__.'\PLUGIN_URL', plugin_dir_url( $basepath ) );
        define( __NAMESPACE__.'\JS_URL', plugin_dir_url( $basepath ).'assets/js' );
        define( __NAMESPACE__.'\CSS_URL', plugin_dir_url( $basepath ).'assets/css' );
        define( __NAMESPACE__.'\IMG_URL', plugin_dir_url( $basepath ).'assets/img' );
        define( __NAMESPACE__.'\PLUGIN_VERSION', '1.3.6' );
    }
    
    public function get_default_settings()
    {
        return array(
            'line_numbers'  => self::$options->get('line_numbers') == 'ON' ? true : false,
            'auto_assign'   => self::$options->get('auto_assign'),
            'show_meta'     => self::$options->get('show_meta') == 'ON' ? true : false,
            'min_lines'     => self::$options->get('min_lines'),
            'default_lang'  => self::$options->get('default_lang'),
            'font_size'     => self::$options->get('font_size'),
            'theme'         => self::$options->get('theme'),
            'version'       => PLUGIN_VERSION,
            'lang_list'     => include(__DIR__.'/configs/langs.php'),
            'i18n'          => include('configs/strings.php')
        );
    }
    
    public function register_admin_assets()
    {
        wp_enqueue_script( 'mivhak', JS_URL.'/mivhak.min.js', array('jquery', 'ace-editor'), PLUGIN_VERSION, true );
    }
    
    public function register_public_assets()
    {
        wp_enqueue_script( 'ace-editor', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js', array('jquery'), '1.2.3', true );
        wp_enqueue_script( 'mivhak', JS_URL.'/mivhak.min.js', array('jquery', 'ace-editor'), PLUGIN_VERSION, true );
        wp_localize_script( 'mivhak', 'mivhak_settings', $this->get_default_settings() );
        wp_enqueue_style( 'mivhak', CSS_URL.'/mivhak.min.css', array(), PLUGIN_VERSION );
        
        // Custom CSS
        add_action( 'wp_head', array( __CLASS__, 'custom_css' ) );
    }
    
    private function add_filters()
    {
        add_filter( 'mce_css', array( __CLASS__, 'editor_css' ) );
        add_action( 'admin_print_footer_scripts', array( __CLASS__, 'add_quicktags' ) );
        add_action( 'wp_print_footer_scripts', array( __CLASS__, 'add_quicktags' ) );
    }
    
    static function editor_css( $wp ) 
    {
        $wp .= ',' . CSS_URL.'/editor.min.css';
        return $wp;
    }
    
    static function custom_css()
    {
        if( 'ON' == self::$options->get('css_toggle') )
        {
            $css = self::$options->get('css');
            echo "<style>$css</style>";
        }
    }
    
    public static function uninstall( $network_wide ) 
    {
        parent::uninstall($network_wide);
        self::$options->uninstall();
    }
    
    /**
     * Add Custom QuickTags to the HTML editor
     * @see http://www.wpexplorer.com/adding-wordpress-custom-quicktags/
     */
    public static function add_quicktags() 
    { ?>
        <script>if( typeof QTags !== 'undefined' ){QTags.addButton( 'pre', 'pre', '<pre>', '</pre>\n', 'p', '', 105 );}</script>
    <?php }
}
new Mivhak();
