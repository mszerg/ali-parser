var globalImgServer="http://style.alibaba.com";location.protocol==="https:"&&(globalImgServer="https://ipaystyle.alibaba.com");function sk_dmtracking_core(){if(dmtrack.isDmTracked)return;dmtrack_pageid=dmtrack.getRand();try{dmtrack.acookieSupport&&dmtrack.acookie()}catch(e){var t=dmtrack.getErrInfo(e),n=dmtrack.err_url+"?type=acookie&exception="+encodeURIComponent(t.toString());dmtrack.SendMessage(n)}dmtrack.deleteUselessCookie();var r=dmtrack.uaMonitor().extraBrowser,i=r.name.toLowerCase();window.postMessage?dmtrack.getBeaconCookieId(dmtrack.tracking):dmtrack.tracking()}function sk_dmtracking(){document.webkitVisibilityState=="prerender"?document.addEventListener("webkitvisibilitychange",function(){sk_dmtracking_core()},!1):sk_dmtracking_core()}if(!dmtrack)var dmtrack={};dmtrack.beaconStartTime||(window.beaconStartTime=(new Date).getTime()),dmtrack.send_head=document.location.protocol+"//",dmtrack.ver=40,dmtrack.err_url=dmtrack.send_head+"stat.china.alibaba.com/dw/error.html",dmtrack.tracelog_url=dmtrack.send_head+"tracelog.www.alibaba.com/null.gif",dmtrack.feedback_url=dmtrack.send_head+"page.china.alibaba.com/shtml/static/forfeedbacklog.html",dmtrack.beacon_url=dmtrack.send_head+"dmtracking2.alibaba.com/b.jpg",dmtrack.beacon2_url=dmtrack.send_head+"dmtracking2.alibaba.com/c.jpg",dmtrack.acookieSupport=dmtrack.send_head!=="https://"?!0:!1,dmtrack.getCookieFromAcookie=!1,dmtrack.isCheckLogin=!1,dmtrack.isChangePid=!0,function(e,t){function i(){var t=e.name;if(t!==""){var i=t.match(/^nameStorage:\{(.*)\}$/);if(i===null)return;i[1].replace(/([^:]+):([^,]+)(?:,|$)/g,function(e,t,r){t=decodeURIComponent(t),r=decodeURIComponent(r),n[t]=r})}r=!0}function s(e,t){r===!0&&(n[e]=t.toString(),f())}function o(e){if(r===!0){var i=n[e];return i!==t?i:null}}function u(e){r===!0&&(delete n[e],f())}function a(){r===!0&&(n={},f())}function f(){var t=[],r;for(var i in n)r=n[i],t.push(encodeURIComponent(i)+":"+encodeURIComponent(r));e.name="nameStorage:{"+t.join(",")+"}"}if(e.nameStorage!==t)return;var n={},r=!1;i(),e.nameStorage={supported:r,getItem:o,setItem:s,removeItem:u,clear:a}}(window),location.protocol==="https:"&&nameStorage.setItem("referer",location.href),function(){var e=[],t="cachedBeaconImg";dmtrack.cacheStatImg=function(n){nameStorage.supported===!0?(e.push(encodeURIComponent(n)),nameStorage.setItem(t,e.join(","))):dmtrack.sendImg(n)},dmtrack.sendCachedStatImgs=function(){var e=nameStorage.getItem(t);nameStorage.removeItem(t);if(e){var n=e.split(","),r;for(var i=0,s=n.length;i<s;i++)r=decodeURIComponent(n[i]),dmtrack.sendImg(r)}}}(),dmtrack.SendMessage=function(e,t,n,r,i,s){function o(e){var t="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",n,r,i,s,o,u;i=e.length,r=0,n="";while(r<i){s=e.charCodeAt(r++)&255;if(r==i){n+=t.charAt(s>>2),n+=t.charAt((s&3)<<4),n+="==";break}o=e.charCodeAt(r++);if(r==i){n+=t.charAt(s>>2),n+=t.charAt((s&3)<<4|(o&240)>>4),n+=t.charAt((o&15)<<2),n+="=";break}u=e.charCodeAt(r++),n+=t.charAt(s>>2),n+=t.charAt((s&3)<<4|(o&240)>>4),n+=t.charAt((o&15)<<2|(u&192)>>6),n+=t.charAt(u&63)}return n}var u="",a="",f=new Date,l=e.length;try{if(t){for(var c in t)u+=c.toString()+"="+t[c].toString()+"&";u=u.substring(0,u.length-1)}u=o(u);if(n){for(var c in n)a+=c.toString()+"="+n[c].toString()+"&";a=a.substring(0,a.length-1)}if(e.indexOf("?")==-1)!u&&a?e+="?"+a+"&ver="+dmtrack.ver+"&time="+f.getTime():!a&&u?e+="?"+u+"&ver="+dmtrack.ver+"&time="+f.getTime():a&&u&&(e+="?"+u+"&"+a+"&ver="+dmtrack.ver+"&time="+f.getTime());else{var h=e.split("?");!u&&a?h[1]?e=""+h[0]+"?"+h[1]+"&"+a+"&ver="+dmtrack.ver+"&time="+f.getTime():e=""+h[0]+"?"+h[1]+a+"&ver="+dmtrack.ver+"&time="+f.getTime():!a&&u?h[1]?e=""+h[0]+"?"+u+"&"+h[1]+"&ver="+dmtrack.ver+"&time="+f.getTime():e=""+h[0]+"?"+u+h[1]+"&ver="+dmtrack.ver+"&time="+f.getTime():a&&u&&(h[1]?e=""+h[0]+"?"+u+"&"+h[1]+"&"+a+"&ver="+dmtrack.ver+"&time="+f.getTime():e=""+h[0]+"?"+u+h[1]+"&"+a+"&ver="+dmtrack.ver+"&time="+f.getTime())}e.length==l&&(e.indexOf("?")==-1?e+="?ver="+dmtrack.ver+"&time="+f.getTime():e.indexOf("?")==e.length-1?e+="ver="+dmtrack.ver+"&time="+f.getTime():e+="&ver="+dmtrack.ver+"&time="+f.getTime()),r=="docwrite"?document.write("<img style='display:none' src = "+e+">"):(r=="newimg"||!r)&&dmtrack.send_head.indexOf("http")!=-1&&dmtrack.send_url!=""&&(i===!0?dmtrack.cacheStatImg(e):dmtrack.sendImg(e,s))}catch(p){var d=dmtrack.getErrInfo(p),v=dmtrack.err_url+"?type=send&exception="+encodeURIComponent(d.toString());r=="docwrite"?document.write("<img style='display:none' src = "+v+">"):(r=="newimg"||!r)&&dmtrack.sendImg(v)}},dmtrack.sendImg=function(e,t){var n=new Image;n.onload=function(){n=null,t&&t()},n.src=e},dmtrack.getRand=function(){var e;try{e=dmtrack_pageid}catch(t){e=""}if(!e)e="001";else{var n=e.substring(0,16),r=e.substring(16,26),i=/^[\-+]?[0-9]+$/.test(r)?parseInt(r,10):r;e=n+i.toString(16)}var s=(new Date).getTime(),o=[e,s.toString(16)].join("");for(var u=1;u<10;u++){var a=parseInt(Math.round(Math.random()*1e10),10).toString(16);o+=a}return o=o.substr(0,42),o},String.prototype.Trim=function(){return this.replace(/(^\s*)|(\s*$)/g,"")},dmtrack.get_cookie=function(e){var t="(?:; )?"+e+"=([^;]*);?",n=new RegExp(t);if(n.test(document.cookie)){var r=decodeURIComponent(RegExp.$1);return r.Trim().length>0?r:"-"}return"-"},dmtrack.set_cookie=function(e,t,n,r,i,s){var o=e+"="+encodeURIComponent(t);n&&(o+="; expires="+n.toGMTString()),r&&(o+="; path="+r),i&&(o+="; domain="+i),s&&(o+="; secure"),document.cookie=o},dmtrack.del_cookie=function(e,t){var n=document.domain,r=n.split("."),i="";dmtrack.set_cookie(e,"",new Date(0),t);for(var s=r.length-1;s>0;s--)i="."+r[s]+i,dmtrack.set_cookie(e,"",new Date(0),t,i);i="."+r[s]+i,dmtrack.set_cookie(e,"",new Date(0),t,i)},dmtrack.getDomainCookie=function(e,t){e=e.replace(/\"/g,"");var n=dmtrack.get_cookie(t),r=e.split("|");r[0]&&r[0]=="-"&&(r=[]),r.push(t+"="+n);var i=r.join("|");return i},dmtrack.getMetaByName=function(e){var t=document.getElementsByTagName("head")[0],n=t.getElementsByTagName("meta"),r;for(var i=0,s=n.length;i<s;i++){r=n[i];if(r.getAttribute("name")===e)return r.getAttribute("content")}return!1},dmtrack.getSpmAB=function(){var e=dmtrack.getMetaByName("spm-id");if(e===!1){var t=dmtrack.getMetaByName("data-spm"),n=document.body,r=n.getAttribute("data-spm");if(!t||!r)return!1;e=t+"."+r}return e},dmtrack.tracking=function(e,t){dmtrack.sendCachedStatImgs();try{window.aplusExParams={};var n=document.referrer;location.protocol==="http:"&&n===""&&(n=nameStorage.getItem("referer")||"");try{n=""==n?opener.location:n,n=""==n?"-":n}catch(r){n="-"}aplusExParams.refer=n,n=encodeURI(n);var i="GET",s=document.URL.indexOf("://"),o=document.URL.substr(s+2);o=encodeURI(o);var u=dmtrack.get_cookie("ali_apache_track");dmtrack.getCookieFromAcookie&&(u=dmtrack.getDomainCookie(u,"cna")),dmtrack.isgetApacheId&&(u=dmtrack.getDomainCookie(u,"ali_apache_id"));var a=dmtrack.beacon_url;try{dmtrack_c||(dmtrack_c="{-}")}catch(r){dmtrack_c="{-}"}dmtrack.isCheckLogin&&dmtrack._checkLogin(),dmtrack_c=dmtrack.addCookieC(),dmtrack.redirect_c(),dmtrack.isChangePid&&dmtrack.change_pid();var f=dmtrack.uaMonitor(),l=f.extraBrowser,c=l.name+l.ver.toFixed(1),h=f.system.name,p=window.screen.width+"*"+window.screen.height,d=window.navigator.language||window.navigator.browserLanguage,v=c+"|"+h+"|"+p+"|"+d,m={pageid:dmtrack_pageid,sys:v};e&&(m.ali_beacon_id=e,m.inc=t);var g=[],y=dmtrack.getSpmAB();y!==!1&&g.push("spmab="+y),g=g.length>0?g.join("|"):"-";if(dmtrack.acookieSupport===!1){aplusExParams.dmtrack_c=dmtrack_c,aplusExParams.pageid=dmtrack_pageid,aplusExParams.sys=v;var b=goldlog.getCookie("ali_beacon_id");b&&(aplusExParams.ali_beacon_id=b);var w=goldlog.getCookie("ali_apache_id");w&&(aplusExParams.ali_apache_id=w);var E=goldlog.getCookie("ali_apache_track");E&&(aplusExParams.ali_apache_track=E);var S=goldlog.getCookie("ali_apache_tracktmp");S&&(aplusExParams.ali_apache_tracktmp=S),dmtrack.aplus()}dmtrack.SendMessage(a,{p:"{"+dmtrack.profile_site+"}",u:"{"+o+"}",m:"{"+i+"}",s:"{200}",r:"{"+n+"}",a:"{"+u+"}",b:"{"+g+"}",c:dmtrack_c},m)}catch(x){var T=dmtrack.getErrInfo(x),N=dmtrack.err_url+"?type=dmtrack&exception="+encodeURIComponent(T.toString());dmtrack.SendMessage(N)}dmtrack.isDmTracked=!0},dmtrack.redirect_c=function(){var e="aliBeacon_bcookie",t=dmtrack.get_cookie(e);t=t.replace(/ali_resin_trace=/,""),"{-}"==dmtrack_c?dmtrack_c="{"+t+"}":(dmtrack_c=dmtrack_c.split("}"),"-"==t?dmtrack_c[1]="}":(dmtrack_c[1]="|",dmtrack_c.push(t),dmtrack_c.push("}")),dmtrack_c=dmtrack_c.join("")),dmtrack.del_cookie(e,"/")},dmtrack.change_pid=function(){var e=document.domain;-1!=e.indexOf("alibado.com")&&(dmtrack.profile_site=4),window.dmconf&&window.dmconf.pid&&(dmtrack.profile_site=window.dmconf.pid)},dmtrack.beacon_click=function(e,t,n){try{var r=t;if(r=="-"||!r)r=encodeURI(document.URL);d=new Date;var i=e.indexOf("://"),s=e.substr(i+2),o="GET",u=dmtrack.get_cookie("ali_apache_track"),a=dmtrack.beacon2_url,f=[];if(n)for(var l in n)f.push(l+"="+n[l]);f.length==0&&f.push("-"),dmtrack.SendMessage(a,{p:"{"+dmtrack.profile_site+"}",u:"{"+s+"}",m:"{"+o+"}",s:"{200}",r:"{"+r+"}",a:"{"+u+"}",b:"{-}",c:"{"+f.join("|")+"}"})}catch(c){var h=dmtrack.getErrInfo(c),p=dmtrack.err_url+"?type=beaconclick&exception="+encodeURIComponent(h.toString());dmtrack.SendMessage(p)}},dmtrack.tracelog=function(e){var t=dmtrack.tracelog_url,n={tracelog:e};dmtrack.clickstat(t,n)},dmtrack.dotstat=function(e,t,n){var r=dmtrack.dotstat_url;if(r)try{window.dmtrack_pageid||(window.dmtrack_pageid="");var i=typeof t;if(i==="undefined")t={};else if(i==="string"){var s={};t.replace(/(\w+)\s*=\s*([^&]*)(&|$)/g,function(e,t,n){s[t]=n}),t=s}t.id=e,t.st_page_id=window.dmtrack_pageid;var o=dmtrack.get_cookie("ali_beacon_id");o!=""&&o!="-"&&(t.ali_beacon_id=o,t.inc=0),dmtrack.SendMessage(r,{},t,"",n)}catch(u){var a=dmtrack.getErrInfo(u),f=dmtrack.err_url+"?type=clickstat&exception="+encodeURIComponent(a.toString());dmtrack.SendMessage(f)}},dmtrack.clickstat=function(e,t,n){try{window.dmtrack_pageid||(window.dmtrack_pageid="");var r=dmtrack.get_cookie("ali_beacon_id"),i={};if(typeof t=="string"){"?"==e.substring(e.length-1,e.length)&&(e=e.replace("?","")),"?"==t.substring(0,1)&&(t=t.replace("?",""));var s=t.split("&");for(var o=0;o<s.length;o++){var u=s[o].split("="),a=u[0],f=u[1];i[a]=f}i.st_page_id=window.dmtrack_pageid,r!=""&&r!="-"&&(i.ali_beacon_id=r,i.inc=0),dmtrack.SendMessage(e,{},i,"",n)}else if(typeof t=="object"){for(var l in t)i[l]=t[l];i.st_page_id=window.dmtrack_pageid,r!=""&&r!="-"&&(i.ali_beacon_id=r,i.inc=0),dmtrack.SendMessage(e,{},i,"",n)}}catch(c){var h=dmtrack.getErrInfo(c),p=dmtrack.err_url+"?type=clickstat&exception="+encodeURIComponent(h.toString());dmtrack.SendMessage(p)}},dmtrack.flash_dmtracking=function(e,t){try{dmtrack_pageid=dmtrack.getRand();var n="GET",r=dmtrack.get_cookie("ali_apache_track"),i=dmtrack.beacon_url;try{dmtrack_c||(dmtrack_c="{-}")}catch(s){dmtrack_c="{-}"}dmtrack.SendMessage(i,{p:"{"+dmtrack.profile_site+"}",u:"{"+e+"}",m:"{"+n+"}",s:"{200}",r:"{"+t+"}",a:"{"+r+"}",b:"{-}",c:dmtrack_c},{pageid:dmtrack_pageid,dmtrack_type:"xuanwangpu"})}catch(o){var u=dmtrack.getErrInfo(o),a=dmtrack.err_url+"?type=flash&exception="+encodeURIComponent(u.toString());dmtrack.SendMessage(a)}dmtrack.isDmTracked=!0},dmtrack.feedback=function(e){var t=dmtrack.feedback_url;e.indexOf("?")>-1?t+=e:t=t+"?"+e;var n="";try{n=document.cookie.match(/track_cookie[^;]*cosite=(\w+)/)[1]}catch(r){}return n.length>0&&(t=t+"&fromsite="+n),dmtrack.beacon_click(t,"-"),dmtrack.SendMessage(t,{},{}),!0},dmtrack.feedbackTraceLog=function(e,t){return!0},dmtrack.acookie=function(){function e(e){return Math.floor(Math.random()*e)+1}var t=escape(document.referrer),n=dmtrack.cmap_url,r=dmtrack.cnamap_url,i=dmtrack.get_cookie("cna");i!==""&&i!=="-"&&n&&dmtrack.sendImg(n+"?cna="+encodeURIComponent(i)),dmtrack.SendMessage(dmtrack.acookie_url,{},{cache:e(9999),pre:t},"",!1,function(){var e=dmtrack.get_cookie("cna");if(e!==i){dmtrack.dotstat(17777,{ext:"cnatime="+((new Date).getTime()-beaconStartTime)}),n&&dmtrack.sendImg(n+"?cna="+encodeURIComponent(e));if(r){var t=dmtrack.get_cookie("ali_beacon_id");dmtrack.sendImg(r+"?cna="+encodeURIComponent(e)+"&pageid="+encodeURIComponent(dmtrack_pageid)+"&ali_beacon_id="+encodeURIComponent(t))}}}),(i===""||i==="-")&&dmtrack.dotstat(17776)},dmtrack.getErrInfo=function(e){var t="";for(var n in e)t+=n+"="+e[n]+";";return t},dmtrack.getBigDomain=function(){var e=document.domain.toLowerCase(),t="";return e.indexOf("aliexpress.com")!==-1?t=".aliexpress.com":e.indexOf("alibaba.com")!==-1&&(t=".alibaba.com"),t},dmtrack.deleteUselessCookie=function(){dmtrack.del_cookie("ali_apache_sid","/")},dmtrack.getBeaconCookieId=function(e){var t="ali_beacon_id",n=dmtrack.get_cookie(t),r=dmtrack.getBigDomain(),i=0;if(r==""){e();return}if(n==""||n=="-"){function s(s){data=s.data;if(/^https?:\/\/(style|stylessl)\.(alibaba|aliexpress)\.com/.test(s.origin)===!0&&typeof data=="string"&&data.substring(0,17)==="get_beacon_cookie"){n=data.substring(18);if(n==""||n=="-"){n=dmtrack.get_cookie(t);if(n==""||n=="-")n=dmtrack.generateCookieId(),i=1}var o=new Date;o.setTime(o.getTime()+31536e7),dmtrack.set_cookie(t,n,o,"/",r),e(n,i)}}window.addEventListener?window.addEventListener("message",s,!1):window.attachEvent("onmessage",s);var o=document.createElement("iframe"),u="",a=document.domain,f=/alibaba\.com$/i.test(a);u=(dmtrack.send_head==="https://"?"stylessl":"style")+"."+(f?"aliexpress":"alibaba")+".com/js/beacon-cookie.html?v=20150204",o.src=dmtrack.send_head+u,o.style.width="1px",o.style.height="1px",o.style.position="absolute",o.style.top="-10000px",o.style.left="-10000px",o.style.visibility="hidden",document.body.appendChild(o)}else e(n,i)},dmtrack.generateCookieId=function(){var e="ali_apache_id",t=dmtrack.get_cookie(e);return t},dmtrack.getGMTUTCTime=function(){var e=new Date,t=e.getTime();return t},dmtrack.isSupportCookie=function(){var e="testIsSupportCookie",t=document.domain,n="";return dmtrack.set_cookie(e,"true","","/",t),n=dmtrack.get_cookie(e),n=="true"?(dmtrack.del_cookie(e,"/"),!0):!1},dmtrack._checkLogin=function(){function e(){if(typeof window._last_loginid_info!="undefined")return window._last_loginid_info;var e=function(e){var t="";try{t=document.cookie.match("(?:^|;)\\s*"+e+"=([^;]*)")}catch(n){}finally{return t?unescape(t[1]):""}};return window._last_loginid_info=e("__cn_logon__")&&e("__cn_logon__")==="true"?e("__last_loginid__"):!1,window._last_loginid_info}window.beaconData||(window.beaconData={}),e()!=0?window.beaconData.c_signed=1:window.beaconData.c_signed=0},dmtrack.addCookieC=function(){var tmp=dmtrack_c.substring(1,dmtrack_c.length-1),result=[];tmp!="-"&&(result=tmp.split("|"));for(var i in window.beaconData)result.push(i+"="+window.beaconData[i]);try{var intl_unc_f=dmtrack.get_cookie("uns_unc_f"),match=intl_unc_f.match(/(?:^|&)trfc_i=(.*?)(&|$)/);match!==null&&result.push("trfc_i="+match[1])}catch(e){}try{var xman_us_f=dmtrack.get_cookie("xman_us_f");if(xman_us_f!=""||xman_us_f!="-"){var zeroIndex=0,endIndex=xman_us_f.length-1,quoFirstIndex=xman_us_f.indexOf('"'),quoEndIndex=xman_us_f.lastIndexOf('"');endIndex==quoEndIndex&&(xman_us_f=xman_us_f.substring(0,endIndex)),zeroIndex==quoFirstIndex&&(xman_us_f=xman_us_f.substring(1));var alicanceCookieKey="x_as_i",alicanceCookieValue="",cookieArrs=xman_us_f.split("&"),cookieArr=[];if(cookieArrs.length>0)for(var i=0;i<cookieArrs.length;i++){cookieArr=cookieArrs[i].split("=");if(cookieArr[0]==alicanceCookieKey){alicanceCookieValue=cookieArr[1];break}}if(alicanceCookieValue!=""){alicanceCookieValue=decodeURIComponent(alicanceCookieValue);var alicanceDataObject=eval("("+alicanceCookieValue+")"),alicanceSrcKey="src",allicanceAfKey="af",allicanceCvKey="cv",allicanceTpKey="tp1",alicanceCptKey="cpt",alicanceVdKey="vd",alicanceAffiliateKey="affiliateKey",allicanceSrcValue=alicanceDataObject[alicanceSrcKey],allicanceAfValue=alicanceDataObject[allicanceAfKey],allicanceCvValue=alicanceDataObject[allicanceCvKey],allicanceTpValue=alicanceDataObject[allicanceTpKey],alicanceCptValue=alicanceDataObject[alicanceCptKey],alicanceVdValue=alicanceDataObject[alicanceVdKey],alicanceAffiliateValue=alicanceDataObject[alicanceAffiliateKey];allicanceSrcValue&&allicanceSrcValue!=""&&(result.push(alicanceSrcKey+"="+allicanceSrcValue),result.push(allicanceAfKey+"="+allicanceAfValue),result.push(allicanceCvKey+"="+allicanceCvValue),result.push(allicanceTpKey+"="+allicanceTpValue),result.push(alicanceCptKey+"="+alicanceCptValue),result.push(alicanceVdKey+"="+alicanceVdValue),result.push(alicanceAffiliateKey+"="+alicanceAffiliateValue))}}}catch(e){}try{var aep_usuc_f=dmtrack.get_cookie("aep_usuc_f");if(aep_usuc_f!=""||aep_usuc_f!="-"){var aepCookieValue=encodeURIComponent(aep_usuc_f);result.push("aep_usuc_f="+aepCookieValue)}}catch(e){}return result=result.length==0?"-":result.join("|"),"{"+result+"}"},dmtrack.uaMonitor=function(){var e={trident:0,webkit:0,gecko:0,presto:0,khtml:0,name:"other",ver:null},t={ie:0,firefox:0,chrome:0,safari:0,opera:0,konq:0,name:"other",ver:null},n={name:"",ver:null},r={win:!1,mac:!1,x11:!1,name:"other"},i="other",s=navigator.userAgent,o=navigator.platform,u,a,f=function(e){var t=0;return parseFloat(e.replace(/\./g,function(){return t++===0?".":""}))};if(window.opera)e.ver=t.ver=f(window.opera.version()),e.presto=t.opera=parseFloat(e.ver),e.name="presto",t.name="opera";else if(/AppleWebKit\/(\S+)/.test(s)){e.ver=f(RegExp.$1),e.webkit=e.ver,e.name="webkit";if(/Chrome\/(\S+)/.test(s))t.ver=f(RegExp.$1),t.chrome=t.ver,t.name="chrome";else if(/Version\/(\S+)/.test(s))t.ver=f(RegExp.$1),t.safari=t.ver,t.name="safari";else{var l=1;e.webkit<100?l=1:e.webkit<312?l=1.2:e.webkit<412?l=1.3:l=2,t.safari=t.ver=l,t.name="safari"}}else/KHTML\/(\S+)/.test(s)||/Konqueror\/([^;]+)/.test(s)?(e.ver=t.ver=f(RegExp.$1),e.khtml=t.konq=e.ver,e.name="khtml",t.name="konq"):/rv:([^\)]+)\) Gecko\/\d{8}/.test(s)?(e.ver=f(RegExp.$1),e.gecko=e.ver,e.name="gecko",/Firefox\/(\S+)/.test(s)&&(t.ver=f(RegExp.$1),t.firefox=t.ver,t.name="firefox")):/MSIE ([^;]+)/.test(s)?(e.ver=t.ver=f(RegExp.$1),e.trident=t.ie=e.ver,e.name="trident",t.name="ie"):/trident.+rv:\s*(\d+(\.\d+)?)\) like gecko/i.test(s)&&(e.ver=t.ver=f(RegExp.$1),e.trident=t.ie=e.ver,e.name="trident",t.name="ie");n.name=t.name,n.ver=t.ver;if(u=s.match(/360SE/))n.name="se360",n.ver=3;else if((u=s.match(/Maxthon/))&&(a=window.external)){n.name="maxthon";try{n.ver=f(a.max_version)}catch(c){n.ver=.1}}else if(u=s.match(/TencentTraveler\s([\d.]*)/))n.name="tt",n.ver=f(u[1])||.1;else if(u=s.match(/TheWorld/))n.name="theworld",n.ver=3;else if(u=s.match(/SE\s([\d.]*)/))n.name="sougou",n.ver=f(u[1])||.1;r.win=o.indexOf("Win")==0,r.mac=o.indexOf("Mac")==0,r.x11=o=="X11"||o.indexOf("Linux")==0;if(r.win){if(/Win(?:dows )?([^do]{2})\s?(\d+\.\d+)?/.test(s))if(RegExp["$1"]=="NT")switch(RegExp.$2){case"5.1":r.win="XP";break;case"6.1":r.win="7";break;case"5.0":r.win="2000";break;case"6.0":r.win="Vista";break;default:r.win="NT"}else RegExp["$1"]=="9x"?r.win="ME":r.win=RegExp.$1;r.name="windows"+r.win}r.mac&&(r.name="mac"),r.x11&&(r.name="x11");if(r.win=="CE")i="windows mobile";else if(/ Mobile\//.test(s))i="apple";else if(u=s.match(/NokiaN[^\/]*|Android \d\.\d|webOS\/\d\.\d/))i=u[0].toLowerCase();return{engine:e,browser:t,extraBrowser:n,system:r,mobile:i}},function(){function B(e){var t=[["alibaba.com","7"],["alibabagroup.com","p"]],n=t.length,r,i;for(r=0;r<n;r++){i=t[r];if(j(e,i[0]))return i[1]}return"g"}function j(e,t){return e.indexOf(t)>-1}function F(e,t){return e.indexOf(t)===0}function I(e,t){var n=t||"";if(e)try{n=decodeURIComponent(e)}catch(r){}return n}function q(e){var t=[],n,r;for(n in e)e.hasOwnProperty(n)&&(r=""+e[n],t.push(F(n,E)?r:n+"="+encodeURIComponent(r)));return t.join("&")}function R(e){var t=[],n,r,i,s=e.length;for(i=0;i<s;i++)n=e[i][0],r=e[i][1],t.push(F(n,E)?r:n+"="+encodeURIComponent(r));return t.join("&")}function U(e,t){for(var n in t)t.hasOwnProperty(n)&&(e[n]=t[n]);return e}function z(e){var t=e.split("&"),n=0,r=t.length,i,s={};for(;n<r;n++)i=t[n].split("="),s[i[0]]=I(i[1]);return s}function W(e){return typeof e=="undefined"}function X(e){return Object.prototype.toString.call(e)==="[object Array]"}function V(e,t){return e&&e.getAttribute?e.getAttribute(t)||"":""}function $(){return N=N||t.getElementsByTagName("head")[0],C||(N?C=N.getElementsByTagName("meta"):[])}function J(e){var n=t.cookie.match(new RegExp("\\b"+e+"=([^;]+)"));return n?n[1]:""}function K(){return Math.floor(Math.random()*268435456).toString(16)}function Q(){var e=$(),t,n,r,i;for(t=0,n=e.length;t<n;t++)r=e[t],i=V(r,"name"),i==S&&(M=V(r,x))}function G(e){var t=$(),n,r,i,s,o,u;if(t)for(n=0,r=t.length;n<r;n++){s=t[n],o=V(s,"name");if(o==e)return D=V(s,"content"),D.indexOf(":")>=0&&(i=D.split(":"),M=i[0]=="i"?"i":"u",D=i[1]),u=V(s,x),u&&(M=u=="i"?"i":"u"),_=D,w}return b}function Y(){if(!W(_))return _;if(A&&O)return A=A.replace(/^{(\w+)}$/g,"$1"),O=O.replace(/^{(\w+)}$/g,"$1"),L=w,_=A+"."+O,Q(),P.spm_ab=[A,O],_;var e=t.getElementsByTagName("head")[0],n;G(S)||G("spm-id"),_=_||a;if(!_)return _;var r=t.getElementsByTagName("body"),i;return n=_.split("."),P.spm_ab=n,r=r&&r.length?r[0]:null,r&&(i=V(r,S),i&&(_=n[0]+"."+i,P.spm_ab=[n[0],i])),_}function Z(t,n,r){t[y]((v?"on":"")+n,function(t){t=t||e.event;var n=t.target||t.srcElement;r(t,n)},b)}function et(e,t){if(!t)return;return ut()?at({url:ft(e,t),js:r,referrer:s.href}):P.send(e,t)}function tt(){return E+Math.random()}function nt(e){var t=e.match(new RegExp("\\?.*spm=([\\w\\.\\-\\*]+)")),n;return t&&(n=t[1])&&n.split(".").length==4?n:null}function rt(e,t){var n,r=t.length,i,s,o;for(n=0;n<r;n++)i=t[n],s=i[0],o=i[1],o&&e.push([s,o])}function it(e,n){var r=t.createElement("script");r.type="text/javascript",r.async=!0,r.src=o?n:e,t.getElementsByTagName("head")[0].appendChild(r)}function st(e,t){var n=document.createElement("iframe");n.style.width="1px",n.style.height="1px",n.style.position="absolute",n.style.display="none",n.src=e,t&&(n.name=t);var r=document.getElementsByTagName("body")[0];return r.appendChild(n),n}function ot(){var t=!1;if("localStorage"in e&&e.localStorage!=null)try{localStorage.setItem("test","test"),localStorage.removeItem("test"),t=!0}catch(n){}return t}function ut(){return!1;var t,n}function at(e){var t="http://cdn.mmstat.com/aplus-proxy.html?v=20130115";st(t,JSON.stringify(e))}function ft(e,t){var n=e.indexOf("?")==-1?"?":"&",r=t?X(t)?R(t):q(t):"";return r?e+n+r:e}function lt(e){try{var t=window.aplusExParams;if(t)for(var n in t)if(t.hasOwnProperty(n)){var r=t[n];e.push([n,r])}}catch(i){}}var e=window,t=document,n="g_aplus_loaded";if(!t.getElementsByTagName("body").length){setTimeout(arguments.callee,50);return}if(e[n])return;e[n]=1;var r="http://style.aliunicorn.com/js/aplus_lsproxy.js?v=20141223",i="1",s=location,o="https:"==s.protocol,u=parent!==self,a="",f=s.hostname,l="//gj.mmstat.com/",c=l+B(s.hostname)+".gif",h=[["logtype",u?0:1]],p=location.href,d=t.referrer,v=!!t.attachEvent,m="attachEvent",g="addEventListener",y=v?m:g,b=!1,w=!0,E="::-plain-::",S="data-spm",x="data-spm-protocol",T,N,C,k=J("cna"),L=b,A=e._SPM_a,O=e._SPM_b,M,_,D,P,H="goldlog";P={send:function(t,n){var r=this,i=new Image,s="_img_"+Math.random(),o=ft(t,n);return e[s]=i,i.onload=function(){r.callback&&!r.pvPost&&(r.pvPost=!0,r.callback.apply(r.context)),e[s]=null},i.onerror=function(){e[s]=null},i.src=o,o},postPV:function(e,t){this.callback=e,this.context=t},getCookie:J,sendPV:function(){var n,r=J("__last_loginid__").replace(/"/g,""),s=nt(p),o=nt(d);n=[[tt(),"title="+escape(t.title)],["pre",d],["cache",K()],["scr",screen.width+"x"+screen.height],["isbeta",i]],k&&n.push([tt(),"cna="+k]),r&&n.push([tt(),"nick="+r]),rt(n,[["spm-url",s],["spm-pre",o]]),_&&n.push(["spm-cnt",_]),n.push([tt(),"aplus"]),lt(n),u||(h=h.concat(n),e.g_aplus_pv_req=et(c,h)),u&&(n.push(["ifs","1"]),h=h.concat(n),et(c,h))}},e[H]=P,Y()}(),dmtrack.profile_site=5,dmtrack.isgetApacheId=!0,dmtrack.cmap_url="http://cmap.alibaba.com/landing_ae.gif",dmtrack.acookieSupport=!1,dmtrack.aplus=function(){var e=window.dmtrack&&dmtrack.clickstat,t=window.beaconStartTime,n=dmtrack.cmap_url,r=goldlog.getCookie("cna");r&&n&&location.protocol!=="https:"&&goldlog.send(n,{cna:r}),goldlog.postPV(function(){var i=goldlog.getCookie("cna");i!==r&&(e&&t&&e(location.protocol+"//stat.alibaba.com/event/common.html",{id:17770,ext:"cnatime="+((new Date).getTime()-t)}),n&&location.protocol!=="https:"&&goldlog.send(n,{cna:i}))}),goldlog.sendPV(),!r&&location.protocol!=="https:"&&e&&e(location.protocol+"//stat.alibaba.com/event/common.html",{id:17769})},sk_dmtracking(),function(){function P(e){var t,n;try{return t=[].slice.call(e),t}catch(r){t=[],n=e.length;for(var i=0;i<n;i++)t.push(e[i]);return t}}function H(e,t){return e&&e.getAttribute?e.getAttribute(t)||"":""}function B(e,t,n){if(e&&e.setAttribute)try{e.setAttribute(t,n)}catch(r){}}function j(e,t){if(e&&e.removeAttribute)try{e.removeAttribute(t)}catch(n){B(e,t,"")}}function F(e,t){return e.indexOf(t)==0}function I(e){return typeof e=="string"}function q(e){return typeof e=="number"}function R(e){return Object.prototype.toString.call(e)==="[object Array]"}function U(e,t){return e.indexOf(t)>=0}function z(e,t){return e.indexOf(t)>-1}function W(e,t){for(var n=0,s=t.length;n<s;n++)if(z(e,t[n]))return r;return i}function X(e){return I(e)?e.replace(/^\s+|\s+$/g,""):""}function V(e){return typeof e=="undefined"}function $(){return x=x||t.getElementsByTagName("head")[0],T||(x?T=x.getElementsByTagName("meta"):[])}function J(){var e=$(),t,n,r,i;for(t=0,n=e.length;t<n;t++)r=e[t],i=H(r,"name"),i==A&&(L=H(r,O))}function K(e){var t=$(),n,s,o,u,a,f;if(t)for(n=0,s=t.length;n<s;n++){u=t[n],a=H(u,"name");if(a==e)return p=H(u,"content"),p.indexOf(":")>=0&&(o=p.split(":"),L=o[0]=="u"?"u":"i",p=o[1]),f=H(u,O),f&&(L=f=="u"?"u":"i"),d=F(p,"110"),c=d?v:p,r}return i}function Q(){return Math.floor(Math.random()*268435456).toString(16)}function G(e){var t=[],n,r;for(n in e)e.hasOwnProperty(n)&&(r=""+e[n],t.push(F(n,g)?r:n+"="+encodeURIComponent(r)));return t.join("&")}function Y(e){var t=[],n,r,i,s=e.length;for(i=0;i<s;i++)n=e[i][0],r=e[i][1],t.push(F(n,g)?r:n+"="+encodeURIComponent(r));return t.join("&")}function Z(e){var t;try{t=X(e.getAttribute("href",2))}catch(n){}return t||""}function et(t,n,r){t[S]((b?"on":"")+n,function(t){t=t||e.event;var n=t.target||t.srcElement;r(t,n)},i)}function tt(n){var r=e.KISSY;r?r.ready(n):e.jQuery?jQuery(t).ready(n):t.readyState==="complete"?n():et(e,"load",n)}function nt(t,n){var r=new Image,i="_img_"+Math.random(),s=t.indexOf("?")==-1?"?":"&",o,u=n?R(n)?Y(n):G(n):"";return e[i]=r,r.onload=r.onerror=function(){e[i]=null},r.src=o=u?t+s+u:t,r=null,o}function rt(){if(!V(c))return c;if(e._SPM_a&&e._SPM_b)return f=e._SPM_a.replace(/^{(\w+)}$/g,"$1"),l=e._SPM_b.replace(/^{(\w+)}$/g,"$1"),N=r,c=f+"."+l,J(),c;K(A)||K("spm-id");if(!c)return m=!0,c=v,v;var n=t.getElementsByTagName("body"),i,s;return n=n&&n.length?n[0]:null,n&&(i=H(n,A),i&&(s=c.split("."),c=s[0]+"."+i)),z(c,".")||(m=!0,c=v),c}function it(e){var n=t.getElementsByTagName("*"),r,i,s,o,u,a;for(r=[];e&&e.nodeType==1;e=e.parentNode)if(e.hasAttribute("id")){a=e.getAttribute("id"),o=0;for(i=0;i<n.length;i++){u=n[i];if(u.hasAttribute("id")&&u.id==a){o++;break}}if(o==1)return r.unshift('id("'+a+'")'),r.join("/");r.unshift(e.localName.toLowerCase()+'[@id="'+a+'"]')}else{for(i=1,s=e.previousSibling;s;s=s.previousSibling)s.localName==e.localName&&i++;r.unshift(e.localName.toLowerCase()+"["+i+"]")}return r.length?"/"+r.join("/"):null}function st(e){var t=C[it(e)];return t?t.spmc:""}function ot(e){var t,n,r,i,s,o=[],u,a,f;t=P(e.getElementsByTagName("a")),n=P(e.getElementsByTagName("area")),i=t.concat(n);for(a=0,f=i.length;a<f;a++){u=!1,s=r=i[a];while(s=s.parentNode){if(s==e)break;if(H(s,A)){u=!0;break}}u||o.push(r)}return o}function ut(e,t,n){var r,i,s,o,u,a,f,l,c,h,p,d,v,m,g;if(H(e,"data-spm-delay")){e.setAttribute("data-spm-delay","");return}t=t||e.getAttribute(A)||"";if(!t)return;r=ot(e),s=t.split("."),d=F(t,"110")&&s.length==3,d&&(v=s[2],m=v.split("-"),s[2]="w"+(m.length>1?m[1]:"0"),t=s.join("."));if(I(l=rt())&&l.match(/^[\w\-\*]+(\.[\w\-\*]+)?$/))if(!U(t,"."))U(l,".")||(l+=".0"),t=l+"."+t;else if(!F(t,l)){o=l.split("."),s=t.split(".");for(h=0,c=o.length;h<c;h++)s[h]=o[h];t=s.join(".")}if(!t.match||!t.match(/^[\w\-\*]+\.[\w\-\*]+\.[\w\-\*]+$/))return;g=parseInt(H(e,"data-spm-max-idx"))||0;for(p=0,u=g,c=r.length;p<c;p++){i=r[p],a=Z(i);if(!a)continue;d&&i.setAttribute(_,v);if(f=i.getAttribute(D)){dt(i,f,n);continue}u++,f=t+"."+(mt(i)||u),dt(i,f,n)}e.setAttribute("data-spm-max-idx",u)}function at(e){var t=["mclick.simba.taobao.com","click.simba.taobao.com","click.tanx.com","click.mz.simba.taobao.com","click.tz.simba.taobao.com","redirect.simba.taobao.com","rdstat.tanx.com","stat.simba.taobao.com","s.click.taobao.com"],n,r=t.length;for(n=0;n<r;n++)if(e.indexOf(t[n])!=-1)return!0;return!1}function ft(e){return e?!!e.match(/^[^\?]*\balipay\.(?:com|net)\b/i):i}function lt(e){return e?!!e.match(/^[^\?]*\balipay\.(?:com|net)\/.*\?.*\bsign=.*/i):i}function ct(e){var t;while((e=e.parentNode)&&e.tagName!="BODY"){t=H(e,O);if(t)return t}return""}function ht(e,t){e&&/&?\bspm=[^&#]*/.test(e)&&(e=e.replace(/&?\bspm=[^&#]*/g,"").replace(/&{2,}/g,"&").replace(/\?&/,"?").replace(/\?$/,""));if(!t)return e;var n,r,i,s="&",o,u,a,f;e.indexOf("#")!=-1&&(i=e.split("#"),e=i.shift(),r=i.join("#")),o=e.split("?"),u=o.length-1,i=o[0].split("//"),i=i[i.length-1].split("/"),a=i.length>1?i.pop():"",u>0&&(n=o.pop(),e=o.join("?")),n&&u>1&&n.indexOf("&")==-1&&n.indexOf("%")!=-1&&(s="%26"),e=e+"?spm="+t+(n?s+n:"")+(r?"#"+r:""),f=z(a,".")?a.split(".").pop().toLowerCase():"";if(f){if({png:1,jpg:1,jpeg:1,gif:1,bmp:1,swf:1}.hasOwnProperty(f))return 0;!n&&u<=1&&!r&&!{htm:1,html:1,php:1}.hasOwnProperty(f)&&(e+="&file="+a)}return e}function pt(e){return e&&s.split("#")[0]==e.split("#")[0]}function dt(t,n,r){t.setAttribute(D,n);if(r)return;y=e.g_aplus_pv_id,y&&(n+="."+y);if(!y&&(!c||c==v))return;var i=Z(t),s=(H(t,O)||ct(t)||L)=="i",o=u;if(!i||at(i))return;if(!s&&(F(i,"#")||pt(i)||F(i.toLowerCase(),"javascript:")||ft(i)||lt(i)))return;if(s){var a=n.split("."),f="";a&&a.length>0&&(f+="?ae_project_id=180115",f+="&ae_page_area="+a[0],f+="&ae_button_type="+a[1],f+="&ae_object_type="+a[2],f+="&ae_object_value="+a[3],window.dmtrack_pageid&&(f+="&st_page_id="+window.dmtrack_pageid)),o+=f,M==t&&nt(o)}else r||(i=ht(i,n))&&vt(t,i)}function vt(e,n){var r,i=e.innerHTML;i&&i.indexOf("<")==-1&&(r=t.createElement("b"),r.style.display="none",e.appendChild(r)),e.href=n,r&&e.removeChild(r)}function mt(e){var t,n,r;if(m)t="0";else if(N)n=it(e),r=C[n],r&&(t=r.spmd);else{t=H(e,A);if(!t||!t.match(/^d\w+$/))t=""}return t}function gt(e){var t,n,r=e;while(e&&e.tagName!="HTML"&&e.tagName!="BODY"&&e.getAttribute){N?n=st(e):n=e.getAttribute(A);if(n){t=n,r=e;break}if(!(e=e.parentNode))break}return{spm_c:t,el:r}}function yt(e){var t;return e&&(t=e.match(/&?\bspm=([^&#]*)/))?t[1]:""}function bt(e){var n=t.getElementsByTagName("a"),r=n.length,i;for(i=0;i<r;i++)if(n[i]==e)return i+1;return 0}function wt(e,t){var n=Z(e),r=yt(n),i=r?r.split("."):null,s,o,u=c&&(s=c.split(".")).length==2;if(i&&i.length>=4&&i[3]){i[0]!="1003"&&i[0]!="2006"&&u&&(i[0]=s[0],i[1]=s[1],m&&(i[2]="0"),o=mt(e),o&&(i[3]=o)),dt(e,i.slice(0,4).join("."),t);return}if(u){i=[c,0,mt(e)||bt(e)],dt(e,i.join("."),t);return}n&&r&&(e.href=" "+n.replace(/&?\bspm=[^&#]*/g,"").replace(/&{2,}/g,"&").replace(/\?&/,"?").replace(/\?$/,"").replace(/\?#/,"#"))}function Et(e,t){M=e;var n=H(e,D),r,i;if(!n){r=gt(e.parentNode),i=r.spm_c;if(!i){wt(e,t);return}m&&(i="0"),ut(r.el,i,t)}else dt(e,n,t)}function St(e){if(!e||e.nodeType!=1)return;j(e,"data-spm-max-idx");var t=P(e.getElementsByTagName("a")),n=P(e.getElementsByTagName("area")),r=t.concat(n),i,s=r.length;for(i=0;i<s;i++)j(r[i],D)}function xt(e){var t=e.parentNode;if(!t)return"";var n=e.getAttribute(A),r=gt(t),i=r.spm_c||0;i&&i.indexOf(".")!=-1&&(i=i.split("."),i=i[i.length-1]);var s=c+"."+i;return n=n||h[s]||0,q(n)&&(n++,h[s]=n),s+".i"+n}function Tt(t){var n=t.tagName,i;return y=e.g_aplus_pv_id,n!="A"&&n!="AREA"?i=xt(t):(Et(t,r),i=H(t,D)),i=(i||"0.0.0.0").split("."),{a:i[0],b:i[1],c:i[2],d:i[3],e:y}}function Nt(){if(k)return;if(!e.spmData){a||setTimeout(arguments.callee,100);return}k=r;var t=e.spmData.data,n,i,s,o;if(!t||!R(t))return;for(n=0,i=t.length;n<i;n++)s=t[n],o=s.xpath,C[o]={spmc:s.spmc,spmd:s.spmd}}var e=window,t=document,n=location,r=!0,i=!1,s=n.href,o="https:"==n.protocol,u=(o?"https://":"http://")+"stat.alibaba.com/ae/aliexpress_button_click.html",a=i,f,l,c,h={},p,d,v="0.0",m=!1,g="::-plain-::",y,b=!!t.attachEvent,w="attachEvent",E="addEventListener",S=b?w:E,x,T,N=i,C={},k=i,L,A="data-spm",O="data-spm-protocol",M,_="data-spm-wangpu-module-id",D="data-spm-anchor-id";if(W(s,["xiaobai.com","admin.taobao.org"]))return;tt(function(){a=r}),rt(),Nt(),et(t,"mousedown",function(e,t){var n;while(t&&(n=t.tagName)){if(n=="A"||n=="AREA"){Et(t,i);break}if(n=="BODY"||n=="HTML")break;t=t.parentNode}}),e.g_SPM={resetModule:St,anchorBeacon:Et,getParam:Tt}}();