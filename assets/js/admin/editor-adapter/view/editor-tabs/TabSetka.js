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
