var
    $ = jQuery,
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.View.extend({

    views: {
        defaults: {},
        setka: null
    },

    initialize: function() {
        this.createSubViews();
    },

    createSubViews: function() {

        _.bindAll(this, 'createDefaultTabsViews');

        // Setup default tabs views
        this.$('.wp-switch-editor')
            .each(this.createDefaultTabsViews);

        // Setup setka tab view
        this.views.setka = new adapter.view.EditorTabSetka();
    },

    createDefaultTabsViews: function(index, element) {
        this.views.defaults[index] = new adapter.view.EditorTabDefault({
            el: element,
            model: this.model
        });
    },

    render: function() {
        // Render subviews
        this.views.setka.render();

        // Append tabs to container
        this.$el.append(this.views.setka.$el);

        return this;
    },

    hideDefaults: function() {
        _.each(this.views.defaults, function(tab){
            tab.$el.hide();
        });
    },

    muteDefaults: function() {
        _.each(this.views.defaults, function(tab){
            tab.mute();
        });
    },

    unMuteDefaults: function() {
        _.each(this.views.defaults, function(tab){
            tab.unMute();
        });
    }
});
