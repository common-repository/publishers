! function(e) {
"function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof exports ? module.exports = e(require("jquery")) : e(jQuery)
}(function(e) {
var n = /\+/g;
function o(e) {
return t.raw ? e : encodeURIComponent(e)
}
function i(e) {
return o(t.json ? JSON.stringify(e) : String(e))
}
function r(o, i) {
var r = t.raw ? o : function(e) {
0 === e.indexOf('"') && (e = e.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
try {
return e = decodeURIComponent(e.replace(n, " ")), t.json ? JSON.parse(e) : e
} catch (e) {}
}(o);
return e.isFunction(i) ? i(r) : r
}
var t = e.cookie = function(n, c, u) {
if (arguments.length > 1 && !e.isFunction(c)) {
if ("number" == typeof(u = e.extend({}, t.defaults, u)).expires) {
var s = u.expires,
a = u.expires = new Date;
a.setMilliseconds(a.getMilliseconds() + 864e5 * s)
}
return document.cookie = [o(n), "=", i(c), u.expires ? "; expires=" + u.expires.toUTCString() : "", u.path ? "; path=" + u.path : "", u.domain ? "; domain=" + u.domain : "", u.secure ? "; secure" : ""].join("")
}
for (var d, f = n ? void 0 : {}, p = document.cookie ? document.cookie.split("; ") : [], l = 0, m = p.length; l < m; l++) {
var x = p[l].split("="),
g = (d = x.shift(), t.raw ? d : decodeURIComponent(d)),
v = x.join("=");
if (n === g) {
f = r(v, c);
break
}
n || void 0 === (v = r(v)) || (f[g] = v)
}
return f
};
t.defaults = {}, e.removeCookie = function(n, o) {
return e.cookie(n, "", e.extend({}, o, {
expires: -1
})), !e.cookie(n)
}
});