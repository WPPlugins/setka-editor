var
    adapter = window.setkaEditorAdapter;

module.exports = Backbone.View.extend({

    // we store here <script> tags with Type Kits
    views: {},

    initialize: function() {
        _.bindAll(this, 'onTypeKitIdChanged');
        this.addEvents();
        this.onTypeKitIdChanged();
    },

    addEvents: function() {
        // If typeKitId changed - then we need to add new Type Kit to this page.
        this.model.get('editorConfig').on('change:typeKitId', this.onTypeKitIdChanged);
    },

    /**
     * Trying to add new Type Kit to the page. If this Type Kit already
     * exists on the page then we doing nothing.
     */
    onTypeKitIdChanged: function() {
        var id = this.model.get('editorConfig').get('typeKitId');

        if(_.isString(id) && id != '') {

            if(_.isUndefined(this.views[id])) {
                this.views[id] = new adapter.view.scripts.TypeKitScript({
                    model: new Backbone.Model({
                      id: id
                    })
                });
                this.views[id].render();
            }
        }
    }
});
