// version 0.2
// Original Author: Puru Choudhary (www.terminusapp.com)
// URL: https://github.com/medius/utm_cookie
//
// Description:
// This script saves UTM parameters in cookies whenever there are any UTM parameters
// in the URL. It also saves the initial referrer information in a cookie which is
// never (365 days) overwritten.
//
// Adding this script is useful for custom tracking. e.g. The values in the cookies
// can be read and added to forms or stored in the backend database, etc.
//
var utmCookie = {
    cookieUtmName: "_utmz_cf7",
    cookieRefName: "_referrer",
    cookieLandingName: "_landing",
    cookieGclidName: "_gclid",

    utmParams: {
        "utm_source": "utmcsr",
        "source": "source",
        "utm_medium": "utmcmd",
        "medium": "medium",
        "utm_campaign": "utmccn",
        "utm_term": "utmctr",
        "utm_content": "utmcct",
        "area": "area",
    },

    cookieExpiryDays: 365,

    // From http://www.quirksmode.org/js/cookies.html
    createCookie: function(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        } else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    },

    readCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },

    eraseCookie: function(name) {
        this.createCookie(name, "", -1);
    },

    getParameterByName: function(name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.search);
        if (results == null) {
            return "";
        } else {
            return decodeURIComponent(results[1].replace(/\+/g, " "));
        }
    },

    utmNeedSave: function() {
        var trafficSrc = this.readCookie(this.cookieUtmName); //get OWN cookie	
        if (trafficSrc != null) {
            return false;
        }

        var utmzCookie = this.readCookie("__utmz"); //get GA cookie		
        if (utmzCookie != null) {
            this.writeUtmCookieFromUtmz(utmzCookie);
            return false;
        }


        var utm_source = this.getParameterByName('utm_source');
        // Fix For Facebook
        var source = this.getParameterByName('source');;
        if (utm_source != "" && source != "" && utm_source != undefined && source != undefined) {
            return true;
        } else {
			this.createCookie(this.cookieUtmName, 'utm_source=(none)', this.cookieExpiryDays);			
		}
        
        var gclid = this.getParameterByName('gclid');
        if (gclid != "" && gclid != undefined) {
            this.writeCookieOnce(this.cookieGclidName, gclid);
        }

    },

    writeUtmCookieFromParams: function() {
        var trafficSrc = '';
        for (utmKey in this.utmParams) {
            trafficSrc += this.utmParams[utmKey] + '=' + this.getParameterByName(utmKey) + '|';
        }
        /*for (var i = 0; i < this.utmParams.length; i++) {
          trafficSrc += this.utmParams[i] + '=' + this.getParameterByName( this.utmParams[i] ) + '|';
        }*/
        this.createCookie(this.cookieUtmName, trafficSrc.substring(0, trafficSrc.length - 1), this.cookieExpiryDays);
    },

    writeUtmCookieFromUtmz: function(utmzCookie) {
        var z = utmzCookie.split('.');
        if (z.length >= 4) {
            var y = z[4].split('|');
            for (i = 0; i < y.length; i++) {
                var pair = y[i].split("=");
                values[pair[0]] = pair[1];
            }
        }
        this.createCookie('utmz', trafficSrc.substring(0, trafficSrc.length - 1), this.cookieExpiryDays);
    },

    writeCookieOnce: function(name, value) {
        var existingValue = this.readCookie(name);
        if (!existingValue) {
            this.createCookie(name, value, this.cookieExpiryDays);
        }
    },

    writeReferrerOnce: function() {
        var value = document.referrer;
        if (value === "" || value === undefined) {
            this.writeCookieOnce(this.cookieRefName, "direct");
        } else {
            this.writeCookieOnce(this.cookieRefName, value);
        }
    },

    writeLandingOnce: function() {
        var value = location.href;
        if (value === "" || value === undefined) {
            this.writeCookieOnce(this.cookieLandingName, value);
        }
    },

    /*
       Remove the protocol for the referral token
    */
    removeProtocol: function(href) {
        return href.replace(/.*?:\/\//g, "");
    },

    run: function() {
        utmCookie.writeReferrerOnce();
        utmCookie.writeLandingOnce();
        if (utmCookie.utmNeedSave()) {
            utmCookie.writeUtmCookieFromParams();
        }
    },

    log: function() {
        console.log(this.readCookie(this.cookieUtmName));
    }
};
utmCookie.run();