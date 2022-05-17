(window["webpackJsonp"] = window["webpackJsonp"] || []).push([
    ["chunk-2d0b1639"], { 2049: function(e, s, t) { "use strict";
            t.r(s); var r = function() { var e = this,
                        s = e.$createElement,
                        t = e._self._c || s; return t("form", { on: { submit: function(s) { return s.preventDefault(), e.onsubmit(s) } } }, [t("va-input", { attrs: { type: "email", label: e.$t("auth.email"), error: !!e.emailErrors.length, "error-messages": e.emailErrors }, model: { value: e.email, callback: function(s) { e.email = s }, expression: "email" } }), t("va-input", { attrs: { type: "password", label: e.$t("auth.password"), error: !!e.passwordErrors.length, "error-messages": e.passwordErrors }, model: { value: e.password, callback: function(s) { e.password = s }, expression: "password" } }), t("div", { staticClass: "auth-layout__options d-flex align--center justify--space-between" }, [t("va-checkbox", { staticClass: "mb-0", attrs: { label: e.$t("auth.keep_logged_in") }, model: { value: e.keepLoggedIn, callback: function(s) { e.keepLoggedIn = s }, expression: "keepLoggedIn" } })], 1), t("div", { staticClass: "d-flex justify--center mt-3" }, [t("va-button", { staticClass: "my-0", attrs: { type: "submit" } }, [e._v(e._s(e.$t("auth.login")))])], 1)], 1) },
                a = [],
                o = { name: "login", data: function() { return { email: "", password: "", keepLoggedIn: !1, emailErrors: [], passwordErrors: [] } }, computed: { formReady: function() { return !this.emailErrors.length && !this.passwordErrors.length } }, methods: { onsubmit: function() { this.emailErrors = this.email ? [] : ["Email is required"], this.passwordErrors = this.password ? [] : ["Password is required"], this.formReady && this.showToast("This feature is under maintain. Please wait...", this.toast) } } },
                i = o,
                n = t("2877"),
                l = Object(n["a"])(i, r, a, !1, null, null, null);
            s["default"] = l.exports } }
]);
//# sourceMappingURL=chunk-2d0b1639.efc81143.js.map