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
