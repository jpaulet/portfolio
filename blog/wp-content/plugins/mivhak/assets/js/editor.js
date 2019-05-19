(function($) {
    
    var config = {
        slug:            'mivhak_button',
        title:           'Insert code',
        width:           800,
        height:          450,
        template:        '<<% tag %> data-start-line="<% start_line %>" data-visibility="<% visibility %>" data-highlight="<% highlight %>" data-caption="<% caption %>" class="prettyprint lang-<% lang %>"><% code %></<% tag %>>',
        template_no_lang:'<<% tag %> data-start-line="<% start_line %>" data-visibility="<% visibility %>" data-highlight="<% highlight %>" data-caption="<% caption %>" class="prettyprint"><% code %></<% tag %>>',
        icon:            'fa fa-code',
        text:            null,
        selector:        ['code', 'pre']
    };
        
    // Add the button to the editor
    tinymce.PluginManager.add( config.slug, function( editor, url ) {
        
        editor.addButton( config.slug, { 
            text: config.text, 
            icon: config.icon, 
            title: config.title,
            stateSelector: config.selector,
            onclick: function() {
                var values = {},
                    selection = editor.selection.getContent();
                
                if( "" !== selection )
                {
                    values.code = selection;
                }
                
                
                // Open a new ajax popup form window
                Amarkal.Editor.Form.open( editor, {
                    title: config.title,
                    url: ajaxurl + '?action=' + config.slug,
                    width: config.width,
                    height: config.height,
                    template: config.template,
                    on_init: on_init,
                    on_insert: on_insert,
                    values: values
                });
            }
        });
        
        // HTML to visual editor
        editor.on('BeforeSetcontent', function(event)
        {
            event.content = visualEditorFormat( event.content );
        });

        // Visual to HTML editor
        editor.on('GetContent', function(event)
        {
            event.content = htmlEditorFormat( event.content );
        });

        /**
         * Fix the format of code blocks in the visual editor.
         * 
         * @param {type} content
         */
        function visualEditorFormat( content ) 
        {
            content = innerHTMLRegex( content, config.selector, function(c) {
                // Account for <code> inside <pre> elements (the structure used by Markdown for code highlighting)
                if(c.match(/^[ \n]*<code/))
                {
                    return innerHTMLRegex( c, 'code', function(c) {return entityEncode( c );});
                }
                return entityEncode( c );
            });

            return content;
        }

        /**
         * Return the original format of code blocks in the html editor.
         * 
         * @param {type} content
         */
        function htmlEditorFormat( content ) 
        {
            return content;
        }
        
        /**
         * Encode HTML entities for the given text (Visual Editor).
         * 
         * @param {type} content
         */
        function entityEncode( content )
        {
            return Amarkal.Utility.arrayReplace([
                [/</g,"&lt;"],
                [/>/g,"&gt;"],
                [/"/g,"&quot;"],
            ], content);
        }
        
        /**
         * Decode HTML entities for the given text (HTML Editor).
         * 
         * @param {type} content
         */
        function entityDecode( content )
        {
            return Amarkal.Utility.arrayReplace([
                [/(&lt;)/g, "<"],
                [/(&gt;)/g, ">"],
                [/(&quot;)/g, "\""],
                [/(&amp;)/g, "&"]
            ], content);
        }
        
        /**
         * Replace the inner content of an element whose tags are
         * given.
         * 
         * @param {string} content The string 
         * @param {array} query HTML DOM query
         * @param {function} replacer a function to run on the inner HTML of the element
         * @returns string
         */
        function innerHTMLRegex( content, tags, replacer )
        {
            for( var i = 0; i < tags.length; i++ )
            {
                var tag = tags[i];
                var regex = new RegExp("<"+tag+"([^>]*)>([\\S\\s]*?)<\\/"+tag+">","g");
                content = content.replace( regex, function( match, atts, cont ) {
                    return '<'+tag+atts+'>'+replacer( cont )+'</'+tag+'>';
                });
            }
            return content;
        }
        
        
        /**
         * Float bar setup
         */
        var toolbar,
            activeToolbar,
            timeout,
            currentSelection,
            fbConfig = {
                buttons: [
                    {
                        slug: 'mivhak_edit',
                        type: 'button',
                        icon: 'dashicons dashicons-edit',
                        onclick: function() { editCode( editor.selection.getNode() ) },
                        tooltip: 'Edit'
                    },
                    {
                        slug: 'mivhak_remove',
                        type: 'button',
                        icon: 'dashicons dashicons-no',
                        onclick: function() { removeCode( editor.selection.getNode() ) },
                        tooltip: 'Remove'
                    }
                ],
                selector: 'pre, code'
            };
        
        editor.on('init', function(e) {
            toolbar = tinymce.ui.Factory.create( {
                type: 'panel',
                layout: 'stack',
                classes: 'toolbar-grp inline-toolbar-grp',
                ariaRoot: true,
                ariaRemember: true,
                items: [
                    {
                        type: 'toolbar',
                        layout: 'flow',
                        items: fbConfig.buttons
                    }
                ]
            });
            
            toolbar.reposition = function(e) {
                var el = this.$el,
                    scrollX = window.pageXOffset || document.documentElement.scrollLeft,
                    scrollY = window.pageYOffset || document.documentElement.scrollTop,
                    windowWidth = window.innerWidth,
                    windowHeight = window.innerHeight,
                    wpAdminbar = document.getElementById( 'wpadminbar' ),
                    mceIframe = document.getElementById( editor.id + '_ifr' ),
                    mceToolbar = tinymce.$( '.mce-toolbar-grp', editor.getContainer() )[0],
                    mceStatusbar = tinymce.$( '.mce-statusbar', editor.getContainer() )[0],
                    wpStatusbar = document.getElementById( 'post-status-info' ),
                    iframeRect = mceIframe ? mceIframe.getBoundingClientRect() : {
                            top: 0,
                            right: windowWidth,
                            bottom: windowHeight,
                            left: 0,
                            width: windowWidth,
                            height: windowHeight
                    },
                    toolbar = this.getEl(),
                    toolbarWidth = toolbar.offsetWidth,
                    toolbarHeight = toolbar.offsetHeight,
                    selection = currentSelection.getBoundingClientRect(),
                    selectionMiddle = ( selection.left + selection.right ) / 2,
                    buffer = 5,
                    margin = 8,
                    spaceNeeded = toolbarHeight + margin + buffer,
                    wpAdminbarBottom = wpAdminbar ? wpAdminbar.getBoundingClientRect().bottom : 0,
                    mceToolbarBottom = mceToolbar ? mceToolbar.getBoundingClientRect().bottom : 0,
                    mceStatusbarTop = mceStatusbar ? windowHeight - mceStatusbar.getBoundingClientRect().top : 0,
                    wpStatusbarTop = wpStatusbar ? windowHeight - wpStatusbar.getBoundingClientRect().top : 0,
                    blockedTop = Math.max( 0, wpAdminbarBottom, mceToolbarBottom, iframeRect.top ),
                    blockedBottom = Math.max( 0, mceStatusbarTop, wpStatusbarTop, windowHeight - iframeRect.bottom ),
                    spaceTop = selection.top + iframeRect.top - blockedTop,
                    spaceBottom = windowHeight - iframeRect.top - selection.bottom - blockedBottom,
                    editorHeight = windowHeight - blockedTop - blockedBottom,
                    className = '',
                    top, left;

                if ( spaceTop >= editorHeight || spaceBottom >= editorHeight ) {
                    return this.hide();
                }

                if ( this.bottom ) {
                    if ( spaceBottom >= spaceNeeded ) {
                        className = ' mce-arrow-up';
                        top = selection.bottom + iframeRect.top + scrollY;
                    } else if ( spaceTop >= spaceNeeded ) {
                        className = ' mce-arrow-down';
                        top = selection.top + iframeRect.top + scrollY - toolbarHeight - margin;
                    }
                } else {
                    if ( spaceTop >= spaceNeeded ) {
                        className = ' mce-arrow-down';
                        top = selection.top + iframeRect.top + scrollY - toolbarHeight - margin;
                    } else if ( spaceBottom >= spaceNeeded && editorHeight / 2 > selection.bottom + iframeRect.top - blockedTop ) {
                        className = ' mce-arrow-up';
                        top = selection.bottom + iframeRect.top + scrollY;
                    }
                }

                if ( typeof top === 'undefined' ) {
                    top = scrollY + blockedTop + buffer;
                }

                left = selectionMiddle - toolbarWidth / 2 + iframeRect.left + scrollX;

                if ( selection.left < 0 || selection.right > iframeRect.width ) {
                    left = iframeRect.left + scrollX + ( iframeRect.width - toolbarWidth ) / 2;
                } else if ( toolbarWidth >= windowWidth ) {
                    className += ' mce-arrow-full';
                    left = 0;
                } else if ( ( left < 0 && selection.left + toolbarWidth > windowWidth ) || ( left + toolbarWidth > windowWidth && selection.right - toolbarWidth < 0 ) ) {
                    left = ( windowWidth - toolbarWidth ) / 2;
                } else if ( left < iframeRect.left + scrollX ) {
                    className += ' mce-arrow-left';
                    left = selection.left + iframeRect.left + scrollX;
                } else if ( left + toolbarWidth > iframeRect.width + iframeRect.left + scrollX ) {
                    className += ' mce-arrow-right';
                    left = selection.right - toolbarWidth + iframeRect.left + scrollX;
                }

                el[0].className = el[0].className.replace( / ?mce-arrow-[\w]+/g, '' ) + className;

                el.css({'top':top,'left':left});

                return this;
            };

            toolbar.on( 'show', function(e) {
                this.reposition(e);
            });

            toolbar.on( 'keydown', function( event ) {
                if ( event.keyCode === 27 ) {
                    this.hide();
                    editor.focus();
                }
            });

            editor.on( 'remove', function() {
                toolbar.remove();
            } );

            toolbar.hide().renderTo( document.body );
        });
        
        editor.on( 'nodechange', function( event ) {
            if( false === event.selectionChange ) return;
            
            var collapsed = editor.selection.isCollapsed(),
                args = {
                    element: event.element,
                    parents: event.parents,
                    collapsed: collapsed
                };

            currentSelection = args.selection || args.element;

            if ( activeToolbar ) {
                activeToolbar.hide();
            }

            if ( $(event.element).is(fbConfig.selector) ) {
                activeToolbar = toolbar;
                activeToolbar.show();
            } else {
                activeToolbar = false;
            }
        });

        editor.on( 'focus', function() {
            if ( activeToolbar ) {
                activeToolbar.show();
            }
        });

        function hide( event ) {
            if ( activeToolbar ) {
                activeToolbar.hide();

                if ( event.type === 'hide' ) {
                    activeToolbar = false;
                } else if ( event.type === 'resize' || event.type === 'scroll' ) {
                    clearTimeout( timeout );

                    timeout = setTimeout( function() {
                        if ( activeToolbar && typeof activeToolbar.show === 'function' ) {
                            activeToolbar.show();
                        }
                    }, 250 );
                }
            }
        }

        $(window).on( 'resize scroll', hide );
        $(editor.getWin()).on( 'resize scroll', hide );

        editor.on( 'remove', function() {
            $(window).off( 'resize scroll', hide );
            $(editor.getWin()).off( 'resize scroll', hide );
        } );

        editor.on( 'blur hide', hide );
        
        /**
         * Edit a code block by a given node.
         * 
         * @param {type} node The code block's HTML node
         */
        function editCode( node )
        {
            var code = entityDecode( node.innerHTML.replace(/(<br ?\/?>)/g, "\n") );
            var lang = '';
            
            if( node.attributes.hasOwnProperty('class') )
            {
                var matches = node.attributes['class'].value.match(/lang-([^ ]+)/);
                if( null !== matches )
                {
                    lang = matches[1];
                }
            }
            
            // Open a new ajax popup form window
            Amarkal.Editor.Form.open( editor, {
                title: config.title,
                url: ajaxurl + '?action=' + config.slug,
                width: config.width,
                height: config.height,
                template: config.template,
                on_init: on_init,
                on_insert: on_insert,
                values: {
                    lang: lang,
                    tag: node.nodeName.toLowerCase(),
                    caption: node.getAttribute('data-caption') || '',
                    start_line: node.getAttribute('data-start-line') || 1,
                    visibility: node.getAttribute('data-visibility') || 'visible',
                    highlight: node.getAttribute('data-highlight') || '',
                    code: code
                }
            });
            return;
        }
        
        /**
         * Remove a code block from the tinyMCE editor.
         * 
         * @param {type} node
         */
        function removeCode( node ) 
        {
            if ( node !== null ) {
                if ( node.nextSibling ) {
                    editor.selection.select( node.nextSibling );
                    editor.selection.collapse( true ); // Go to start of selection
                } else if ( node.previousSibling ) {
                    editor.selection.select( node.previousSibling );
                    editor.selection.collapse( false ); // Go to end of selection
                } else {
                    editor.selection.select( node.parentNode );
                }
            }
            
            editor.dom.remove( node );
            editor.nodeChanged();
            editor.undoManager.add();
        }
        
        /**
         * Called once the popup has been loaded.
         * 
         * @param {type} window
         */
        function on_init( window )
        {
            var editorEl = $(window.document).find('[data-name="code"]')[0],
                dropdown = $(window.document).find('[data-name="lang"]')[0],
                editor = window.Amarkal.UI.getComponent( editorEl ),
                dropdownui = window.Amarkal.UI.getComponent( dropdown ).getInput( dropdown );

            // Change the language initially and on dropdown value change
            editor.setMode( editorEl, dropdownui.select2('val'));
            dropdownui.select2({width:'resolve'}).on("change", function(e) {
                editor.setMode( editorEl, e.val);
            });

            $(window.document).find('.ui-spinner-button').click(function(){
                var line = parseInt($(window.document).find('[data-name="start_line"]').attr('data-value'));
                editor.getEditor($(window.document).find('.afw-ui-ace-editor')[0]).setOption('firstLineNumber', line);
            });
            
        }
        
        /**
         * Called once the "insert" button (in the popup window) is clicked.
         * This is used by both the 'edit' and the 'insert' buttons in the visual editor.
         * 
         * @param {type} editor
         * @param {type} values
         */
        function on_insert( editor, values ) {
            var args = editor.windowManager.getParams(),
                node = editor.selection.getNode(),
                isLast = node.nextSibling === null,
                template = args.template;
            
            // No language has been selected, use the appropriate template
            if( "" === values.lang )
            {
                template = config.template_no_lang
            }

            // Remove the selected node if the node is one of [pre|code|xhr]
            // Note that getNode() returns the common ancestor of the selection, 
            // which might be the entire document, therefore this test is neccessary.
            if( ["PRE","CODE","XHR"].indexOf(node.nodeName) >= 0 ) 
            {
                removeCode( node );
                //editor.dom.remove( node, false );
            }
            else
            {
                editor.dom.remove( editor.selection.getContent(), false );
            }
            
            // Insert the code element
            editor.insertContent( Amarkal.Editor.Form.parseTemplate( template, values ) );
            
            // Move the cursor out of the inserted element after inserting
            if( 'code' === values.tag && isLast ) { editor.insertContent( '&nbsp;' ); }
            if( 'pre' === values.tag && isLast ) { editor.insertContent( '<p></p>' ); }
        }
    });
})(jQuery);
