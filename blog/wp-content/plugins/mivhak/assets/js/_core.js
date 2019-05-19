(function($){
    
    // Extend jQuery functionality
    $.fn.extend({
        mivhak: function (settings) {
            return this.each(function () {
                new Mivhak( this, settings );
            });
        }
    });
    
    /**
     * Highlight code using the Ace Editor for all code elements
     * in the root node.
     * 
     * @see Mivhak.prototype.defaults
     * @param {Node} root
     * @param {Object} settings
     */
    function Mivhak( root, settings )
    {
        var self = this;
        this.config = $.extend({}, this.defaults(), settings );
        
        // Assign the class 'prettyprint' to all code segments
        if(this.config.auto_assign !== "")
        {
            $(root).find(this.config.auto_assign).each(function(){
                // Ignore <code> inside <pre> and set the language
                if($(this).parent().is('pre'))
                {
                    if(this.hasAttribute("class"))
                        $(this).parent().addClass('lang-'+$(this).attr('class'));
                    return;
                }
                $(this).addClass('prettyprint');
            });
        }
        
        $('.prettyprint').each(function(){
            self.prettify(this);
        });
    }
    
    /**
     * Do syntax highlighting for the given element
     * 
     * @param {Node} el
     */
    Mivhak.prototype.prettify = function( el )
    {
        if( 'CODE' === el.tagName ) return;
        
        var config      = this.config,
            lang        = this.getLang(el),
            start_line  = parseInt( el.getAttribute('data-start-line') ) || 1,
            visibility  = el.getAttribute('data-visibility'),
            highlight   = el.getAttribute('data-highlight'),
            caption     = el.getAttribute('data-caption'),
            wrapper     = $('<div>').addClass('mivhak-code-wrapper'),
            editor      = ace.edit(el);

        // Hide line numbers for inline code elements
        if( !config.line_numbers )
        {
            editor.renderer.setShowGutter(false);
            // Prevent scrolling
            editor.getSession().on('changeScrollLeft', function(){
                editor.getSession().setScrollLeft(0);
            });
        }

        wrapper = $(el).wrap(wrapper).parent();
        if( 'hidden' === visibility )
        {
            var btn = $('<div>').addClass('mivhak-visibility-toggle').text(this.config.i18n[5]).click(function(){
                var height = $(el).outerHeight() + 30; // 30 for the top bar
                wrapper.toggleClass('mivhak-hidden').css({height:height+'px'});
            });
            wrapper.append(btn).addClass('mivhak-hidden');
        }
        
        // Build header
        if( config.show_meta && editor.session.getLength() >= config.min_lines )
        {
            $(el).before(this.buildHeader(el,editor,lang));
        }
        
        // Build captions
        if( null !== caption && '' !== caption )
        {
            $(el).after( $('<div></div>').addClass('caption-text').text(caption));
        }
        
        // Highligh lines
        if( null !== highlight && '' !== highlight )
        {
            this.highlightLines( editor, highlight );
        }

        ace.config.set('basePath', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/');
        editor.setReadOnly(true);
        editor.setTheme("ace/theme/"+config.theme);
        editor.getSession().setMode("ace/mode/"+lang);
        editor.getSession().setUseWorker(false); // Disable syntax checking
        
        editor.setOptions({
            maxLines: Infinity,
            firstLineNumber: start_line,
            highlightActiveLine: false,
            fontSize: parseInt(config.font_size)
        });
    };
    
    /**
     * Default settings for Mivhak
     * @returns {Object}
     */
    Mivhak.prototype.defaults = function ()
    {
        return {
            auto_assign : false,    // comma delimited list of tag names ('tag1,tag2')
            show_meta   : false,
            line_numbers: false,
            min_lines   : 2,
            font_size   : 12,
            theme       : 'twilight',
            default_lang: 'text',
            lang_list   : {}
        };
    }
    
    /**
     * List of button settings for the header bar
     * 
     * @returns {Array}
     */
    Mivhak.prototype.buttons = function()
    {
        return [
            {
                description : this.config.i18n[2],
                class       : 'mivhak-icon copy-icon',
                func        : function( editor, $button ){
                    
                    if($button.hasClass('active'))
                    {
                        editor.selection.clearSelection();
                    }
                    else
                    {
                        editor.selection.selectAll();
                        editor.focus();
                    }
                    $button.toggleClass('active');
                }
            },
            {
                description : this.config.i18n[3],
                class       : 'mivhak-icon expand-icon',
                func        : function( editor ){
                    var win = window.open('','','width=500,height=330,resizable=1');
                    editor.selection.selectAll();
                    var output = editor.session.getTextRange(editor.getSelectionRange());
                    win.document.write('<pre>'+$('<div/>').text(output).html()+'</pre>');
                    editor.selection.clearSelection();
                }
            },
            {
                description : this.config.i18n[4],
                class       : 'mivhak-icon wrap-icon',
                func        : function( editor, $button ){
                    if($button.hasClass('active'))
                    {
                        editor.getSession().setUseWrapMode(false);
                    }
                    else
                    {
                        editor.getSession().setUseWrapMode(true);
                    }
                    $button.toggleClass('active');
                }
            },
            {
                description : this.config.i18n[6],
                class       : 'mivhak-icon info-icon',
                func        : function( editor, $button ){
                    var $info = $('<div>',{class: 'mivhak-info'}).html('<a href="https://wordpress.org/plugins/mivhak/">Mivhak Syntax Highlighter</a> v'+mivhak_settings.version);
                    if($button.hasClass('active'))
                    {
                        $button.find('.mivhak-info').remove();
                    }
                    else
                    {
                        $button.append($info);
                    }
                    $button.toggleClass('active');
                }
            }
        ];
    };
    
    /**
     * Generate the meta header for the code snippet
     * 
     * @param {Node} wrapper
     * @returns {Node}
     */
    Mivhak.prototype.buildHeader = function( wrapper, editor )
    {
        var $header  = $('<div>',{class:'meta'}),
            $text    = $('<div>',{class:'text'}),
            $control = $('<div>',{class:'control'}),
            $lang    = $('<div>',{class:'lang'}),
            $info    = $('<div>',{class:'info'}),
            lang     = this.getLang(wrapper);
        
        if( null !== lang )
        {
            $lang.html(this.config.lang_list[lang]);
            $header.append($lang);
        }
        $control.append(this.generateButtons(editor));
        $text.html(editor.session.getLength() + " " + this.config.i18n[1]);
        $info.html('<h3>Mivhak Syntax Highlighter</h3>');
        $header.append($text, $control);
        
        return $header;
    };
    
    /**
     * Generate buttons for the header bar.
     * 
     * @param {type} editor
     * @returns {Array} The generated buttons
     */
    Mivhak.prototype.generateButtons = function( editor )
    {
        var buttons = [];
        $.each( this.buttons(), function(){
            var $button = $('<div>',{title: this.description});
            var $icon = $('<i>',{class: this.class});
            var func = this.func;
            
            $button.append($icon);
            $button.click(function(){
                func(editor, $button);
            });
            buttons.push($button);
        });
        return buttons;
    };
    
    /**
     * Get the programming language specified for this current code segment.
     * 
     * Languages can be specified via css classes e.g. 'lang-html'
     * 
     * @param {type} o
     * @returns {undefined}
     */
    Mivhak.prototype.getLang = function( el ) 
    {
        var langClasses = el.className.match(/lang-([^ ]+)/g);
        
        if( langClasses === null )
        {
            if( '' !== this.config.default_lang )
            {
                return this.config.default_lang;
            }
            return null;
        }
        return langClasses[0].split('-')[1];
    };
    
    /**
     * Highlight lines in the given range
     * 
     * @param {type} editor
     * @param {string} range
     */
    Mivhak.prototype.highlightLines = function( editor, range )
    {
        var AceRange = ace.require("ace/range").Range;
        range = range.replace(' ', '');
    
        // Split all ranges
        $(range.split(',')).each(function(){
 
            var start,
                end;
            
            // Multiple lines highlight
            if( this.indexOf('-') > -1 )
            {
                start = parseInt(this.split('-')[0])-1;
                end = parseInt(this.split('-')[1])-1;
            }
            
            // Single line highlight
            else
            {
                start = parseInt(this)-1;
                end = start;
            }
            
            editor.session.addMarker(
                new AceRange(start, 0, end, 1), // Define the range of the marker
                "ace_active-line",     // Set the CSS class for the marker
                "fullLine"             // Marker type
            );
        });
    };
    
    // Run!
    $(document).mivhak(typeof mivhak_settings === 'undefined' ? {} : mivhak_settings);
}(window.jQuery));