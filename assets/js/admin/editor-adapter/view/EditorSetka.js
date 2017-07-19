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
