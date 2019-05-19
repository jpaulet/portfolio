=== Mivhak Syntax Highlighter ===
Contributors: Askupa Software, ykadosh
Tags: editor, code editor, highlighter, syntax highlighter, code prettifier, highlighting, syntax, ace editor, programming
Requires at least: 3.0
Tested up to: 4.9
Stable tag: 1.3.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight, editor safe syntax highlighter with real time syntax highlighting and error checking.

== Description ==

Have you been looking for a syntax highlighter that is safe to use on both the visual and the HTML WordPress editor? What
about a tool that highlights your syntax *While* writing your code, and also checks for syntax errors?

Well, look no further. *Mivhak* is a lightweight syntax highlighter for WordPress, based on a slightly modified version of the great *Ace Code Editor*.
Mivhak comes with a simple settings panel that allows the user to setup basic plugin behavior and appearance.

Additionally, code can be easily inserted to both the HTML and the visual editor using a TinyMCE popup the features live syntax highlighting and error checking for 100+ languages.

**Features**

* Lightweight - minified CSS and JS, language scripts and themes are loaded on request
* Supports 130+ different programming languages
* 36 different skins
* Visual + HTML editor code insertion buttons
* Backend code editor with live syntax highlighting and error checking
* Options to add caption text, change starting line, highlight single/multiple lines, and much more!
* Easy-to-use control panel
* Visual editor placeholders with floating control bar (see screenshots)
* Automatic code highlighting for &lt;pre>, &lt;code> and/or &lt;xhr>
* Works with Markdown
* Inline and block code widgets

**Useful Links**

