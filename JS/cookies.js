/*
*JQuery Cookies
*
*/
(function(factory){
    if(typeof define === "function" && define.amd){
        //AMD
        define(['jquery'] , factory);
    }else if(typeof exports === 'object'){
        //commonJS
        factory(require('jquery'));
    }else{
        //browser global
        factory(jQuery);
    }
}(function($){
    var pluses = /\+/g;

    function encode(s){
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s){
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode( config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s){
        if(s.indexOf('"') === 0 ){
            //this is a quoted cookie as arcoding to RFC2068 unescape...
            s = s.slice(1, -1).replace(/\\"/g , '"').replace(/\\\\/g, '\\');
        }

        try{
            //replace server-side written pluses with spaces
            //if we cant decode the cookies ignore it its is unusable.
            //if we cant pass the cookie , its is unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch(e) {}
    }

    function read(s , converter){
        var value = config.raw ? s : parseCookieValue(s);
        return $.isfunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function (key , value , option){
        //write
        if(value !== undefined && !$.isfunction(value)){
            options = $.extend({} , config,defaults, options);

            if(typeof options.expires === "number"){
                var days = options.expires ,  t = option.expires = new Date();
                t.setTime(+t + days * 864e+5);
            }

            return (document.cookie = [
                encode(key) , '=' , stringifyCookieValue(value),
                options.expires ? '; expires=' + option.expires.toUTCString() : '',// use expires attribute , max-age is not surpoorted by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        //read

        var result = key ? undefined : {};
        //to prevent the fopr loop in th first place assign an empty array
        //incase there are no cookies at all .Also prevent add result when
        //calling $.cookies().

        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var i = 0 , l = cookies.length; i < l; i++){
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');

            if(key && key === name ){
                //if second argument (value) is a function its a converter..
                result = read(cookie , value);
                break;
            }

            //prevent storing a cookie we coundnt decode.
            if(!key && (cookie = read(cookie)) !== undefined ){
                 result[name] = cookie;
            }
        }
        return result;
    };
    config.defaults = {};
    $.removeCookie = function (key , options){
        if($.cookie(key) === undefined ){
            return false;
        }

        //must not alter options , thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, {expires: -1}));
        return !$.cookie(key);
    };
}));