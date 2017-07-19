
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
