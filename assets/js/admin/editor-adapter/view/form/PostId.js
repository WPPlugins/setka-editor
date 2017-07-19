
module.exports = Backbone.View.extend({

    el: '#post_ID',

    initialize: function() {
        this.model.set('postId', this.get());
    },

    get: function() {
        return this.$el.val();
    }

});
