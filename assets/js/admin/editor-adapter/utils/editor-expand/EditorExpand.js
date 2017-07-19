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
