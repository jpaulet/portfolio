<?php
/**
 * @package   Mivhak Syntax Highlighter
 * @date      2016-06-21
 * @version   1.3.6
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/mivhak
 * @copyright 2016 Askupa Software
 */

use Amarkal\Extensions\WordPress\Options;
use Amarkal\UI\Components;

return array(
    'banner'        => Mivhak\IMG_URL.'/banner.jpg',
    'title'         => 'Mivhak',
    'subtitle'      => 'A lightweight syntax highlighter for WordPress',
    'version'       => 'v'.Mivhak\PLUGIN_VERSION,
    'author'        => 'Askupa Software',
    'author_url'    => 'http://askupasoftware.com',
    'sidebar_title' => 'Mivhak',
    'sidebar_icon'  => 'dashicons-editor-code',
    'footer_icon'   => Mivhak\IMG_URL.'/askupa-logo.png',
    'footer_text'   => date("Y").' © Askupa Software',
    'subfooter_text'=> 'If you like Mivhak, please give it <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/mivhak?filter=5#postform">★★★★★</a> rating on WordPress.org. Thanks!',
    'sections'      => array(
        new Options\Section(array(
            'title'         => 'General',
            'description'   => __('General syntax highlighting settings.','mivhak'),
            'icon'          => 'fa-gear',
            'fields'        => array(
                new Components\ToggleButton(array(
                    'name'          => 'line_numbers',
                    'title'         => __('Line Numbers','mivhak'),
                    'default'       => 'OFF',
                    'help'          => __('Show/hide line numbers for highlighted code blocks.','mivhak')
                )),
                new Components\ToggleButton(array(
                    'name'          => 'show_meta',
                    'title'         => __('Show Meta Bar','mivhak'),
                    'default'       => 'ON',
                    'help'          => __('Show/hide a header bar with meta information and controls.','mivhak')
                )),
                new Components\Spinner(array(
                    'name'          => 'min_lines',
                    'title'         => __('Minimum Lines','mivhak'),
                    'min'           => 1,
                    'step'          => 1,
                    'default'       => 1,
                    'help'          => __('Code blocks with less lines than specified here will not show a meta header bar.','mivhak')
                )),
                new Components\ToggleButton(array(
                    'name'          => 'auto_assign',
                    'title'         => __('Auto Assign','mivhak'),
                    'labels'        => array('PRE', 'CODE', 'XHR'),
                    'multivalue'    => true,
                    'help'          => __('Choose the HTML elements that you want Mivhak to automatically syntax-highlight.','mivhak')
                )),
                new Amarkal\UI\Components\DropDown(array(
                    'name'          => 'default_lang',
                    'title'         => __('Default Language','mivhak'),
                    'help'          => __('Choose a programming language that will be used by default for code blocks that have no specified programming language.','mivhak'),
                    'default'       => '',
                    'options'       => array( '' => 'None' ) + include('langs.php')
                ))
            )
        )),
        new Options\Section(array(
            'title'         => 'Appearance',
            'icon'          => 'fa-paint-brush',
            'description'   => __('Setup the look and feel of Mivhak','mivhak'),
            'fields'        => array(
                new Components\ToggleButton(array(
                    'name'          => 'css_toggle',
                    'title'         => __('Use Custom CSS','mivhak'),
                    'help'          => __('Toggle on/off to use the custom CSS on the next field. The CSS code will be printed in the document\'s head','mivhak'),
                    'default'       => 'OFF'
                )),
                new Components\CodeEditor(array(
                    'name'          => 'css',
                    'title'         => __('Custom CSS Code','mivhak'),
                    'help'          => __('Insert your custom CSS here. Since this will be printed in the head of the document (as opposed to making an http request), it is not recommended to use this for big CSS changes (hundreds of lines of code).','mivhak'),
                    'language'      => 'css',
                    'default'       => "/**\n * Insert your custom CSS here\n */"
                )),
                new Components\Spinner(array(
                    'name'          => 'font_size',
                    'title'         => __('Font Size','mivhak'),
                    'help'          => __('Set the code editor font size in pixels.','mivhak'),
                    'min'           => 0,
                    'default'       => 12
                )),
                new Components\DropDown(array(
                    'name'          => 'theme',
                    'title'         => __('Theme','mivhak'),
                    'help'          => __('Choose a highlighter theme that best matches your blog\'s theme style.','mivhak'),
                    'default'       => 'default.css',
                    'options'       => include __DIR__.'/themes.php'
                )),
                new Components\Content(array(
                    'title'         => __('Preview','mivhak'),
                    'full_width'    => true,
                    'template'      => Mivhak\PLUGIN_DIR . '/preview.phtml'
                ))
            )
        ))
    )
);