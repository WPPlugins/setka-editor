var
    adapter    = window.setkaEditorAdapter,
    NoticePrototype = require('./Prototype');
    //translations = window.setkaEditorAdapterL10n;

module.exports = NoticePrototype.extend({

    //el: '#' + setkaEditorAdapterL10n.names.css + '-notice-' + 'setka-editor-save-snippet',

    DOM: {},

    initialize: function() {
        _.bindAll(this, 'onDone', 'onFail');
        //_.bindAll(this, 'onStart', 'onDone', 'onFail');
        //this.setupDOM();
        this.addEvents();
    },

    /*setupDOM: function() {
        //this.DOM.save    = this.$el.find('#' + this.$el.attr('id') + '-save');
        //this.DOM.error   = this.$el.find('#' + this.$el.attr('id') + '-error');
        //this.DOM.success = this.$el.find('#' + this.$el.attr('id') + '-success');
    },*/

    addEvents: function() {
        //Backbone.on('setka:editor:adapter:editors:setka:snippet:start', this.onStart);
        Backbone.on('setka:editor:adapter:editors:setka:snippet:done', this.onDone);
        Backbone.on('setka:editor:adapter:editors:setka:snippet:fail', this.onFail);
    },

    /*hideMessages: function() {
        _.each(this.DOM, function(value, key, list){
            value.addClass('hidden');
        });
    },*/

    /*showMessage: function(message) {
        message.removeClass('hidden');
    },*/

    /*show: function() {
        this.$el.removeClass('hidden');
    },*/

    /*onStart: function() {
        this.hideMessages();
        this.setStatusInfo();
        this.showMessage(this.DOM.save);
        this.show();
    },*/

    onDone: function(snippet, response) {
        //console.log(snippet, response);
        //this.hideMessages();

        // We have errors
        if(_.has(response, 'errors') && !_.isEmpty(response.errors)) {
            //this.setStatusError();
            //this.showMessage(this.DOM.error);
            var message = '';
            _.each(response.errors, function(element, index, list) {
                if(!_.isEmpty(message))
                    message += '\n';
                message += element.message;
            });
            window.alert(message);
        } // else { // no errors
            //this.setStatusSuccess();
            //this.showMessage(this.DOM.success);
        //}

        //this.show();
    },

    onFail: function(snippet, jqXHR, textStatus, errorThrown) {
        window.alert(translations.view.notices.setkaEditorSaveSnippet.cantConnectToWordPress);
        //console.log(snippet, jqXHR, textStatus, errorThrown);
        //this.hideMessages();
        //this.setStatusError();
        //this.showMessage(this.DOM.error);
        //this.show();

        /**
         * Possible issues:
         *  Webserver not running.
         *  Connection timeout (WordPress is so slow)
         *  WordPress fails with PHP fatal error.
         */
    }

});