* [Official Page](http://products.askupasoftware.com/mivhak/)
* [Examples](http://products.askupasoftware.com/mivhak/examples/)
* [Documentation](http://products.askupasoftware.com/mivhak/documentation/)

**Available Translations**

* English
* Hebrew
* German
* Danish

== Installation ==

1. Download and activate the plugin.
1. Use the control panel to choose a skin.
1. Specify which tags you would like Mivhak to prettify (CODE, PRE, XHR).

== Frequently Asked Questions ==


== Screenshots ==

1. Code snippet before Mivhak
2. Code snippet after Mivhak
3. General settings section under Mivhak options page
4. Skin selector under Mivhak options page
5. Code can be easily edited or removed in the visual editor
6. The code insertion/edition popup window

== Changelog ==

= 1.3.9 =
* (FIX) Fixed an issue related to the Amarkal framework.

= 1.3.8 =
* (FIX) Fixed an issue that was causing PHP 7.1 to throw a lexical error.

= 1.3.7 =
* (FIX) "mivhak_settings is not defined" error fixed.

= 1.3.6 =
* (FIX) Moved render blocking scripts to footer
* (NEW) Added 5 new languages

= 1.3.5 =
* (NEW) Added a Danish translation (Thanks Henrik Gregersen!)
* (FIX) Made TinyMCE button public facing as well

= 1.3.4 =
* (NEW) Added 2 new translations: Hebrew & German
* (NEW) Added a .pot file for internationalization
* (UPDATE) Changed internal file structure
* (UPDATE) Updated Ace Editor to the latest version (1.2.3)

= 1.3.3 =
* (NEW) Added support for Jetpack Markdown code parsing
* (FIX) Visual Editor nextSibling issue

= 1.3.2 =
* (NEW) The code font size can now be set in Mivhak->Appearance
* (NEW) Users can now highlight single and multiple lines
* (FIX) Safari hidden code issue

= 1.3.1 =
* (NEW) Users can now set the initial visibility of a code block. If hidden, a "show code" button will be visible to control the visibility of the code block
* (FIX) Only showing caption when necessary
* (FIX) Editor popup editor updates to reflect changes when "starting line" is changed

= 1.3.0 =
* (UPDATE) Bumped up ace version to 1.2.2
* (NEW) Option to add caption text
* (NEW) Option to set the starting line
* (NEW) Added new languages: Dockerfile, HTML Elixir, Maze, Praat, SQLServer, Swift, Swig.
* (FIX) Formatting issues when switching between editors
* (FIX) Issue that was breaking embedded media
* (FIX) WordPress Editor floating bar issue
* (FIX) &lt;p&gt; tag encoding issue

= 1.2.6 =
* (FIX) Move the cursor out of the inserted element after inserting
* (FIX) Issue that was breaking the editing functionality in the visual editor
* Tested under WordPress 4.2.3

= 1.2.5 =
* (UPDATE) Bumped up ace version to 1.1.9
* (NEW) Added 12 new languages: ABC, AppleScript, Eiffel, Elixir, Elm, G-Code, gitignore, io, Lean, Mask, MIPS Assembler and Vala.
* (FIX) Removed 2 unsupported languages: HTML Completions, MUSHCode High Rules.
* (FIX) Inline code element issue.

= 1.2.4 =
* (UPDATE) Amarkal Framework v0.3.6
* (NEW) Added &lt;pre&gt; quicktag to the HTML editor
* (FIX) Improved the way HTML formatting is preserved when switching between editors
* Tested under WordPress 4.2.1

= 1.2.3 =
* (FIX) Fixed some notices that were showing when WP_DEBUG was set to true
* (FIX) Uninstalling the plugin now removes any traces from the database
* (NEW) Add an option the write custom CSS (under Mivhak->appearance)

= 1.2.2 =
* (FIX) PHP Strict Standards issue

= 1.2.1 =
* (NEW) Selected text is used as input for the popup code editor
* (NEW) Option to select a default language when no language has been detected
* (NEW) Programming languages pretty names (As opposed to all uppercase names as it has been until now)
* (FIX) Extra lines/spaces will be trimmed
* (FIX) Visual Editor issue that was preventing code blocks without the class attribute from being edited
* (FIX) Non-breaking space issue that was treated as an invalid character
* (FIX) A bug that was causing line breaks to be added when switching between the visual and the HTML editors
* (FIX) CSS issues

= 1.2.0 =
* (UPDATE) Amarkal Framework v0.3.4
* (UPDATE) Completed migration to Ace Editor
* (UPDATE) Slightly modified visual appearance

= 1.1.1 =
* (UPDATE) Amarkal Framework v0.3.3
* (FIX) Fixed an issue that was causing line breaks to be removed when switching between visual and HTML editor.

= 1.1.0 =
* (NEW) Added support for bbPress
* (NEW) Visual + HTML editor buttons with code writing tools
* (NEW) Static/dynamic highlighting is now implemented using the great Ace Code Editor
* (NEW) Visual editor placeholders with floating control bar
* (UPDATE) Improved CSS

= 1.0.7 =
* (FIX) Minor CSS fixes (tested on multiple themes)
* (UPDATE) Amarkal Framework

= 1.0.6 =
* (FIX) Visibility issue in FireFox (thanks zeaks!)
* (FIX) Issue with code snippets in comments
* (UPDATE) Code blocks are now print friendly
* (UPDATE) Brand new admin page - see screenshots

= 1.0.5 =
* (UPDATE) Amarkal framework update

= 1.0.4 =
* (FIX) Prevent line numbers from being copied

= 1.0.3 =
* (FIX) inline code segments issue
* (FIX) no line-numbers issue
* (UPDATE) Improved CSS styling
* (NEW) Choose whether to show/hide meta header by line count

= 1.0.2 =
* (FIX) script tag encoding issue
* (UPDATE) Amarkal framework
* (NEW) Meta header shows language name

= 1.0.1 =
* (FIX) HTML encoding issue

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.2.3 =
* New features and minor bug fixes

= 1.2.2 =
* Improved code formatting

= 1.2.1 =
* New features and minor bug fixes

= 1.2.0 =
* Completed migration to Ace Editor

= 1.1.1 =
* Updated framework, minor bug fixes

= 1.1.0 =
* Migrated to Ace Editor, multiple new features

= 1.0.7 =
* Minor CSS fixes

= 1.0.6 =
* Visibility improvements

= 1.0.5 =
* Amarkal framework update

= 1.0.4 =
* Prevent line numbers from being copied

= 1.0.3 =
* Fixed line numbers issue

= 1.0.2 =
* Fixed script tag encoding issue

= 1.0.1 =
* Fixed HTML encoding issue
