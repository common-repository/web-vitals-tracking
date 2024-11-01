!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports):"function"==typeof define&&define.amd?define(["exports"],e):e((t=t||self).webVitals={})}(this,(function(t){"use strict";var e,n,i=function(){return"".concat(Date.now(),"-").concat(Math.floor(8999999999999*Math.random())+1e12)},a=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:-1;return{name:t,value:e,delta:0,entries:[],id:i(),isFinal:!1}},r=function(t,e){try{if(PerformanceObserver.supportedEntryTypes.includes(t)){var n=new PerformanceObserver((function(t){return t.getEntries().map(e)}));return n.observe({type:t,buffered:!0}),n}}catch(t){}},o=!1,s=!1,u=function(t){o=!t.persisted},c=function(){addEventListener("pagehide",u),addEventListener("unload",(function(){}))},d=function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1];s||(c(),s=!0),addEventListener("visibilitychange",(function(e){var n=e.timeStamp;"hidden"===document.visibilityState&&t({timeStamp:n,isUnloading:o})}),{capture:!0,once:e})},f=function(t,e,n,i){var a;return function(){n&&e.isFinal&&n.disconnect(),e.value>=0&&(i||e.isFinal||"hidden"===document.visibilityState)&&(e.delta=e.value-(a||0),(e.delta||e.isFinal||void 0===a)&&(t(e),a=e.value))}},p=function(){return void 0===e&&(e="hidden"===document.visibilityState?0:1/0,d((function(t){var n=t.timeStamp;return e=n}),!0)),{get timeStamp(){return e}}},l=function(){return n||(n=new Promise((function(t){return["scroll","keydown","pointerdown"].map((function(e){addEventListener(e,t,{once:!0,passive:!0,capture:!0})}))}))),n};t.getCLS=function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=a("CLS",0),i=function(t){t.hadRecentInput||(n.value+=t.value,n.entries.push(t),s())},o=r("layout-shift",i),s=f(t,n,o,e);d((function(t){var e=t.isUnloading;o&&o.takeRecords().map(i),e&&(n.isFinal=!0),s()}))},t.getFCP=function(t){var e=a("FCP"),n=p(),i=r("paint",(function(t){"first-contentful-paint"===t.name&&t.startTime<n.timeStamp&&(e.value=t.startTime,e.isFinal=!0,e.entries.push(t),o())})),o=f(t,e,i)},t.getFID=function(t){var e=a("FID"),n=p(),i=function(t){t.startTime<n.timeStamp&&(e.value=t.processingStart-t.startTime,e.entries.push(t),e.isFinal=!0,s())},o=r("first-input",i),s=f(t,e,o);d((function(){o&&(o.takeRecords().map(i),o.disconnect())}),!0),o||window.perfMetrics&&window.perfMetrics.onFirstInputDelay&&window.perfMetrics.onFirstInputDelay((function(t,i){i.timeStamp<n.timeStamp&&(e.value=t,e.isFinal=!0,e.entries=[{entryType:"first-input",name:i.type,target:i.target,cancelable:i.cancelable,startTime:i.timeStamp,processingStart:i.timeStamp+t}],s())}))},t.getLCP=function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=a("LCP"),i=p(),o=function(t){var e=t.startTime;e<i.timeStamp?(n.value=e,n.entries.push(t)):n.isFinal=!0,u()},s=r("largest-contentful-paint",o),u=f(t,n,s,e),c=function(){n.isFinal||(s&&s.takeRecords().map(o),n.isFinal=!0,u())};l().then(c),d(c,!0)},t.getTTFB=function(t){var e,n=a("TTFB");e=function(){try{var e=performance.getEntriesByType("navigation")[0]||function(){var t=performance.timing,e={entryType:"navigation",startTime:0};for(var n in t)"navigationStart"!==n&&"toJSON"!==n&&(e[n]=Math.max(t[n]-t.navigationStart,0));return e}();n.value=n.delta=e.responseStart,n.entries=[e],n.isFinal=!0,t(n)}catch(t){}},"complete"===document.readyState?setTimeout(e,0):addEventListener("pageshow",e)},Object.defineProperty(t,"__esModule",{value:!0})}));

// tracking
(function(){
    function isMobile() {
        var check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    }
    function track(result) {
        // console.log(result);
        var value = result.delta;
        if ("CLS" == result.name) {
            value *= 1000;
        }
        var value_rounded = Math.round(value);
        
        if (typeof gtag == "function") {
            try {
                gtag("event", result.name, {
                    event_category: "web-vitals",
                    event_label: result.id,
                    value: value_rounded
                });
            } catch (e) { console.warn(e); }
        } else if (typeof ga == "function") {
            try {
                ga("send", {
                    hitType: "event",
                    eventCategory: "web-vitals",
                    eventAction: result.name,
                    eventLabel: result.id,
                    eventValue: value_rounded
                });
            } catch (e) { console.warn(e); }
        }

        // send to our ajax endpoint
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", wbwvt.admin_ajax_url, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(
            "action=webvitalstrack" + 
            "&device=" + (isMobile() ? "mobile" : "desktop") +
            "&name="+ encodeURIComponent(result.name) + 
            "&delta="+ encodeURIComponent(result.delta) + 
            "&id_view="+ encodeURIComponent(result.id) + 
            "&path="+ encodeURIComponent(document.location.pathname)
        );

        // call custom method to allow third-party tracking or Google Tag Manger tracking
        if (typeof window.wpWebVitalTrack == "function") {
            try {
                window.wpWebVitalTrack(result.id, result.name, result.value, result.delta);
            } catch (e) { console.warn(e); }
        }

        // Default for Google Tag Manger
        if (typeof dataLayer != "undefined") {
            try {
                dataLayer.push({
                    event: "web-vitals",
                    event_category: "Web Vitals",
                    event_action: name.name,
                    event_value: value_rounded,
                    event_label: name.id,
                });
            } catch (e) { console.warn(e); }
        }
    }
    function init() {
        if (typeof webVitals === "undefined") {
            m();
            return;
        }
        webVitals.getCLS(track);
        webVitals.getFID(track);
        webVitals.getLCP(track);
    }
    function m(){ setTimeout(init, 50); }
    m();
})()