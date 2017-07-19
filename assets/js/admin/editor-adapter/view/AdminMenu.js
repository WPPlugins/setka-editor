var
    $ = jQuery;

/**
 * WordPress Admin Menu (left col on page). With this view
 * we can fold (collapse) this menu or unfold.
 */
module.exports = Backbone.View.extend({

    el: '#adminmenu',

    DOM: {},

    initialize: function() {
        this.setupDOM();
    },

    setupDOM: function() {
        this.DOM.body = $(document.body);
        this.DOM.collapseButton = $('#collapse-button');
    },

    isFolded: function() {
        // If not folded then we can fold it (collapse).
        return this.DOM.body.hasClass('folded');
    },

    fold: function() {
        if(!this.isFolded()) {
            this.DOM.collapseButton
                .trigger('click.collapse-menu');
        }
    },

    unFold: function() {
        if(this.isFolded()) {
            this.DOM.collapseButton
                .trigger('click.collapse-menu');
        }
    }

});
