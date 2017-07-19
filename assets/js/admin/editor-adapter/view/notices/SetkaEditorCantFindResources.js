var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    el: '#' + translations.names.css + '-notice-' + translations.names.css + '-cant-find-resources',

    initialize: function() {
        _.bindAll(this, 'requiredResourcesNotAvailable', 'requiredResourcesAvailable');
        this.addEvents();
    },

    addEvents: function() {
        Backbone.on('setka:editor:adapter:editors:setka:requiredResourcesNotAvailable', this.requiredResourcesNotAvailable);
        Backbone.on('setka:editor:adapter:editors:setka:requiredResourcesAvailable', this.requiredResourcesAvailable);
    },

    requiredResourcesNotAvailable: function() {
        this.show();
    },

    requiredResourcesAvailable: function() {
        this.hide();
    },

    show: function() {
        this.$el.removeClass('hidden');
    },

    hide: function() {
        this.$el.addClass('hidden');
    }
});
