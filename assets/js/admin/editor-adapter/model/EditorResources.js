var
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.Model.extend({

    initialize: function() {
        this._setupThemes();
        this._setupLayouts();
    },

    _setupThemes: function() {
        var themes = new adapter.collection.Themes();

        _.each(this.get('themes'), function(element, index, list) {
            themes.add(new adapter.model.Theme(element));
        });

        this.set('themes', themes);
    },

    _setupLayouts: function() {
        var layouts = new adapter.collection.Layouts();

        _.each(this.get('layouts'), function(element, index, list) {
            layouts.add(new adapter.model.Layout(element));
        });

        this.set('layouts', layouts);
    },

    getThemeBySlug: function(slug) {
        return this.get('themes').findWhere({
            id: slug
        });
    },

    getThemeById: function(id) {
        return this.get('themes').findWhere({
            theme_id: id
        });
    },

    getLayoutBySlug: function(slug) {
        return this.get('layouts').findWhere({
            id: slug
        });
    },

    toJSON: function(options) {
        var result = _.clone(this.attributes);

        // themes to array
        result.themes = result.themes.toJSON();

        // layouts to array
        result.layouts = result.layouts.toJSON();

        return result;
    }
});
