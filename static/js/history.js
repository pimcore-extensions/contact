pimcore.registerNS("contact.history");

contact.history = Class.create({

    initialize: function(){
        this.createPanel();
    },

    createPanel: function(){

        var tabPanel = Ext.getCmp("pimcore_panel_tabs");

        var layout = this.getHistory();

        var panel = new Ext.Panel({
            id: "contactBody",
            title: t("history of contact"),
            border: true,
            layout:'fit',
            closable:true,
            iconCls:"contact_icon",
            bodyStyle: 'padding:4px;',
            items:layout
        });



        tabPanel.add(panel);
        tabPanel.activate("contactBody");

    },

    /////////////////////////////////////////////////////////////////////
    //Paneau Users

    getHistory: function(){

        var expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template('{metadata}<br/>{text}')
        });
        var group = new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{values.group} ({[values.rs.length]} {[values.rs.length > 1 ? "messages" : "message"]})'
        });

        var proxy = new Ext.data.HttpProxy({
            url: '/plugin/Contact/admin/history'
        });



        var readerFields = [
        {
            name: 'id'
        },
        {
            name: 'subject'
        },

        {
            name: 'sender'
        },

        {
            name: 'receiver'
        },

        {
            name: 'text'
        },
        {
            name: 'metadata'
        }
        ,
        {
            name: 'date'
        }
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
            root:'data',
            restful: false,
            proxy: proxy,
            reader: reader,
            writer: writer,
            sortInfo:{
                field: 'date',
                direction: "DESC"
            },
            groupField:'receiver'
        });

        // create the Grid
        this.ugrid = new Ext.grid.GridPanel({
            store: this.ustore,
            plugins: expander,
            view: group,
            columns: [expander,
            {
                id       :'id',
                header   : 'id',
                width    : 10,
                sortable : true,
                dataIndex: 'id',
                hidden:true
            },
            {
                id:'receiver',
                header   : t('receiver'),
                width    : 150,
                sortable : true,
                dataIndex: 'receiver',
                hidden:true
            },
            {
                header   : t('subject'),
                width    : 150,
                sortable : true,
                dataIndex: 'subject'
            },
            {
                id:'subject',
                header   : t('sender'),
                width    : 150,
                sortable : true,
                dataIndex: 'sender'
            },
            {
                header   : t('date'),
                width    : 150,
                sortable : true,
                dataIndex: 'date',
                renderer: function(d) {
                    var date = new Date(d * 1000);
                    return date.format("d/m/Y H:i:s");
                }
            }
            ],
            stripeRows: true,
            autoExpandColumn: 'subject',
            height: 350,
            width: 600,
            //iconCls : "subscriber_icon",
            //title: t('history of contact'),
            // config options for stateful behavior
            id:'ContactGrid'
        });

        this.ustore.load();
        return this.ugrid;
    }

});
