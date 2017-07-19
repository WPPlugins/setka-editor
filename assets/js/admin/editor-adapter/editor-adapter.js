(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

module.exports = Backbone.Collection.extend({

});

},{}],2:[function(require,module,exports){
arguments[4][1][0].apply(exports,arguments)
},{"dup":1}],3:[function(require,module,exports){
/* global jQuery, tinyMCE, Backbone */

var setkaEditorAdapter = {};

// Store everything globally
window.setkaEditorAdapter = setkaEditorAdapter;

// Models
setkaEditorAdapter.model = {

    Form: require('./model/Form'),
    EditorConfig: require('./model/EditorConfig'),
    EditorResources: require('./model/EditorResources'),
    Theme: require('./model/Theme'),
    Layout: require('./model/Layout')
};

// Collections
setkaEditorAdapter.collection = {
    Themes: require('./collection/Themes'),
    Layouts: require('./collection/Layouts')
};

// Views
setkaEditorAdapter.view = {

    scripts: {
        TypeKitScript: require('./view/scripts/TypeKitScript')
    },

    // Main view ever
    Page: require('./view/Page'),

    HTML: require('./view/HTML'),

    screenMeta: {
        EditorExpand: require('./view/screen-meta/EditorExpand')
    },

    notices: {
        Prototype: require('./view/notices/Prototype'),

        SetkaEditorCantFindResources: require('./view/notices/SetkaEditorCantFindResources'),
        SetkaEditorThemeDisabled: require('./view/notices/SetkaEditorThemeDisabled'),
        SetkaEditorSaveSnippet: require('./view/notices/SetkaEditorSaveSnippet')
    },

    // Add Media button
    AddMediaButton: require('./view/AddMediaButton'),

    // Tabs
    EditorTabs: require('./view/editor-tabs/EditorTabs'),
    EditorTabDefault: require('./view/editor-tabs/TabDefault'),
    EditorTabSetka: require('./view/editor-tabs/TabSetka'),

    // Editors
    Editor: require('./view/EditorSetka'),
    EditorDefault: require('./view/EditorDefault'),

    postStuff: {
        PostStuff: require('./view/poststuff/PostStuff'),

        // Post Body
        postBody: {
            PostBody: require('./view/poststuff/post-body/PostBody')
        }
    },

    // Admin Menu
    AdminMenu: require('./view/AdminMenu'),

    // Form
    Form: require('./view/form/Form'),
    Settings: require('./view/form/Settings'),
    Nonce: require('./view/form/Nonce'),
    PostId: require('./view/form/PostId'),

    pointers: {
        DisabledTabsPointer: require('./view/pointers/DisabledTabsPointer')
    }
};

// Utils
setkaEditorAdapter.utils = {
    editorExpand: {
        EditorExpand: require('./utils/editor-expand/EditorExpand')
    },
    AutoSave: require('./utils/AutoSave'),
    autop: require('./utils/autop'),

    SaveSnippet: require('./utils/SaveSnippet'),
    TypeKitScriptsManager: require('./utils/TypeKitScriptsManager')
};

},{"./collection/Layouts":1,"./collection/Themes":2,"./model/EditorConfig":4,"./model/EditorResources":5,"./model/Form":6,"./model/Layout":7,"./model/Theme":8,"./utils/AutoSave":9,"./utils/SaveSnippet":10,"./utils/TypeKitScriptsManager":11,"./utils/autop":12,"./utils/editor-expand/EditorExpand":13,"./view/AddMediaButton":14,"./view/AdminMenu":15,"./view/EditorDefault":16,"./view/EditorSetka":17,"./view/HTML":18,"./view/Page":19,"./view/editor-tabs/EditorTabs":20,"./view/editor-tabs/TabDefault":21,"./view/editor-tabs/TabSetka":22,"./view/form/Form":23,"./view/form/Nonce":24,"./view/form/PostId":25,"./view/form/Settings":26,"./view/notices/Prototype":27,"./view/notices/SetkaEditorCantFindResources":28,"./view/notices/SetkaEditorSaveSnippet":29,"./view/notices/SetkaEditorThemeDisabled":30,"./view/pointers/DisabledTabsPointer":31,"./view/poststuff/PostStuff":32,"./view/poststuff/post-body/PostBody":33,"./view/screen-meta/EditorExpand":34,"./view/scripts/TypeKitScript":35}],4:[function(require,module,exports){
module.exports = Backbone.Model.extend({

});

},{}],5:[function(require,module,exports){
var
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.Model.extend({

    initialize: function() {
        this._setupThemes();
        this._setupLayouts();
    },

    _setupThemes: function() {
        var themes = new adapter.collection.Themes();

        _.each(this.get('themes'), function(element, index, list) {
            themes.add(new adapter.model.Theme(element));
        });

        this.set('themes', themes);
    },

    _setupLayouts: function() {
        var layouts = new adapter.collection.Layouts();

        _.each(this.get('layouts'), function(element, index, list) {
            layouts.add(new adapter.model.Layout(element));
        });

        this.set('layouts', layouts);
    },

    getThemeBySlug: function(slug) {
        return this.get('themes').findWhere({
            id: slug
        });
    },

    getThemeById: function(id) {
        return this.get('themes').findWhere({
            theme_id: id
        });
    },

    getLayoutBySlug: function(slug) {
        return this.get('layouts').findWhere({
            id: slug
        });
    },

    toJSON: function(options) {
        var result = _.clone(this.attributes);

        // themes to array
        result.themes = result.themes.toJSON();

        // layouts to array
        result.layouts = result.layouts.toJSON();

        return result;
    }
});

},{}],6:[function(require,module,exports){

module.exports = Backbone.Model.extend({

    defaults: {
        postId: null,
        textareaId: null,
        editorInitialized: false,
        editorConfig: {},
        editorContent: '',
        useSetkaEditor: false,
        postBoxesColumns: null,

        postBoxContainer1Hovered: false
    },

    initialize: function() {

        _.bindAll(this, 'onEditorInitializedChanged');

        // Parse useSetkaEditor
        var useSetkaEditor = this.get('useSetkaEditor');
        if(!_.isBoolean(useSetkaEditor)) {
            if( useSetkaEditor === '1' ) {
                this.set('useSetkaEditor', true);
            }
            else {
                this.set('useSetkaEditor', false);
            }
        }

        this.addEvents();
    },

    addEvents: function() {
        this.on('change:editorInitialized', this.onEditorInitializedChanged);
    },

    onEditorInitializedChanged: function() {
        this.set('useSetkaEditor', this.get('editorInitialized'));
    },

    getDataForAutosave: function() {
        return {
            useSetkaEditor: this.get('useSetkaEditor') ? '1' : '0',
            editorConfig: this.get('editorConfig').toJSON(),
            _wpnonce: this.get('nonce'),
            postId: this.get('postId')
        };
    }

});

},{}],7:[function(require,module,exports){

module.exports = Backbone.Model.extend({

});

},{}],8:[function(require,module,exports){

module.exports = Backbone.Model.extend({

    isDisabled: function() {
        var disabled = this.get('disabled');

        if(!_.isUndefined(this.get('disabled')) && disabled === true)
            return true;

        return false;
    },

    getTypeKidId: function() {
        var typeKit = this.get('kit_id');

        if(_.isUndefined(typeKit)) {
            typeKit = '';
        }

        return typeKit;
    }

});

},{}],9:[function(require,module,exports){
var
    $ = jQuery,
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    DOM: {},

    initialize: function() {
        _.bindAll(this, 'save', 'onHeartBeatSend', 'onHeartBeatTick');
        this.setupDOM();
        this.addEvents();
    },

    setupDOM: function() {
        this.DOM.document = $(document);
    },

    addEvents: function() {
        this.DOM.document
            .on('heartbeat-send.' + translations.names.css, this.onHeartBeatSend);

        this.DOM.document
            .on('heartbeat-tick.' + translations.names.css, this.onHeartBeatTick);

        // Start saving
        this.enable();
    },

    enable: function() {
        // WordPress autosave with Heartbeat API
        this.model.get('editorConfig').on('change', this.save);
        this.model.on('change:useSetkaEditor',      this.save);
        this.model.on('change:postId',              this.save);
    },

    disable: function() {
        this.model.get('editorConfig').off('change', this.save);
        this.model.off('change:useSetkaEditor',      this.save);
        this.model.off('change:postId',              this.save);
    },

    save: function() {
        // Nonce may outdated here. We need update it just before sending request
        // and we update it on heartbeat-send event.
        window.wp.heartbeat.enqueue(
            translations.names.css,
            this.model.getDataForAutosave(),
            false
        );
    },

    /**
     * Before Heartbeat API sends data to server.
     *
     * @param event
     * @param data
     */
    onHeartBeatSend: function(event, data) {
        // Add our plugin data if WP doing auto save or some settings in editor changed
        // (our own data already added to query and we need update it)
        if(
            // We replace all data for 100% actual data in request (nonce may outdated)
            !_.isUndefined(data[translations.names.css])
            ||
            // if WP doing auto save also include our data
            !_.isUndefined(data['wp_autosave'])
        ) {
            data[translations.names.css] = this.model.getDataForAutosave();
        }
    },

    /**
     * Heartbeat get a response from server.
     *
     * @param event
     * @param data
     */
    onHeartBeatTick: function(event, data) {
        // Do nothing at now
    }

});

},{}],10:[function(require,module,exports){
var
    $ = jQuery,
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    initialize: function() {
        _.bindAll(this, 'addSnippet');
        this.addEvents();
    },

    addEvents: function() {
        ContentEdit.Root.get().bind('ADD_SNIPPET', this.addSnippet);
    },

    addSnippet: function(snippet) {
        var theme =
            this.model.get('editorResources')
                .getThemeBySlug(snippet.themeId);

        if(!_.isEmpty(theme)) {
            snippet.theme_id = theme.get('theme_id');
            this.saveSnippet(snippet);
        }
    },

    saveSnippet: function(snippet) {
        // start saving snippet
        Backbone.trigger('setka:editor:adapter:editors:setka:snippet:save:start', snippet);
        var xhr = $.ajax({
                url: ajaxurl,
                type: 'post',
                timeout: 30000, // throw an error if not completed after 30 sec.
                data: {
                    action: translations.names._ + '_save_setka_editor_snippet',
                    data: JSON.stringify(snippet)
                },
                // We awaiting JSON response from WordPress
                dataType: 'json',
                jsonp: false
            })
            .done(function(response) {
                Backbone.trigger('setka:editor:adapter:editors:setka:snippet:done', snippet, response);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                Backbone.trigger('setka:editor:adapter:editors:setka:snippet:fail', snippet, jqXHR, textStatus, errorThrown);
            });
    }

});

},{}],11:[function(require,module,exports){
var
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.View.extend({

    // we store here <script> tags with Type Kits
    views: {},

    initialize: function() {
        _.bindAll(this, 'onTypeKitIdChanged');
        this.addEvents();
        this.onTypeKitIdChanged();
    },

    addEvents: function() {
        // If typeKitId changed - then we need to add new Type Kit to this page.
        this.model.get('editorConfig').on('change:typeKitId', this.onTypeKitIdChanged);
    },

    /**
     * Trying to add new Type Kit to the page. If this Type Kit already
     * exists on the page then we doing nothing.
     */
    onTypeKitIdChanged: function() {
        var id = this.model.get('editorConfig').get('typeKitId');

        if(_.isString(id) && id != '') {

            if(_.isUndefined(this.views[id])) {
                this.views[id] = new adapter.view.scripts.TypeKitScript({
                    model: new Backbone.Model({
                      id: id
                    })
                });
                this.views[id].render();
            }
        }
    }
});

},{}],12:[function(require,module,exports){
/**
 * Copied from wp-admin/js/editor.js
 */

// Similar to `wpautop()` in formatting.php
module.exports = function( text ) {
    var preserve_linebreaks = false,
        preserve_br = false,
        blocklist = 'table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre' +
            '|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section' +
            '|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary';

    // Normalize line breaks
    text = text.replace( /\r\n|\r/g, '\n' );

    if ( text.indexOf( '\n' ) === -1 ) {
        return text;
    }

    if ( text.indexOf( '<object' ) !== -1 ) {
        text = text.replace( /<object[\s\S]+?<\/object>/g, function( a ) {
            return a.replace( /\n+/g, '' );
        });
    }

    text = text.replace( /<[^<>]+>/g, function( a ) {
        return a.replace( /[\n\t ]+/g, ' ' );
    });

    // Protect pre|script tags
    if ( text.indexOf( '<pre' ) !== -1 || text.indexOf( '<script' ) !== -1 ) {
        preserve_linebreaks = true;
        text = text.replace( /<(pre|script)[^>]*>[\s\S]*?<\/\1>/g, function( a ) {
            return a.replace( /\n/g, '<wp-line-break>' );
        });
    }

    // keep <br> tags inside captions and convert line breaks
    if ( text.indexOf( '[caption' ) !== -1 ) {
        preserve_br = true;
        text = text.replace( /\[caption[\s\S]+?\[\/caption\]/g, function( a ) {
            // keep existing <br>
            a = a.replace( /<br([^>]*)>/g, '<wp-temp-br$1>' );
            // no line breaks inside HTML tags
            a = a.replace( /<[^<>]+>/g, function( b ) {
                return b.replace( /[\n\t ]+/, ' ' );
            });
            // convert remaining line breaks to <br>
            return a.replace( /\s*\n\s*/g, '<wp-temp-br />' );
        });
    }

    text = text + '\n\n';
    text = text.replace( /<br \/>\s*<br \/>/gi, '\n\n' );
    text = text.replace( new RegExp( '(<(?:' + blocklist + ')(?: [^>]*)?>)', 'gi' ), '\n$1' );
    text = text.replace( new RegExp( '(</(?:' + blocklist + ')>)', 'gi' ), '$1\n\n' );
    text = text.replace( /<hr( [^>]*)?>/gi, '<hr$1>\n\n' ); // hr is self closing block element
    text = text.replace( /\s*<option/gi, '<option' ); // No <p> or <br> around <option>
    text = text.replace( /<\/option>\s*/gi, '</option>' );
    text = text.replace( /\n\s*\n+/g, '\n\n' );
    text = text.replace( /([\s\S]+?)\n\n/g, '<p>$1</p>\n' );
    text = text.replace( /<p>\s*?<\/p>/gi, '');
    text = text.replace( new RegExp( '<p>\\s*(</?(?:' + blocklist + ')(?: [^>]*)?>)\\s*</p>', 'gi' ), '$1' );
    text = text.replace( /<p>(<li.+?)<\/p>/gi, '$1');
    text = text.replace( /<p>\s*<blockquote([^>]*)>/gi, '<blockquote$1><p>');
    text = text.replace( /<\/blockquote>\s*<\/p>/gi, '</p></blockquote>');
    text = text.replace( new RegExp( '<p>\\s*(</?(?:' + blocklist + ')(?: [^>]*)?>)', 'gi' ), '$1' );
    text = text.replace( new RegExp( '(</?(?:' + blocklist + ')(?: [^>]*)?>)\\s*</p>', 'gi' ), '$1' );

    // Remove redundant spaces and line breaks after existing <br /> tags
    text = text.replace( /(<br[^>]*>)\s*\n/gi, '$1' );

    // Create <br /> from the remaining line breaks
    text = text.replace( /\s*\n/g, '<br />\n');

    text = text.replace( new RegExp( '(</?(?:' + blocklist + ')[^>]*>)\\s*<br />', 'gi' ), '$1' );
    text = text.replace( /<br \/>(\s*<\/?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)/gi, '$1' );
    text = text.replace( /(?:<p>|<br ?\/?>)*\s*\[caption([^\[]+)\[\/caption\]\s*(?:<\/p>|<br ?\/?>)*/gi, '[caption$1[/caption]' );

    text = text.replace( /(<(?:div|th|td|form|fieldset|dd)[^>]*>)(.*?)<\/p>/g, function( a, b, c ) {
        if ( c.match( /<p( [^>]*)?>/ ) ) {
            return a;
        }

        return b + '<p>' + c + '</p>';
    });

    // put back the line breaks in pre|script
    if ( preserve_linebreaks ) {
        text = text.replace( /<wp-line-break>/g, '\n' );
    }

    if ( preserve_br ) {
        text = text.replace( /<wp-temp-br([^>]*)>/g, '<br$1>' );
    }

    return text;
};

},{}],13:[function(require,module,exports){
var
    $ = jQuery;

/**
 * For more details checkout
 * /wp-admin/js/editor-expand.js:710 (editor expand section) and read description bellow.
 *
 * This util (ok, actually view) unfortunately can't prevent auto init of editorExpand and adjust() method
 * called on page loading. We are only disable this after the page is fully loaded and if
 * user switched to Setka Editor.
 */
module.exports = Backbone.View.extend({

    DOM: {},

    initialize: function() {
        _.bindAll(this, 'autoOff', 'lateInit');
        this.setupDOM();

        // After 3 sec on DOM ready
        // We play dirty because of editorExpand init
        // checkout wp-admin/js/editor-expand.js:710.
        // There adjust() method calling each 500ms for 6 times.
        setTimeout(this.lateInit, 3000);
    },

    lateInit: function() {
        this.addEvents();
        this.autoOff();
    },

    setupDOM: function() {
        this.DOM.document = $(document);
    },

    addEvents: function() {
        this.model.on('change:editorInitialized', this.autoOff);
        this.DOM.document
            .on('editor-expand-on', this.autoOff);
    },

    autoOff: function() {
        // if Setka Editor already running
        if(this.model.get('editorInitialized')) {
            this.off();
        }
    },

    off: function() {
        if(!_.isUndefined('window.editorExpand'))
            window.editorExpand.off();
    },

    on: function() {
        if(!_.isUndefined('window.editorExpand'))
            window.editorExpand.on();
    }

});

},{}],14:[function(require,module,exports){

module.exports = Backbone.View.extend({

    el: '#insert-media-button',

    initialize: function() {
        this.addEvents();
    },

    addEvents: function() {
        _.bindAll(this, 'onEditorInitialized');
        this.model.on('change:editorInitialized', this.onEditorInitialized);
    },

    onEditorInitialized: function() {
        if(this.model.get('editorInitialized')) {
            this.hide();
        }
        else {
            this.show();
        }
    },

    hide: function() {
        this.$el.hide();
    },

    show: function() {
        this.$el.show();
    }
});

},{}],15:[function(require,module,exports){
var
    $ = jQuery;

/**
 * WordPress Admin Menu (left col on page). With this view
 * we can fold (collapse) this menu or unfold.
 */
module.exports = Backbone.View.extend({

    el: '#adminmenu',

    DOM: {},

    initialize: function() {
        this.setupDOM();
    },

    setupDOM: function() {
        this.DOM.body = $(document.body);
        this.DOM.collapseButton = $('#collapse-button');
    },

    isFolded: function() {
        // If not folded then we can fold it (collapse).
        return this.DOM.body.hasClass('folded');
    },

    fold: function() {
        if(!this.isFolded()) {
            this.DOM.collapseButton
                .trigger('click.collapse-menu');
        }
    },

    unFold: function() {
        if(this.isFolded()) {
            this.DOM.collapseButton
                .trigger('click.collapse-menu');
        }
    }

});

},{}],16:[function(require,module,exports){

module.exports = Backbone.View.extend({

    DOM: {},

    initialize: function() {
        // Helper elements
        this.setupDOM();

        // Load content inside model
        this.syncModel();

        // Set correct context (this) for sync()
        _.bindAll(this, 'sync');
    },

    setupDOM: function() {
        // Textarea (<textarea>)
        this.DOM.textarea = this.$('#' + this.model.get('textareaId'));
    },

    render: function() {
        this.setContent();
        return this;
    },

    show: function() {
        this.$el.show();
    },

    hide: function() {
        this.$el.hide();
    },

    getContent: function() {
        return this.DOM.textarea.val();
    },

    setContent: function() {
        this.DOM.textarea.val(this.model.get('editorContent'));
    },

    sync: function() {
        this.setContent();
    },

    syncModel: function() {
        this.model.set('editorContent', this.getContent());
    }

});

},{}],17:[function(require,module,exports){
var
    adapter = window.setkaEditorAdapter,
    translations = window.setkaEditorAdapterL10n;
    $ = jQuery;

module.exports = Backbone.View.extend({

    DOM: {},

    attributes: function() {
        return {
            id: 'content-' + translations.names.css,
            class: translations.names.css + '-wrapper',
            style: 'display: none;'
        };
    },

    initialize: function() {
        this.setupDOM();
        this.addEvents();
    },

    setupDOM: function() {
        this.DOM.document = $(document);
    },

    addEvents: function() {

        // Set correct context (this) for onLayoutChanged(), onThemeChanged()
        _.bindAll(this, 'onLayoutChanged', 'onThemeChanged', 'recalculateWidth');

        // Editor layout changed
        window.ContentEdit.Root.get()
            .bind('post:layout', this.onLayoutChanged);

        // Editor theme changed
        window.ContentEdit.Root.get()
            .bind('post:theme', this.onThemeChanged);

        this.DOM.document.on('wp-collapse-menu', this.recalculateWidth);
    },

    render: function() {

        this.DOM.stk_editor_wrapper_2 =
            $('<div></div>')
                .addClass(translations.names.css + '-wrapper-2');

        this.$el.append(this.DOM.stk_editor_wrapper_2);

        return this;
    },

    show: function() {
        // If already initialized do nothing
        if( !this.model.get('editorInitialized') ) {

            // Get & prepare content
            var editorContent = this.model.get('editorContent');
            editorContent = this._preparePlainContent(editorContent);

            // Create brand new editor container
            this.DOM.stk_editor_wrapper_2.html('<div id="setka-editor" class="stk-editor"></div>');

            // Show editor container
            this.$el.show();

            // Editor starter
            this._start();

            // Set editorInitialized flag to true
            this.model.set('editorInitialized', true);

            $('body')
                .trigger('resize')
                .trigger('scroll');

            // Insert desired content
            this.setContent(editorContent);

            // Content sync
            this.onContentChanged = this.onContentChanged.bind(this);
            window.ContentEdit.Root.get()
                .bind('taint', this.onContentChanged);

            // Need to save on next hearbeat tick
            //this.autosave.save();

            // Find DOM elements which used in some functions of this view
            this.DOM.stk_editor = this.$('.stk-editor');

            Backbone.trigger('setka:editor:adapter:editors:setka:launch');
        }
    },

    afterShow: function() {
        this.recalculateWidth();
    },

    hide: function() {
        this._stop();
        this.model.set('editorInitialized', false);
        this.$el.hide();
        Backbone.trigger('setka:editor:adapter:editors:setka:stop');
    },

    getContent: function() {
        return SetkaEditor.getHTML();
    },

    setContent: function(content) {
        SetkaEditor.replaceHTML(content);
    },

    getWidth: function() {
        // width + paddings
        return this.DOM.stk_editor.outerWidth();
    },

    setTheme: function(theme) {
        SetkaEditor.post.setTheme(theme);
        return this;
    },

    getTheme: function() {
        return SetkaEditor.getCurrentTheme();
    },

    setLayout: function(layout) {
        SetkaEditor.post.setLayout(layout);
        return this;
    },

    getLayout: function() {
        return SetkaEditor.getCurrentLayout();
    },

    /**
     * Parse content from WordPress textarea before inserting in our editor. Wrap plain text inside paragraphs (<p>).
     * If content not looks like plain text do nothing at now. This logic may have issues with parsing content but
     * it's ok. As last part of this method content puts inside the div with `stk-post` class.
     *
     * @param content string
     * @returns string
     * @private
     */
    _preparePlainContent: function(content) {

        var div = document.createElement('div');
        div.innerHTML = content;

        var newContent = '';
        var needWrapper = false;

        for(var i = 0; i < div.childNodes.length; i++) {
            var node = div.childNodes[i];

            // 3 means TEXT_NODE (plain text)
            if(node.nodeType === 3) {

                // The node must contain something.
                // Nodes with only spaces, line breaks and tabs will be skipped
                if(node.textContent.trim() === '') {
                    continue;
                }

                // Prepare content with autop()
                var wrapped = adapter.utils.autop(node.textContent);

                // If one line of text (without any \n symbols) autop() do nothing.
                // We manually wrap it in <p>.
                if(wrapped.indexOf('<p>') === -1) {
                    wrapped = '<p>' + wrapped + '</p>';
                }

                newContent += wrapped;
                needWrapper = true;
            }
            else {
                if( ! jQuery(node).hasClass('stk-post') ) {
                    needWrapper = true;
                }
                newContent += node.outerHTML;
            }
        }

        // Add wrapper if not founded
        if( needWrapper ) {
            newContent = '<div class="stk-post">' + newContent + '</div>';
        }

        return newContent;
    },

    _start: function() {

        var config = this.model.get('editorConfig').toJSON();

        /*var resources = {
            'layouts': window.resources.layouts,
            'themes': window.resources.themes,
            'images': [],
            'symbols': window.resources.symbols
        };*/

        var resources = this.model.get('editorResources').toJSON();

        // Start editor
        SetkaEditor.start(config, resources);

        // Just sure what editor settings up-to-date in Backbone Model
        var layoutConfig = this.getLayout();
        var themeConfig = this.getTheme();
        this.model.get('editorConfig').set('layout', layoutConfig.id);
        this.model.get('editorConfig').set('theme', themeConfig.id);
        var typeKitId =
            this.model.get('editorResources')
                .getThemeBySlug(themeConfig.id)
                    .getTypeKidId();
        this.model.get('editorConfig').set('typeKitId', typeKitId);
    },

    _stop: function() {
        SetkaEditor.stop();
    },

    recalculateWidth: function() {
        window.ContentEdit.Root.get().trigger('editor:width');
    },

    onContentChanged: function() {
        this.model.set('editorContent', this.getContent());
    },

    onLayoutChanged: function(newLayout) {
        this.model.get('editorConfig').set('layout', newLayout);
    },

    onThemeChanged: function(newTheme) {
        this.model.get('editorConfig').set('theme', newTheme);

        // Find selected theme
        var theme = this.model.get('editorResources').getThemeBySlug(newTheme);

        // Check for theme TypeKit
        var typeKitId = theme.getTypeKidId();

        // Update TypeKit
        this.model.get('editorConfig').set('typeKitId', typeKitId);
    },

    isRequiredResourcesAvailable: function() {


        // Theme
        var themeSearch =
            this.model.get('editorResources')
                .getThemeBySlug(this.model.get('editorConfig').get('theme'));

        if(_.isEmpty(themeSearch))
            return false;


        // Layout
        var layoutSearch =
            this.model.get('editorResources')
                . getLayoutBySlug(this.model.get('editorConfig').get('layout'));

        if(_.isEmpty(layoutSearch))
            return false;


        return true;
    },

    isThemeDisabled: function() {
        var slug = this.model.get('editorConfig').get('theme');

        return this.model.get('editorResources')
            .getThemeBySlug(slug)
                .isDisabled();
    }
});

},{}],18:[function(require,module,exports){

module.exports = Backbone.View.extend({

    el: 'html',

    initialize: function() {
        _.bindAll(this, 'setkaEditorEnabled', 'setkaEditorDisabled');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:editors:setka:launch', this.setkaEditorEnabled);
        Backbone.on('setka:editor:adapter:editors:setka:stop',   this.setkaEditorDisabled);
    },

    setkaEditorEnabled: function() {
        this.$el.addClass('wpstkeditor');
    },

    setkaEditorDisabled: function() {
        this.$el.removeClass('wpstkeditor');
    }

});

},{}],19:[function(require,module,exports){
var
    adapter = window.setkaEditorAdapter,
    $ = jQuery,
    translations = window.setkaEditorAdapterL10n;

/**
 * Page which may have Admin Menu, Sidebar (Side Sortables WordPress Meta boxes),
 * Editor and other things.
 */
module.exports = Backbone.View.extend({

    views: {},

    utils: {},

    DOM: {},

    initialize: function() {
        this.setupDOM();
        this.createUtils();

        // Init children views
        this.createSubViews();

        // Events
        _.bindAll(this, 'switchToSetkaEditor', 'switchToDefaultEditor', 'onPostBoxesColumns');
        this.addEvents();

        // Render
        this.render();

        // Auto init editor if post created with this editor
        if(this.model.get('useSetkaEditor')) {
            this.switchToSetkaEditor();
        }
    },

    setupDOM: function() {
        this.DOM.wp_content_wrap = $('#wp-content-wrap');
    },

    createUtils: function() {
        this.utils.autosave = new adapter.utils.AutoSave({
            model: this.model
        });

        this.utils.editorExpand = {
            editorExpand: new adapter.utils.editorExpand.EditorExpand({
                model: this.model
            })
        };

        this.utils.saveSnippet = new adapter.utils.SaveSnippet({
            model: this.model
        });

        this.utils.typeKitScriptsManagaer = new adapter.utils.TypeKitScriptsManager({
            model: this.model
        });
    },

    createSubViews: function() {

        // HTML
        this.views.html = new adapter.view.HTML();

        // WordPress Admin Menu (dark left vertical side)
        this.views.adminMenu = new adapter.view.AdminMenu();

        // Screen Options (#screen-meta)
        this.views.screenMeta = {
            editorExpand: new adapter.view.screenMeta.EditorExpand()
        };

        // Notices
        this.views.notices = {
            setkaEditorCantFindResources: new adapter.view.notices.SetkaEditorCantFindResources(),
            setkaEditorThemeDisabled:     new adapter.view.notices.SetkaEditorThemeDisabled(),
            setkaEditorSaveSnippet:       new adapter.view.notices.SetkaEditorSaveSnippet()
        };

        this.views.postStuff = new adapter.view.postStuff.PostStuff({
            model: this.model
        });

        // Main form on post.php pages
        this.views.form = new adapter.view.Form({
            model: this.model
        });

        // Add Media button
        this.views.addMediaButton = new adapter.view.AddMediaButton({
            model: this.model
        });

        // Editors tabs (Visual, Text,..)
        this.views.editorTabs = new adapter.view.EditorTabs({
            el: '#wp-' + this.model.get('textareaId') + '-wrap .wp-editor-tabs',
            model: this.model
        });

        // Default editor (textarea)
        this.views.editorDefault = new adapter.view.EditorDefault({
            el: '#wp-' + this.model.get('textareaId') + '-editor-container',
            model: this.model
        });

        // Grid editor
        this.views.setkaEditor = new adapter.view.Editor({
            model: this.model
        });

        // Pointers
        this.views.pointers = {
          disabledTabs: new adapter.view.pointers.DisabledTabsPointer()
        };
    },

    addEvents: function() {
        // Tabs clicks (switching editors)
        Backbone.on('setka:editor:adapter:editorTabs:setka:click', this.switchToSetkaEditor);
        Backbone.on('setka:editor:adapter:editorTabs:default:click', this.switchToDefaultEditor);
    },

    render: function() {

        this.views.form.render();

        this.views.editorTabs.render();

        this.views.setkaEditor.render();
        this.DOM.wp_content_wrap
            .append(this.views.setkaEditor.$el);
    },


    switchToSetkaEditor: function() {

        // Check for required resources
        if( !this.views.setkaEditor.isRequiredResourcesAvailable() ) {
            Backbone.trigger('setka:editor:adapter:editors:setka:requiredResourcesNotAvailable');
            return;
        } else {
            Backbone.trigger('setka:editor:adapter:editors:setka:requiredResourcesAvailable');
        }

        // Theme disabled notice
        if( this.views.setkaEditor.isThemeDisabled() ) {
            Backbone.trigger('setka:editor:adapter:editors:setka:themeDisabled');
        } else {
            Backbone.trigger('setka:editor:adapter:editors:setka:themeEnabled');
        }

        // Update content in model
        this.views.editorDefault.syncModel();

        // Switch to HTML editor if TinyMCE is available
        if(!_.isUndefined(window.switchEditors)) {
            window.switchEditors.go(this.model.get('textareaId'), 'html');
        }
        // Toggle wrapper classes
        this.DOM.wp_content_wrap
            .removeClass('html-active tmce-active')
            .addClass(translations.names.css + '-active');


        // Switch editors
        this.views.setkaEditor.show();
        this.views.editorDefault.hide();

        // Mute default editor tabs
        this.views.editorTabs.muteDefaults();

        // Start sync content
        this.model.on('change:editorContent', this.views.editorDefault.sync);


        // Try to encrease horizontal space (width) for editor
        // by collapsing (folding) admin menu
        if( ! this.isEditorFitToSize() ) {
            this.views.adminMenu.fold();
        }
        // by collapsing right meta boxes
        if( ! this.isEditorFitToSize() && this.model.get('postBoxesColumns') === 2 ) {
            this.views.postStuff.collapse();
        }

        // WordPress can change the number of cols and we need
        // adopt to this changes.
        this.model.on('change:postBoxesColumns', this.onPostBoxesColumns);

        /**
         * After increasing horizontal space (width) in the code above
         * editor need to be updated.
         */
        this.views.setkaEditor.afterShow();
    },

    switchToDefaultEditor: function() {
        // Toogle wrapper classes
        this.DOM.wp_content_wrap
            .removeClass(translations.names.css + '-active')
            .addClass('html-active');

        // Switch editors
        this.views.editorDefault.show();
        this.views.setkaEditor.hide();

        // Unmute default editor tabs
        this.views.editorTabs.unMuteDefaults();

        this.model.off('change:editorContent', this.views.editorDefault.sync);

        // WordPress can change the number of cols and we need
        // adopt to this changes.
        this.model.off('change:postBoxesColumns', this.onPostBoxesColumns);

        this.views.postStuff.unCollapse();
    },

    isEditorFitToSize: function() {
        // Container must be great (or equal) than editor
        return (this.views.postStuff.views.postBody.$el.width() >= this.views.setkaEditor.getWidth());
    },

    onPostBoxesColumns: function () {
        if(this.model.get('postBoxesColumns') === 1) {
            this.views.postStuff.unCollapse();
        }
        else if(!this.isEditorFitToSize()) {
            this.views.postStuff.collapse();
        }
        this.views.setkaEditor.recalculateWidth();
    }

});

},{}],20:[function(require,module,exports){
var
    $ = jQuery,
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.View.extend({

    views: {
        defaults: {},
        setka: null
    },

    initialize: function() {
        this.createSubViews();
    },

    createSubViews: function() {

        _.bindAll(this, 'createDefaultTabsViews');

        // Setup default tabs views
        this.$('.wp-switch-editor')
            .each(this.createDefaultTabsViews);

        // Setup setka tab view
        this.views.setka = new adapter.view.EditorTabSetka();
    },

    createDefaultTabsViews: function(index, element) {
        this.views.defaults[index] = new adapter.view.EditorTabDefault({
            el: element,
            model: this.model
        });
    },

    render: function() {
        // Render subviews
        this.views.setka.render();

        // Append tabs to container
        this.$el.append(this.views.setka.$el);

        return this;
    },

    hideDefaults: function() {
        _.each(this.views.defaults, function(tab){
            tab.$el.hide();
        });
    },

    muteDefaults: function() {
        _.each(this.views.defaults, function(tab){
            tab.mute();
        });
    },

    unMuteDefaults: function() {
        _.each(this.views.defaults, function(tab){
            tab.unMute();
        });
    }
});

},{}],21:[function(require,module,exports){
var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    events: {
        click: 'onClick'
    },

    classes: {
        muted: translations.names.css + '-switch-editor-muted'
    },

    onClick: function(event) {
        if( this.model.get('editorInitialized') ) {
            Backbone.trigger('setka:editor:adapter:defaultTabClick', event);
            event.stopImmediatePropagation();

            // This part of code can add confirm window and only if user click "ok" then switch to default editor.

            /*if (confirm(translations.view.editor.switchToDefaultEditorsConfirm)) {
                Backbone.trigger('setka:editor:adapter:editorTabs:default:click');
            }
            else {
                // Keeps the rest of the handlers from being executed
                event.stopImmediatePropagation();
            }*/
        }
    },

    mute: function() {
        this.$el.addClass(this.classes.muted);
        return this;
    },

    unMute: function() {
        this.$el.removeClass(this.classes.muted);
        return this;
    },

    isMuted: function() {
        return this.$el.hasClass(this.classes.muted);
    }
});

},{}],22:[function(require,module,exports){
var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    events: {
        click: 'onClick'
    },

    tagName: 'button',

    attributes: function() {

        var classes = [
            translations.names.css + '-switch-editor',
            'switch-' + translations.names.css
        ];

        return {
            id: 'content-' + translations.names.css,
            class: classes.join(' ')
        };
    },

    render: function() {
        this.$el
            .text(translations.view.editor.tabName);
        return this;
    },

    onClick: function(event) {
        event.preventDefault();
        event.stopPropagation(); // to prevent triggering `click` outside of editor and blurring as side-effect
        Backbone.trigger('setka:editor:adapter:editorTabs:setka:click');
    }
});

},{}],23:[function(require,module,exports){
var
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.View.extend({

    el: '#post',

    events: {
        submit: 'onSubmit'
        //'change input#_wpnonce': 'onNonceChange'
    },

    views: {},

    initialize: function() {
        // Init children views
        this.createSubViews();
    },

    render: function() {
        this.$el
            .append(this.views.settings.$el);

        return this;
    },

    createSubViews: function() {
        this.views.settings = new adapter.view.Settings({
            model: this.model
        });

        this.views.nonce = new adapter.view.Nonce({
            model: this.model
        });

        this.views.postId = new adapter.view.PostId({
            model: this.model
        });
    },

    onSubmit: function() {
        // Update settings input with actual model data for POST request.
        this.views.settings.render();
    }

});

},{}],24:[function(require,module,exports){
var
    $ = jQuery;

module.exports = Backbone.View.extend({

    el: '#_wpnonce',

    initialize: function() {
        this.model.set('nonce', this.get());
        _.bindAll(this, 'wpRefreshNonces');
        $(document).on('heartbeat-tick.wp-refresh-nonces', this.wpRefreshNonces);
    },

    get: function() {
        return this.$el.val();
    },

    wpRefreshNonces: function(event, data) {
        var nonces = data['wp-refresh-post-nonces'];
        if(nonces && nonces.replace[this.$el.attr('id')]) {
            this.model.set('nonce', nonces.replace[this.$el.attr('id')]);
        }
    }

});

},{}],25:[function(require,module,exports){

module.exports = Backbone.View.extend({

    el: '#post_ID',

    initialize: function() {
        this.model.set('postId', this.get());
    },

    get: function() {
        return this.$el.val();
    }

});

},{}],26:[function(require,module,exports){
var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    tagName: 'input',

    attributes: function() {
        return {
            type: 'hidden',
            id:   translations.names.css + '-settings',
            name: translations.names.css + '-settings',
            val:  JSON.stringify(this.model.getDataForAutosave())
        };
    },

    render: function() {
        this.$el.val(
            JSON.stringify(this.model.getDataForAutosave())
        );
        return this;
    }

});

},{}],27:[function(require,module,exports){

module.exports = Backbone.View.extend({

    statusClasses: {
        success: 'notice-success',
        warning: 'notice-warning',
        error:   'notice-error',
        info:    'notice-info'
    },

    removeAllStatuses: function() {
        _.each(
            this.statusClasses,
            function(value, key, list) {
                this.$el.removeClass(value);
            },
            this
        );
    },

    setStatusSuccess: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.success);
    },

    setStatusWarning: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.warning);
    },

    setStatusError: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.error);
    },

    setStatusInfo: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.info);
    }

});

},{}],28:[function(require,module,exports){
var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    el: '#' + translations.names.css + '-notice-' + translations.names.css + '-cant-find-resources',

    initialize: function() {
        _.bindAll(this, 'requiredResourcesNotAvailable', 'requiredResourcesAvailable');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:editors:setka:requiredResourcesNotAvailable', this.requiredResourcesNotAvailable);
        Backbone.on('setka:editor:adapter:editors:setka:requiredResourcesAvailable', this.requiredResourcesAvailable);
    },

    requiredResourcesNotAvailable: function() {
        this.show();
    },

    requiredResourcesAvailable: function() {
        this.hide();
    },

    show: function() {
        this.$el.removeClass('hidden');
    },

    hide: function() {
        this.$el.addClass('hidden');
    }
});

},{}],29:[function(require,module,exports){
var
    adapter    = window.setkaEditorAdapter,
    NoticePrototype = require('./Prototype');
    //translations = window.setkaEditorAdapterL10n;

module.exports = NoticePrototype.extend({

    //el: '#' + setkaEditorAdapterL10n.names.css + '-notice-' + 'setka-editor-save-snippet',

    DOM: {},

    initialize: function() {
        _.bindAll(this, 'onDone', 'onFail');
        //_.bindAll(this, 'onStart', 'onDone', 'onFail');
        //this.setupDOM();
        this.addEvents();
    },

    /*setupDOM: function() {
        //this.DOM.save    = this.$el.find('#' + this.$el.attr('id') + '-save');
        //this.DOM.error   = this.$el.find('#' + this.$el.attr('id') + '-error');
        //this.DOM.success = this.$el.find('#' + this.$el.attr('id') + '-success');
    },*/

    addEvents: function() {
        //Backbone.on('setka:editor:adapter:editors:setka:snippet:start', this.onStart);
        Backbone.on('setka:editor:adapter:editors:setka:snippet:done', this.onDone);
        Backbone.on('setka:editor:adapter:editors:setka:snippet:fail', this.onFail);
    },

    /*hideMessages: function() {
        _.each(this.DOM, function(value, key, list){
            value.addClass('hidden');
        });
    },*/

    /*showMessage: function(message) {
        message.removeClass('hidden');
    },*/

    /*show: function() {
        this.$el.removeClass('hidden');
    },*/

    /*onStart: function() {
        this.hideMessages();
        this.setStatusInfo();
        this.showMessage(this.DOM.save);
        this.show();
    },*/

    onDone: function(snippet, response) {
        //console.log(snippet, response);
        //this.hideMessages();

        // We have errors
        if(_.has(response, 'errors') && !_.isEmpty(response.errors)) {
            //this.setStatusError();
            //this.showMessage(this.DOM.error);
            var message = '';
            _.each(response.errors, function(element, index, list) {
                if(!_.isEmpty(message))
                    message += '\n';
                message += element.message;
            });
            window.alert(message);
        } // else { // no errors
            //this.setStatusSuccess();
            //this.showMessage(this.DOM.success);
        //}

        //this.show();
    },

    onFail: function(snippet, jqXHR, textStatus, errorThrown) {
        window.alert(translations.view.notices.setkaEditorSaveSnippet.cantConnectToWordPress);
        //console.log(snippet, jqXHR, textStatus, errorThrown);
        //this.hideMessages();
        //this.setStatusError();
        //this.showMessage(this.DOM.error);
        //this.show();

        /**
         * Possible issues:
         *  Webserver not running.
         *  Connection timeout (WordPress is so slow)
         *  WordPress fails with PHP fatal error.
         */
    }

});

},{"./Prototype":27}],30:[function(require,module,exports){
var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    el: '#' + translations.names.css + '-notice-' + translations.names.css + '-theme-disabled',

    initialize: function() {
        _.bindAll(this, 'themeDisabled', 'themeEnabled');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:editors:setka:themeDisabled', this.themeDisabled);
        Backbone.on('setka:editor:adapter:editors:setka:themeEnabled', this.themeEnabled);
    },

    themeDisabled: function() {
        this.show();
    },

     themeEnabled: function() {
        this.hide();
    },

    show: function() {
        this.$el.removeClass('hidden');
    },

    hide: function() {
        this.$el.addClass('hidden');
    }
});

},{}],31:[function(require,module,exports){
var
    $ = jQuery,
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    initialize: function() {
        _.bindAll(this, 'onDefaultTabClick');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:defaultTabClick', this.onDefaultTabClick);
    },

    removeEvents: function() {
        Backbone.off('setka:editor:adapter:defaultTabClick', this.onDefaultTabClick);
    },

    onDefaultTabClick: function(event) {
        $(translations.pointers.disabledEditorTabs.target) // or we can use event.target
            .pointer(translations.pointers.disabledEditorTabs.options)
            .pointer('open');
    }

});

},{}],32:[function(require,module,exports){
var
    adapter = window.setkaEditorAdapter,
    translations = setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    el: '#poststuff',

    views: {}, DOM: {},

    classes: {
        collapsed: translations.names.css + '-poststuff-container-1-collapsed'
    },

    initialize: function() {
        _.bindAll(this, 'collapse', 'unCollapse');
        this.createSubViews();
    },

    createSubViews: function() {
        // Post Body
        this.views.postBody = new adapter.view.postStuff.postBody.PostBody({
            model: this.model
        });
    },

    collapse: function() {
        this.$el.addClass(this.classes.collapsed);
        Backbone.trigger('setka:editor:adapter:poststuff:collapsed');
    },

    unCollapse: function() {
        this.$el.removeClass(this.classes.collapsed);
        Backbone.trigger('setka:editor:adapter:poststuff:unCollapsed');
    }

});

},{}],33:[function(require,module,exports){
var
    $ = jQuery;

module.exports = Backbone.View.extend({

    el: '#post-body',

    DOM: {},

    initialize: function() {
        // Load current number of cols
        this.model.set('postBoxesColumns', this.getCols());

        _.bindAll(this, 'onPostBoxesColumnChange');

        this.setupDOM();
        this.addEvents();
    },

    setupDOM: function() {
        this.DOM.document = $(document);
    },

    addEvents: function() {
        // Fired when user switch number of columns on Screen Options WP tab.
        // This event triggered in /wp-admin/js/postbox.js:208
        this.DOM.document
            .on('postboxes-columnchange', this.onPostBoxesColumnChange);
    },

    getCols: function() {
        var col_1, col_2;
        col_1 = this.$el.hasClass('columns-1');
        col_2 = this.$el.hasClass('columns-2');

        return ( col_2 && !col_1 ) ? 2 : 1;
    },

    onPostBoxesColumnChange: function() {
        this.model.set('postBoxesColumns', this.getCols());
    }

});

},{}],34:[function(require,module,exports){

module.exports = Backbone.View.extend({

    el: '#adv-settings .editor-expand',

    initialize: function() {
        _.bindAll(this, 'onLaunch', 'onStop');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:editors:setka:launch', this.onLaunch);
        Backbone.on('setka:editor:adapter:editors:setka:stop', this.onStop);
    },

    removeEvents: function() {
        Backbone.off('setka:editor:adapter:editors:setka:launch', this.onLaunch);
        Backbone.off('setka:editor:adapter:editors:setka:stop', this.onStop);
    },

    onLaunch: function() {
        this.hide();
    },

    onStop: function() {
        this.show();
    },

    hide: function() {
        this.$el.hide();
    },

    show: function() {
        this.$el.show();
    }

});

},{}],35:[function(require,module,exports){
var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    tagName: 'script',

    attributes: {
        type: 'text/javascript',
        async: 'async'
    },

    initialize: function() {
        _.bindAll(this, 'onLoad');
        this.addEvents();
    },

    addEvents: function() {
        this.model.on('change:id', this.update);
    },

    getSrc: function() {
        return '//use.typekit.net/' + this.model.get('id') + '.js';
    },

    getId: function() {
        return translations.names.css + '-type-kit-script-' + this.model.get('id');
    },

    render: function() {
        this.$el
            .attr('id',  this.getId())
            .attr('src', this.getSrc());

        // We using plain JS here because jQuery's .append()
        // not triggering load event.
        document.head.appendChild(this.el);
        this.el.addEventListener('load', this.onLoad);
    },

    onLoad: function() {
        try {
            Typekit.load({
                async: true
            });
        } catch(e) {}
    }
});

},{}]},{},[3])
//# sourceMappingURL=editor-adapter.js.map
