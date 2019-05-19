<?php
/**
 * @package   Mivhak Syntax Highlighter
 * @date      2016-06-21
 * @version   1.3.6
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/mivhak
 * @copyright 2016 Askupa Software
 */

use Amarkal\UI;

return new Amarkal\Extensions\WordPress\Editor\Plugin(array(
    'slug'      => 'mivhak_button',
    'row'       => 1,
    'script'    => Mivhak\JS_URL.'/editor.js',
    'callback'  => new Amarkal\Extensions\WordPress\Editor\FormCallback(array(
        new Amarkal\UI\Components\DropDown(array(
            'name'          => 'lang',
            'title'         => __('Language','mivhak'),
            'help'          => __('Choose the programming language.','mivhak'),
            'default'       => 'javascript',
            'options'       => include('langs.php')
        )),
        new UI\Components\ToggleButton(array(
            'name'          => 'tag',
            'title'         => __('Display Type','mivhak'),
            'help'          => __('Choose between displaying the code inlined with the text, or in its own block.','mivhak'),
            'default'       => 'pre',
            'labels'        => array(
                'code'      => 'Inline',
                'pre'       => 'Block' 
            )
        )),
        new UI\Components\ToggleButton(array(
            'name'          => 'visibility',
            'title'         => __('Initial Visibility','mivhak'),
            'help'          => __('Hidden code blocks will not be visible to the user until he clicks on "show code"','mivhak'),
            'description'   => __('(applicable to code blocks only)','mivhak'),
            'default'       => 'visible',
            'labels'        => array(
                'visible'      => 'Visible',
                'hidden'       => 'Hidden' 
            )
        )),
        new UI\Components\Text(array(
            'name'          => 'caption',
            'title'         => __('Caption Text','mivhak'),
            'help'          => __('Add a description to your code','mivhak'),
            'description'   => __('(applicable to code blocks only)','mivhak')
        )),
        new UI\Components\Text(array(
            'name'          => 'highlight',
            'title'         => __('Highlight Line(s)','mivhak'),
            'help'          => __('Highlight specific code lines or a range of lines','mivhak'),
            'description'   => __('(i.e. 1, 3-5, 7-12)','mivhak')
        )),
        new UI\Components\Spinner(array(
            'name'          => 'start_line',
            'title'         => __('Starting Line','mivhak'),
            'default'       => 1,
            'min'           => 1,
            'help'          => __('Choose the first line number','mivhak'),
        )),
        new UI\Components\CodeEditor(array(
            'name'      => 'code',
            'title'     => 'Code',
            'language'  => 'javascript',
            'default'   => "/**\n * Insert your code here\n */",
            'full'      => true
        ))
    ))
));
