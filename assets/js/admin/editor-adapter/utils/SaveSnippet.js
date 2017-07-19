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
