pimcore.registerNS("pimcore.plugin.contact");

pimcore.plugin.contact = Class.create(pimcore.plugin.admin, {

    getClassName: function () {
        return "pimcore.plugin.contact";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },


    uninstall: function() {
    },

    pimcoreReady: function (params,broker){

        var toolbar = Ext.getCmp("pimcore_panel_toolbar");

        var action = new Ext.Action({
            text: t('Contact history'),
            iconCls:"contact_icon",
            handler: function(){
                var gestion = new contact.history;
            }
        });

        toolbar.insert(4,action);
    }

});

new pimcore.plugin.contact();
