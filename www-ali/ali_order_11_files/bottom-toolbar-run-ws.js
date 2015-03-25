/**
 * 加载toolbar,加载toolbar里的插件
 */
;(function() {
    function getConfig(){
        var webAtmBar = {
                name: 'webAtmBar',
                srcUrl: 'http://style.aliexpress.com/js/5v/run/intl_webatm/v1/deploy/webatm-bar.js'
            },
            config = {
                plugins: []
            };

        //------特殊页面的配置在这里写

        config.plugins.push( webAtmBar );

        return config;

    }
    if (typeof AE.run === 'undefined') {
        AE.namespace('AE.run');
    }
    if (typeof AE.run.webatm === 'undefined') {
        AE.namespace('AE.run.webatm');
    }

    var webAtmApi = 'http://style.aliunicorn.com/js/5v/??' +
        'util/stylesheet.js,' +
        'app/bottom_toolbar/bottom_toolbar.js';
    YAHOO.util.Get.script(webAtmApi, {
        onSuccess : function () {
            try {
                if (typeof AE.run.webatm.config === "undefined") {
                    AE.run.webatm.config = {};
                }
                //AE.app.bottomToolbar.init( getConfig() );
            } catch(e) {}
        }
    });
})();