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
