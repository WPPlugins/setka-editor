var
    $ = jQuery;

module.exports = Backbone.View.extend({

    el: '#post-body',

    DOM: {},

    initialize: function() {
        // Load current number of cols
        this.model.set('postBoxesColumns', this.getCols());

        _.bindAll(this, 'onPostBoxesColumnChange');

        this.setupDOM();
        this.addEvents();
    },

    setupDOM: function() {
        this.DOM.document = $(document);
    },

    addEvents: function() {
        // Fired when user switch number of columns on Screen Options WP tab.
        // This event triggered in /wp-admin/js/postbox.js:208
        this.DOM.document
            .on('postboxes-columnchange', this.onPostBoxesColumnChange);
    },

    getCols: function() {
        var col_1, col_2;
        col_1 = this.$el.hasClass('columns-1');
        col_2 = this.$el.hasClass('columns-2');

        return ( col_2 && !col_1 ) ? 2 : 1;
    },

    onPostBoxesColumnChange: function() {
        this.model.set('postBoxesColumns', this.getCols());
    }

});
