
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
