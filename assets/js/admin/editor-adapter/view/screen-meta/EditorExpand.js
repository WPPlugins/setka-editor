
module.exports = Backbone.View.extend({

    el: '#adv-settings .editor-expand',

    initialize: function() {
        _.bindAll(this, 'onLaunch', 'onStop');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:editors:setka:launch', this.onLaunch);
        Backbone.on('setka:editor:adapter:editors:setka:stop', this.onStop);
    },

    removeEvents: function() {
        Backbone.off('setka:editor:adapter:editors:setka:launch', this.onLaunch);
        Backbone.off('setka:editor:adapter:editors:setka:stop', this.onStop);
    },

    onLaunch: function() {
        this.hide();
    },

    onStop: function() {
        this.show();
    },

    hide: function() {
        this.$el.hide();
    },

    show: function() {
        this.$el.show();
    }

});
