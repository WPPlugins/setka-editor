var
    $ = jQuery;

module.exports = Backbone.View.extend({

    el: '#_wpnonce',

    initialize: function() {
        this.model.set('nonce', this.get());
        _.bindAll(this, 'wpRefreshNonces');
        $(document).on('heartbeat-tick.wp-refresh-nonces', this.wpRefreshNonces);
    },

    get: function() {
        return this.$el.val();
    },

    wpRefreshNonces: function(event, data) {
        var nonces = data['wp-refresh-post-nonces'];
        if(nonces && nonces.replace[this.$el.attr('id')]) {
            this.model.set('nonce', nonces.replace[this.$el.attr('id')]);
        }
    }

});
