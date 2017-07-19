(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* global jQuery, Backbone */

var setkaEditorSettingPages = {};

// Store everything globally
window.setkaEditorSettingPages = setkaEditorSettingPages;

setkaEditorSettingPages.model = {
    Fragment: require('./model/Fragment')
};

setkaEditorSettingPages.collection = {

};

setkaEditorSettingPages.view = {
    Fragment: require('./view/Fragment')
};
},{"./model/Fragment":2,"./view/Fragment":3}],2:[function(require,module,exports){

module.exports = Backbone.Model.extend({

    initialize: function() {
        if(!this.has('id')) {
            var names = this.getNamesRecursive();
            this.set('id', names.join('_'));
        }
    },

    getID: function() {
        return this.get('id');
    },

    getNamesRecursive: function() {
        var names = [];
        if(this.has('parent')) {
            names = this.get('parent').getNamesRecursive();
        }
        names.push(this.get('name'));
        return names;
    },

    getFragmentRole: function() {
        if(this.has('options')) {
            var options = this.get('options');
            if(!_.isUndefined(options.attr['data-form-element-role'])) {
                return options.attr['data-form-element-role'];
            }
        }
        return false;
    }
});

},{}],3:[function(require,module,exports){
var
    adapter = window.setkaEditorSettingPages,
    $ = jQuery;

module.exports = Backbone.View.extend({

    /**
     * Nested fragment instances (sections, inputs wrapped into divs).
     */
    fragments: {},

    initialize: function() {
        // If no parent then this is the root element (actually form)
        if(!this.model.has('parent')) {
            // Try to find form config
            var configSelector = '#' + this.model.get('name') + '_config';
            var json = this.$el.find(configSelector).val();
            // And parse this config from JSON string
            try {
                var fragments = JSON.parse(json);
                this.model.set('fragments', fragments);
            } catch(e) {
                return;
            } finally {
                delete(json);
                delete(fragments);
            }
        }

        // After preparing config search for nested fragments (sections, inputs wrapped into divs)
        var fragments = this.model.get('fragments');
        // And setup each fragment
        if(!_.isEmpty(fragments)) {
            _.each(fragments, this.initializeSubFragment, this);
            this.model.unset('fragments');
        }

        // All events runs in similar context (environment) for accessing to this.model and this.fragments
        _.bindAll(this, 'onSelectNewCondition', 'onConditionChanged', 'afterInit');

        // Attach events
        this.addEvents();

        // Kick it!
        if(!this.model.has('parent')) {
            // This event for make conditions work after init the form.
            Backbone.trigger('setka:editor:admin:form:afterInit', this);
        }
    },

    initializeSubFragment: function(fragment) {
        fragment.parent = this.model;
        var model = new adapter.model.Fragment(fragment);

        var selector = '#' + model.getID();

        if(!_.isUndefined(fragment.options.attr['data-fragment'])) {
            selector += '_' + fragment.options.attr['data-fragment'];
        } else {
            selector += '_wrapper';
        }

        var $fragment = $(selector);

        if($fragment.length) {
            this.fragments[model.get('name')] = new adapter.view.Fragment({
                model: model,
                el: $(selector)
            });
        } else {
            this.fragments[model.get('name')] = new adapter.view.Fragment({
                model: model
            });
        }
    },

    addEvents: function() {
        var role = this.model.getFragmentRole();
        if(role) {
            switch(role) {
                case 'conditional-switcher':
                    $('#' + this.model.getID()).click(this.onSelectNewCondition);
                    break;

                case 'conditional-listener':
                default:
                    Backbone.on('setka:editor:admin:form:condition:changed', this.onConditionChanged);
                    break;
            }
        }
        Backbone.on('setka:editor:admin:form:afterInit', this.afterInit);
    },

    onSelectNewCondition: function(event) {
        Backbone.trigger('setka:editor:admin:form:condition:changed', event);
    },

    onConditionChanged: function(event) {
        var options = this.model.get('options');
        if(_.contains(options['validation_groups'], event.target.value)) {
            this.$el.show();
        } else {
            this.$el.hide();
        }
    },

    afterInit: function() {
        var role = this.model.getFragmentRole();
        if(role == 'conditional-switcher') {
            var element = $('#' + this.model.getID());
            if(element.attr('checked') == 'checked') {
                element.click();
            }
        }
    }
});
},{}]},{},[1])
//# sourceMappingURL=setting-pages.js.map
