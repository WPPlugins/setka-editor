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
