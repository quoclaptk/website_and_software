$(function() {
  function o() {
    $("img.lazyload").lazyload({
      failure_limit: 10,
      threshold: 500,
      placeholder: BASE_URL + "res/images/rolling.gif"
    })
  }
  new Swiper(".home-header-banner", {
    autoplay: {
      delay: 3500,
      disableOnInteraction: !1
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: !0
    }
  });
  var i = 1,
    e = 20,
    t = doT.template($("#J_ListHtml").text()),
    a = ($("#c-loading-layer"), !0);
  var l = 0,
    n = !0,
    r = $(window),
    c = $(".scroll-to-top");
  $(window).on("scroll.sticky.qui", $.throttle(300, function() {
    200 < r.scrollTop() ? c.is(":visible") || c.show() : c.is(":visible") && c.hide(), 1 == a && 1 == n && (l = r.scrollTop(), (n = n = !1))
  })), $(".scroll-to-top").on("click", function() {
    $("html,body").animate({
      scrollTop: 0
    }, 0)
  }), o()
});
