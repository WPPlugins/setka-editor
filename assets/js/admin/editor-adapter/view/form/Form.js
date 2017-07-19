var
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.View.extend({

    el: '#post',

    events: {
        submit: 'onSubmit'
        //'change input#_wpnonce': 'onNonceChange'
    },

    views: {},

    initialize: function() {
        // Init children views
        this.createSubViews();
    },

    render: function() {
        this.$el
            .append(this.views.settings.$el);

        return this;
    },

    createSubViews: function() {
        this.views.settings = new adapter.view.Settings({
            model: this.model
        });

        this.views.nonce = new adapter.view.Nonce({
            model: this.model
        });

        this.views.postId = new adapter.view.PostId({
            model: this.model
        });
    },

    onSubmit: function() {
        // Update settings input with actual model data for POST request.
        this.views.settings.render();
    }

});
