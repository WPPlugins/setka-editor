
module.exports = Backbone.Model.extend({

    defaults: {
        postId: null,
        textareaId: null,
        editorInitialized: false,
        editorConfig: {},
        editorContent: '',
        useSetkaEditor: false,
        postBoxesColumns: null,

        postBoxContainer1Hovered: false
    },

    initialize: function() {

        _.bindAll(this, 'onEditorInitializedChanged');

        // Parse useSetkaEditor
        var useSetkaEditor = this.get('useSetkaEditor');
        if(!_.isBoolean(useSetkaEditor)) {
            if( useSetkaEditor === '1' ) {
                this.set('useSetkaEditor', true);
            }
            else {
                this.set('useSetkaEditor', false);
            }
        }

        this.addEvents();
    },

    addEvents: function() {
        this.on('change:editorInitialized', this.onEditorInitializedChanged);
    },

    onEditorInitializedChanged: function() {
        this.set('useSetkaEditor', this.get('editorInitialized'));
    },

    getDataForAutosave: function() {
        return {
            useSetkaEditor: this.get('useSetkaEditor') ? '1' : '0',
            editorConfig: this.get('editorConfig').toJSON(),
            _wpnonce: this.get('nonce'),
            postId: this.get('postId')
        };
    }

});
