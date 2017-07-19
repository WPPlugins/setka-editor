/* global jQuery, setkaEditorSettingPages */
(function( $ ) {
    $(document).ready(function() {
        var forms = $('form');
        var FragmentView = setkaEditorSettingPages.view.Fragment;
        var FragmentModel = setkaEditorSettingPages.model.Fragment;
        window.setkaEditorSettingPageFormInstances = [];

        _.each(forms, function(form){
            if($(form).data('form-type') == 'conditional-form') {
                window.setkaEditorSettingPageFormInstances.push(
                    new FragmentView({
                        el: form,
                        model: new FragmentModel({
                            name: $(form).attr('name')
                        })
                    })
                );
            }
        });
    });
}(jQuery));
