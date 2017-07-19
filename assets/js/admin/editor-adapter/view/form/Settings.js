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
