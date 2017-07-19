
module.exports = Backbone.View.extend({

    statusClasses: {
        success: 'notice-success',
        warning: 'notice-warning',
        error:   'notice-error',
        info:    'notice-info'
    },

    removeAllStatuses: function() {
        _.each(
            this.statusClasses,
            function(value, key, list) {
                this.$el.removeClass(value);
            },
            this
        );
    },

    setStatusSuccess: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.success);
    },

    setStatusWarning: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.warning);
    },

    setStatusError: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.error);
    },

    setStatusInfo: function() {
        this.removeAllStatuses();
        this.$el.addClass(this.statusClasses.info);
    }

});
