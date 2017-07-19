
module.exports = Backbone.Model.extend({

    isDisabled: function() {
        var disabled = this.get('disabled');

        if(!_.isUndefined(this.get('disabled')) && disabled === true)
            return true;

        return false;
    },

    getTypeKidId: function() {
        var typeKit = this.get('kit_id');

        if(_.isUndefined(typeKit)) {
            typeKit = '';
        }

        return typeKit;
    }

});
