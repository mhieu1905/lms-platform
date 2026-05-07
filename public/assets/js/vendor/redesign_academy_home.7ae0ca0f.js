"use strict";
(self.webpackChunkawwwards_new = self.webpackChunkawwwards_new || []).push([
  [5785],
  {
    877: (t, e, n) => {
      n(6342);
      var o = n(3378),
        r = n(9804),
        a = n(4892),
        c = n(1500);

      document.addEventListener("DOMContentLoaded", function () {
        new r.A();
        o.A.track();
        (0, a.A)(".js-offer-countdown");

        // Button offer icon animation
        document.querySelectorAll(".js-button-offer").forEach((t) => {
          const e = t.querySelector("lord-icon");
          if (e && "loop" !== e.getAttribute("trigger")) {
            t.addEventListener("mouseenter", () => {
              e.setAttribute("trigger", "loop");
            });
            t.addEventListener("mouseleave", () => {
              e.setAttribute("trigger", "hover");
            });
          }
        });

        // Ring text scroll rotation
        var t = document.querySelector(".js-ring-text"),
          e = window.scrollY,
          n = 0;

        function s() {
          var o = (window.scrollY - e) / 4;
          (function (t, e) {
            t.style.transform = "rotate(" + e + "deg)";
          })(t, (n += o));
          e = window.scrollY;
        }

        t &&
          new IntersectionObserver(function (t) {
            t.forEach(function (t) {
              t.isIntersecting
                ? window.addEventListener("scroll", s)
                : window.removeEventListener("scroll", s);
            });
          }, {}).observe(t);

        // Scroll paint text
        const i = document.querySelector(".js-scroll-paint-text");
        if (i) {
          const t = Array.from(i.childNodes);
          i.innerHTML = "";

          const e = (t) => {
            const e = document.createDocumentFragment();
            t.split(/(\s+)/).forEach((t) => {
              if (/\s+/.test(t)) {
                e.appendChild(document.createTextNode(t));
              } else {
                const n = document.createElement("span");
                n.classList.add("word");
                for (let e of t) {
                  const t = document.createElement("span");
                  t.classList.add("letter");
                  const o = document.createElement("span");
                  o.classList.add("normal");
                  o.textContent = e;
                  const r = document.createElement("span");
                  r.classList.add("bold");
                  r.textContent = e;
                  t.appendChild(o);
                  t.appendChild(r);
                  n.appendChild(t);
                }
                e.appendChild(n);
              }
            });
            return e;
          };

          t.forEach((t) => {
            t.nodeType === Node.TEXT_NODE
              ? i.appendChild(e(t.textContent))
              : ((t.nodeType === Node.ELEMENT_NODE &&
                  "a" === t.tagName.toLowerCase()) ||
                  t.nodeType === Node.ELEMENT_NODE) &&
                i.appendChild(t.cloneNode(!0));
          });

          const n = () => {
            const t = i.getBoundingClientRect(),
              e = window.innerHeight,
              n = Math.min(Math.max((e - t.top) / (e + t.height), 0), 1),
              o = Math.min(n / 0.8, 1),
              r = i.querySelectorAll(".letter"),
              a = r.length;

            r.forEach((t, e) => {
              const n = Math.min(Math.max((o - e / a) * a, 0), 1),
                r = t.querySelector(".bold");
              c.os.to(r, {
                width: 100 * n + "%",
                duration: 0.1,
                overwrite: !0,
              });
            });
          };

          window.addEventListener("scroll", n);
          n();
        }

        // Video play
        const d = document.querySelector(".js-video-play");
        d &&
          d.addEventListener("click", function () {
            let t = this.querySelector(".card-academy-video__video");
            this.classList.add("is-playing");
            t.play();
          });
      });
    },

    // Offer countdown
    4892: (t, e, n) => {
    function o(t) {
    const e = new Date();

    t.forEach((el) => {
      const endDateStr = el.dataset.dateEnd;
      const n = endDateStr ? new Date(endDateStr + " 23:59:59") : new Date(e.getTime() + 7*24*60*60*1000);
      const diff = n - e;
      const r = Math.floor(diff / 864e5);
      const a = Math.floor(diff / 36e5 % 24);
      const c = Math.floor(diff / 6e4 % 60);
      const s = Math.floor(diff / 1e3 % 60);

            el && (el.innerHTML = diff <= 0
                ? "00d : 00h : 00m : 00s"
                : `${r.toString().padStart(2,"0")}d : ${a.toString().padStart(2,"0")}h : ${c.toString().padStart(2,"0")}m : ${s.toString().padStart(2,"0")}s`);
        });
    }

      n.d(e, { A: () => r });

      const r = function (t = ".js-offer-countdown") {
        const e = document.querySelectorAll(t);
        o(e);
        const n = setInterval(() => {
          o(e),
            0 === new Date().getHours() &&
              0 === new Date().getMinutes() &&
              0 === new Date().getSeconds() &&
              clearInterval(n);
        }, 1e3);
      };
    },

    // Cart functions
    9804: (t, e, n) => {
      n.d(e, { A: () => u });
      var o = n(4079),
        r = n(1391),
        a = n.n(r),
        c = n(6425),
        s = n(5687),
        i = n(6292),
        d = n(7204);

      class l {
        constructor() {
          this.lightbox = null;
        }

        show() {
          this._loading();
          this._getCart();
        }

        add(t) {
          let e = t.dataset.id;
          this._loading();
          d.A.post(o.A.generate("add_product_cart", { uuid: e })).then(() => {
            this.show();
            (0, i.M2)(t);
          });
        }

        remove(t) {
          let e = t.dataset.id;
          this._loading();
          d.A.post(o.A.generate("tv_remove_product", { uuid: e })).then(() => {
            this.show();
            (0, i.GG)(t);
          });
        }

        checkout(t) {
          try {
            (0, i.J6)(t);
          } catch (t) {
            console.log("Error GTM");
          }
          location.href = t.href;
        }

        _getCart() {
          d.A.get(o.A.generate("cart")).then((t) => {
            this._openCart(t.data);
          });
        }

        _loading() {
          let t = document.createElement("div");
          t.classList.add("cart-sidebar");

          let e = document.createElement("div");
          e.classList.add("loading", "loading--white", "is-visible");
          e.innerHTML = '<div class="loading__spinner"></div>';

          t.appendChild(e);
          this._getOrCreateLightbox().show(t);
        }

        _openCart(t) {
          this._getOrCreateLightbox().show((0, s.zw)(t));

          let e = document.querySelectorAll(".list-cart li").length,
            n = document.querySelector(".cart-count"),
            o = n.querySelector(".js-bubble");

          if (null === o) {
            o = document.createElement("span");
            o.classList.add("js-bubble");
            n.appendChild(o);
          }

          o.innerHTML = e;
        }

        _getOrCreateLightbox() {
          return (
            null === this.lightbox &&
              (this.lightbox = new c.A({
                add_close_button: !1,
                remove_after_close: !0,
                extra_content_class: "modal__content--sidebar",
                add_container: !1,
                close_callback: () => {
                  this.lightbox = null;
                },
              })),
            this.lightbox
          );
        }
      }

      const u = a().View.extend({
        el: "body",
        events: {
          "click .js-add-cart-button": "_addCart",
          "click .js-show-cart-button": "_showCart",
          "click .js-remove-cart": "_removeCart",
          "click .js-checkout": "_checkout",
        },
        cart: null,
        initialize: function () {
          this.cart = new l();
        },
        _checkout: function (t) {
          t.preventDefault();
          t.stopPropagation();
          this.cart.checkout(t.currentTarget);
        },
        _addCart: function (t) {
          t.preventDefault();
          t.stopPropagation();
          this.cart.add(t.currentTarget);
        },
        _removeCart: function (t) {
          t.preventDefault();
          this.cart.remove(t.currentTarget);
        },
        _showCart: function () {
          this.cart.show();
        },
      });
    },
  },
  (t) => {
    t.O(0, [1500, 3116, 162, 966], () => {
      var e = 877;
      t((t.s = e));
    });
    t.O();
  },
]);
