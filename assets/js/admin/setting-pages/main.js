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