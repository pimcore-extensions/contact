    pimcore.registerNS('pimcore.plugin.pimcontact');

pimcore.plugin.pimcontact = Class.create(pimcore.plugin.admin, {

    getClassName: function () {
        return 'pimcore.plugin.pimcontact';
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },


    uninstall: function() {
    },

    pimcoreReady: function (params, broker) {
        // backward pimcore compatibility
        if(pimcore.plugin.admin.prototype.getMenu == undefined) {
            var toolbar = Ext.getCmp("pimcore_panel_toolbar");
            toolbar.items.items[1].menu.add(this.getMenu());
        }
    },

    getMenu: function(){
        return new Ext.menu.Item({
            text: ts('Contact history'),
            iconCls: 'contact_icon',
            handler: function() {
                new pimcore.plugin.pimcontact.history;
            }
        });
    }

});

new pimcore.plugin.pimcontact();
