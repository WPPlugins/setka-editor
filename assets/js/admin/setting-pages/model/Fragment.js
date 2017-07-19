
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
