(function () {
  window.jara = {
    API: process.env.VUE_APP_API_URL,

    Number: (number) => {
      return Intl.NumberFormat("en-US", {
        notation: "compact",
        compactDisplay: "short",
      }).format(number);
    },

    Time: (e) => {
      if ((e instanceof Date || (e = new Date(e)), isNaN(e.getDate())))
        return "Unknown";
      function r(e, r, a) {
        var t = [
            "\0",
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
          ],
          g = [
            "",
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          n = [
            "",
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
          ],
          c = ["", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        function l(e, r) {
          var a = e + "";
          for (r = r || 2; a.length < r; ) a = "0" + a;
          return a;
        }
        var p = a ? e.getUTCFullYear() : e.getFullYear();
        r = (r = (r = r.replace(/(^|[^\\])yyyy+/g, "$1" + p)).replace(
          /(^|[^\\])yy/g,
          "$1" + p.toString().substr(2, 2)
        )).replace(/(^|[^\\])y/g, "$1" + p);
        var u = (a ? e.getUTCMonth() : e.getMonth()) + 1;
        r = (r = (r = (r = r.replace(/(^|[^\\])MMMM+/g, "$1" + t[0])).replace(
          /(^|[^\\])MMM/g,
          "$1" + g[0]
        )).replace(/(^|[^\\])MM/g, "$1" + l(u))).replace(
          /(^|[^\\])M/g,
          "$1" + u
        );
        var M = a ? e.getUTCDate() : e.getDate();
        r = (r = (r = (r = r.replace(/(^|[^\\])dddd+/g, "$1" + n[0])).replace(
          /(^|[^\\])ddd/g,
          "$1" + c[0]
        )).replace(/(^|[^\\])dd/g, "$1" + l(M))).replace(
          /(^|[^\\])d/g,
          "$1" + M
        );
        var o = a ? e.getUTCHours() : e.getHours(),
          d = 12 < o ? o - 12 : 0 == o ? 12 : o;
        r = (r = (r = (r = r.replace(/(^|[^\\])HH+/g, "$1" + l(o))).replace(
          /(^|[^\\])H/g,
          "$1" + o
        )).replace(/(^|[^\\])hh+/g, "$1" + l(d))).replace(
          /(^|[^\\])h/g,
          "$1" + d
        );
        var s = a ? e.getUTCMinutes() : e.getMinutes();
        r = (r = r.replace(/(^|[^\\])mm+/g, "$1" + l(s))).replace(
          /(^|[^\\])m/g,
          "$1" + s
        );
        var i = a ? e.getUTCSeconds() : e.getSeconds();
        r = (r = r.replace(/(^|[^\\])ss+/g, "$1" + l(i))).replace(
          /(^|[^\\])s/g,
          "$1" + i
        );
        var $ = a ? e.getUTCMilliseconds() : e.getMilliseconds();
        (r = r.replace(/(^|[^\\])fff+/g, "$1" + l($, 3))),
          ($ = Math.round($ / 10)),
          (r = r.replace(/(^|[^\\])ff/g, "$1" + l($))),
          ($ = Math.round($ / 10));
        var y = o < 12 ? "AM" : "PM";
        r = (r = (r = r.replace(/(^|[^\\])f/g, "$1" + $)).replace(
          /(^|[^\\])TT+/g,
          "$1" + y
        )).replace(/(^|[^\\])T/g, "$1" + y.charAt(0));
        var h = y.toLowerCase();
        r = (r = r.replace(/(^|[^\\])tt+/g, "$1" + h)).replace(
          /(^|[^\\])t/g,
          "$1" + h.charAt(0)
        );
        var f,
          m = -e.getTimezoneOffset(),
          v = a || !m ? "Z" : 0 < m ? "+" : "-";
        a ||
          ((f = (m = Math.abs(m)) % 60),
          (v += l(Math.floor(m / 60)) + ":" + l(f))),
          (r = r.replace(/(^|[^\\])K/g, "$1" + v));
        var T = (a ? e.getUTCDay() : e.getDay()) + 1;
        return (r = (r = (r = (r = (r = r.replace(
          new RegExp(n[0], "g"),
          n[T]
        )).replace(new RegExp(c[0], "g"), c[T])).replace(
          new RegExp(t[0], "g"),
          t[u]
        )).replace(new RegExp(g[0], "g"), g[u])).replace(/\\(.)/g, "$1"));
      }
      var a = new Date(),
        t = (a.getTime() - e.getTime()) / 1e3;
      if (t < 0) return e;
      if (t < 30) return "Just now";
      if (t < 60) return parseInt(t) + " seconds ago";
      if (t < 3600) {
        var g = parseInt(t / 60);
        return 1 == g ? g + " minute ago" : g + " minutes ago";
      }
      if (t <= 86400)
        return 1 == parseInt(t / 3600)
          ? parseInt(t / 3600) + " hour ago"
          : parseInt(t / 3600) + " hours ago";
      if (t <= 172800) return "Yesterday at " + r(e, "h:mmtt");
      if (172800 < t) {
        var n =
          t <= 604800
            ? r(e, "dddd") + " at " + r(e, "h:mmtt")
            : a.getFullYear() > e.getFullYear()
            ? r(e, "MMMM d, yyyy")
            : a.getMonth() > e.getMonth()
            ? r(e, "MMMM d")
            : r(e, "MMMM d") + " at " + r(e, "h:mmtt");
        return n;
      }
    },

    Sanitize: (data) => {
      data = data
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#x27")
        .replace(/\//g, "&#x2F");
      return data;
    },

    inView: (selector, visibleCallback, invisibleCallback) => {
      if ("IntersectionObserver" in window) {
        const items = selector;

        var observer = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.intersectionRatio > 0) {
              entry.target.classList.add("visible");
              var visible = entry.isIntersecting;
              visibleCallback(visible, entry);
            } else {
              entry.target.classList.remove("visible");
              visible = entry.isIntersecting;
              invisibleCallback(visible, entry);
            }
          });
        });

        items.forEach((item) => {
          observer.observe(item);
        });
      }
    },

    on: (event, selector, handler) => {
      document.addEventListener(
        event,
        function (event) {
          if (event.target.matches(selector + ", " + selector + " *")) {
            handler.apply(event.target.closest(selector), arguments);
          }
        },
        false
      );
    },

    getHight: (oldHeight, oldWidth, newWidth) => {
      var newHeight = (oldWidth / oldHeight) * newWidth;
      return newHeight;
    },

    setCookie: (cname, cvalue, exdays) => {
      if (!exdays) {
        document.cookie =
          cname +
          "=" +
          cvalue +
          ";domain=" +
          window.location.hostname +
          ";path=/";
      } else {
        var d = new Date();
        d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
        var expires = "expires=" + d.toUTCString();
        document.cookie =
          cname +
          "=" +
          cvalue +
          ";" +
          expires +
          ";domain=" +
          window.location.hostname +
          ";path=/";
      }
    },

    getCookie: (cname) => {
      var name = cname + "=";
      var ca = document.cookie.split(";");
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return false;
    },

    removeCookie(cname) {
      document.cookie =
        cname +
        "=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain=" +
        window.location.hostname;
    },

    SortDate(array, index) {
      return array.sort(function (a, b) {
        var dateA = new Date(a[index]).getTime();
        var dateB = new Date(b[index]).getTime();
        return dateA < dateB ? 1 : -1;
      });
    },

    Paginate(array, page_size, page_number) {
      return array.slice(
        page_number * page_size,
        page_number * page_size + page_size
      );
    },
  };
})();
