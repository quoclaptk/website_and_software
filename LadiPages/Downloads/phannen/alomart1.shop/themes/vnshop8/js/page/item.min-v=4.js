function initLazyLoad() {
  $("img.lazyload").lazyload({
    threshold: 800,
    placeholder: BASE_URL + "res/images/rolling.gif"
  })
}

function goView() {
  $("#user-index-language-alert").show().addClass("fadeOutRightBig").addClass("").addClass("faster")
}
$(".reviews-items").on("click", ".review-content img", function() {
  var o = $(".pswp")[0],
    e = [],
    t = {
      src: $(this)[0].src,
      w: $(this)[0].naturalWidth,
      h: $(this)[0].naturalHeight
    };
  e.push(t);
  new PhotoSwipe(o, PhotoSwipeUI_Default, e, {
    tapToClose: !0,
    showHideOpacity: !0,
    loop: !1
  }).init()
});
var swiper = new Swiper(".swiper-container", {
  spaceBetween: 30,
  centeredSlides: true,
  loop:false,
  autoplay: {
    delay: 3500,
    disableOnInteraction: false,
    stopOnLast: true,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: !0
  }
});
$(function() {
  var e = 1,
    t = doT.template($("#J_ListHtml").text()),
    i = doT.template($("#J_List_like_Html").text());
  $(".sheet-open").on("click", function() {
    $($(this).data("sheet")).css("visibility", "visible").show().addClass("modal-in"), ".sheet-modal-reviews" == $(this).data("sheet") && $.ajax({
      url: SITE_URL + "/item/reviews",
      dataType: "json",
      data: {
        sid: goodsId,
        pageNumber: e,
        pageSize: 20
      },
      beforeSend: function() {
        $.loading(!0)
      },
      success: function(o) {
        "ok" == o.state && ($("#j_review_ListContent").append(t(o)), !o.lastPage, e++)
      },
      complete: function() {
        toLoadMore = !0, $.loading(!1)
      }
    })
  }), $(".sheet-modal").on("click", function() {
    var o = $(this);
    o.removeClass("modal-in").addClass("modal-out"), setTimeout(function() {
      o.css("visibility", "hidden"), o.removeClass("modal-out")
    }, 300)
  }), $(".sheet-close").on("click", function() {
    var o = $(this).closest(".sheet-modal");
    o.removeClass("modal-in").addClass("modal-out"), setTimeout(function() {
      o.css("visibility", "hidden"), o.removeClass("modal-out")
    }, 300)
  }), $(".share-container").on("click", function() {
    event.stopPropagation()
  });
  var o = !0,
    a = $(".nav-front"),
    s = $(".nav-back"),
    n = $(window),
    l = $(".scroll-to-top"),
    d = !1,
    r = !1;
  $(".scroll-to-top").on("click", function() {
    $("html,body").animate({
      scrollTop: 0
    }, 0)
  }), $(".share-container").on("click", "li", function() {
    ! function(o, e) {
      var t = encodeURIComponent(o);
      0 <= ["facebook", "whatsapp", "google", "line", "skype", "reload", "copy"].indexOf(e) && ("facebook" == e ? FB.ui({
        method: "share",
        display: "popup",
        href: o
      }, function() {}) : "whatsapp" == e ? window.open("https://wa.me/?text=" + t) : "google" == e ? window.open("https://plus.google.com/share?url=" + t) : "line" == e ? window.open("https://social-plugins.line.me/lineit/share?url=" + t) : "skype" == e ? window.open("https://web.skype.com/share?url=" + t) : "reload" == e ? window.location.reload() : "copy" == e && window.location.reload())
    }("https:" + SITE_URL + "/p/" + goodsId + "?s1=share", $(this).find(".icon").data("type"))
  }), initLazyLoad()
});
