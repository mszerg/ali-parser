/**
 * 卖家新功能提示app，支持自定义HTML标签配置自动渲染和js调用。
 * @require http://style.aliexpress.com/css/5v/wholesale/me/component/tip.css
 * 自定义HTML标签配置, 建议结合天窗使用,在页面代码中输出：
 * <updatetip data-target-uri="*.alizoo.???" data-config="{target: get('headMenu_wantSell'), flag: 'twoFlag'}"></updatetip>
 * YUE.on(window, 'load', function() {
 *     YAHOO.util.Get.css('http://style.aliexpress.com/css/5v/wholesale/me/component/tip.css', {onSuccess: function() {
 *         YAHOO.util.Get.script('http://style.aliexpress.com/js/5v/app/ae-update-tip/me-update-tip.js', {onSuccess: function() {
 *             AE.use('app.aeUpdateTip.meUpdateTip');
 *         }});
 *     }});
 * });
 * uri匹配支持*和?通配符
 *
 * js调用:
 * AE.use('app.aeUpdateTip.meUpdateTip', function(Tip) {
 *     new Tip(yourConfig);
 * });
 * 
 * @author 徐飞 34884 fei.xf@alibaba-inc.com
 * TODO 需要增加只对根域名生效的支持，如：cn.ae.alibaba.com, www.aliexpress.com
 */
