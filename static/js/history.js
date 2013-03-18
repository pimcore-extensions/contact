pimcore.registerNS('pimcore.plugin.pimcontact');

pimcore.plugin.pimcontact.history = Class.create({
    initialize: function() {
        this.createPanel();
    },
    createPanel: function() {

        var tabPanel = Ext.getCmp('pimcore_panel_tabs');

        var layout = this.getHistory();

        var panel = new Ext.Panel({
            id: 'contactBody',
            title: ts('Contact history'),
            border: true,
            layout: 'fit',
            closable: true,
            iconCls: 'contact_icon',
            bodyStyle: 'padding:4px;',
            items: layout
        });

        tabPanel.add(panel);
        tabPanel.activate('contactBody');

    },

    getHistory: function() {

        var expander = new Ext.ux.grid.RowExpander({
            tpl: new Ext.Template('{metadata}<br/>{text}')
        });
        var group = new Ext.grid.GroupingView({
            forceFit: true,
            groupTextTpl: '{values.group} ({[values.rs.length]} {[values.rs.length > 1 ? "messages" : "message"]})'
        });

        var proxy = new Ext.data.HttpProxy({
            url: '/plugin/PimContact/admin/history'
        });

        var readerFields = [
            { name: 'id' },
            { name: 'subject' },
            { name: 'sender' },
            { name: 'receiver' },
            { name: 'text' },
            { name: 'metadata' },
            { name: 'date' }
        ];
        var reader = new Ext.data.JsonReader({
            totalProperty: 'total',
            successProperty: 'success',
            root: 'data',
            idProperty: 'id'
        }, readerFields);

        var writer = new Ext.data.JsonWriter();
        // create the data store
        this.ustore = new Ext.data.GroupingStore({
            fields: readerFields,
            root: 'data',
            restful: false,
            proxy: proxy,
            reader: reader,
            writer: writer,
            sortInfo: {
                field: 'date',
                direction: 'DESC'
            },
            groupField: 'receiver'
        });

        // create the Grid
        this.ugrid = new Ext.grid.GridPanel({
            store: this.ustore,
            plugins: expander,
            view: group,
            columns: [expander,
                {
                    id: 'id',
                    header: 'id',
                    width: 10,
                    sortable: true,
                    dataIndex: 'id',
                    hidden: true
                },
                {
                    id: 'receiver',
                    header: ts('receiver'),
                    width: 150,
                    sortable: true,
                    dataIndex: 'receiver',
                    hidden: true
                },
                {
                    header: ts('subject'),
                    width: 150,
                    sortable: true,
                    dataIndex: 'subject'
                },
                {
                    id: 'subject',
                    header: ts('sender'),
                    width: 150,
                    sortable: true,
                    dataIndex: 'sender'
                },
                {
                    header: ts('date'),
                    width: 150,
                    sortable: true,
                    dataIndex: 'date'
                }
            ],
            stripeRows: true,
            autoExpandColumn: 'subject',
            height: 350,
            width: 600,
            //iconCls : "subscriber_icon",
            //title: ts('Contact history'),
            // config options for stateful behavior
            id: 'ContactGrid'
        });

        this.ustore.load();
        return this.ugrid;
    }

});
