
Swipe = function(a, b) {
    function c() {
        m = l.children;
        B = m.length;
        2 > m.length && (b.continuous = !1);
        t.transitions && (b.continuous && 3 > m.length) && (l.appendChild(m[0].cloneNode(!0)), l.appendChild(l.children[1].cloneNode(!0)), m = l.children);
        q = Array(m.length);
        k = a.getBoundingClientRect().width || a.offsetWidth;
        l.style.width = m.length * k + "px";
        for (var c = m.length; c--;) {
            var d = m[c];
            d.style.width = k + "px";
            d.setAttribute("data-index", c);
            t.transitions && (d.style.left = c * -k + "px", h(c, f > c ? -k: f < c ? k: 0, 0))
        }
        b.continuous && t.transitions && (h(e(f - 1), -k, 0), h(e(f + 1), k, 0));
        t.transitions || (l.style.left = f * -k + "px");
        a.style.visibility = "visible"
    }
    function d() {
        b.continuous ? g(f + 1) : f < m.length - 1 && g(f + 1)
    }
    function e(a) {
        return (m.length + a % m.length) % m.length
    }
    function g(a, c) {
        if (f != a) {
            if (t.transitions) {
                var d = Math.abs(f - a) / (f - a);
                if (b.continuous) {
                    var g = d,
                        d = -q[e(a)] / k;
                    d !== g && (a = -d * m.length + a)
                }
                for (g = Math.abs(f - a) - 1; g--;) h(e((a > f ? a: f) - g - 1), k * d, 0);
                a = e(a);
                h(f, k * d, c || r);
                h(a, 0, c || r);
                b.continuous && h(e(a - d), -(k * d), 0)
            } else a = e(a),
                F(f * -k, a * -k, c || r);
            f = a;
            x(b.callback && b.callback(f, m[f]))
        }
    }
    function h(a, b, c) {
        s(a, b, c);
        q[a] = b
    }
    function s(a, b, c) {
        if (a = (a = m[a]) && a.style) a.webkitTransitionDuration = a.MozTransitionDuration = a.msTransitionDuration = a.OTransitionDuration = a.transitionDuration = c + "ms",
            a.webkitTransform = "translate(" + b + "px,0) translateZ(0)",
            a.msTransform = a.MozTransform = a.OTransform = "translateX(" + b + "px)"
    }
    function F(a, c, d) {
        if (d) var e = +new Date,
            g = setInterval(function() {
                    var h = +new Date - e;
                    h > d ? (l.style.left = c + "px", v && y(), b.transitionEnd && b.transitionEnd.call(event, f, m[f]), clearInterval(g)) : l.style.left = (c - a) * (Math.floor(100 * (h / d)) / 100) + a + "px"
                },
                4);
        else l.style.left = c + "px"
    }
    function y() {
        v = b.auto || 0;
        z = setTimeout(d, v)
    }
    function u(a) {
        a || (v = 0);
        clearTimeout(z);
        z = null
    }
    var G = function() {},
        x = function(a) {
            setTimeout(a || G, 0)
        },
        t = {
            addEventListener: !!window.addEventListener,
            touch: "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch,
            transitions: function(a) {
                var b = ["transitionProperty", "WebkitTransition", "MozTransition", "OTransition", "msTransition"],
                    c;
                for (c in b) if (void 0 !== a.style[b[c]]) return ! 0;
                return ! 1
            } (document.createElement("swipe"))
        };
    if (a) {
        var l = a.children[0],
            m,
            q,
            k,
            B;
        b = b || {};
        var f = parseInt(b.startSlide, 10) || 0,
            r = b.speed || 300;
        b.continuous = void 0 !== b.continuous ? b.continuous: !0;
        b.isSwipe = void 0 !== b.isSwipe ? b.isSwipe: !0;
        var v = b.auto || 0,
            z, C, D, E, n, A, w, p = {
                handleEvent: function(a) {
                    switch (a.type) {
                        case "touchstart":
                            this.start(a);
                            break;
                        case "touchmove":
                            this.move(a);
                            break;
                        case "touchend":
                            x(this.end(a));
                            break;
                        case "webkitTransitionEnd":
                        case "msTransitionEnd":
                        case "oTransitionEnd":
                        case "otransitionend":
                        case "transitionend":
                            x(this.transitionEnd(a));
                            break;
                        case "resize":
                            x(c.call())
                    }
                    b.stopPropagation && a.stopPropagation()
                },
                start: function(a) {
                    a = a.touches[0];
                    C = a.pageX;
                    D = a.pageY;
                    E = +new Date;
                    A = n = w = void 0;
                    l.addEventListener("touchmove", this, !1);
                    l.addEventListener("touchend", this, !1)
                },
                move: function(a) {
                    if (! (1 < a.touches.length || a.scale && 1 !== a.scale)) {
                        b.disableScroll && a.preventDefault();
                        var c = a.touches[0];
                        n = c.pageX - C;
                        A = c.pageY - D;
                        "undefined" == typeof w && (w = !!(w || Math.abs(n) < Math.abs(A)));
                        w || (a.preventDefault(), u(!0), b.continuous ? (s(e(f - 1), n + q[e(f - 1)], 0), s(f, n + q[f], 0), s(e(f + 1), n + q[e(f + 1)], 0)) : (n /= !f && 0 < n || f == m.length - 1 && 0 > n ? Math.abs(n) / k + 1 : 1, s(f - 1, n + q[f - 1], 0), s(f, n + q[f], 0), s(f + 1, n + q[f + 1], 0)))
                    }
                },
                end: function(a) {
                    a = 250 > Number( + new Date - E) && 20 < Math.abs(n) || Math.abs(n) > k / 2;
                    var c = !f && 0 < n || f == m.length - 1 && 0 > n;
                    b.continuous && (c = !1);
                    var d = 0 > n;
                    w || (a && !c ? (d ? (b.continuous ? (h(e(f - 1), -k, 0), h(e(f + 2), k, 0)) : h(f - 1, -k, 0), h(f, q[f] - k, r), h(e(f + 1), q[e(f + 1)] - k, r), f = e(f + 1)) : (b.continuous ? (h(e(f + 1), k, 0), h(e(f - 2), -k, 0)) : h(f + 1, k, 0), h(f, q[f] + k, r), h(e(f - 1), q[e(f - 1)] + k, r), f = e(f - 1)), b.callback && b.callback(f, m[f])) : b.continuous ? (h(e(f - 1), -k, r), h(f, 0, r), h(e(f + 1), k, r)) : (h(f - 1, -k, r), h(f, 0, r), h(f + 1, k, r)));
                    l.removeEventListener("touchmove", p, !1);
                    l.removeEventListener("touchend", p, !1)
                },
                transitionEnd: function(a) {
                    parseInt(a.target.getAttribute("data-index"), 10) == f && (v && y(), b.transitionEnd && b.transitionEnd.call(a, f, m[f]))
                }
            };
        c();
        v && y();
        t.addEventListener ? (b.isSwipe && (t.touch && l.addEventListener("touchstart", p, !1), t.transitions && (l.addEventListener("webkitTransitionEnd", p, !1), l.addEventListener("msTransitionEnd", p, !1), l.addEventListener("oTransitionEnd", p, !1), l.addEventListener("otransitionend", p, !1), l.addEventListener("transitionend", p, !1))), window.addEventListener("resize", p, !1)) : window.onresize = function() {
            c()
        };
        x(b.oninit && b.oninit(f, m[f]));
        return {
            setup: function() {
                c()
            },
            stop: u,
            resume: function() {
                u();
                y(!0)
            },
            slide: function(a, b) {
                u();
                g(a, b)
            },
            prev: function() {
                u();
                b.continuous ? g(f - 1) : f && g(f - 1)
            },
            next: function() {
                u();
                d()
            },
            getPos: function() {
                return f
            },
            getNumSlides: function() {
                return B
            },
            kill: function() {
                u();
                l.style.width = "auto";
                l.style.left = 0;
                for (var a = m.length; a--;) {
                    var b = m[a];
                    b.style.width = "100%";
                    b.style.left = 0;
                    t.transitions && s(a, 0, 0)
                }
                t.addEventListener ? (l.removeEventListener("touchstart", p, !1), l.removeEventListener("webkitTransitionEnd", p, !1), l.removeEventListener("msTransitionEnd", p, !1), l.removeEventListener("oTransitionEnd", p, !1), l.removeEventListener("otransitionend", p, !1), l.removeEventListener("transitionend", p, !1), window.removeEventListener("resize", p, !1)) : window.onresize = null
            }
        }
    }
};
function initSlideImgIB() {
    var a = $(".topic-swipe-ib"),
        b = "topic-swipe-ib" + a.length;
    a.get(a.length - 1).id = b;
    var c = $(".dot-slider");
    c.get(c.length - 1).id = "dot-slider" + a.length;
    var d = document.getElementById(b).children[0].children.length,
        c = !0;
    1 == d && (c = !1);
    new Swipe($("#" + b)[0], {
        auto: 3E3,
        continuous: !0,
        isSwipe: c,
        callback: function(b, c) {
            1 != d && (b + 1 > d && (2 == b && (b = 0), 3 == b && (b = 1)), $("#dot-slider" + a.length)[0].innerHTML = "<span>" + (b + 1) + "</span>/" + d)
        },
        oninit: function() {
            1 != d ? $("#dot-slider" + a.length)[0].innerHTML = "<span>1</span>/" + d: $("#dot-slider" + a.length)[0].innerHTML = ""
        }
    })
}