AE.define('app.aeUpdateTip.meUpdateTip', function() {
    // 简单实现渐进增强的localStorage
    var localStorage = {
        get: function() {},
        set: function() {},
        remove: function() {},
        init: function() {
            if (window.localStorage) {
                this.get = this._getLocalStorage;
                this.set = this._setLocalStorage;
                this.remove = this._removeLocalStorage;
            } else if (window.globalStorage) {
                this.get = this._getGlobalStorage;
                this.set = this._setGlobalStorage;
                this.remove = this._removeGlobalStorage;
            } else {
                this._elUserData = document.createElement('style');
                this._elUserData.addBehavior('#default#userData');
                document.getElementsByTagName('head')[0].appendChild(this._elUserData);
                this._elUserData.load('updateTipFlag');
                this.get = this._getUserData;
                this.set = this._setUserData;
                this.remove = this._removeUserData;
            }
        },
        _elUserData: null,
        _setLocalStorage: function(name, value) {
            window.localStorage.setItem(name, value);
        },
        _getLocalStorage : function(name) {
            return window.localStorage.getItem(name);
        },
        _removeLocalStorage : function(name) {
            window.localStorage.removeItem(name);
        },
        _setGlobalStorage : function(name, value) {
            window.globalStorage[document.domain][name] = value;
        },
        _getGlobalStorage : function(name) {
            return window.globalStorage[document.domain][name].value;
        },
        _removeGlobalStorage : function(name) {
            window.globalStorage[document.domain][name] = null;
        },
        _setUserData : function(name, value) {
            this._elUserData.setAttribute(name, value);
            this._elUserData.save('updateTipFlag');
        },
        _getUserData : function(name) {
            return this._elUserData.getAttribute(name);
        },
        _removeUserData : function(name) {
            this._elUserData.removeAttribute(name);
            this._elUserData.save('updateTipFlag');
        }
    };
    localStorage.init();
    /**
     * Simple JavaScript Templating
     * John Resig - http://ejohn.org/ - MIT Licensed
     * @param  {String} tpl  HTML模板
     * @param  {Object} data 用于渲染的数据
     * @return {String} 拼装好的HTML代码片段
     */
    var tplUtil = function(tpl, data) {
        var fn = new Function('data',
            'var p = [];' +
            "with(data) {p.push('" +
            tpl
            .replace(/[\r\t\n]/g, " ")
            .split("<%").join("\t")
            .replace(/((^|%>)[^\t]*)'/g, "$1\r")
            .replace(/\t=(.*?)%>/g, "',$1,'")
            .split("\t").join("');")
            .split("%>").join("p.push('")
            .split("\r").join("\\'") +
            "');} return p.join('');");
        return fn(data);
    };

    // 提示tip主体app
    var Tip = function(config) {
        this._config = {
            name: 'me-update-tip', // {String} optional tip的名称，用于标记tip，就像人名一样
            title: '', // {String} optional tip的标题
            content: 'tip content', // {String} tip的正文
            target: get('headMenu_default'), // {String|HTMLElement} tip的目标对象， 如果是String要求能被eval出一个DOM
            direction: 'top', // {String} optional top|left tip的箭头方向
            iGotIt: true, // {Boolean} optional 是否显示我知道了
            closeIcon: true, // {Boolean} optional 是否显示关闭图标
            offset: [20, 35], // {Array} optional 相对target左上角的偏移，
            flag: 'testFlag' // {String} optional 用于标记"我知道了"的标记名
        };

        this._cache = {
            tipDom: null, // tip的dom元素
            target: null // tip的目标dom元素
        };

        this._config = YL.merge(this._config, config || {});
        this.init();
    };
    Tip.prototype = {
        // tip的json tpl模板，基于config渲染
        TPL: '<div class="tip-me <% if (title) {%> tip-me-hd <% } %>" data-name="<%=name%>" style="display: none;"> <% if (title) { %> <h3 class="tip-header"><%=title%></h3> <% } %> <div class="tip-text"> <%=content%> <% if (iGotIt && flag) { %> <a class="got-it" href="javascript:;">我知道了</a> <% } %> </div> <div class="tip-arrow tip-arrow-<%=direction%>"></div> <% if (closeIcon) { %> <a class="tip-close" href="javascript:;"></a> <% } %> </div>',
        // 初始化
        init: function() {
            if (this._config.flag && localStorage.get(this._config.flag) === '0') return;
            this._cache.target = typeof this._config.target === 'string' ? eval(this._config.target) : this._config.target;
            if (!this._config.target || this._config.target.nodeType != 1) return;
            this._createTip();
            this._bind();
            this._setStyle();
        },
        // 创建Tip dom并追加到body中
        _createTip: function() {
            var frag = document.createElement('div');
            frag.innerHTML = tplUtil(this.TPL, this._config);
            document.body.appendChild(this._cache.tipDom = frag.children[0]);
        },
        // 事件绑定，基于事件代理
        _bind: function() {
            YUE.on(this._cache.tipDom, 'click', function(ev) {
                var target = YUE.getTarget(ev, true);
                if (YUD.hasClass(target, 'got-it')) this._gotIt();
                if (YUD.hasClass(target, 'tip-close')) this._close();
            }, null, this);
        },
        // 设置tip的css
        _setStyle: function() {
            var xy = YUD.getXY(this._cache.target), off = this._config.offset, tipDom = this._cache.tipDom;
            tipDom.style.position = 'absolute';
            tipDom.style.left = xy[0] + off[0] + 'px';
            tipDom.style.top = xy[1] + off[1] + 'px';
            tipDom.style.display = 'block';
        },
        // 点击'我知道了'的callback
        _gotIt: function() {
            localStorage.set(this._config.flag, '0');
            this._close();
        },
        // 关闭tip
        _close: function() {
            this._destroy();
        },
        // 销毁实例
        _destroy: function() {
            YUE.purgeElement(this._cache.tipDom);
            document.body.removeChild(this._cache.tipDom);
            for (var key in this) {
                if (this.hasOwnProperty(key)) delete(this[key]); 
            }
        }
    };

    var tips = document.getElementsByTagName('updatetip');
    YUD.batch(tips, function(tip) {
        var config;
        var targetURI = decodeURIComponent(tip.getAttribute('data-target-uri'));
        if (!targetURI) return;
        var uriRegStr = targetURI.replace(/[\\\.\^\$\|\?\*\+\(\)\[\]\{\}]/g, function(meta) {
                switch(meta) { // 将元字符替换
                    case '*': return '.*'; // 实现通配符'*'
                    case '?': return '.{1}'; // 实现通配符'?'
                    default : return '\\' + meta; // 将其他元字符转义
                }
            });
        var uriReg = new RegExp(uriRegStr, 'g');

        if (!uriReg.test(decodeURIComponent(document.location.href))) return;
        try {
            config = eval('(' + tip.getAttribute('data-config') + ')');
            if (config) new Tip(config);
        } catch(ex) {}
    });

    return Tip;
});