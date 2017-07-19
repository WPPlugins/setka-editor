var
    translations = window.setkaEditorAdapterL10n;

module.exports = Backbone.View.extend({

    tagName: 'script',

    attributes: {
        type: 'text/javascript',
        async: 'async'
    },

    initialize: function() {
        _.bindAll(this, 'onLoad');
        this.addEvents();
    },

    addEvents: function() {
        this.model.on('change:id', this.update);
    },

    getSrc: function() {
        return '//use.typekit.net/' + this.model.get('id') + '.js';
    },

    getId: function() {
        return translations.names.css + '-type-kit-script-' + this.model.get('id');
    },

    render: function() {
        this.$el
            .attr('id',  this.getId())
            .attr('src', this.getSrc());

        // We using plain JS here because jQuery's .append()
        // not triggering load event.
        document.head.appendChild(this.el);
        this.el.addEventListener('load', this.onLoad);
    },

    onLoad: function() {
        try {
            Typekit.load({
                async: true
            });
        } catch(e) {}
    }
});
