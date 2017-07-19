
module.exports = Backbone.View.extend({

    el: 'html',

    initialize: function() {
        _.bindAll(this, 'setkaEditorEnabled', 'setkaEditorDisabled');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:editors:setka:launch', this.setkaEditorEnabled);
        Backbone.on('setka:editor:adapter:editors:setka:stop',   this.setkaEditorDisabled);
    },

    setkaEditorEnabled: function() {
        this.$el.addClass('wpstkeditor');
    },

    setkaEditorDisabled: function() {
        this.$el.removeClass('wpstkeditor');
    }

});
