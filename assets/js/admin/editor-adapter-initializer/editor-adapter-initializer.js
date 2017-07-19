/* global jQuery, setkaEditorAdapterL10n */
(function( $ ) {
    /**
     * To run an editor we need load some configs from Setka Server
     * and init after DOM is ready.
     * Technical details can be found at http://stackoverflow.com/a/21750260
     */

    // DOM is ready
    var readyToInit = $.Deferred();
    $(document).ready(readyToInit.resolve);

    // Load remote config
    var ajaxAssets = $.getJSON(
        setkaEditorAdapterL10n.settings.themeData
    );

    // Init everything when DOM is ready and config is loaded.
    $.when(ajaxAssets, readyToInit.promise())
        /*.fail(function(jqXHR,status){
            console.log('AJAX ERROR:' + status);
        })*/
        .done(init);

    function init(response) {
        var EditorConfigModel = setkaEditorAdapter.model.EditorConfig;
        var EditorResourcesModel = setkaEditorAdapter.model.EditorResources;
        var PageView = setkaEditorAdapter.view.Page;
        var FormModel = setkaEditorAdapter.model.Form;
        var translations = setkaEditorAdapterL10n;

        var settings = {
            textareaId: 'content',
            editorConfig: new EditorConfigModel(
                // Merge post specific settings with defaults
                _.extend( response[0].config, translations.settings.editorConfig )
            ),
            editorResources: new EditorResourcesModel(response[0].assets),
            useSetkaEditor: translations.settings.useSetkaEditor
        };

        // Auto init editor from /wp-admin/post-new.php?setka-editor-auto-init
        var uri = new URI(window.location.href);
        var uriQuery = uri.search(true);
        if(typeof uriQuery[translations.names.css + '-auto-init'] !== 'undefined') {
            settings.useSetkaEditor = true;
        }

        window.setkaEditorPlugin = new PageView({
            model: new FormModel(settings)
        });
    }
}(jQuery));
