if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(a){"use strict";var b=a.fn.jquery.split(" ")[0].split(".");if(b[0]<2&&b[1]<9||1==b[0]&&9==b[1]&&b[2]<1||b[0]>3)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")}(jQuery),+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one("bsTransitionEnd",function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b(),a.support.transition&&(a.event.special.bsTransitionEnd={bindType:a.support.transition.end,delegateType:a.support.transition.end,handle:function(b){if(a(b.target).is(this))return b.handleObj.handler.apply(this,arguments)}})})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var c=a(this),e=c.data("bs.alert");e||c.data("bs.alert",e=new d(this)),"string"==typeof b&&e[b].call(c)})}var c='[data-dismiss="alert"]',d=function(b){a(b).on("click",c,this.close)};d.VERSION="3.3.7",d.TRANSITION_DURATION=150,d.prototype.close=function(b){function c(){g.detach().trigger("closed.bs.alert").remove()}var e=a(this),f=e.attr("data-target");f||(f=e.attr("href"),f=f&&f.replace(/.*(?=#[^\s]*$)/,""));var g=a("#"===f?[]:f);b&&b.preventDefault(),g.length||(g=e.closest(".alert")),g.trigger(b=a.Event("close.bs.alert")),b.isDefaultPrevented()||(g.removeClass("in"),a.support.transition&&g.hasClass("fade")?g.one("bsTransitionEnd",c).emulateTransitionEnd(d.TRANSITION_DURATION):c())};var e=a.fn.alert;a.fn.alert=b,a.fn.alert.Constructor=d,a.fn.alert.noConflict=function(){return a.fn.alert=e,this},a(document).on("click.bs.alert.data-api",c,d.prototype.close)}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.button"),f="object"==typeof b&&b;e||d.data("bs.button",e=new c(this,f)),"toggle"==b?e.toggle():b&&e.setState(b)})}var c=function(b,d){this.$element=a(b),this.options=a.extend({},c.DEFAULTS,d),this.isLoading=!1};c.VERSION="3.3.7",c.DEFAULTS={loadingText:"loading..."},c.prototype.setState=function(b){var c="disabled",d=this.$element,e=d.is("input")?"val":"html",f=d.data();b+="Text",null==f.resetText&&d.data("resetText",d[e]()),setTimeout(a.proxy(function(){d[e](null==f[b]?this.options[b]:f[b]),"loadingText"==b?(this.isLoading=!0,d.addClass(c).attr(c,c).prop(c,!0)):this.isLoading&&(this.isLoading=!1,d.removeClass(c).removeAttr(c).prop(c,!1))},this),0)},c.prototype.toggle=function(){var a=!0,b=this.$element.closest('[data-toggle="buttons"]');if(b.length){var c=this.$element.find("input");"radio"==c.prop("type")?(c.prop("checked")&&(a=!1),b.find(".active").removeClass("active"),this.$element.addClass("active")):"checkbox"==c.prop("type")&&(c.prop("checked")!==this.$element.hasClass("active")&&(a=!1),this.$element.toggleClass("active")),c.prop("checked",this.$element.hasClass("active")),a&&c.trigger("change")}else this.$element.attr("aria-pressed",!this.$element.hasClass("active")),this.$element.toggleClass("active")};var d=a.fn.button;a.fn.button=b,a.fn.button.Constructor=c,a.fn.button.noConflict=function(){return a.fn.button=d,this},a(document).on("click.bs.button.data-api",'[data-toggle^="button"]',function(c){var d=a(c.target).closest(".btn");b.call(d,"toggle"),a(c.target).is('input[type="radio"], input[type="checkbox"]')||(c.preventDefault(),d.is("input,button")?d.trigger("focus"):d.find("input:visible,button:visible").first().trigger("focus"))}).on("focus.bs.button.data-api blur.bs.button.data-api",'[data-toggle^="button"]',function(b){a(b.target).closest(".btn").toggleClass("focus",/^focus(in)?$/.test(b.type))})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.carousel"),f=a.extend({},c.DEFAULTS,d.data(),"object"==typeof b&&b),g="string"==typeof b?b:f.slide;e||d.data("bs.carousel",e=new c(this,f)),"number"==typeof b?e.to(b):g?e[g]():f.interval&&e.pause().cycle()})}var c=function(b,c){this.$element=a(b),this.$indicators=this.$element.find(".carousel-indicators"),this.options=c,this.paused=null,this.sliding=null,this.interval=null,this.$active=null,this.$items=null,this.options.keyboard&&this.$element.on("keydown.bs.carousel",a.proxy(this.keydown,this)),"hover"==this.options.pause&&!("ontouchstart"in document.documentElement)&&this.$element.on("mouseenter.bs.carousel",a.proxy(this.pause,this)).on("mouseleave.bs.carousel",a.proxy(this.cycle,this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=600,c.DEFAULTS={interval:5e3,pause:"hover",wrap:!0,keyboard:!0},c.prototype.keydown=function(a){if(!/input|textarea/i.test(a.target.tagName)){switch(a.which){case 37:this.prev();break;case 39:this.next();break;default:return}a.preventDefault()}},c.prototype.cycle=function(b){return b||(this.paused=!1),this.interval&&clearInterval(this.interval),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},c.prototype.getItemIndex=function(a){return this.$items=a.parent().children(".item"),this.$items.index(a||this.$active)},c.prototype.getItemForDirection=function(a,b){var c=this.getItemIndex(b),d="prev"==a&&0===c||"next"==a&&c==this.$items.length-1;if(d&&!this.options.wrap)return b;var e="prev"==a?-1:1,f=(c+e)%this.$items.length;return this.$items.eq(f)},c.prototype.to=function(a){var b=this,c=this.getItemIndex(this.$active=this.$element.find(".item.active"));if(!(a>this.$items.length-1||a<0))return this.sliding?this.$element.one("slid.bs.carousel",function(){b.to(a)}):c==a?this.pause().cycle():this.slide(a>c?"next":"prev",this.$items.eq(a))},c.prototype.pause=function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition&&(this.$element.trigger(a.support.transition.end),this.cycle(!0)),this.interval=clearInterval(this.interval),this},c.prototype.next=function(){if(!this.sliding)return this.slide("next")},c.prototype.prev=function(){if(!this.sliding)return this.slide("prev")},c.prototype.slide=function(b,d){var e=this.$element.find(".item.active"),f=d||this.getItemForDirection(b,e),g=this.interval,h="next"==b?"left":"right",i=this;if(f.hasClass("active"))return this.sliding=!1;var j=f[0],k=a.Event("slide.bs.carousel",{relatedTarget:j,direction:h});if(this.$element.trigger(k),!k.isDefaultPrevented()){if(this.sliding=!0,g&&this.pause(),this.$indicators.length){this.$indicators.find(".active").removeClass("active");var l=a(this.$indicators.children()[this.getItemIndex(f)]);l&&l.addClass("active")}var m=a.Event("slid.bs.carousel",{relatedTarget:j,direction:h});return a.support.transition&&this.$element.hasClass("slide")?(f.addClass(b),f[0].offsetWidth,e.addClass(h),f.addClass(h),e.one("bsTransitionEnd",function(){f.removeClass([b,h].join(" ")).addClass("active"),e.removeClass(["active",h].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger(m)},0)}).emulateTransitionEnd(c.TRANSITION_DURATION)):(e.removeClass("active"),f.addClass("active"),this.sliding=!1,this.$element.trigger(m)),g&&this.cycle(),this}};var d=a.fn.carousel;a.fn.carousel=b,a.fn.carousel.Constructor=c,a.fn.carousel.noConflict=function(){return a.fn.carousel=d,this};var e=function(c){var d,e=a(this),f=a(e.attr("data-target")||(d=e.attr("href"))&&d.replace(/.*(?=#[^\s]+$)/,""));if(f.hasClass("carousel")){var g=a.extend({},f.data(),e.data()),h=e.attr("data-slide-to");h&&(g.interval=!1),b.call(f,g),h&&f.data("bs.carousel").to(h),c.preventDefault()}};a(document).on("click.bs.carousel.data-api","[data-slide]",e).on("click.bs.carousel.data-api","[data-slide-to]",e),a(window).on("load",function(){a('[data-ride="carousel"]').each(function(){var c=a(this);b.call(c,c.data())})})}(jQuery),+function(a){"use strict";function b(b){var c,d=b.attr("data-target")||(c=b.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,"");return a(d)}function c(b){return this.each(function(){var c=a(this),e=c.data("bs.collapse"),f=a.extend({},d.DEFAULTS,c.data(),"object"==typeof b&&b);!e&&f.toggle&&/show|hide/.test(b)&&(f.toggle=!1),e||c.data("bs.collapse",e=new d(this,f)),"string"==typeof b&&e[b]()})}var d=function(b,c){this.$element=a(b),this.options=a.extend({},d.DEFAULTS,c),this.$trigger=a('[data-toggle="collapse"][href="#'+b.id+'"],[data-toggle="collapse"][data-target="#'+b.id+'"]'),this.transitioning=null,this.options.parent?this.$parent=this.getParent():this.addAriaAndCollapsedClass(this.$element,this.$trigger),this.options.toggle&&this.toggle()};d.VERSION="3.3.7",d.TRANSITION_DURATION=350,d.DEFAULTS={toggle:!0},d.prototype.dimension=function(){var a=this.$element.hasClass("width");return a?"width":"height"},d.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var b,e=this.$parent&&this.$parent.children(".panel").children(".in, .collapsing");if(!(e&&e.length&&(b=e.data("bs.collapse"),b&&b.transitioning))){var f=a.Event("show.bs.collapse");if(this.$element.trigger(f),!f.isDefaultPrevented()){e&&e.length&&(c.call(e,"hide"),b||e.data("bs.collapse",null));var g=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[g](0).attr("aria-expanded",!0),this.$trigger.removeClass("collapsed").attr("aria-expanded",!0),this.transitioning=1;var h=function(){this.$element.removeClass("collapsing").addClass("collapse in")[g](""),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!a.support.transition)return h.call(this);var i=a.camelCase(["scroll",g].join("-"));this.$element.one("bsTransitionEnd",a.proxy(h,this)).emulateTransitionEnd(d.TRANSITION_DURATION)[g](this.$element[0][i])}}}},d.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var b=a.Event("hide.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.dimension();this.$element[c](this.$element[c]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded",!1),this.$trigger.addClass("collapsed").attr("aria-expanded",!1),this.transitioning=1;var e=function(){this.transitioning=0,this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")};return a.support.transition?void this.$element[c](0).one("bsTransitionEnd",a.proxy(e,this)).emulateTransitionEnd(d.TRANSITION_DURATION):e.call(this)}}},d.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()},d.prototype.getParent=function(){return a(this.options.parent).find('[data-toggle="collapse"][data-parent="'+this.options.parent+'"]').each(a.proxy(function(c,d){var e=a(d);this.addAriaAndCollapsedClass(b(e),e)},this)).end()},d.prototype.addAriaAndCollapsedClass=function(a,b){var c=a.hasClass("in");a.attr("aria-expanded",c),b.toggleClass("collapsed",!c).attr("aria-expanded",c)};var e=a.fn.collapse;a.fn.collapse=c,a.fn.collapse.Constructor=d,a.fn.collapse.noConflict=function(){return a.fn.collapse=e,this},a(document).on("click.bs.collapse.data-api",'[data-toggle="collapse"]',function(d){var e=a(this);e.attr("data-target")||d.preventDefault();var f=b(e),g=f.data("bs.collapse"),h=g?"toggle":e.data();c.call(f,h)})}(jQuery),+function(a){"use strict";function b(b){var c=b.attr("data-target");c||(c=b.attr("href"),c=c&&/#[A-Za-z]/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,""));var d=c&&a(c);return d&&d.length?d:b.parent()}function c(c){c&&3===c.which||(a(e).remove(),a(f).each(function(){var d=a(this),e=b(d),f={relatedTarget:this};e.hasClass("open")&&(c&&"click"==c.type&&/input|textarea/i.test(c.target.tagName)&&a.contains(e[0],c.target)||(e.trigger(c=a.Event("hide.bs.dropdown",f)),c.isDefaultPrevented()||(d.attr("aria-expanded","false"),e.removeClass("open").trigger(a.Event("hidden.bs.dropdown",f)))))}))}function d(b){return this.each(function(){var c=a(this),d=c.data("bs.dropdown");d||c.data("bs.dropdown",d=new g(this)),"string"==typeof b&&d[b].call(c)})}var e=".dropdown-backdrop",f='[data-toggle="dropdown"]',g=function(b){a(b).on("click.bs.dropdown",this.toggle)};g.VERSION="3.3.7",g.prototype.toggle=function(d){var e=a(this);if(!e.is(".disabled, :disabled")){var f=b(e),g=f.hasClass("open");if(c(),!g){"ontouchstart"in document.documentElement&&!f.closest(".navbar-nav").length&&a(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(a(this)).on("click",c);var h={relatedTarget:this};if(f.trigger(d=a.Event("show.bs.dropdown",h)),d.isDefaultPrevented())return;e.trigger("focus").attr("aria-expanded","true"),f.toggleClass("open").trigger(a.Event("shown.bs.dropdown",h))}return!1}},g.prototype.keydown=function(c){if(/(38|40|27|32)/.test(c.which)&&!/input|textarea/i.test(c.target.tagName)){var d=a(this);if(c.preventDefault(),c.stopPropagation(),!d.is(".disabled, :disabled")){var e=b(d),g=e.hasClass("open");if(!g&&27!=c.which||g&&27==c.which)return 27==c.which&&e.find(f).trigger("focus"),d.trigger("click");var h=" li:not(.disabled):visible a",i=e.find(".dropdown-menu"+h);if(i.length){var j=i.index(c.target);38==c.which&&j>0&&j--,40==c.which&&j<i.length-1&&j++,~j||(j=0),i.eq(j).trigger("focus")}}}};var h=a.fn.dropdown;a.fn.dropdown=d,a.fn.dropdown.Constructor=g,a.fn.dropdown.noConflict=function(){return a.fn.dropdown=h,this},a(document).on("click.bs.dropdown.data-api",c).on("click.bs.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.bs.dropdown.data-api",f,g.prototype.toggle).on("keydown.bs.dropdown.data-api",f,g.prototype.keydown).on("keydown.bs.dropdown.data-api",".dropdown-menu",g.prototype.keydown)}(jQuery),+function(a){"use strict";function b(b,d){return this.each(function(){var e=a(this),f=e.data("bs.modal"),g=a.extend({},c.DEFAULTS,e.data(),"object"==typeof b&&b);f||e.data("bs.modal",f=new c(this,g)),"string"==typeof b?f[b](d):g.show&&f.show(d)})}var c=function(b,c){this.options=c,this.$body=a(document.body),this.$element=a(b),this.$dialog=this.$element.find(".modal-dialog"),this.$backdrop=null,this.isShown=null,this.originalBodyPad=null,this.scrollbarWidth=0,this.ignoreBackdropClick=!1,this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,a.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=300,c.BACKDROP_TRANSITION_DURATION=150,c.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},c.prototype.toggle=function(a){return this.isShown?this.hide():this.show(a)},c.prototype.show=function(b){var d=this,e=a.Event("show.bs.modal",{relatedTarget:b});this.$element.trigger(e),this.isShown||e.isDefaultPrevented()||(this.isShown=!0,this.checkScrollbar(),this.setScrollbar(),this.$body.addClass("modal-open"),this.escape(),this.resize(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',a.proxy(this.hide,this)),this.$dialog.on("mousedown.dismiss.bs.modal",function(){d.$element.one("mouseup.dismiss.bs.modal",function(b){a(b.target).is(d.$element)&&(d.ignoreBackdropClick=!0)})}),this.backdrop(function(){var e=a.support.transition&&d.$element.hasClass("fade");d.$element.parent().length||d.$element.appendTo(d.$body),d.$element.show().scrollTop(0),d.adjustDialog(),e&&d.$element[0].offsetWidth,d.$element.addClass("in"),d.enforceFocus();var f=a.Event("shown.bs.modal",{relatedTarget:b});e?d.$dialog.one("bsTransitionEnd",function(){d.$element.trigger("focus").trigger(f)}).emulateTransitionEnd(c.TRANSITION_DURATION):d.$element.trigger("focus").trigger(f)}))},c.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.bs.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),this.resize(),a(document).off("focusin.bs.modal"),this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"),this.$dialog.off("mousedown.dismiss.bs.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one("bsTransitionEnd",a.proxy(this.hideModal,this)).emulateTransitionEnd(c.TRANSITION_DURATION):this.hideModal())},c.prototype.enforceFocus=function(){a(document).off("focusin.bs.modal").on("focusin.bs.modal",a.proxy(function(a){document===a.target||this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.trigger("focus")},this))},c.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.bs.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.bs.modal")},c.prototype.resize=function(){this.isShown?a(window).on("resize.bs.modal",a.proxy(this.handleUpdate,this)):a(window).off("resize.bs.modal")},c.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.$body.removeClass("modal-open"),a.resetAdjustments(),a.resetScrollbar(),a.$element.trigger("hidden.bs.modal")})},c.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},c.prototype.backdrop=function(b){var d=this,e=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var f=a.support.transition&&e;if(this.$backdrop=a(document.createElement("div")).addClass("modal-backdrop "+e).appendTo(this.$body),this.$element.on("click.dismiss.bs.modal",a.proxy(function(a){return this.ignoreBackdropClick?void(this.ignoreBackdropClick=!1):void(a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus():this.hide()))},this)),f&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;f?this.$backdrop.one("bsTransitionEnd",b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):b()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");var g=function(){d.removeBackdrop(),b&&b()};a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("bsTransitionEnd",g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):g()}else b&&b()},c.prototype.handleUpdate=function(){this.adjustDialog()},c.prototype.adjustDialog=function(){var a=this.$element[0].scrollHeight>document.documentElement.clientHeight;this.$element.css({paddingLeft:!this.bodyIsOverflowing&&a?this.scrollbarWidth:"",paddingRight:this.bodyIsOverflowing&&!a?this.scrollbarWidth:""})},c.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:"",paddingRight:""})},c.prototype.checkScrollbar=function(){var a=window.innerWidth;if(!a){var b=document.documentElement.getBoundingClientRect();a=b.right-Math.abs(b.left)}this.bodyIsOverflowing=document.body.clientWidth<a,this.scrollbarWidth=this.measureScrollbar()},c.prototype.setScrollbar=function(){var a=parseInt(this.$body.css("padding-right")||0,10);this.originalBodyPad=document.body.style.paddingRight||"",this.bodyIsOverflowing&&this.$body.css("padding-right",a+this.scrollbarWidth)},c.prototype.resetScrollbar=function(){this.$body.css("padding-right",this.originalBodyPad)},c.prototype.measureScrollbar=function(){var a=document.createElement("div");a.className="modal-scrollbar-measure",this.$body.append(a);var b=a.offsetWidth-a.clientWidth;return this.$body[0].removeChild(a),b};var d=a.fn.modal;a.fn.modal=b,a.fn.modal.Constructor=c,a.fn.modal.noConflict=function(){return a.fn.modal=d,this},a(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(c){var d=a(this),e=d.attr("href"),f=a(d.attr("data-target")||e&&e.replace(/.*(?=#[^\s]+$)/,"")),g=f.data("bs.modal")?"toggle":a.extend({remote:!/#/.test(e)&&e},f.data(),d.data());d.is("a")&&c.preventDefault(),f.one("show.bs.modal",function(a){a.isDefaultPrevented()||f.one("hidden.bs.modal",function(){d.is(":visible")&&d.trigger("focus")})}),b.call(f,g,this)})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tooltip"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("bs.tooltip",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",a,b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},c.prototype.init=function(b,c,d){if(this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d),this.$viewport=this.options.viewport&&a(a.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",i="hover"==g?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},c.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},c.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);return c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c)),b instanceof a.Event&&(c.inState["focusin"==b.type?"focus":"hover"]=!0),c.tip().hasClass("in")||"in"==c.hoverState?void(c.hoverState="in"):(clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show)):c.show())},c.prototype.isInStateTrue=function(){for(var a in this.inState)if(this.inState[a])return!0;return!1},c.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);if(c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c)),b instanceof a.Event&&(c.inState["focusout"==b.type?"focus":"hover"]=!1),!c.isInStateTrue())return clearTimeout(c.timeout),c.hoverState="out",c.options.delay&&c.options.delay.hide?void(c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide)):c.hide()},c.prototype.show=function(){var b=a.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(b);var d=a.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(b.isDefaultPrevented()||!d)return;var e=this,f=this.tip(),g=this.getUID(this.type);this.setContent(),f.attr("id",g),this.$element.attr("aria-describedby",g),this.options.animation&&f.addClass("fade");var h="function"==typeof this.options.placement?this.options.placement.call(this,f[0],this.$element[0]):this.options.placement,i=/\s?auto?\s?/i,j=i.test(h);j&&(h=h.replace(i,"")||"top"),f.detach().css({top:0,left:0,display:"block"}).addClass(h).data("bs."+this.type,this),this.options.container?f.appendTo(this.options.container):f.insertAfter(this.$element),this.$element.trigger("inserted.bs."+this.type);var k=this.getPosition(),l=f[0].offsetWidth,m=f[0].offsetHeight;if(j){var n=h,o=this.getPosition(this.$viewport);h="bottom"==h&&k.bottom+m>o.bottom?"top":"top"==h&&k.top-m<o.top?"bottom":"right"==h&&k.right+l>o.width?"left":"left"==h&&k.left-l<o.left?"right":h,f.removeClass(n).addClass(h)}var p=this.getCalculatedOffset(h,k,l,m);this.applyPlacement(p,h);var q=function(){var a=e.hoverState;e.$element.trigger("shown.bs."+e.type),e.hoverState=null,"out"==a&&e.leave(e)};a.support.transition&&this.$tip.hasClass("fade")?f.one("bsTransitionEnd",q).emulateTransitionEnd(c.TRANSITION_DURATION):q()}},c.prototype.applyPlacement=function(b,c){var d=this.tip(),e=d[0].offsetWidth,f=d[0].offsetHeight,g=parseInt(d.css("margin-top"),10),h=parseInt(d.css("margin-left"),10);isNaN(g)&&(g=0),isNaN(h)&&(h=0),b.top+=g,b.left+=h,a.offset.setOffset(d[0],a.extend({using:function(a){d.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),d.addClass("in");var i=d[0].offsetWidth,j=d[0].offsetHeight;"top"==c&&j!=f&&(b.top=b.top+f-j);var k=this.getViewportAdjustedDelta(c,b,i,j);k.left?b.left+=k.left:b.top+=k.top;var l=/top|bottom/.test(c),m=l?2*k.left-e+i:2*k.top-f+j,n=l?"offsetWidth":"offsetHeight";d.offset(b),this.replaceArrow(m,d[0][n],l)},c.prototype.replaceArrow=function(a,b,c){this.arrow().css(c?"left":"top",50*(1-a/b)+"%").css(c?"top":"left","")},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},c.prototype.hide=function(b){function d(){"in"!=e.hoverState&&f.detach(),e.$element&&e.$element.removeAttr("aria-describedby").trigger("hidden.bs."+e.type),b&&b()}var e=this,f=a(this.$tip),g=a.Event("hide.bs."+this.type);if(this.$element.trigger(g),!g.isDefaultPrevented())return f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one("bsTransitionEnd",d).emulateTransitionEnd(c.TRANSITION_DURATION):d(),this.hoverState=null,this},c.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},c.prototype.hasContent=function(){return this.getTitle()},c.prototype.getPosition=function(b){b=b||this.$element;var c=b[0],d="BODY"==c.tagName,e=c.getBoundingClientRect();null==e.width&&(e=a.extend({},e,{width:e.right-e.left,height:e.bottom-e.top}));var f=window.SVGElement&&c instanceof window.SVGElement,g=d?{top:0,left:0}:f?null:b.offset(),h={scroll:d?document.documentElement.scrollTop||document.body.scrollTop:b.scrollTop()},i=d?{width:a(window).width(),height:a(window).height()}:null;return a.extend({},e,h,i,g)},c.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},c.prototype.getViewportAdjustedDelta=function(a,b,c,d){var e={top:0,left:0};if(!this.$viewport)return e;var f=this.options.viewport&&this.options.viewport.padding||0,g=this.getPosition(this.$viewport);if(/right|left/.test(a)){var h=b.top-f-g.scroll,i=b.top+f-g.scroll+d;h<g.top?e.top=g.top-h:i>g.top+g.height&&(e.top=g.top+g.height-i)}else{var j=b.left-f,k=b.left+f+c;j<g.left?e.left=g.left-j:k>g.right&&(e.left=g.left+g.width-k)}return e},c.prototype.getTitle=function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||("function"==typeof c.title?c.title.call(b[0]):c.title)},c.prototype.getUID=function(a){do a+=~~(1e6*Math.random());while(document.getElementById(a));return a},c.prototype.tip=function(){if(!this.$tip&&(this.$tip=a(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},c.prototype.enable=function(){this.enabled=!0},c.prototype.disable=function(){this.enabled=!1},c.prototype.toggleEnabled=function(){this.enabled=!this.enabled},c.prototype.toggle=function(b){var c=this;b&&(c=a(b.currentTarget).data("bs."+this.type),c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c))),b?(c.inState.click=!c.inState.click,c.isInStateTrue()?c.enter(c):c.leave(c)):c.tip().hasClass("in")?c.leave(c):c.enter(c)},c.prototype.destroy=function(){var a=this;clearTimeout(this.timeout),this.hide(function(){a.$element.off("."+a.type).removeData("bs."+a.type),a.$tip&&a.$tip.detach(),a.$tip=null,a.$arrow=null,a.$viewport=null,a.$element=null})};var d=a.fn.tooltip;a.fn.tooltip=b,a.fn.tooltip.Constructor=c,a.fn.tooltip.noConflict=function(){return a.fn.tooltip=d,this}}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.popover"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("bs.popover",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.init("popover",a,b)};if(!a.fn.tooltip)throw new Error("Popover requires tooltip.js");c.VERSION="3.3.7",c.DEFAULTS=a.extend({},a.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}),c.prototype=a.extend({},a.fn.tooltip.Constructor.prototype),c.prototype.constructor=c,c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle(),c=this.getContent();a.find(".popover-title")[this.options.html?"html":"text"](b),a.find(".popover-content").children().detach().end()[this.options.html?"string"==typeof c?"html":"append":"text"](c),a.removeClass("fade top bottom left right in"),a.find(".popover-title").html()||a.find(".popover-title").hide()},c.prototype.hasContent=function(){return this.getTitle()||this.getContent()},c.prototype.getContent=function(){var a=this.$element,b=this.options;return a.attr("data-content")||("function"==typeof b.content?b.content.call(a[0]):b.content)},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")};var d=a.fn.popover;a.fn.popover=b,a.fn.popover.Constructor=c,a.fn.popover.noConflict=function(){return a.fn.popover=d,this}}(jQuery),+function(a){"use strict";function b(c,d){this.$body=a(document.body),this.$scrollElement=a(a(c).is(document.body)?window:c),this.options=a.extend({},b.DEFAULTS,d),this.selector=(this.options.target||"")+" .nav li > a",this.offsets=[],this.targets=[],this.activeTarget=null,this.scrollHeight=0,this.$scrollElement.on("scroll.bs.scrollspy",a.proxy(this.process,this)),this.refresh(),this.process()}function c(c){return this.each(function(){var d=a(this),e=d.data("bs.scrollspy"),f="object"==typeof c&&c;e||d.data("bs.scrollspy",e=new b(this,f)),"string"==typeof c&&e[c]()})}b.VERSION="3.3.7",b.DEFAULTS={offset:10},b.prototype.getScrollHeight=function(){return this.$scrollElement[0].scrollHeight||Math.max(this.$body[0].scrollHeight,document.documentElement.scrollHeight)},b.prototype.refresh=function(){var b=this,c="offset",d=0;this.offsets=[],this.targets=[],this.scrollHeight=this.getScrollHeight(),a.isWindow(this.$scrollElement[0])||(c="position",d=this.$scrollElement.scrollTop()),this.$body.find(this.selector).map(function(){var b=a(this),e=b.data("target")||b.attr("href"),f=/^#./.test(e)&&a(e);return f&&f.length&&f.is(":visible")&&[[f[c]().top+d,e]]||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){b.offsets.push(this[0]),b.targets.push(this[1])})},b.prototype.process=function(){var a,b=this.$scrollElement.scrollTop()+this.options.offset,c=this.getScrollHeight(),d=this.options.offset+c-this.$scrollElement.height(),e=this.offsets,f=this.targets,g=this.activeTarget;if(this.scrollHeight!=c&&this.refresh(),b>=d)return g!=(a=f[f.length-1])&&this.activate(a);if(g&&b<e[0])return this.activeTarget=null,this.clear();for(a=e.length;a--;)g!=f[a]&&b>=e[a]&&(void 0===e[a+1]||b<e[a+1])&&this.activate(f[a])},b.prototype.activate=function(b){
this.activeTarget=b,this.clear();var c=this.selector+'[data-target="'+b+'"],'+this.selector+'[href="'+b+'"]',d=a(c).parents("li").addClass("active");d.parent(".dropdown-menu").length&&(d=d.closest("li.dropdown").addClass("active")),d.trigger("activate.bs.scrollspy")},b.prototype.clear=function(){a(this.selector).parentsUntil(this.options.target,".active").removeClass("active")};var d=a.fn.scrollspy;a.fn.scrollspy=c,a.fn.scrollspy.Constructor=b,a.fn.scrollspy.noConflict=function(){return a.fn.scrollspy=d,this},a(window).on("load.bs.scrollspy.data-api",function(){a('[data-spy="scroll"]').each(function(){var b=a(this);c.call(b,b.data())})})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new c(this)),"string"==typeof b&&e[b]()})}var c=function(b){this.element=a(b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a"),f=a.Event("hide.bs.tab",{relatedTarget:b[0]}),g=a.Event("show.bs.tab",{relatedTarget:e[0]});if(e.trigger(f),b.trigger(g),!g.isDefaultPrevented()&&!f.isDefaultPrevented()){var h=a(d);this.activate(b.closest("li"),c),this.activate(h,h.parent(),function(){e.trigger({type:"hidden.bs.tab",relatedTarget:b[0]}),b.trigger({type:"shown.bs.tab",relatedTarget:e[0]})})}}},c.prototype.activate=function(b,d,e){function f(){g.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!1),b.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded",!0),h?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu").length&&b.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!0),e&&e()}var g=d.find("> .active"),h=e&&a.support.transition&&(g.length&&g.hasClass("fade")||!!d.find("> .fade").length);g.length&&h?g.one("bsTransitionEnd",f).emulateTransitionEnd(c.TRANSITION_DURATION):f(),g.removeClass("in")};var d=a.fn.tab;a.fn.tab=b,a.fn.tab.Constructor=c,a.fn.tab.noConflict=function(){return a.fn.tab=d,this};var e=function(c){c.preventDefault(),b.call(a(this),"show")};a(document).on("click.bs.tab.data-api",'[data-toggle="tab"]',e).on("click.bs.tab.data-api",'[data-toggle="pill"]',e)}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.affix"),f="object"==typeof b&&b;e||d.data("bs.affix",e=new c(this,f)),"string"==typeof b&&e[b]()})}var c=function(b,d){this.options=a.extend({},c.DEFAULTS,d),this.$target=a(this.options.target).on("scroll.bs.affix.data-api",a.proxy(this.checkPosition,this)).on("click.bs.affix.data-api",a.proxy(this.checkPositionWithEventLoop,this)),this.$element=a(b),this.affixed=null,this.unpin=null,this.pinnedOffset=null,this.checkPosition()};c.VERSION="3.3.7",c.RESET="affix affix-top affix-bottom",c.DEFAULTS={offset:0,target:window},c.prototype.getState=function(a,b,c,d){var e=this.$target.scrollTop(),f=this.$element.offset(),g=this.$target.height();if(null!=c&&"top"==this.affixed)return e<c&&"top";if("bottom"==this.affixed)return null!=c?!(e+this.unpin<=f.top)&&"bottom":!(e+g<=a-d)&&"bottom";var h=null==this.affixed,i=h?e:f.top,j=h?g:b;return null!=c&&e<=c?"top":null!=d&&i+j>=a-d&&"bottom"},c.prototype.getPinnedOffset=function(){if(this.pinnedOffset)return this.pinnedOffset;this.$element.removeClass(c.RESET).addClass("affix");var a=this.$target.scrollTop(),b=this.$element.offset();return this.pinnedOffset=b.top-a},c.prototype.checkPositionWithEventLoop=function(){setTimeout(a.proxy(this.checkPosition,this),1)},c.prototype.checkPosition=function(){if(this.$element.is(":visible")){var b=this.$element.height(),d=this.options.offset,e=d.top,f=d.bottom,g=Math.max(a(document).height(),a(document.body).height());"object"!=typeof d&&(f=e=d),"function"==typeof e&&(e=d.top(this.$element)),"function"==typeof f&&(f=d.bottom(this.$element));var h=this.getState(g,b,e,f);if(this.affixed!=h){null!=this.unpin&&this.$element.css("top","");var i="affix"+(h?"-"+h:""),j=a.Event(i+".bs.affix");if(this.$element.trigger(j),j.isDefaultPrevented())return;this.affixed=h,this.unpin="bottom"==h?this.getPinnedOffset():null,this.$element.removeClass(c.RESET).addClass(i).trigger(i.replace("affix","affixed")+".bs.affix")}"bottom"==h&&this.$element.offset({top:g-b-f})}};var d=a.fn.affix;a.fn.affix=b,a.fn.affix.Constructor=c,a.fn.affix.noConflict=function(){return a.fn.affix=d,this},a(window).on("load",function(){a('[data-spy="affix"]').each(function(){var c=a(this),d=c.data();d.offset=d.offset||{},null!=d.offsetBottom&&(d.offset.bottom=d.offsetBottom),null!=d.offsetTop&&(d.offset.top=d.offsetTop),b.call(c,d)})})}(jQuery);
!function(a,b,c,d){function e(b,c){this.settings=null,this.options=a.extend({},e.Defaults,c),this.$element=a(b),this.drag=a.extend({},m),this.state=a.extend({},n),this.e=a.extend({},o),this._plugins={},this._supress={},this._current=null,this._speed=null,this._coordinates=[],this._breakpoint=null,this._width=null,this._items=[],this._clones=[],this._mergers=[],this._invalidated={},this._pipe=[],a.each(e.Plugins,a.proxy(function(a,b){this._plugins[a[0].toLowerCase()+a.slice(1)]=new b(this)},this)),a.each(e.Pipe,a.proxy(function(b,c){this._pipe.push({filter:c.filter,run:a.proxy(c.run,this)})},this)),this.setup(),this.initialize()}function f(a){if(a.touches!==d)return{x:a.touches[0].pageX,y:a.touches[0].pageY};if(a.touches===d){if(a.pageX!==d)return{x:a.pageX,y:a.pageY};if(a.pageX===d)return{x:a.clientX,y:a.clientY}}}function g(a){var b,d,e=c.createElement("div"),f=a;for(b in f)if(d=f[b],"undefined"!=typeof e.style[d])return e=null,[d,b];return[!1]}function h(){return g(["transition","WebkitTransition","MozTransition","OTransition"])[1]}function i(){return g(["transform","WebkitTransform","MozTransform","OTransform","msTransform"])[0]}function j(){return g(["perspective","webkitPerspective","MozPerspective","OPerspective","MsPerspective"])[0]}function k(){return"ontouchstart"in b||!!navigator.msMaxTouchPoints}function l(){return b.navigator.msPointerEnabled}var m,n,o;m={start:0,startX:0,startY:0,current:0,currentX:0,currentY:0,offsetX:0,offsetY:0,distance:null,startTime:0,endTime:0,updatedX:0,targetEl:null},n={isTouch:!1,isScrolling:!1,isSwiping:!1,direction:!1,inMotion:!1},o={_onDragStart:null,_onDragMove:null,_onDragEnd:null,_transitionEnd:null,_resizer:null,_responsiveCall:null,_goToLoop:null,_checkVisibile:null},e.Defaults={items:3,loop:!1,center:!1,mouseDrag:!0,touchDrag:!0,pullDrag:!0,freeDrag:!1,margin:0,stagePadding:0,merge:!1,mergeFit:!0,autoWidth:!1,startPosition:0,rtl:!1,smartSpeed:250,fluidSpeed:!1,dragEndSpeed:!1,responsive:{},responsiveRefreshRate:200,responsiveBaseElement:b,responsiveClass:!1,fallbackEasing:"swing",info:!1,nestedItemSelector:!1,itemElement:"div",stageElement:"div",themeClass:"owl-theme",baseClass:"owl-carousel",itemClass:"owl-item",centerClass:"center",activeClass:"active"},e.Width={Default:"default",Inner:"inner",Outer:"outer"},e.Plugins={},e.Pipe=[{filter:["width","items","settings"],run:function(a){a.current=this._items&&this._items[this.relative(this._current)]}},{filter:["items","settings"],run:function(){var a=this._clones,b=this.$stage.children(".cloned");(b.length!==a.length||!this.settings.loop&&a.length>0)&&(this.$stage.children(".cloned").remove(),this._clones=[])}},{filter:["items","settings"],run:function(){var a,b,c=this._clones,d=this._items,e=this.settings.loop?c.length-Math.max(2*this.settings.items,4):0;for(a=0,b=Math.abs(e/2);b>a;a++)e>0?(this.$stage.children().eq(d.length+c.length-1).remove(),c.pop(),this.$stage.children().eq(0).remove(),c.pop()):(c.push(c.length/2),this.$stage.append(d[c[c.length-1]].clone().addClass("cloned")),c.push(d.length-1-(c.length-1)/2),this.$stage.prepend(d[c[c.length-1]].clone().addClass("cloned")))}},{filter:["width","items","settings"],run:function(){var a,b,c,d=this.settings.rtl?1:-1,e=(this.width()/this.settings.items).toFixed(3),f=0;for(this._coordinates=[],b=0,c=this._clones.length+this._items.length;c>b;b++)a=this._mergers[this.relative(b)],a=this.settings.mergeFit&&Math.min(a,this.settings.items)||a,f+=(this.settings.autoWidth?this._items[this.relative(b)].width()+this.settings.margin:e*a)*d,this._coordinates.push(f)}},{filter:["width","items","settings"],run:function(){var b,c,d=(this.width()/this.settings.items).toFixed(3),e={width:Math.abs(this._coordinates[this._coordinates.length-1])+2*this.settings.stagePadding,"padding-left":this.settings.stagePadding||"","padding-right":this.settings.stagePadding||""};if(this.$stage.css(e),e={width:this.settings.autoWidth?"auto":d-this.settings.margin},e[this.settings.rtl?"margin-left":"margin-right"]=this.settings.margin,!this.settings.autoWidth&&a.grep(this._mergers,function(a){return a>1}).length>0)for(b=0,c=this._coordinates.length;c>b;b++)e.width=Math.abs(this._coordinates[b])-Math.abs(this._coordinates[b-1]||0)-this.settings.margin,this.$stage.children().eq(b).css(e);else this.$stage.children().css(e)}},{filter:["width","items","settings"],run:function(a){a.current&&this.reset(this.$stage.children().index(a.current))}},{filter:["position"],run:function(){this.animate(this.coordinates(this._current))}},{filter:["width","position","items","settings"],run:function(){var a,b,c,d,e=this.settings.rtl?1:-1,f=2*this.settings.stagePadding,g=this.coordinates(this.current())+f,h=g+this.width()*e,i=[];for(c=0,d=this._coordinates.length;d>c;c++)a=this._coordinates[c-1]||0,b=Math.abs(this._coordinates[c])+f*e,(this.op(a,"<=",g)&&this.op(a,">",h)||this.op(b,"<",g)&&this.op(b,">",h))&&i.push(c);this.$stage.children("."+this.settings.activeClass).removeClass(this.settings.activeClass),this.$stage.children(":eq("+i.join("), :eq(")+")").addClass(this.settings.activeClass),this.settings.center&&(this.$stage.children("."+this.settings.centerClass).removeClass(this.settings.centerClass),this.$stage.children().eq(this.current()).addClass(this.settings.centerClass))}}],e.prototype.initialize=function(){if(this.trigger("initialize"),this.$element.addClass(this.settings.baseClass).addClass(this.settings.themeClass).toggleClass("owl-rtl",this.settings.rtl),this.browserSupport(),this.settings.autoWidth&&this.state.imagesLoaded!==!0){var b,c,e;if(b=this.$element.find("img"),c=this.settings.nestedItemSelector?"."+this.settings.nestedItemSelector:d,e=this.$element.children(c).width(),b.length&&0>=e)return this.preloadAutoWidthImages(b),!1}this.$element.addClass("owl-loading"),this.$stage=a("<"+this.settings.stageElement+' class="owl-stage"/>').wrap('<div class="owl-stage-outer">'),this.$element.append(this.$stage.parent()),this.replace(this.$element.children().not(this.$stage.parent())),this._width=this.$element.width(),this.refresh(),this.$element.removeClass("owl-loading").addClass("owl-loaded"),this.eventsCall(),this.internalEvents(),this.addTriggerableEvents(),this.trigger("initialized")},e.prototype.setup=function(){var b=this.viewport(),c=this.options.responsive,d=-1,e=null;c?(a.each(c,function(a){b>=a&&a>d&&(d=Number(a))}),e=a.extend({},this.options,c[d]),delete e.responsive,e.responsiveClass&&this.$element.attr("class",function(a,b){return b.replace(/\b owl-responsive-\S+/g,"")}).addClass("owl-responsive-"+d)):e=a.extend({},this.options),(null===this.settings||this._breakpoint!==d)&&(this.trigger("change",{property:{name:"settings",value:e}}),this._breakpoint=d,this.settings=e,this.invalidate("settings"),this.trigger("changed",{property:{name:"settings",value:this.settings}}))},e.prototype.optionsLogic=function(){this.$element.toggleClass("owl-center",this.settings.center),this.settings.loop&&this._items.length<this.settings.items&&(this.settings.loop=!1),this.settings.autoWidth&&(this.settings.stagePadding=!1,this.settings.merge=!1)},e.prototype.prepare=function(b){var c=this.trigger("prepare",{content:b});return c.data||(c.data=a("<"+this.settings.itemElement+"/>").addClass(this.settings.itemClass).append(b)),this.trigger("prepared",{content:c.data}),c.data},e.prototype.update=function(){for(var b=0,c=this._pipe.length,d=a.proxy(function(a){return this[a]},this._invalidated),e={};c>b;)(this._invalidated.all||a.grep(this._pipe[b].filter,d).length>0)&&this._pipe[b].run(e),b++;this._invalidated={}},e.prototype.width=function(a){switch(a=a||e.Width.Default){case e.Width.Inner:case e.Width.Outer:return this._width;default:return this._width-2*this.settings.stagePadding+this.settings.margin}},e.prototype.refresh=function(){if(0===this._items.length)return!1;(new Date).getTime();this.trigger("refresh"),this.setup(),this.optionsLogic(),this.$stage.addClass("owl-refresh"),this.update(),this.$stage.removeClass("owl-refresh"),this.state.orientation=b.orientation,this.watchVisibility(),this.trigger("refreshed")},e.prototype.eventsCall=function(){this.e._onDragStart=a.proxy(function(a){this.onDragStart(a)},this),this.e._onDragMove=a.proxy(function(a){this.onDragMove(a)},this),this.e._onDragEnd=a.proxy(function(a){this.onDragEnd(a)},this),this.e._onResize=a.proxy(function(a){this.onResize(a)},this),this.e._transitionEnd=a.proxy(function(a){this.transitionEnd(a)},this),this.e._preventClick=a.proxy(function(a){this.preventClick(a)},this)},e.prototype.onThrottledResize=function(){b.clearTimeout(this.resizeTimer),this.resizeTimer=b.setTimeout(this.e._onResize,this.settings.responsiveRefreshRate)},e.prototype.onResize=function(){return this._items.length?this._width===this.$element.width()?!1:this.trigger("resize").isDefaultPrevented()?!1:(this._width=this.$element.width(),this.invalidate("width"),this.refresh(),void this.trigger("resized")):!1},e.prototype.eventsRouter=function(a){var b=a.type;"mousedown"===b||"touchstart"===b?this.onDragStart(a):"mousemove"===b||"touchmove"===b?this.onDragMove(a):"mouseup"===b||"touchend"===b?this.onDragEnd(a):"touchcancel"===b&&this.onDragEnd(a)},e.prototype.internalEvents=function(){var c=(k(),l());this.settings.mouseDrag?(this.$stage.on("mousedown",a.proxy(function(a){this.eventsRouter(a)},this)),this.$stage.on("dragstart",function(){return!1}),this.$stage.get(0).onselectstart=function(){return!1}):this.$element.addClass("owl-text-select-on"),this.settings.touchDrag&&!c&&this.$stage.on("touchstart touchcancel",a.proxy(function(a){this.eventsRouter(a)},this)),this.transitionEndVendor&&this.on(this.$stage.get(0),this.transitionEndVendor,this.e._transitionEnd,!1),this.settings.responsive!==!1&&this.on(b,"resize",a.proxy(this.onThrottledResize,this))},e.prototype.onDragStart=function(d){var e,g,h,i;if(e=d.originalEvent||d||b.event,3===e.which||this.state.isTouch)return!1;if("mousedown"===e.type&&this.$stage.addClass("owl-grab"),this.trigger("drag"),this.drag.startTime=(new Date).getTime(),this.speed(0),this.state.isTouch=!0,this.state.isScrolling=!1,this.state.isSwiping=!1,this.drag.distance=0,g=f(e).x,h=f(e).y,this.drag.offsetX=this.$stage.position().left,this.drag.offsetY=this.$stage.position().top,this.settings.rtl&&(this.drag.offsetX=this.$stage.position().left+this.$stage.width()-this.width()+this.settings.margin),this.state.inMotion&&this.support3d)i=this.getTransformProperty(),this.drag.offsetX=i,this.animate(i),this.state.inMotion=!0;else if(this.state.inMotion&&!this.support3d)return this.state.inMotion=!1,!1;this.drag.startX=g-this.drag.offsetX,this.drag.startY=h-this.drag.offsetY,this.drag.start=g-this.drag.startX,this.drag.targetEl=e.target||e.srcElement,this.drag.updatedX=this.drag.start,("IMG"===this.drag.targetEl.tagName||"A"===this.drag.targetEl.tagName)&&(this.drag.targetEl.draggable=!1),a(c).on("mousemove.owl.dragEvents mouseup.owl.dragEvents touchmove.owl.dragEvents touchend.owl.dragEvents",a.proxy(function(a){this.eventsRouter(a)},this))},e.prototype.onDragMove=function(a){var c,e,g,h,i,j;this.state.isTouch&&(this.state.isScrolling||(c=a.originalEvent||a||b.event,e=f(c).x,g=f(c).y,this.drag.currentX=e-this.drag.startX,this.drag.currentY=g-this.drag.startY,this.drag.distance=this.drag.currentX-this.drag.offsetX,this.drag.distance<0?this.state.direction=this.settings.rtl?"right":"left":this.drag.distance>0&&(this.state.direction=this.settings.rtl?"left":"right"),this.settings.loop?this.op(this.drag.currentX,">",this.coordinates(this.minimum()))&&"right"===this.state.direction?this.drag.currentX-=(this.settings.center&&this.coordinates(0))-this.coordinates(this._items.length):this.op(this.drag.currentX,"<",this.coordinates(this.maximum()))&&"left"===this.state.direction&&(this.drag.currentX+=(this.settings.center&&this.coordinates(0))-this.coordinates(this._items.length)):(h=this.coordinates(this.settings.rtl?this.maximum():this.minimum()),i=this.coordinates(this.settings.rtl?this.minimum():this.maximum()),j=this.settings.pullDrag?this.drag.distance/5:0,this.drag.currentX=Math.max(Math.min(this.drag.currentX,h+j),i+j)),(this.drag.distance>8||this.drag.distance<-8)&&(c.preventDefault!==d?c.preventDefault():c.returnValue=!1,this.state.isSwiping=!0),this.drag.updatedX=this.drag.currentX,(this.drag.currentY>16||this.drag.currentY<-16)&&this.state.isSwiping===!1&&(this.state.isScrolling=!0,this.drag.updatedX=this.drag.start),this.animate(this.drag.updatedX)))},e.prototype.onDragEnd=function(b){var d,e,f;if(this.state.isTouch){if("mouseup"===b.type&&this.$stage.removeClass("owl-grab"),this.trigger("dragged"),this.drag.targetEl.removeAttribute("draggable"),this.state.isTouch=!1,this.state.isScrolling=!1,this.state.isSwiping=!1,0===this.drag.distance&&this.state.inMotion!==!0)return this.state.inMotion=!1,!1;this.drag.endTime=(new Date).getTime(),d=this.drag.endTime-this.drag.startTime,e=Math.abs(this.drag.distance),(e>3||d>300)&&this.removeClick(this.drag.targetEl),f=this.closest(this.drag.updatedX),this.speed(this.settings.dragEndSpeed||this.settings.smartSpeed),this.current(f),this.invalidate("position"),this.update(),this.settings.pullDrag||this.drag.updatedX!==this.coordinates(f)||this.transitionEnd(),this.drag.distance=0,a(c).off(".owl.dragEvents")}},e.prototype.removeClick=function(c){this.drag.targetEl=c,a(c).on("click.preventClick",this.e._preventClick),b.setTimeout(function(){a(c).off("click.preventClick")},300)},e.prototype.preventClick=function(b){b.preventDefault?b.preventDefault():b.returnValue=!1,b.stopPropagation&&b.stopPropagation(),a(b.target).off("click.preventClick")},e.prototype.getTransformProperty=function(){var a,c;return a=b.getComputedStyle(this.$stage.get(0),null).getPropertyValue(this.vendorName+"transform"),a=a.replace(/matrix(3d)?\(|\)/g,"").split(","),c=16===a.length,c!==!0?a[4]:a[12]},e.prototype.closest=function(b){var c=-1,d=30,e=this.width(),f=this.coordinates();return this.settings.freeDrag||a.each(f,a.proxy(function(a,g){return b>g-d&&g+d>b?c=a:this.op(b,"<",g)&&this.op(b,">",f[a+1]||g-e)&&(c="left"===this.state.direction?a+1:a),-1===c},this)),this.settings.loop||(this.op(b,">",f[this.minimum()])?c=b=this.minimum():this.op(b,"<",f[this.maximum()])&&(c=b=this.maximum())),c},e.prototype.animate=function(b){this.trigger("translate"),this.state.inMotion=this.speed()>0,this.support3d?this.$stage.css({transform:"translate3d("+b+"px,0px, 0px)",transition:this.speed()/1e3+"s"}):this.state.isTouch?this.$stage.css({left:b+"px"}):this.$stage.animate({left:b},this.speed()/1e3,this.settings.fallbackEasing,a.proxy(function(){this.state.inMotion&&this.transitionEnd()},this))},e.prototype.current=function(a){if(a===d)return this._current;if(0===this._items.length)return d;if(a=this.normalize(a),this._current!==a){var b=this.trigger("change",{property:{name:"position",value:a}});b.data!==d&&(a=this.normalize(b.data)),this._current=a,this.invalidate("position"),this.trigger("changed",{property:{name:"position",value:this._current}})}return this._current},e.prototype.invalidate=function(a){this._invalidated[a]=!0},e.prototype.reset=function(a){a=this.normalize(a),a!==d&&(this._speed=0,this._current=a,this.suppress(["translate","translated"]),this.animate(this.coordinates(a)),this.release(["translate","translated"]))},e.prototype.normalize=function(b,c){var e=c?this._items.length:this._items.length+this._clones.length;return!a.isNumeric(b)||1>e?d:b=this._clones.length?(b%e+e)%e:Math.max(this.minimum(c),Math.min(this.maximum(c),b))},e.prototype.relative=function(a){return a=this.normalize(a),a-=this._clones.length/2,this.normalize(a,!0)},e.prototype.maximum=function(a){var b,c,d,e=0,f=this.settings;if(a)return this._items.length-1;if(!f.loop&&f.center)b=this._items.length-1;else if(f.loop||f.center)if(f.loop||f.center)b=this._items.length+f.items;else{if(!f.autoWidth&&!f.merge)throw"Can not detect maximum absolute position.";for(revert=f.rtl?1:-1,c=this.$stage.width()-this.$element.width();(d=this.coordinates(e))&&!(d*revert>=c);)b=++e}else b=this._items.length-f.items;return b},e.prototype.minimum=function(a){return a?0:this._clones.length/2},e.prototype.items=function(a){return a===d?this._items.slice():(a=this.normalize(a,!0),this._items[a])},e.prototype.mergers=function(a){return a===d?this._mergers.slice():(a=this.normalize(a,!0),this._mergers[a])},e.prototype.clones=function(b){var c=this._clones.length/2,e=c+this._items.length,f=function(a){return a%2===0?e+a/2:c-(a+1)/2};return b===d?a.map(this._clones,function(a,b){return f(b)}):a.map(this._clones,function(a,c){return a===b?f(c):null})},e.prototype.speed=function(a){return a!==d&&(this._speed=a),this._speed},e.prototype.coordinates=function(b){var c=null;return b===d?a.map(this._coordinates,a.proxy(function(a,b){return this.coordinates(b)},this)):(this.settings.center?(c=this._coordinates[b],c+=(this.width()-c+(this._coordinates[b-1]||0))/2*(this.settings.rtl?-1:1)):c=this._coordinates[b-1]||0,c)},e.prototype.duration=function(a,b,c){return Math.min(Math.max(Math.abs(b-a),1),6)*Math.abs(c||this.settings.smartSpeed)},e.prototype.to=function(c,d){if(this.settings.loop){var e=c-this.relative(this.current()),f=this.current(),g=this.current(),h=this.current()+e,i=0>g-h?!0:!1,j=this._clones.length+this._items.length;h<this.settings.items&&i===!1?(f=g+this._items.length,this.reset(f)):h>=j-this.settings.items&&i===!0&&(f=g-this._items.length,this.reset(f)),b.clearTimeout(this.e._goToLoop),this.e._goToLoop=b.setTimeout(a.proxy(function(){this.speed(this.duration(this.current(),f+e,d)),this.current(f+e),this.update()},this),30)}else this.speed(this.duration(this.current(),c,d)),this.current(c),this.update()},e.prototype.next=function(a){a=a||!1,this.to(this.relative(this.current())+1,a)},e.prototype.prev=function(a){a=a||!1,this.to(this.relative(this.current())-1,a)},e.prototype.transitionEnd=function(a){return a!==d&&(a.stopPropagation(),(a.target||a.srcElement||a.originalTarget)!==this.$stage.get(0))?!1:(this.state.inMotion=!1,void this.trigger("translated"))},e.prototype.viewport=function(){var d;if(this.options.responsiveBaseElement!==b)d=a(this.options.responsiveBaseElement).width();else if(b.innerWidth)d=b.innerWidth;else{if(!c.documentElement||!c.documentElement.clientWidth)throw"Can not detect viewport width.";d=c.documentElement.clientWidth}return d},e.prototype.replace=function(b){this.$stage.empty(),this._items=[],b&&(b=b instanceof jQuery?b:a(b)),this.settings.nestedItemSelector&&(b=b.find("."+this.settings.nestedItemSelector)),b.filter(function(){return 1===this.nodeType}).each(a.proxy(function(a,b){b=this.prepare(b),this.$stage.append(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").andSelf("[data-merge]").attr("data-merge")||1)},this)),this.reset(a.isNumeric(this.settings.startPosition)?this.settings.startPosition:0),this.invalidate("items")},e.prototype.add=function(a,b){b=b===d?this._items.length:this.normalize(b,!0),this.trigger("add",{content:a,position:b}),0===this._items.length||b===this._items.length?(this.$stage.append(a),this._items.push(a),this._mergers.push(1*a.find("[data-merge]").andSelf("[data-merge]").attr("data-merge")||1)):(this._items[b].before(a),this._items.splice(b,0,a),this._mergers.splice(b,0,1*a.find("[data-merge]").andSelf("[data-merge]").attr("data-merge")||1)),this.invalidate("items"),this.trigger("added",{content:a,position:b})},e.prototype.remove=function(a){a=this.normalize(a,!0),a!==d&&(this.trigger("remove",{content:this._items[a],position:a}),this._items[a].remove(),this._items.splice(a,1),this._mergers.splice(a,1),this.invalidate("items"),this.trigger("removed",{content:null,position:a}))},e.prototype.addTriggerableEvents=function(){var b=a.proxy(function(b,c){return a.proxy(function(a){a.relatedTarget!==this&&(this.suppress([c]),b.apply(this,[].slice.call(arguments,1)),this.release([c]))},this)},this);a.each({next:this.next,prev:this.prev,to:this.to,destroy:this.destroy,refresh:this.refresh,replace:this.replace,add:this.add,remove:this.remove},a.proxy(function(a,c){this.$element.on(a+".owl.carousel",b(c,a+".owl.carousel"))},this))},e.prototype.watchVisibility=function(){function c(a){return a.offsetWidth>0&&a.offsetHeight>0}function d(){c(this.$element.get(0))&&(this.$element.removeClass("owl-hidden"),this.refresh(),b.clearInterval(this.e._checkVisibile))}c(this.$element.get(0))||(this.$element.addClass("owl-hidden"),b.clearInterval(this.e._checkVisibile),this.e._checkVisibile=b.setInterval(a.proxy(d,this),500))},e.prototype.preloadAutoWidthImages=function(b){var c,d,e,f;c=0,d=this,b.each(function(g,h){e=a(h),f=new Image,f.onload=function(){c++,e.attr("src",f.src),e.css("opacity",1),c>=b.length&&(d.state.imagesLoaded=!0,d.initialize())},f.src=e.attr("src")||e.attr("data-src")||e.attr("data-src-retina")})},e.prototype.destroy=function(){this.$element.hasClass(this.settings.themeClass)&&this.$element.removeClass(this.settings.themeClass),this.settings.responsive!==!1&&a(b).off("resize.owl.carousel"),this.transitionEndVendor&&this.off(this.$stage.get(0),this.transitionEndVendor,this.e._transitionEnd);for(var d in this._plugins)this._plugins[d].destroy();(this.settings.mouseDrag||this.settings.touchDrag)&&(this.$stage.off("mousedown touchstart touchcancel"),a(c).off(".owl.dragEvents"),this.$stage.get(0).onselectstart=function(){},this.$stage.off("dragstart",function(){return!1})),this.$element.off(".owl"),this.$stage.children(".cloned").remove(),this.e=null,this.$element.removeData("owlCarousel"),this.$stage.children().contents().unwrap(),this.$stage.children().unwrap(),this.$stage.unwrap()},e.prototype.op=function(a,b,c){var d=this.settings.rtl;switch(b){case"<":return d?a>c:c>a;case">":return d?c>a:a>c;case">=":return d?c>=a:a>=c;case"<=":return d?a>=c:c>=a}},e.prototype.on=function(a,b,c,d){a.addEventListener?a.addEventListener(b,c,d):a.attachEvent&&a.attachEvent("on"+b,c)},e.prototype.off=function(a,b,c,d){a.removeEventListener?a.removeEventListener(b,c,d):a.detachEvent&&a.detachEvent("on"+b,c)},e.prototype.trigger=function(b,c,d){var e={item:{count:this._items.length,index:this.current()}},f=a.camelCase(a.grep(["on",b,d],function(a){return a}).join("-").toLowerCase()),g=a.Event([b,"owl",d||"carousel"].join(".").toLowerCase(),a.extend({relatedTarget:this},e,c));return this._supress[b]||(a.each(this._plugins,function(a,b){b.onTrigger&&b.onTrigger(g)}),this.$element.trigger(g),this.settings&&"function"==typeof this.settings[f]&&this.settings[f].apply(this,g)),g},e.prototype.suppress=function(b){a.each(b,a.proxy(function(a,b){this._supress[b]=!0},this))},e.prototype.release=function(b){a.each(b,a.proxy(function(a,b){delete this._supress[b]},this))},e.prototype.browserSupport=function(){if(this.support3d=j(),this.support3d){this.transformVendor=i();var a=["transitionend","webkitTransitionEnd","transitionend","oTransitionEnd"];this.transitionEndVendor=a[h()],this.vendorName=this.transformVendor.replace(/Transform/i,""),this.vendorName=""!==this.vendorName?"-"+this.vendorName.toLowerCase()+"-":""}this.state.orientation=b.orientation},a.fn.owlCarousel=function(b){return this.each(function(){a(this).data("owlCarousel")||a(this).data("owlCarousel",new e(this,b))})},a.fn.owlCarousel.Constructor=e}(window.Zepto||window.jQuery,window,document),function(a,b){var c=function(b){this._core=b,this._loaded=[],this._handlers={"initialized.owl.carousel change.owl.carousel":a.proxy(function(b){if(b.namespace&&this._core.settings&&this._core.settings.lazyLoad&&(b.property&&"position"==b.property.name||"initialized"==b.type))for(var c=this._core.settings,d=c.center&&Math.ceil(c.items/2)||c.items,e=c.center&&-1*d||0,f=(b.property&&b.property.value||this._core.current())+e,g=this._core.clones().length,h=a.proxy(function(a,b){this.load(b)},this);e++<d;)this.load(g/2+this._core.relative(f)),g&&a.each(this._core.clones(this._core.relative(f++)),h)},this)},this._core.options=a.extend({},c.Defaults,this._core.options),this._core.$element.on(this._handlers)};c.Defaults={lazyLoad:!1},c.prototype.load=function(c){var d=this._core.$stage.children().eq(c),e=d&&d.find(".owl-lazy");!e||a.inArray(d.get(0),this._loaded)>-1||(e.each(a.proxy(function(c,d){var e,f=a(d),g=b.devicePixelRatio>1&&f.attr("data-src-retina")||f.attr("data-src");this._core.trigger("load",{element:f,url:g},"lazy"),f.is("img")?f.one("load.owl.lazy",a.proxy(function(){f.css("opacity",1),this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("src",g):(e=new Image,e.onload=a.proxy(function(){f.css({"background-image":"url("+g+")",opacity:"1"}),this._core.trigger("loaded",{element:f,url:g},"lazy")},this),e.src=g)},this)),this._loaded.push(d.get(0)))},c.prototype.destroy=function(){var a,b;for(a in this.handlers)this._core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Lazy=c}(window.Zepto||window.jQuery,window,document),function(a){var b=function(c){this._core=c,this._handlers={"initialized.owl.carousel":a.proxy(function(){this._core.settings.autoHeight&&this.update()},this),"changed.owl.carousel":a.proxy(function(a){this._core.settings.autoHeight&&"position"==a.property.name&&this.update()},this),"loaded.owl.lazy":a.proxy(function(a){this._core.settings.autoHeight&&a.element.closest("."+this._core.settings.itemClass)===this._core.$stage.children().eq(this._core.current())&&this.update()},this)},this._core.options=a.extend({},b.Defaults,this._core.options),this._core.$element.on(this._handlers)};b.Defaults={autoHeight:!1,autoHeightClass:"owl-height"},b.prototype.update=function(){this._core.$stage.parent().height(this._core.$stage.children().eq(this._core.current()).height()).addClass(this._core.settings.autoHeightClass)},b.prototype.destroy=function(){var a,b;for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoHeight=b}(window.Zepto||window.jQuery,window,document),function(a,b,c){var d=function(b){this._core=b,this._videos={},this._playing=null,this._fullscreen=!1,this._handlers={"resize.owl.carousel":a.proxy(function(a){this._core.settings.video&&!this.isInFullScreen()&&a.preventDefault()},this),"refresh.owl.carousel changed.owl.carousel":a.proxy(function(){this._playing&&this.stop()},this),"prepared.owl.carousel":a.proxy(function(b){var c=a(b.content).find(".owl-video");c.length&&(c.css("display","none"),this.fetch(c,a(b.content)))},this)},this._core.options=a.extend({},d.Defaults,this._core.options),this._core.$element.on(this._handlers),this._core.$element.on("click.owl.video",".owl-video-play-icon",a.proxy(function(a){this.play(a)},this))};d.Defaults={video:!1,videoHeight:!1,videoWidth:!1},d.prototype.fetch=function(a,b){var c=a.attr("data-vimeo-id")?"vimeo":"youtube",d=a.attr("data-vimeo-id")||a.attr("data-youtube-id"),e=a.attr("data-width")||this._core.settings.videoWidth,f=a.attr("data-height")||this._core.settings.videoHeight,g=a.attr("href");if(!g)throw new Error("Missing video URL.");if(d=g.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/),d[3].indexOf("youtu")>-1)c="youtube";else{if(!(d[3].indexOf("vimeo")>-1))throw new Error("Video URL not supported.");c="vimeo"}d=d[6],this._videos[g]={type:c,id:d,width:e,height:f},b.attr("data-video",g),this.thumbnail(a,this._videos[g])},d.prototype.thumbnail=function(b,c){var d,e,f,g=c.width&&c.height?'style="width:'+c.width+"px;height:"+c.height+'px;"':"",h=b.find("img"),i="src",j="",k=this._core.settings,l=function(a){e='<div class="owl-video-play-icon"></div>',d=k.lazyLoad?'<div class="owl-video-tn '+j+'" '+i+'="'+a+'"></div>':'<div class="owl-video-tn" style="opacity:1;background-image:url('+a+')"></div>',b.after(d),b.after(e)};return b.wrap('<div class="owl-video-wrapper"'+g+"></div>"),this._core.settings.lazyLoad&&(i="data-src",j="owl-lazy"),h.length?(l(h.attr(i)),h.remove(),!1):void("youtube"===c.type?(f="http://img.youtube.com/vi/"+c.id+"/hqdefault.jpg",l(f)):"vimeo"===c.type&&a.ajax({type:"GET",url:"http://vimeo.com/api/v2/video/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a[0].thumbnail_large,l(f)}}))},d.prototype.stop=function(){this._core.trigger("stop",null,"video"),this._playing.find(".owl-video-frame").remove(),this._playing.removeClass("owl-video-playing"),this._playing=null},d.prototype.play=function(b){this._core.trigger("play",null,"video"),this._playing&&this.stop();var c,d,e=a(b.target||b.srcElement),f=e.closest("."+this._core.settings.itemClass),g=this._videos[f.attr("data-video")],h=g.width||"100%",i=g.height||this._core.$stage.height();"youtube"===g.type?c='<iframe width="'+h+'" height="'+i+'" src="http://www.youtube.com/embed/'+g.id+"?autoplay=1&v="+g.id+'" frameborder="0" allowfullscreen></iframe>':"vimeo"===g.type&&(c='<iframe src="http://player.vimeo.com/video/'+g.id+'?autoplay=1" width="'+h+'" height="'+i+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'),f.addClass("owl-video-playing"),this._playing=f,d=a('<div style="height:'+i+"px; width:"+h+'px" class="owl-video-frame">'+c+"</div>"),e.after(d)},d.prototype.isInFullScreen=function(){var d=c.fullscreenElement||c.mozFullScreenElement||c.webkitFullscreenElement;return d&&a(d).parent().hasClass("owl-video-frame")&&(this._core.speed(0),this._fullscreen=!0),d&&this._fullscreen&&this._playing?!1:this._fullscreen?(this._fullscreen=!1,!1):this._playing&&this._core.state.orientation!==b.orientation?(this._core.state.orientation=b.orientation,!1):!0},d.prototype.destroy=function(){var a,b;this._core.$element.off("click.owl.video");for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Video=d}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this.core=b,this.core.options=a.extend({},e.Defaults,this.core.options),this.swapping=!0,this.previous=d,this.next=d,this.handlers={"change.owl.carousel":a.proxy(function(a){"position"==a.property.name&&(this.previous=this.core.current(),this.next=a.property.value)},this),"drag.owl.carousel dragged.owl.carousel translated.owl.carousel":a.proxy(function(a){this.swapping="translated"==a.type},this),"translate.owl.carousel":a.proxy(function(){this.swapping&&(this.core.options.animateOut||this.core.options.animateIn)&&this.swap()},this)},this.core.$element.on(this.handlers)};e.Defaults={animateOut:!1,animateIn:!1},e.prototype.swap=function(){if(1===this.core.settings.items&&this.core.support3d){this.core.speed(0);var b,c=a.proxy(this.clear,this),d=this.core.$stage.children().eq(this.previous),e=this.core.$stage.children().eq(this.next),f=this.core.settings.animateIn,g=this.core.settings.animateOut;this.core.current()!==this.previous&&(g&&(b=this.core.coordinates(this.previous)-this.core.coordinates(this.next),d.css({left:b+"px"}).addClass("animated owl-animated-out").addClass(g).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",c)),f&&e.addClass("animated owl-animated-in").addClass(f).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",c))}},e.prototype.clear=function(b){a(b.target).css({left:""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),this.core.transitionEnd()},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this.core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Animate=e}(window.Zepto||window.jQuery,window,document),function(a,b,c){var d=function(b){this.core=b,this.core.options=a.extend({},d.Defaults,this.core.options),this.handlers={"translated.owl.carousel refreshed.owl.carousel":a.proxy(function(){this.autoplay()
},this),"play.owl.autoplay":a.proxy(function(a,b,c){this.play(b,c)},this),"stop.owl.autoplay":a.proxy(function(){this.stop()},this),"mouseover.owl.autoplay":a.proxy(function(){this.core.settings.autoplayHoverPause&&this.pause()},this),"mouseleave.owl.autoplay":a.proxy(function(){this.core.settings.autoplayHoverPause&&this.autoplay()},this)},this.core.$element.on(this.handlers)};d.Defaults={autoplay:!1,autoplayTimeout:5e3,autoplayHoverPause:!1,autoplaySpeed:!1},d.prototype.autoplay=function(){this.core.settings.autoplay&&!this.core.state.videoPlay?(b.clearInterval(this.interval),this.interval=b.setInterval(a.proxy(function(){this.play()},this),this.core.settings.autoplayTimeout)):b.clearInterval(this.interval)},d.prototype.play=function(){return c.hidden===!0||this.core.state.isTouch||this.core.state.isScrolling||this.core.state.isSwiping||this.core.state.inMotion?void 0:this.core.settings.autoplay===!1?void b.clearInterval(this.interval):void this.core.next(this.core.settings.autoplaySpeed)},d.prototype.stop=function(){b.clearInterval(this.interval)},d.prototype.pause=function(){b.clearInterval(this.interval)},d.prototype.destroy=function(){var a,c;b.clearInterval(this.interval);for(a in this.handlers)this.core.$element.off(a,this.handlers[a]);for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},a.fn.owlCarousel.Constructor.Plugins.autoplay=d}(window.Zepto||window.jQuery,window,document),function(a){"use strict";var b=function(c){this._core=c,this._initialized=!1,this._pages=[],this._controls={},this._templates=[],this.$element=this._core.$element,this._overrides={next:this._core.next,prev:this._core.prev,to:this._core.to},this._handlers={"prepared.owl.carousel":a.proxy(function(b){this._core.settings.dotsData&&this._templates.push(a(b.content).find("[data-dot]").andSelf("[data-dot]").attr("data-dot"))},this),"add.owl.carousel":a.proxy(function(b){this._core.settings.dotsData&&this._templates.splice(b.position,0,a(b.content).find("[data-dot]").andSelf("[data-dot]").attr("data-dot"))},this),"remove.owl.carousel prepared.owl.carousel":a.proxy(function(a){this._core.settings.dotsData&&this._templates.splice(a.position,1)},this),"change.owl.carousel":a.proxy(function(a){if("position"==a.property.name&&!this._core.state.revert&&!this._core.settings.loop&&this._core.settings.navRewind){var b=this._core.current(),c=this._core.maximum(),d=this._core.minimum();a.data=a.property.value>c?b>=c?d:c:a.property.value<d?c:a.property.value}},this),"changed.owl.carousel":a.proxy(function(a){"position"==a.property.name&&this.draw()},this),"refreshed.owl.carousel":a.proxy(function(){this._initialized||(this.initialize(),this._initialized=!0),this._core.trigger("refresh",null,"navigation"),this.update(),this.draw(),this._core.trigger("refreshed",null,"navigation")},this)},this._core.options=a.extend({},b.Defaults,this._core.options),this.$element.on(this._handlers)};b.Defaults={nav:!1,navRewind:!0,navText:["prev","next"],navSpeed:!1,navElement:"div",navContainer:!1,navContainerClass:"owl-nav",navClass:["owl-prev","owl-next"],slideBy:1,dotClass:"owl-dot",dotsClass:"owl-dots",dots:!0,dotsEach:!1,dotData:!1,dotsSpeed:!1,dotsContainer:!1,controlsClass:"owl-controls"},b.prototype.initialize=function(){var b,c,d=this._core.settings;d.dotsData||(this._templates=[a("<div>").addClass(d.dotClass).append(a("<span>")).prop("outerHTML")]),d.navContainer&&d.dotsContainer||(this._controls.$container=a("<div>").addClass(d.controlsClass).appendTo(this.$element)),this._controls.$indicators=d.dotsContainer?a(d.dotsContainer):a("<div>").hide().addClass(d.dotsClass).appendTo(this._controls.$container),this._controls.$indicators.on("click","div",a.proxy(function(b){var c=a(b.target).parent().is(this._controls.$indicators)?a(b.target).index():a(b.target).parent().index();b.preventDefault(),this.to(c,d.dotsSpeed)},this)),b=d.navContainer?a(d.navContainer):a("<div>").addClass(d.navContainerClass).prependTo(this._controls.$container),this._controls.$next=a("<"+d.navElement+">"),this._controls.$previous=this._controls.$next.clone(),this._controls.$previous.addClass(d.navClass[0]).html(d.navText[0]).hide().prependTo(b).on("click",a.proxy(function(){this.prev(d.navSpeed)},this)),this._controls.$next.addClass(d.navClass[1]).html(d.navText[1]).hide().appendTo(b).on("click",a.proxy(function(){this.next(d.navSpeed)},this));for(c in this._overrides)this._core[c]=a.proxy(this[c],this)},b.prototype.destroy=function(){var a,b,c,d;for(a in this._handlers)this.$element.off(a,this._handlers[a]);for(b in this._controls)this._controls[b].remove();for(d in this.overides)this._core[d]=this._overrides[d];for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},b.prototype.update=function(){var a,b,c,d=this._core.settings,e=this._core.clones().length/2,f=e+this._core.items().length,g=d.center||d.autoWidth||d.dotData?1:d.dotsEach||d.items;if("page"!==d.slideBy&&(d.slideBy=Math.min(d.slideBy,d.items)),d.dots||"page"==d.slideBy)for(this._pages=[],a=e,b=0,c=0;f>a;a++)(b>=g||0===b)&&(this._pages.push({start:a-e,end:a-e+g-1}),b=0,++c),b+=this._core.mergers(this._core.relative(a))},b.prototype.draw=function(){var b,c,d="",e=this._core.settings,f=(this._core.$stage.children(),this._core.relative(this._core.current()));if(!e.nav||e.loop||e.navRewind||(this._controls.$previous.toggleClass("disabled",0>=f),this._controls.$next.toggleClass("disabled",f>=this._core.maximum())),this._controls.$previous.toggle(e.nav),this._controls.$next.toggle(e.nav),e.dots){if(b=this._pages.length-this._controls.$indicators.children().length,e.dotData&&0!==b){for(c=0;c<this._controls.$indicators.children().length;c++)d+=this._templates[this._core.relative(c)];this._controls.$indicators.html(d)}else b>0?(d=new Array(b+1).join(this._templates[0]),this._controls.$indicators.append(d)):0>b&&this._controls.$indicators.children().slice(b).remove();this._controls.$indicators.find(".active").removeClass("active"),this._controls.$indicators.children().eq(a.inArray(this.current(),this._pages)).addClass("active")}this._controls.$indicators.toggle(e.dots)},b.prototype.onTrigger=function(b){var c=this._core.settings;b.page={index:a.inArray(this.current(),this._pages),count:this._pages.length,size:c&&(c.center||c.autoWidth||c.dotData?1:c.dotsEach||c.items)}},b.prototype.current=function(){var b=this._core.relative(this._core.current());return a.grep(this._pages,function(a){return a.start<=b&&a.end>=b}).pop()},b.prototype.getPosition=function(b){var c,d,e=this._core.settings;return"page"==e.slideBy?(c=a.inArray(this.current(),this._pages),d=this._pages.length,b?++c:--c,c=this._pages[(c%d+d)%d].start):(c=this._core.relative(this._core.current()),d=this._core.items().length,b?c+=e.slideBy:c-=e.slideBy),c},b.prototype.next=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!0),b)},b.prototype.prev=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!1),b)},b.prototype.to=function(b,c,d){var e;d?a.proxy(this._overrides.to,this._core)(b,c):(e=this._pages.length,a.proxy(this._overrides.to,this._core)(this._pages[(b%e+e)%e].start,c))},a.fn.owlCarousel.Constructor.Plugins.Navigation=b}(window.Zepto||window.jQuery,window,document),function(a,b){"use strict";var c=function(d){this._core=d,this._hashes={},this.$element=this._core.$element,this._handlers={"initialized.owl.carousel":a.proxy(function(){"URLHash"==this._core.settings.startPosition&&a(b).trigger("hashchange.owl.navigation")},this),"prepared.owl.carousel":a.proxy(function(b){var c=a(b.content).find("[data-hash]").andSelf("[data-hash]").attr("data-hash");this._hashes[c]=b.content},this)},this._core.options=a.extend({},c.Defaults,this._core.options),this.$element.on(this._handlers),a(b).on("hashchange.owl.navigation",a.proxy(function(){var a=b.location.hash.substring(1),c=this._core.$stage.children(),d=this._hashes[a]&&c.index(this._hashes[a])||0;return a?void this._core.to(d,!1,!0):!1},this))};c.Defaults={URLhashListener:!1},c.prototype.destroy=function(){var c,d;a(b).off("hashchange.owl.navigation");for(c in this._handlers)this._core.$element.off(c,this._handlers[c]);for(d in Object.getOwnPropertyNames(this))"function"!=typeof this[d]&&(this[d]=null)},a.fn.owlCarousel.Constructor.Plugins.Hash=c}(window.Zepto||window.jQuery,window,document);
!function(e){e(["jquery"],function(e){return function(){function t(e,t,n){return g({type:O.error,iconClass:m().iconClasses.error,message:e,optionsOverride:n,title:t})}function n(t,n){return t||(t=m()),v=e("#"+t.containerId),v.length?v:(n&&(v=d(t)),v)}function o(e,t,n){return g({type:O.info,iconClass:m().iconClasses.info,message:e,optionsOverride:n,title:t})}function s(e){C=e}function i(e,t,n){return g({type:O.success,iconClass:m().iconClasses.success,message:e,optionsOverride:n,title:t})}function a(e,t,n){return g({type:O.warning,iconClass:m().iconClasses.warning,message:e,optionsOverride:n,title:t})}function r(e,t){var o=m();v||n(o),u(e,o,t)||l(o)}function c(t){var o=m();return v||n(o),t&&0===e(":focus",t).length?void h(t):void(v.children().length&&v.remove())}function l(t){for(var n=v.children(),o=n.length-1;o>=0;o--)u(e(n[o]),t)}function u(t,n,o){var s=!(!o||!o.force)&&o.force;return!(!t||!s&&0!==e(":focus",t).length)&&(t[n.hideMethod]({duration:n.hideDuration,easing:n.hideEasing,complete:function(){h(t)}}),!0)}function d(t){return v=e("<div/>").attr("id",t.containerId).addClass(t.positionClass),v.appendTo(e(t.target)),v}function p(){return{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,closeMethod:!1,closeDuration:!1,closeEasing:!1,closeOnHover:!0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",escapeHtml:!1,target:"body",closeHtml:'<button type="button">&times;</button>',closeClass:"toast-close-button",newestOnTop:!0,preventDuplicates:!1,progressBar:!1,progressClass:"toast-progress",rtl:!1}}function f(e){C&&C(e)}function g(t){function o(e){return null==e&&(e=""),e.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function s(){c(),u(),d(),p(),g(),C(),l(),i()}function i(){var e="";switch(t.iconClass){case"toast-success":case"toast-info":e="polite";break;default:e="assertive"}I.attr("aria-live",e)}function a(){E.closeOnHover&&I.hover(H,D),!E.onclick&&E.tapToDismiss&&I.click(b),E.closeButton&&j&&j.click(function(e){e.stopPropagation?e.stopPropagation():void 0!==e.cancelBubble&&e.cancelBubble!==!0&&(e.cancelBubble=!0),E.onCloseClick&&E.onCloseClick(e),b(!0)}),E.onclick&&I.click(function(e){E.onclick(e),b()})}function r(){I.hide(),I[E.showMethod]({duration:E.showDuration,easing:E.showEasing,complete:E.onShown}),E.timeOut>0&&(k=setTimeout(b,E.timeOut),F.maxHideTime=parseFloat(E.timeOut),F.hideEta=(new Date).getTime()+F.maxHideTime,E.progressBar&&(F.intervalId=setInterval(x,10)))}function c(){t.iconClass&&I.addClass(E.toastClass).addClass(y)}function l(){E.newestOnTop?v.prepend(I):v.append(I)}function u(){if(t.title){var e=t.title;E.escapeHtml&&(e=o(t.title)),M.append(e).addClass(E.titleClass),I.append(M)}}function d(){if(t.message){var e=t.message;E.escapeHtml&&(e=o(t.message)),B.append(e).addClass(E.messageClass),I.append(B)}}function p(){E.closeButton&&(j.addClass(E.closeClass).attr("role","button"),I.prepend(j))}function g(){E.progressBar&&(q.addClass(E.progressClass),I.prepend(q))}function C(){E.rtl&&I.addClass("rtl")}function O(e,t){if(e.preventDuplicates){if(t.message===w)return!0;w=t.message}return!1}function b(t){var n=t&&E.closeMethod!==!1?E.closeMethod:E.hideMethod,o=t&&E.closeDuration!==!1?E.closeDuration:E.hideDuration,s=t&&E.closeEasing!==!1?E.closeEasing:E.hideEasing;if(!e(":focus",I).length||t)return clearTimeout(F.intervalId),I[n]({duration:o,easing:s,complete:function(){h(I),clearTimeout(k),E.onHidden&&"hidden"!==P.state&&E.onHidden(),P.state="hidden",P.endTime=new Date,f(P)}})}function D(){(E.timeOut>0||E.extendedTimeOut>0)&&(k=setTimeout(b,E.extendedTimeOut),F.maxHideTime=parseFloat(E.extendedTimeOut),F.hideEta=(new Date).getTime()+F.maxHideTime)}function H(){clearTimeout(k),F.hideEta=0,I.stop(!0,!0)[E.showMethod]({duration:E.showDuration,easing:E.showEasing})}function x(){var e=(F.hideEta-(new Date).getTime())/F.maxHideTime*100;q.width(e+"%")}var E=m(),y=t.iconClass||E.iconClass;if("undefined"!=typeof t.optionsOverride&&(E=e.extend(E,t.optionsOverride),y=t.optionsOverride.iconClass||y),!O(E,t)){T++,v=n(E,!0);var k=null,I=e("<div/>"),M=e("<div/>"),B=e("<div/>"),q=e("<div/>"),j=e(E.closeHtml),F={intervalId:null,hideEta:null,maxHideTime:null},P={toastId:T,state:"visible",startTime:new Date,options:E,map:t};return s(),r(),a(),f(P),E.debug&&console&&console.log(P),I}}function m(){return e.extend({},p(),b.options)}function h(e){v||(v=n()),e.is(":visible")||(e.remove(),e=null,0===v.children().length&&(v.remove(),w=void 0))}var v,C,w,T=0,O={error:"error",info:"info",success:"success",warning:"warning"},b={clear:r,remove:c,error:t,getContainer:n,info:o,options:{},subscribe:s,success:i,version:"2.1.4",warning:a};return b}()})}("function"==typeof define&&define.amd?define:function(e,t){"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):window.toastr=t(window.jQuery)});

(function(t,e){"object"==typeof exports?module.exports=e():"function"==typeof define&&define.amd?define(e):t.Spinner=e()})(this,function(){"use strict";function t(t,e){var i,n=document.createElement(t||"div");for(i in e)n[i]=e[i];return n}function e(t){for(var e=1,i=arguments.length;i>e;e++)t.appendChild(arguments[e]);return t}function i(t,e,i,n){var r=["opacity",e,~~(100*t),i,n].join("-"),o=.01+100*(i/n),a=Math.max(1-(1-t)/e*(100-o),t),s=u.substring(0,u.indexOf("Animation")).toLowerCase(),l=s&&"-"+s+"-"||"";return c[r]||(p.insertRule("@"+l+"keyframes "+r+"{"+"0%{opacity:"+a+"}"+o+"%{opacity:"+t+"}"+(o+.01)+"%{opacity:1}"+(o+e)%100+"%{opacity:"+t+"}"+"100%{opacity:"+a+"}"+"}",p.cssRules.length),c[r]=1),r}function n(t,e){var i,n,r=t.style;for(e=e.charAt(0).toUpperCase()+e.slice(1),n=0;d.length>n;n++)if(i=d[n]+e,void 0!==r[i])return i;return void 0!==r[e]?e:void 0}function r(t,e){for(var i in e)t.style[n(t,i)||i]=e[i];return t}function o(t){for(var e=1;arguments.length>e;e++){var i=arguments[e];for(var n in i)void 0===t[n]&&(t[n]=i[n])}return t}function a(t,e){return"string"==typeof t?t:t[e%t.length]}function s(t){this.opts=o(t||{},s.defaults,f)}function l(){function i(e,i){return t("<"+e+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',i)}p.addRule(".spin-vml","behavior:url(#default#VML)"),s.prototype.lines=function(t,n){function o(){return r(i("group",{coordsize:d+" "+d,coordorigin:-u+" "+-u}),{width:d,height:d})}function s(t,s,l){e(p,e(r(o(),{rotation:360/n.lines*t+"deg",left:~~s}),e(r(i("roundrect",{arcsize:n.corners}),{width:u,height:n.width,left:n.radius,top:-n.width>>1,filter:l}),i("fill",{color:a(n.color,t),opacity:n.opacity}),i("stroke",{opacity:0}))))}var l,u=n.length+n.width,d=2*u,c=2*-(n.width+n.length)+"px",p=r(o(),{position:"absolute",top:c,left:c});if(n.shadow)for(l=1;n.lines>=l;l++)s(l,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(l=1;n.lines>=l;l++)s(l);return e(t,p)},s.prototype.opacity=function(t,e,i,n){var r=t.firstChild;n=n.shadow&&n.lines||0,r&&r.childNodes.length>e+n&&(r=r.childNodes[e+n],r=r&&r.firstChild,r=r&&r.firstChild,r&&(r.opacity=i))}}var u,d=["webkit","Moz","ms","O"],c={},p=function(){var i=t("style",{type:"text/css"});return e(document.getElementsByTagName("head")[0],i),i.sheet||i.styleSheet}(),f={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"50%",left:"50%",position:"absolute"};s.defaults={},o(s.prototype,{spin:function(e){this.stop();var i=this,n=i.opts,o=i.el=r(t(0,{className:n.className}),{position:n.position,width:0,zIndex:n.zIndex});if(n.radius+n.length+n.width,r(o,{left:n.left,top:n.top}),e&&e.insertBefore(o,e.firstChild||null),o.setAttribute("role","progressbar"),i.lines(o,i.opts),!u){var a,s=0,l=(n.lines-1)*(1-n.direction)/2,d=n.fps,c=d/n.speed,p=(1-n.opacity)/(c*n.trail/100),f=c/n.lines;(function h(){s++;for(var t=0;n.lines>t;t++)a=Math.max(1-(s+(n.lines-t)*f)%c*p,n.opacity),i.opacity(o,t*n.direction+l,a,n);i.timeout=i.el&&setTimeout(h,~~(1e3/d))})()}return i},stop:function(){var t=this.el;return t&&(clearTimeout(this.timeout),t.parentNode&&t.parentNode.removeChild(t),this.el=void 0),this},lines:function(n,o){function s(e,i){return r(t(),{position:"absolute",width:o.length+o.width+"px",height:o.width+"px",background:e,boxShadow:i,transformOrigin:"left",transform:"rotate("+~~(360/o.lines*d+o.rotate)+"deg) translate("+o.radius+"px"+",0)",borderRadius:(o.corners*o.width>>1)+"px"})}for(var l,d=0,c=(o.lines-1)*(1-o.direction)/2;o.lines>d;d++)l=r(t(),{position:"absolute",top:1+~(o.width/2)+"px",transform:o.hwaccel?"translate3d(0,0,0)":"",opacity:o.opacity,animation:u&&i(o.opacity,o.trail,c+d*o.direction,o.lines)+" "+1/o.speed+"s linear infinite"}),o.shadow&&e(l,r(s("#000","0 0 4px #000"),{top:"2px"})),e(n,e(l,s(a(o.color,d),"0 0 1px rgba(0,0,0,.1)")));return n},opacity:function(t,e,i){t.childNodes.length>e&&(t.childNodes[e].style.opacity=i)}});var h=r(t("group"),{behavior:"url(#default#VML)"});return!n(h,"transform")&&h.adj?l():u=n(h,"animation"),s});
(function(t,e){"object"==typeof exports?module.exports=e(require("spin.js")):"function"==typeof define&&define.amd?define(["spin"],e):t.Ladda=e(t.Spinner)})(this,function(t){"use strict";function e(t){if(t===void 0)return console.warn("Ladda button target must be defined."),void 0;t.querySelector(".ladda-label")||(t.innerHTML='<span class="ladda-label">'+t.innerHTML+"</span>");var e,n=document.createElement("span");n.className="ladda-spinner",t.appendChild(n);var r,a={start:function(){return e||(e=o(t)),t.setAttribute("disabled",""),t.setAttribute("data-loading",""),clearTimeout(r),e.spin(n),this.setProgress(0),this},startAfter:function(t){return clearTimeout(r),r=setTimeout(function(){a.start()},t),this},stop:function(){return t.removeAttribute("disabled"),t.removeAttribute("data-loading"),clearTimeout(r),e&&(r=setTimeout(function(){e.stop()},1e3)),this},toggle:function(){return this.isLoading()?this.stop():this.start(),this},setProgress:function(e){e=Math.max(Math.min(e,1),0);var n=t.querySelector(".ladda-progress");0===e&&n&&n.parentNode?n.parentNode.removeChild(n):(n||(n=document.createElement("div"),n.className="ladda-progress",t.appendChild(n)),n.style.width=(e||0)*t.offsetWidth+"px")},enable:function(){return this.stop(),this},disable:function(){return this.stop(),t.setAttribute("disabled",""),this},isLoading:function(){return t.hasAttribute("data-loading")},remove:function(){clearTimeout(r),t.removeAttribute("disabled",""),t.removeAttribute("data-loading",""),e&&(e.stop(),e=null);for(var n=0,i=u.length;i>n;n++)if(a===u[n]){u.splice(n,1);break}}};return u.push(a),a}function n(t,e){for(;t.parentNode&&t.tagName!==e;)t=t.parentNode;return e===t.tagName?t:void 0}function r(t){for(var e=["input","textarea"],n=[],r=0;e.length>r;r++)for(var a=t.getElementsByTagName(e[r]),i=0;a.length>i;i++)a[i].hasAttribute("required")&&n.push(a[i]);return n}function a(t,a){a=a||{};var i=[];"string"==typeof t?i=s(document.querySelectorAll(t)):"object"==typeof t&&"string"==typeof t.nodeName&&(i=[t]);for(var o=0,u=i.length;u>o;o++)(function(){var t=i[o];if("function"==typeof t.addEventListener){var s=e(t),u=-1;t.addEventListener("click",function(){var e=!0,i=n(t,"FORM");if(i!==void 0)for(var o=r(i),d=0;o.length>d;d++)""===o[d].value.replace(/^\s+|\s+$/g,"")&&(e=!1);e&&(s.startAfter(1),"number"==typeof a.timeout&&(clearTimeout(u),u=setTimeout(s.stop,a.timeout)),"function"==typeof a.callback&&a.callback.apply(null,[s]))},!1)}})()}function i(){for(var t=0,e=u.length;e>t;t++)u[t].stop()}function o(e){var n,r=e.offsetHeight;0===r&&(r=parseFloat(window.getComputedStyle(e).height)),r>32&&(r*=.8),e.hasAttribute("data-spinner-size")&&(r=parseInt(e.getAttribute("data-spinner-size"),10)),e.hasAttribute("data-spinner-color")&&(n=e.getAttribute("data-spinner-color"));var a=12,i=.2*r,o=.6*i,s=7>i?2:3;return new t({color:n||"#fff",lines:a,radius:i,length:o,width:s,zIndex:"auto",top:"auto",left:"auto",className:""})}function s(t){for(var e=[],n=0;t.length>n;n++)e.push(t[n]);return e}var u=[];return{bind:a,create:e,stopAll:i}});
var acp_name = 'hi';

$(function () {
    'use strict';
    $('.fileupload').click(function() {
        var url = '/ajaxupload/server/php/' + '?type=' + $(this).data('type') + '&id=' + $(this).data('id') + '&folder=' + $(this).data('folder');
        var box_html = $('#files_' + $(this).data('type') + '_' + $(this).data('id'));
        $('.fileupload').fileupload({
            url: url,
            dataType: 'json',
            done: function (e, data) {
                // console.log(data.result.files[0]);
                var file = data.result.files[0];
                box_html.html('');
                box_html.html('<p><img src="'+ file.url +'"></p><a href="javascript:;" data-url="'+ file.url +'" data-type="'+ $(this).data('type') +'" data-id="'+ $(this).data('id') +'" class="delete_img_upload_editor"><i class="fa fa-times"></i></a>');
                if ($(this).data('id') == 'portrait') {
                    $('input[name=c_mgs_portrait_image]').val(file.url);
                }
                if ($(this).data('id') == 'certificate') {
                    $('input[name=c_mgs_certificate_image]').val(file.url);
                }
                if ($(this).data('id') == 'customer_photo') {
                    $('input[name=cc_f_photo]').val(file.url);
                }
                if ($(this).data('id') == 'usually_question') {
                    $('input[name=us_photo]').val(file.url);
                }

                deleteFileUpload();
            },
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    })
    
});

$(document).ready(function() {
    deleteFileUpload();
})

function deleteFileUpload()
{
    $('.delete_img_upload_editor').click(function() {
        var url = $(this).data('url');
        var box_html = $('#files_' + $(this).data('type') + '_' + $(this).data('id'));
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/index/deleteFile',
            data: {'url':url},
            success:function(result) {
                if (result == 1) {
                    box_html.html('');
                    if (id == 'portrait') {
                        $('input[name=c_mgs_portrait_image]').val('');
                    }
                    if (id == 'certificate') {
                        $('input[name=c_mgs_certificate_image]').val('');
                    }
                    if (id == 'certificate') {
                        $('input[name=cc_f_photo]').val('');
                    }
                } else {
                    console.log('Fifle không tồn tại');
                }
            }
        })
    })
}
!function(s){"use strict";s.fn.mobileMenu=function(e){var i={MenuWidth:250,SlideSpeed:300,WindowsMaxWidth:767,PagePush:!0,FromLeft:!0,Overlay:!0,CollapseMenu:!0,ClassName:"mobile-menu"};return this.each(function(){function n(){1==d.FromLeft?c.css("left",-d.MenuWidth):c.css("right",-d.MenuWidth),c.find("ul:first").addClass(d.ClassName),g=d.ClassName,c.css("width",d.MenuWidth),c.find("."+g+" ul").css("display","none");var e='<span class="expand fa fa-plus"></span>';c.find("li ul").parent().prepend(e),s("."+g).append('<li style="height: 30px;"></li>'),s("."+g+" li:has(span)").each(function(){s(this).find("a:first").css("padding-right",55)})}function a(){var e=0,i=s(document).height();return c.find("."+g+" > li").each(function(){var i=s(this).height();e+=i}),i>=e&&(e=i),e}function l(e){C=s("."+g+" span.expand").height(),1===e&&c.find("."+g+" > li:has(span)").each(function(){var e=s(this).height(),i=(e-C)/2;s(this).find("span").css({"padding-bottom":i,"padding-top":i})}),2===e&&c.find("."+g+" > li > ul > li:has(span)").each(function(){var e=s(this).height(),i=(e-C)/2;s(this).find("span").css({"padding-bottom":i,"padding-top":i})})}function t(){u.addClass("mmPushBody"),1==d.Overlay?h.addClass("overlay"):h.addClass("overlay").css("opacity",0),c.css({display:"block",overflow:"hidden"}),1==d.FromLeft?(1==d.PagePush&&p.animate({left:d.MenuWidth},d.SlideSpeed,"linear"),c.animate({left:"0"},d.SlideSpeed,"linear",function(){c.css("height",a()),r=!0})):(1==d.PagePush&&p.animate({left:-d.MenuWidth},d.SlideSpeed,"linear"),c.animate({right:"0"},d.SlideSpeed,"linear",function(){c.css("height",a()),r=!0})),m||(l(1),m=!0)}function o(){1==d.FromLeft?(1==d.PagePush&&p.animate({left:"0"},d.SlideSpeed,"linear"),c.animate({left:-d.MenuWidth},d.SlideSpeed,"linear",function(){u.removeClass("mmPushBody"),h.css("height",0).removeClass("overlay"),c.css("display","none"),r=!1})):(1==d.PagePush&&p.animate({left:"0"},d.SlideSpeed,"linear"),c.animate({right:-d.MenuWidth},d.SlideSpeed,"linear",function(){u.removeClass("mmPushBody"),h.css("height",0).removeClass("overlay"),c.css("display","none"),r=!1}))}var d=s.extend({},i,e),c=s(this),h=s("#overlay"),u=s("body"),p=s("#page"),r=!1,m=!1,f=!1,C=0,g="";n(),s(".mm-toggle").click(function(){c.css("height",s(document).height()),1==d.Overlay&&h.css("height",s(document).height()),r?o():t()}),s(window).resize(function(){s(window).width()>=d.WindowsMaxWidth&&r&&c.css("left")!=-d.MenuWidth&&o()}),s("."+g+" > li > span.expand").click(function(){if(1==d.CollapseMenu){var e=s("."+g+" li span");e.hasClass("open")&&"none"===s(this).next().next().css("display")&&(s("."+g+" li ul").slideUp(),e.hasClass("open")?e.removeClass("fa fa-minus").addClass("fa fa-plus"):e.removeClass("fa fa-plus").addClass("fa fa-minus"),e.removeClass("open"))}s(this).nextAll("."+g+" ul").slideToggle(function(){1==d.CollapseMenu?s(this).promise().done(function(){c.css("height",a())}):c.css("height",a())}),s(this).hasClass("fa fa-plus")?s(this).removeClass("fa fa-plus").addClass("fa fa-minus"):s(this).removeClass("fa fa-minus").addClass("fa fa-plus"),s(this).toggleClass("open"),f||(l(2),f=!0)}),s("."+g+" > li > ul > li > span.expand").click(function(){if(1==d.CollapseMenu){var e=s("."+g+" li ul li span");e.hasClass("open")&&"none"===s(this).next().next().css("display")&&(s("."+g+" li ul ul").slideUp(),e.hasClass("open")?e.removeClass("fa fa-minus").addClass("fa fa-plus"):e.removeClass("fa fa-plus").addClass("fa fa-minus"),e.removeClass("open"))}s(this).nextAll("."+g+" ul ul").slideToggle(function(){1==d.CollapseMenu?s(this).promise().done(function(){c.css("height",a())}):c.css("height",a())}),s(this).hasClass("fa fa-plus")?s(this).removeClass("fa fa-plus").addClass("fa fa-minus"):s(this).removeClass("fa fa-minus").addClass("fa fa-plus"),s(this).toggleClass("open")}),s("."+g+" li a").click(function(){s("."+g+" li a").removeClass("active"),s(this).addClass("active"),o()}),h.click(function(){o()}),s("."+g+" li a.active").parent().children(".expand").removeClass("fa fa-plus").addClass("fa fa-minus open"),s("."+g+" li a.active").parent().children("ul").css("display","block")})}}(jQuery);
!function(e){e.fn.autoComplete=function(t){var o=e.extend({},e.fn.autoComplete.defaults,t);return"string"==typeof t?(this.each(function(){var o=e(this);"destroy"==t&&(e(window).off("resize.autocomplete",o.updateSC),o.off("blur.autocomplete focus.autocomplete keydown.autocomplete keyup.autocomplete"),o.data("autocomplete")?o.attr("autocomplete",o.data("autocomplete")):o.removeAttr("autocomplete"),e(o.data("sc")).remove(),o.removeData("sc").removeData("autocomplete"))}),this):this.each(function(){function t(e){var t=s.val();if(s.cache[t]=e,e.length&&t.length>=o.minChars){for(var a="",c=0;c<e.length;c++)a+=o.renderItem(e[c],t);s.sc.html(a),s.updateSC(0)}else s.sc.hide()}var s=e(this);s.sc=e('<div class="autocomplete-suggestions '+o.menuClass+'"></div>'),s.data("sc",s.sc).data("autocomplete",s.attr("autocomplete")),s.attr("autocomplete","off"),s.cache={},s.last_val="",s.updateSC=function(t,o){if(s.sc.css({top:s.offset().top+s.outerHeight(),left:s.offset().left,width:s.outerWidth()}),!t&&(s.sc.show(),s.sc.maxHeight||(s.sc.maxHeight=parseInt(s.sc.css("max-height"))),s.sc.suggestionHeight||(s.sc.suggestionHeight=e(".autocomplete-suggestion",s.sc).first().outerHeight()),s.sc.suggestionHeight))if(o){var a=s.sc.scrollTop(),c=o.offset().top-s.sc.offset().top;c+s.sc.suggestionHeight-s.sc.maxHeight>0?s.sc.scrollTop(c+s.sc.suggestionHeight+a-s.sc.maxHeight):0>c&&s.sc.scrollTop(c+a)}else s.sc.scrollTop(0)},e(window).on("resize.autocomplete",s.updateSC),s.sc.appendTo("body"),s.sc.on("mouseleave",".autocomplete-suggestion",function(){e(".autocomplete-suggestion.selected").removeClass("selected")}),s.sc.on("mouseenter",".autocomplete-suggestion",function(){e(".autocomplete-suggestion.selected").removeClass("selected"),e(this).addClass("selected")}),s.sc.on("mousedown click",".autocomplete-suggestion",function(t){var a=e(this),c=a.data("val");return(c||a.hasClass("autocomplete-suggestion"))&&(s.val(c),o.onSelect(t,c,a),s.sc.hide()),!1}),s.on("blur.autocomplete",function(){try{over_sb=e(".autocomplete-suggestions:hover").length}catch(t){over_sb=0}over_sb?s.is(":focus")||setTimeout(function(){s.focus()},20):(s.last_val=s.val(),s.sc.hide(),setTimeout(function(){s.sc.hide()},350))}),o.minChars||s.on("focus.autocomplete",function(){s.last_val="\n",s.trigger("keyup.autocomplete")}),s.on("keydown.autocomplete",function(t){if((40==t.which||38==t.which)&&s.sc.html()){var a,c=e(".autocomplete-suggestion.selected",s.sc);return c.length?(a=40==t.which?c.next(".autocomplete-suggestion"):c.prev(".autocomplete-suggestion"),a.length?(c.removeClass("selected"),s.val(a.addClass("selected").data("val"))):(c.removeClass("selected"),s.val(s.last_val),a=0)):(a=40==t.which?e(".autocomplete-suggestion",s.sc).first():e(".autocomplete-suggestion",s.sc).last(),s.val(a.addClass("selected").data("val"))),s.updateSC(0,a),!1}if(27==t.which)s.val(s.last_val).sc.hide();else if(13==t.which||9==t.which){var c=e(".autocomplete-suggestion.selected",s.sc);c.length&&s.sc.is(":visible")&&(o.onSelect(t,c.data("val"),c),setTimeout(function(){s.sc.hide()},20))}}),s.on("keyup.autocomplete",function(a){if(!~e.inArray(a.which,[13,27,35,36,37,38,39,40])){var c=s.val();if(c.length>=o.minChars){if(c!=s.last_val){if(s.last_val=c,clearTimeout(s.timer),o.cache){if(c in s.cache)return void t(s.cache[c]);for(var l=1;l<c.length-o.minChars;l++){var i=c.slice(0,c.length-l);if(i in s.cache&&!s.cache[i].length)return void t([])}}s.timer=setTimeout(function(){o.source(c,t)},o.delay)}}else s.last_val=c,s.sc.hide()}})})},e.fn.autoComplete.defaults={source:0,minChars:3,delay:150,cache:1,menuClass:"",renderItem:function(e,t){t=t.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&");var o=new RegExp("("+t.split(" ").join("|")+")","gi");return'<div class="autocomplete-suggestion" data-val="'+e+'">'+e.replace(o,"<b>$1</b>")+"</div>"},onSelect:function(e,t,o){}}}(jQuery);

(function(a){if(typeof define==="function"&&define.amd){define(["jquery"],a)}else{if(typeof exports==="object"){a(require("jquery"))}else{a(jQuery)}}}(function(d,f){if(!("indexOf" in Array.prototype)){Array.prototype.indexOf=function(k,j){if(j===f){j=0}if(j<0){j+=this.length}if(j<0){j=0}for(var l=this.length;j<l;j++){if(j in this&&this[j]===k){return j}}return -1}}function a(){var q,k,p,l,j,n,m,o;k=(new Date()).toString();p=((m=k.split("(")[1])!=null?m.slice(0,-1):0)||k.split(" ");if(p instanceof Array){n=[];for(var l=0,j=p.length;l<j;l++){o=p[l];if((q=(m=o.match(/\b[A-Z]+\b/))!==null)?m[0]:0){n.push(q)}}p=n.pop()}return p}function h(){return new Date(Date.UTC.apply(Date,arguments))}var g=function(k,j){var m=this;this.element=d(k);this.container=j.container||"body";this.language=j.language||this.element.data("date-language")||"en";this.language=this.language in e?this.language:this.language.split("-")[0];this.language=this.language in e?this.language:"en";this.isRTL=e[this.language].rtl||false;this.formatType=j.formatType||this.element.data("format-type")||"standard";this.format=c.parseFormat(j.format||this.element.data("date-format")||e[this.language].format||c.getDefaultFormat(this.formatType,"input"),this.formatType);this.isInline=false;this.isVisible=false;this.isInput=this.element.is("input");this.fontAwesome=j.fontAwesome||this.element.data("font-awesome")||false;this.bootcssVer=j.bootcssVer||(this.isInput?(this.element.is(".form-control")?3:2):(this.bootcssVer=this.element.is(".input-group")?3:2));this.component=this.element.is(".date")?(this.bootcssVer===3?this.element.find(".input-group-addon .glyphicon-th, .input-group-addon .glyphicon-time, .input-group-addon .glyphicon-remove, .input-group-addon .glyphicon-calendar, .input-group-addon .fa-calendar, .input-group-addon .fa-clock-o").parent():this.element.find(".add-on .icon-th, .add-on .icon-time, .add-on .icon-calendar, .add-on .fa-calendar, .add-on .fa-clock-o").parent()):false;this.componentReset=this.element.is(".date")?(this.bootcssVer===3?this.element.find(".input-group-addon .glyphicon-remove, .input-group-addon .fa-times").parent():this.element.find(".add-on .icon-remove, .add-on .fa-times").parent()):false;this.hasInput=this.component&&this.element.find("input").length;if(this.component&&this.component.length===0){this.component=false}this.linkField=j.linkField||this.element.data("link-field")||false;this.linkFormat=c.parseFormat(j.linkFormat||this.element.data("link-format")||c.getDefaultFormat(this.formatType,"link"),this.formatType);this.minuteStep=j.minuteStep||this.element.data("minute-step")||5;this.pickerPosition=j.pickerPosition||this.element.data("picker-position")||"bottom-right";this.showMeridian=j.showMeridian||this.element.data("show-meridian")||false;this.initialDate=j.initialDate||new Date();this.zIndex=j.zIndex||this.element.data("z-index")||f;this.title=typeof j.title==="undefined"?false:j.title;this.timezone=j.timezone||a();this.icons={leftArrow:this.fontAwesome?"fa-arrow-left":(this.bootcssVer===3?"glyphicon-arrow-left":"icon-arrow-left"),rightArrow:this.fontAwesome?"fa-arrow-right":(this.bootcssVer===3?"glyphicon-arrow-right":"icon-arrow-right")};this.icontype=this.fontAwesome?"fa":"glyphicon";this._attachEvents();this.clickedOutside=function(n){if(d(n.target).closest(".datetimepicker").length===0){m.hide()}};this.formatViewType="datetime";if("formatViewType" in j){this.formatViewType=j.formatViewType}else{if("formatViewType" in this.element.data()){this.formatViewType=this.element.data("formatViewType")}}this.minView=0;if("minView" in j){this.minView=j.minView}else{if("minView" in this.element.data()){this.minView=this.element.data("min-view")}}this.minView=c.convertViewMode(this.minView);this.maxView=c.modes.length-1;if("maxView" in j){this.maxView=j.maxView}else{if("maxView" in this.element.data()){this.maxView=this.element.data("max-view")}}this.maxView=c.convertViewMode(this.maxView);this.wheelViewModeNavigation=false;if("wheelViewModeNavigation" in j){this.wheelViewModeNavigation=j.wheelViewModeNavigation}else{if("wheelViewModeNavigation" in this.element.data()){this.wheelViewModeNavigation=this.element.data("view-mode-wheel-navigation")}}this.wheelViewModeNavigationInverseDirection=false;if("wheelViewModeNavigationInverseDirection" in j){this.wheelViewModeNavigationInverseDirection=j.wheelViewModeNavigationInverseDirection}else{if("wheelViewModeNavigationInverseDirection" in this.element.data()){this.wheelViewModeNavigationInverseDirection=this.element.data("view-mode-wheel-navigation-inverse-dir")}}this.wheelViewModeNavigationDelay=100;if("wheelViewModeNavigationDelay" in j){this.wheelViewModeNavigationDelay=j.wheelViewModeNavigationDelay}else{if("wheelViewModeNavigationDelay" in this.element.data()){this.wheelViewModeNavigationDelay=this.element.data("view-mode-wheel-navigation-delay")}}this.startViewMode=2;if("startView" in j){this.startViewMode=j.startView}else{if("startView" in this.element.data()){this.startViewMode=this.element.data("start-view")}}this.startViewMode=c.convertViewMode(this.startViewMode);this.viewMode=this.startViewMode;this.viewSelect=this.minView;if("viewSelect" in j){this.viewSelect=j.viewSelect}else{if("viewSelect" in this.element.data()){this.viewSelect=this.element.data("view-select")}}this.viewSelect=c.convertViewMode(this.viewSelect);this.forceParse=true;if("forceParse" in j){this.forceParse=j.forceParse}else{if("dateForceParse" in this.element.data()){this.forceParse=this.element.data("date-force-parse")}}var l=this.bootcssVer===3?c.templateV3:c.template;while(l.indexOf("{iconType}")!==-1){l=l.replace("{iconType}",this.icontype)}while(l.indexOf("{leftArrow}")!==-1){l=l.replace("{leftArrow}",this.icons.leftArrow)}while(l.indexOf("{rightArrow}")!==-1){l=l.replace("{rightArrow}",this.icons.rightArrow)}this.picker=d(l).appendTo(this.isInline?this.element:this.container).on({click:d.proxy(this.click,this),mousedown:d.proxy(this.mousedown,this)});if(this.wheelViewModeNavigation){if(d.fn.mousewheel){this.picker.on({mousewheel:d.proxy(this.mousewheel,this)})}else{console.log("Mouse Wheel event is not supported. Please include the jQuery Mouse Wheel plugin before enabling this option")}}if(this.isInline){this.picker.addClass("datetimepicker-inline")}else{this.picker.addClass("datetimepicker-dropdown-"+this.pickerPosition+" dropdown-menu")}if(this.isRTL){this.picker.addClass("datetimepicker-rtl");var i=this.bootcssVer===3?".prev span, .next span":".prev i, .next i";this.picker.find(i).toggleClass(this.icons.leftArrow+" "+this.icons.rightArrow)}d(document).on("mousedown touchend",this.clickedOutside);this.autoclose=false;if("autoclose" in j){this.autoclose=j.autoclose}else{if("dateAutoclose" in this.element.data()){this.autoclose=this.element.data("date-autoclose")}}this.keyboardNavigation=true;if("keyboardNavigation" in j){this.keyboardNavigation=j.keyboardNavigation}else{if("dateKeyboardNavigation" in this.element.data()){this.keyboardNavigation=this.element.data("date-keyboard-navigation")}}this.todayBtn=(j.todayBtn||this.element.data("date-today-btn")||false);this.clearBtn=(j.clearBtn||this.element.data("date-clear-btn")||false);this.todayHighlight=(j.todayHighlight||this.element.data("date-today-highlight")||false);this.weekStart=0;if(typeof j.weekStart!=="undefined"){this.weekStart=j.weekStart}else{if(typeof this.element.data("date-weekstart")!=="undefined"){this.weekStart=this.element.data("date-weekstart")}else{if(typeof e[this.language].weekStart!=="undefined"){this.weekStart=e[this.language].weekStart}}}this.weekStart=this.weekStart%7;this.weekEnd=((this.weekStart+6)%7);this.onRenderDay=function(n){var p=(j.onRenderDay||function(){return[]})(n);if(typeof p==="string"){p=[p]}var o=["day"];return o.concat((p?p:[]))};this.onRenderHour=function(n){var p=(j.onRenderHour||function(){return[]})(n);var o=["hour"];if(typeof p==="string"){p=[p]}return o.concat((p?p:[]))};this.onRenderMinute=function(n){var p=(j.onRenderMinute||function(){return[]})(n);var o=["minute"];if(typeof p==="string"){p=[p]}if(n<this.startDate||n>this.endDate){o.push("disabled")}else{if(Math.floor(this.date.getUTCMinutes()/this.minuteStep)===Math.floor(n.getUTCMinutes()/this.minuteStep)){o.push("active")}}return o.concat((p?p:[]))};this.onRenderYear=function(o){var q=(j.onRenderYear||function(){return[]})(o);var p=["year"];if(typeof q==="string"){q=[q]}if(this.date.getUTCFullYear()===o.getUTCFullYear()){p.push("active")}var n=o.getUTCFullYear();var r=this.endDate.getUTCFullYear();if(o<this.startDate||n>r){p.push("disabled")}return p.concat((q?q:[]))};this.onRenderMonth=function(n){var p=(j.onRenderMonth||function(){return[]})(n);var o=["month"];if(typeof p==="string"){p=[p]}return o.concat((p?p:[]))};this.startDate=new Date(-8639968443048000);this.endDate=new Date(8639968443048000);this.datesDisabled=[];this.daysOfWeekDisabled=[];this.setStartDate(j.startDate||this.element.data("date-startdate"));this.setEndDate(j.endDate||this.element.data("date-enddate"));this.setDatesDisabled(j.datesDisabled||this.element.data("date-dates-disabled"));this.setDaysOfWeekDisabled(j.daysOfWeekDisabled||this.element.data("date-days-of-week-disabled"));this.setMinutesDisabled(j.minutesDisabled||this.element.data("date-minute-disabled"));this.setHoursDisabled(j.hoursDisabled||this.element.data("date-hour-disabled"));this.fillDow();this.fillMonths();this.update();this.showMode();if(this.isInline){this.show()}};g.prototype={constructor:g,_events:[],_attachEvents:function(){this._detachEvents();if(this.isInput){this._events=[[this.element,{focus:d.proxy(this.show,this),keyup:d.proxy(this.update,this),keydown:d.proxy(this.keydown,this)}]]}else{if(this.component&&this.hasInput){this._events=[[this.element.find("input"),{focus:d.proxy(this.show,this),keyup:d.proxy(this.update,this),keydown:d.proxy(this.keydown,this)}],[this.component,{click:d.proxy(this.show,this)}]];if(this.componentReset){this._events.push([this.componentReset,{click:d.proxy(this.reset,this)}])}}else{if(this.element.is("div")){this.isInline=true}else{this._events=[[this.element,{click:d.proxy(this.show,this)}]]}}}for(var j=0,k,l;j<this._events.length;j++){k=this._events[j][0];l=this._events[j][1];k.on(l)}},_detachEvents:function(){for(var j=0,k,l;j<this._events.length;j++){k=this._events[j][0];l=this._events[j][1];k.off(l)}this._events=[]},show:function(i){this.picker.show();this.height=this.component?this.component.outerHeight():this.element.outerHeight();if(this.forceParse){this.update()}this.place();d(window).on("resize",d.proxy(this.place,this));if(i){i.stopPropagation();i.preventDefault()}this.isVisible=true;this.element.trigger({type:"show",date:this.date})},hide:function(){if(!this.isVisible){return}if(this.isInline){return}this.picker.hide();d(window).off("resize",this.place);this.viewMode=this.startViewMode;this.showMode();if(!this.isInput){d(document).off("mousedown",this.hide)}if(this.forceParse&&(this.isInput&&this.element.val()||this.hasInput&&this.element.find("input").val())){this.setValue()}this.isVisible=false;this.element.trigger({type:"hide",date:this.date})},remove:function(){this._detachEvents();d(document).off("mousedown",this.clickedOutside);this.picker.remove();delete this.picker;delete this.element.data().datetimepicker},getDate:function(){var i=this.getUTCDate();if(i===null){return null}return new Date(i.getTime()+(i.getTimezoneOffset()*60000))},getUTCDate:function(){return this.date},getInitialDate:function(){return this.initialDate},setInitialDate:function(i){this.initialDate=i},setDate:function(i){this.setUTCDate(new Date(i.getTime()-(i.getTimezoneOffset()*60000)))},setUTCDate:function(i){if(i>=this.startDate&&i<=this.endDate){this.date=i;this.setValue();this.viewDate=this.date;this.fill()}else{this.element.trigger({type:"outOfRange",date:i,startDate:this.startDate,endDate:this.endDate})}},setFormat:function(j){this.format=c.parseFormat(j,this.formatType);var i;if(this.isInput){i=this.element}else{if(this.component){i=this.element.find("input")}}if(i&&i.val()){this.setValue()}},setValue:function(){var i=this.getFormattedDate();if(!this.isInput){if(this.component){this.element.find("input").val(i)}this.element.data("date",i)}else{this.element.val(i)}if(this.linkField){d("#"+this.linkField).val(this.getFormattedDate(this.linkFormat))}},getFormattedDate:function(i){i=i||this.format;return c.formatDate(this.date,i,this.language,this.formatType,this.timezone)},setStartDate:function(i){this.startDate=i||this.startDate;if(this.startDate.valueOf()!==8639968443048000){this.startDate=c.parseDate(this.startDate,this.format,this.language,this.formatType,this.timezone)}this.update();this.updateNavArrows()},setEndDate:function(i){this.endDate=i||this.endDate;if(this.endDate.valueOf()!==8639968443048000){this.endDate=c.parseDate(this.endDate,this.format,this.language,this.formatType,this.timezone)}this.update();this.updateNavArrows()},setDatesDisabled:function(j){this.datesDisabled=j||[];if(!d.isArray(this.datesDisabled)){this.datesDisabled=this.datesDisabled.split(/,\s*/)}var i=this;this.datesDisabled=d.map(this.datesDisabled,function(k){return c.parseDate(k,i.format,i.language,i.formatType,i.timezone).toDateString()});this.update();this.updateNavArrows()},setTitle:function(i,j){return this.picker.find(i).find("th:eq(1)").text(this.title===false?j:this.title)},setDaysOfWeekDisabled:function(i){this.daysOfWeekDisabled=i||[];if(!d.isArray(this.daysOfWeekDisabled)){this.daysOfWeekDisabled=this.daysOfWeekDisabled.split(/,\s*/)}this.daysOfWeekDisabled=d.map(this.daysOfWeekDisabled,function(j){return parseInt(j,10)});this.update();this.updateNavArrows()},setMinutesDisabled:function(i){this.minutesDisabled=i||[];if(!d.isArray(this.minutesDisabled)){this.minutesDisabled=this.minutesDisabled.split(/,\s*/)}this.minutesDisabled=d.map(this.minutesDisabled,function(j){return parseInt(j,10)});this.update();this.updateNavArrows()},setHoursDisabled:function(i){this.hoursDisabled=i||[];if(!d.isArray(this.hoursDisabled)){this.hoursDisabled=this.hoursDisabled.split(/,\s*/)}this.hoursDisabled=d.map(this.hoursDisabled,function(j){return parseInt(j,10)});this.update();this.updateNavArrows()},place:function(){if(this.isInline){return}if(!this.zIndex){var j=0;d("div").each(function(){var o=parseInt(d(this).css("zIndex"),10);if(o>j){j=o}});this.zIndex=j+10}var n,m,l,k;if(this.container instanceof d){k=this.container.offset()}else{k=d(this.container).offset()}if(this.component){n=this.component.offset();l=n.left;if(this.pickerPosition==="bottom-left"||this.pickerPosition==="top-left"){l+=this.component.outerWidth()-this.picker.outerWidth()}}else{n=this.element.offset();l=n.left;if(this.pickerPosition==="bottom-left"||this.pickerPosition==="top-left"){l+=this.element.outerWidth()-this.picker.outerWidth()}}var i=document.body.clientWidth||window.innerWidth;if(l+220>i){l=i-220}if(this.pickerPosition==="top-left"||this.pickerPosition==="top-right"){m=n.top-this.picker.outerHeight()}else{m=n.top+this.height}m=m-k.top;l=l-k.left;this.picker.css({top:m,left:l,zIndex:this.zIndex})},hour_minute:"^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]",update:function(){var i,j=false;if(arguments&&arguments.length&&(typeof arguments[0]==="string"||arguments[0] instanceof Date)){i=arguments[0];j=true}else{i=(this.isInput?this.element.val():this.element.find("input").val())||this.element.data("date")||this.initialDate;if(typeof i==="string"){i=i.replace(/^\s+|\s+$/g,"")}}if(!i){i=new Date();j=false}if(typeof i==="string"){if(new RegExp(this.hour_minute).test(i)||new RegExp(this.hour_minute+":[0-5][0-9]").test(i)){i=this.getDate()}}this.date=c.parseDate(i,this.format,this.language,this.formatType,this.timezone);if(j){this.setValue()}if(this.date<this.startDate){this.viewDate=new Date(this.startDate)}else{if(this.date>this.endDate){this.viewDate=new Date(this.endDate)}else{this.viewDate=new Date(this.date)}}this.fill()},fillDow:function(){var i=this.weekStart,j="<tr>";while(i<this.weekStart+7){j+='<th class="dow">'+e[this.language].daysMin[(i++)%7]+"</th>"}j+="</tr>";this.picker.find(".datetimepicker-days thead").append(j)},fillMonths:function(){var l="";var m=new Date(this.viewDate);for(var k=0;k<12;k++){m.setUTCMonth(k);var j=this.onRenderMonth(m);l+='<span class="'+j.join(" ")+'">'+e[this.language].monthsShort[k]+"</span>"}this.picker.find(".datetimepicker-months td").html(l)},fill:function(){if(!this.date||!this.viewDate){return}var E=new Date(this.viewDate),t=E.getUTCFullYear(),G=E.getUTCMonth(),n=E.getUTCDate(),A=E.getUTCHours(),w=this.startDate.getUTCFullYear(),B=this.startDate.getUTCMonth(),p=this.endDate.getUTCFullYear(),x=this.endDate.getUTCMonth()+1,q=(new h(this.date.getUTCFullYear(),this.date.getUTCMonth(),this.date.getUTCDate())).valueOf(),D=new Date();this.setTitle(".datetimepicker-days",e[this.language].months[G]+" "+t);if(this.formatViewType==="time"){var k=this.getFormattedDate();this.setTitle(".datetimepicker-hours",k);this.setTitle(".datetimepicker-minutes",k)}else{this.setTitle(".datetimepicker-hours",n+" "+e[this.language].months[G]+" "+t);this.setTitle(".datetimepicker-minutes",n+" "+e[this.language].months[G]+" "+t)}this.picker.find("tfoot th.today").text(e[this.language].today||e.en.today).toggle(this.todayBtn!==false);this.picker.find("tfoot th.clear").text(e[this.language].clear||e.en.clear).toggle(this.clearBtn!==false);this.updateNavArrows();this.fillMonths();var I=h(t,G-1,28,0,0,0,0),z=c.getDaysInMonth(I.getUTCFullYear(),I.getUTCMonth());I.setUTCDate(z);I.setUTCDate(z-(I.getUTCDay()-this.weekStart+7)%7);var j=new Date(I);j.setUTCDate(j.getUTCDate()+42);j=j.valueOf();var r=[];var F;while(I.valueOf()<j){if(I.getUTCDay()===this.weekStart){r.push("<tr>")}F=this.onRenderDay(I);if(I.getUTCFullYear()<t||(I.getUTCFullYear()===t&&I.getUTCMonth()<G)){F.push("old")}else{if(I.getUTCFullYear()>t||(I.getUTCFullYear()===t&&I.getUTCMonth()>G)){F.push("new")}}if(this.todayHighlight&&I.getUTCFullYear()===D.getFullYear()&&I.getUTCMonth()===D.getMonth()&&I.getUTCDate()===D.getDate()){F.push("today")}if(I.valueOf()===q){F.push("active")}if((I.valueOf()+86400000)<=this.startDate||I.valueOf()>this.endDate||d.inArray(I.getUTCDay(),this.daysOfWeekDisabled)!==-1||d.inArray(I.toDateString(),this.datesDisabled)!==-1){F.push("disabled")}r.push('<td class="'+F.join(" ")+'">'+I.getUTCDate()+"</td>");if(I.getUTCDay()===this.weekEnd){r.push("</tr>")}I.setUTCDate(I.getUTCDate()+1)}this.picker.find(".datetimepicker-days tbody").empty().append(r.join(""));r=[];var u="",C="",s="";var l=this.hoursDisabled||[];E=new Date(this.viewDate);for(var y=0;y<24;y++){E.setUTCHours(y);F=this.onRenderHour(E);if(l.indexOf(y)!==-1){F.push("disabled")}var v=h(t,G,n,y);if((v.valueOf()+3600000)<=this.startDate||v.valueOf()>this.endDate){F.push("disabled")}else{if(A===y){F.push("active")}}if(this.showMeridian&&e[this.language].meridiem.length===2){C=(y<12?e[this.language].meridiem[0]:e[this.language].meridiem[1]);if(C!==s){if(s!==""){r.push("</fieldset>")}r.push('<fieldset class="hour"><legend>'+C.toUpperCase()+"</legend>")}s=C;u=(y%12?y%12:12);if(y<12){F.push("hour_am")}else{F.push("hour_pm")}r.push('<span class="'+F.join(" ")+'">'+u+"</span>");if(y===23){r.push("</fieldset>")}}else{u=y+":00";r.push('<span class="'+F.join(" ")+'">'+u+"</span>")}}this.picker.find(".datetimepicker-hours td").html(r.join(""));r=[];u="";C="";s="";var m=this.minutesDisabled||[];E=new Date(this.viewDate);for(var y=0;y<60;y+=this.minuteStep){if(m.indexOf(y)!==-1){continue}E.setUTCMinutes(y);E.setUTCSeconds(0);F=this.onRenderMinute(E);if(this.showMeridian&&e[this.language].meridiem.length===2){C=(A<12?e[this.language].meridiem[0]:e[this.language].meridiem[1]);if(C!==s){if(s!==""){r.push("</fieldset>")}r.push('<fieldset class="minute"><legend>'+C.toUpperCase()+"</legend>")}s=C;u=(A%12?A%12:12);r.push('<span class="'+F.join(" ")+'">'+u+":"+(y<10?"0"+y:y)+"</span>");if(y===59){r.push("</fieldset>")}}else{u=y+":00";r.push('<span class="'+F.join(" ")+'">'+A+":"+(y<10?"0"+y:y)+"</span>")}}this.picker.find(".datetimepicker-minutes td").html(r.join(""));var J=this.date.getUTCFullYear();var o=this.setTitle(".datetimepicker-months",t).end().find(".month").removeClass("active");if(J===t){o.eq(this.date.getUTCMonth()).addClass("active")}if(t<w||t>p){o.addClass("disabled")}if(t===w){o.slice(0,B).addClass("disabled")}if(t===p){o.slice(x).addClass("disabled")}r="";t=parseInt(t/10,10)*10;var H=this.setTitle(".datetimepicker-years",t+"-"+(t+9)).end().find("td");t-=1;E=new Date(this.viewDate);for(var y=-1;y<11;y++){E.setUTCFullYear(t);F=this.onRenderYear(E);if(y===-1||y===10){F.push(b)}r+='<span class="'+F.join(" ")+'">'+t+"</span>";t+=1}H.html(r);this.place()},updateNavArrows:function(){var m=new Date(this.viewDate),k=m.getUTCFullYear(),l=m.getUTCMonth(),j=m.getUTCDate(),i=m.getUTCHours();switch(this.viewMode){case 0:if(k<=this.startDate.getUTCFullYear()&&l<=this.startDate.getUTCMonth()&&j<=this.startDate.getUTCDate()&&i<=this.startDate.getUTCHours()){this.picker.find(".prev").css({visibility:"hidden"})}else{this.picker.find(".prev").css({visibility:"visible"})}if(k>=this.endDate.getUTCFullYear()&&l>=this.endDate.getUTCMonth()&&j>=this.endDate.getUTCDate()&&i>=this.endDate.getUTCHours()){this.picker.find(".next").css({visibility:"hidden"})}else{this.picker.find(".next").css({visibility:"visible"})}break;case 1:if(k<=this.startDate.getUTCFullYear()&&l<=this.startDate.getUTCMonth()&&j<=this.startDate.getUTCDate()){this.picker.find(".prev").css({visibility:"hidden"})}else{this.picker.find(".prev").css({visibility:"visible"})}if(k>=this.endDate.getUTCFullYear()&&l>=this.endDate.getUTCMonth()&&j>=this.endDate.getUTCDate()){this.picker.find(".next").css({visibility:"hidden"})}else{this.picker.find(".next").css({visibility:"visible"})}break;case 2:if(k<=this.startDate.getUTCFullYear()&&l<=this.startDate.getUTCMonth()){this.picker.find(".prev").css({visibility:"hidden"})}else{this.picker.find(".prev").css({visibility:"visible"})}if(k>=this.endDate.getUTCFullYear()&&l>=this.endDate.getUTCMonth()){this.picker.find(".next").css({visibility:"hidden"})}else{this.picker.find(".next").css({visibility:"visible"})}break;case 3:case 4:if(k<=this.startDate.getUTCFullYear()){this.picker.find(".prev").css({visibility:"hidden"})}else{this.picker.find(".prev").css({visibility:"visible"})}if(k>=this.endDate.getUTCFullYear()){this.picker.find(".next").css({visibility:"hidden"})}else{this.picker.find(".next").css({visibility:"visible"})}break}},mousewheel:function(j){j.preventDefault();j.stopPropagation();if(this.wheelPause){return}this.wheelPause=true;var i=j.originalEvent;var l=i.wheelDelta;var k=l>0?1:(l===0)?0:-1;if(this.wheelViewModeNavigationInverseDirection){k=-k}this.showMode(k);setTimeout(d.proxy(function(){this.wheelPause=false},this),this.wheelViewModeNavigationDelay)},click:function(m){m.stopPropagation();m.preventDefault();var n=d(m.target).closest("span, td, th, legend");if(n.is("."+this.icontype)){n=d(n).parent().closest("span, td, th, legend")}if(n.length===1){if(n.is(".disabled")){this.element.trigger({type:"outOfRange",date:this.viewDate,startDate:this.startDate,endDate:this.endDate});return}switch(n[0].nodeName.toLowerCase()){case"th":switch(n[0].className){case"switch":this.showMode(1);break;case"prev":case"next":var i=c.modes[this.viewMode].navStep*(n[0].className==="prev"?-1:1);switch(this.viewMode){case 0:this.viewDate=this.moveHour(this.viewDate,i);break;case 1:this.viewDate=this.moveDate(this.viewDate,i);break;case 2:this.viewDate=this.moveMonth(this.viewDate,i);break;case 3:case 4:this.viewDate=this.moveYear(this.viewDate,i);break}this.fill();this.element.trigger({type:n[0].className+":"+this.convertViewModeText(this.viewMode),date:this.viewDate,startDate:this.startDate,endDate:this.endDate});break;case"clear":this.reset();if(this.autoclose){this.hide()}break;case"today":var j=new Date();j=h(j.getFullYear(),j.getMonth(),j.getDate(),j.getHours(),j.getMinutes(),j.getSeconds(),0);if(j<this.startDate){j=this.startDate}else{if(j>this.endDate){j=this.endDate}}this.viewMode=this.startViewMode;this.showMode(0);this._setDate(j);this.fill();if(this.autoclose){this.hide()}break}break;case"span":if(!n.is(".disabled")){var p=this.viewDate.getUTCFullYear(),o=this.viewDate.getUTCMonth(),q=this.viewDate.getUTCDate(),r=this.viewDate.getUTCHours(),k=this.viewDate.getUTCMinutes(),s=this.viewDate.getUTCSeconds();if(n.is(".month")){this.viewDate.setUTCDate(1);o=n.parent().find("span").index(n);q=this.viewDate.getUTCDate();this.viewDate.setUTCMonth(o);this.element.trigger({type:"changeMonth",date:this.viewDate});if(this.viewSelect>=3){this._setDate(h(p,o,q,r,k,s,0))}}else{if(n.is(".year")){this.viewDate.setUTCDate(1);p=parseInt(n.text(),10)||0;this.viewDate.setUTCFullYear(p);this.element.trigger({type:"changeYear",date:this.viewDate});if(this.viewSelect>=4){this._setDate(h(p,o,q,r,k,s,0))}}else{if(n.is(".hour")){r=parseInt(n.text(),10)||0;if(n.hasClass("hour_am")||n.hasClass("hour_pm")){if(r===12&&n.hasClass("hour_am")){r=0}else{if(r!==12&&n.hasClass("hour_pm")){r+=12}}}this.viewDate.setUTCHours(r);this.element.trigger({type:"changeHour",date:this.viewDate});if(this.viewSelect>=1){this._setDate(h(p,o,q,r,k,s,0))}}else{if(n.is(".minute")){k=parseInt(n.text().substr(n.text().indexOf(":")+1),10)||0;this.viewDate.setUTCMinutes(k);this.element.trigger({type:"changeMinute",date:this.viewDate});if(this.viewSelect>=0){this._setDate(h(p,o,q,r,k,s,0))}}}}}if(this.viewMode!==0){var l=this.viewMode;this.showMode(-1);this.fill();if(l===this.viewMode&&this.autoclose){this.hide()}}else{this.fill();if(this.autoclose){this.hide()}}}break;case"td":if(n.is(".day")&&!n.is(".disabled")){var q=parseInt(n.text(),10)||1;var p=this.viewDate.getUTCFullYear(),o=this.viewDate.getUTCMonth(),r=this.viewDate.getUTCHours(),k=this.viewDate.getUTCMinutes(),s=this.viewDate.getUTCSeconds();if(n.is(".old")){if(o===0){o=11;p-=1}else{o-=1}}else{if(n.is(".new")){if(o===11){o=0;p+=1}else{o+=1}}}this.viewDate.setUTCFullYear(p);this.viewDate.setUTCMonth(o,q);this.element.trigger({type:"changeDay",date:this.viewDate});if(this.viewSelect>=2){this._setDate(h(p,o,q,r,k,s,0))}}var l=this.viewMode;this.showMode(-1);this.fill();if(l===this.viewMode&&this.autoclose){this.hide()}break}}},_setDate:function(i,k){if(!k||k==="date"){this.date=i}if(!k||k==="view"){this.viewDate=i}this.fill();this.setValue();var j;if(this.isInput){j=this.element}else{if(this.component){j=this.element.find("input")}}if(j){j.change()}this.element.trigger({type:"changeDate",date:this.getDate()});if(i===null){this.date=this.viewDate}},moveMinute:function(j,i){if(!i){return j}var k=new Date(j.valueOf());k.setUTCMinutes(k.getUTCMinutes()+(i*this.minuteStep));return k},moveHour:function(j,i){if(!i){return j}var k=new Date(j.valueOf());k.setUTCHours(k.getUTCHours()+i);return k},moveDate:function(j,i){if(!i){return j}var k=new Date(j.valueOf());k.setUTCDate(k.getUTCDate()+i);return k},moveMonth:function(j,k){if(!k){return j}var n=new Date(j.valueOf()),r=n.getUTCDate(),o=n.getUTCMonth(),m=Math.abs(k),q,p;k=k>0?1:-1;if(m===1){p=k===-1?function(){return n.getUTCMonth()===o}:function(){return n.getUTCMonth()!==q};q=o+k;n.setUTCMonth(q);if(q<0||q>11){q=(q+12)%12}}else{for(var l=0;l<m;l++){n=this.moveMonth(n,k)}q=n.getUTCMonth();n.setUTCDate(r);p=function(){return q!==n.getUTCMonth()}}while(p()){n.setUTCDate(--r);n.setUTCMonth(q)}return n},moveYear:function(j,i){return this.moveMonth(j,i*12)},dateWithinRange:function(i){return i>=this.startDate&&i<=this.endDate},keydown:function(o){if(this.picker.is(":not(:visible)")){if(o.keyCode===27){this.show()}return}var k=false,j,i,n;switch(o.keyCode){case 27:this.hide();o.preventDefault();break;case 37:case 39:if(!this.keyboardNavigation){break}j=o.keyCode===37?-1:1;var m=this.viewMode;if(o.ctrlKey){m+=2}else{if(o.shiftKey){m+=1}}if(m===4){i=this.moveYear(this.date,j);n=this.moveYear(this.viewDate,j)}else{if(m===3){i=this.moveMonth(this.date,j);n=this.moveMonth(this.viewDate,j)}else{if(m===2){i=this.moveDate(this.date,j);n=this.moveDate(this.viewDate,j)}else{if(m===1){i=this.moveHour(this.date,j);n=this.moveHour(this.viewDate,j)}else{if(m===0){i=this.moveMinute(this.date,j);n=this.moveMinute(this.viewDate,j)}}}}}if(this.dateWithinRange(i)){this.date=i;this.viewDate=n;this.setValue();this.update();o.preventDefault();k=true}break;case 38:case 40:if(!this.keyboardNavigation){break}j=o.keyCode===38?-1:1;m=this.viewMode;if(o.ctrlKey){m+=2}else{if(o.shiftKey){m+=1}}if(m===4){i=this.moveYear(this.date,j);n=this.moveYear(this.viewDate,j)}else{if(m===3){i=this.moveMonth(this.date,j);n=this.moveMonth(this.viewDate,j)}else{if(m===2){i=this.moveDate(this.date,j*7);n=this.moveDate(this.viewDate,j*7)}else{if(m===1){if(this.showMeridian){i=this.moveHour(this.date,j*6);n=this.moveHour(this.viewDate,j*6)}else{i=this.moveHour(this.date,j*4);n=this.moveHour(this.viewDate,j*4)}}else{if(m===0){i=this.moveMinute(this.date,j*4);n=this.moveMinute(this.viewDate,j*4)}}}}}if(this.dateWithinRange(i)){this.date=i;this.viewDate=n;this.setValue();this.update();o.preventDefault();k=true}break;case 13:if(this.viewMode!==0){var p=this.viewMode;this.showMode(-1);this.fill();if(p===this.viewMode&&this.autoclose){this.hide()}}else{this.fill();if(this.autoclose){this.hide()}}o.preventDefault();break;case 9:this.hide();break}if(k){var l;if(this.isInput){l=this.element}else{if(this.component){l=this.element.find("input")}}if(l){l.change()}this.element.trigger({type:"changeDate",date:this.getDate()})}},showMode:function(i){if(i){var j=Math.max(0,Math.min(c.modes.length-1,this.viewMode+i));if(j>=this.minView&&j<=this.maxView){this.element.trigger({type:"changeMode",date:this.viewDate,oldViewMode:this.viewMode,newViewMode:j});this.viewMode=j}}this.picker.find(">div").hide().filter(".datetimepicker-"+c.modes[this.viewMode].clsName).css("display","block");this.updateNavArrows()},reset:function(){this._setDate(null,"date")},convertViewModeText:function(i){switch(i){case 4:return"decade";case 3:return"year";case 2:return"month";case 1:return"day";case 0:return"hour"}}};var b=d.fn.datetimepicker;d.fn.datetimepicker=function(k){var i=Array.apply(null,arguments);i.shift();var j;this.each(function(){var n=d(this),m=n.data("datetimepicker"),l=typeof k==="object"&&k;if(!m){n.data("datetimepicker",(m=new g(this,d.extend({},d.fn.datetimepicker.defaults,l))))}if(typeof k==="string"&&typeof m[k]==="function"){j=m[k].apply(m,i);if(j!==f){return false}}});if(j!==f){return j}else{return this}};d.fn.datetimepicker.defaults={};d.fn.datetimepicker.Constructor=g;var e=d.fn.datetimepicker.dates={en:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sun"],daysMin:["Su","Mo","Tu","We","Th","Fr","Sa","Su"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],meridiem:["am","pm"],suffix:["st","nd","rd","th"],today:"Today",clear:"Clear"}};var c={modes:[{clsName:"minutes",navFnc:"Hours",navStep:1},{clsName:"hours",navFnc:"Date",navStep:1},{clsName:"days",navFnc:"Month",navStep:1},{clsName:"months",navFnc:"FullYear",navStep:1},{clsName:"years",navFnc:"FullYear",navStep:10}],isLeapYear:function(i){return(((i%4===0)&&(i%100!==0))||(i%400===0))},getDaysInMonth:function(i,j){return[31,(c.isLeapYear(i)?29:28),31,30,31,30,31,31,30,31,30,31][j]},getDefaultFormat:function(i,j){if(i==="standard"){if(j==="input"){return"yyyy-mm-dd hh:ii"}else{return"yyyy-mm-dd hh:ii:ss"}}else{if(i==="php"){if(j==="input"){return"Y-m-d H:i"}else{return"Y-m-d H:i:s"}}else{throw new Error("Invalid format type.")}}},validParts:function(i){if(i==="standard"){return/t|hh?|HH?|p|P|z|Z|ii?|ss?|dd?|DD?|mm?|MM?|yy(?:yy)?/g}else{if(i==="php"){return/[dDjlNwzFmMnStyYaABgGhHis]/g}else{throw new Error("Invalid format type.")}}},nonpunctuation:/[^ -\/:-@\[-`{-~\t\n\rTZ]+/g,parseFormat:function(l,j){var i=l.replace(this.validParts(j),"\0").split("\0"),k=l.match(this.validParts(j));if(!i||!i.length||!k||k.length===0){throw new Error("Invalid date format.")}return{separators:i,parts:k}},parseDate:function(A,y,v,j,r){if(A instanceof Date){var u=new Date(A.valueOf()-A.getTimezoneOffset()*60000);u.setMilliseconds(0);return u}if(/^\d{4}\-\d{1,2}\-\d{1,2}$/.test(A)){y=this.parseFormat("yyyy-mm-dd",j)}if(/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}$/.test(A)){y=this.parseFormat("yyyy-mm-dd hh:ii",j)}if(/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}\:\d{1,2}[Z]{0,1}$/.test(A)){y=this.parseFormat("yyyy-mm-dd hh:ii:ss",j)}if(/^[-+]\d+[dmwy]([\s,]+[-+]\d+[dmwy])*$/.test(A)){var l=/([-+]\d+)([dmwy])/,q=A.match(/([-+]\d+)([dmwy])/g),t,p;A=new Date();for(var x=0;x<q.length;x++){t=l.exec(q[x]);p=parseInt(t[1]);switch(t[2]){case"d":A.setUTCDate(A.getUTCDate()+p);break;case"m":A=g.prototype.moveMonth.call(g.prototype,A,p);break;case"w":A.setUTCDate(A.getUTCDate()+p*7);break;case"y":A=g.prototype.moveYear.call(g.prototype,A,p);break}}return h(A.getUTCFullYear(),A.getUTCMonth(),A.getUTCDate(),A.getUTCHours(),A.getUTCMinutes(),A.getUTCSeconds(),0)}var q=A&&A.toString().match(this.nonpunctuation)||[],A=new Date(0,0,0,0,0,0,0),m={},z=["hh","h","ii","i","ss","s","yyyy","yy","M","MM","m","mm","D","DD","d","dd","H","HH","p","P","z","Z"],o={hh:function(s,i){return s.setUTCHours(i)},h:function(s,i){return s.setUTCHours(i)},HH:function(s,i){return s.setUTCHours(i===12?0:i)},H:function(s,i){return s.setUTCHours(i===12?0:i)},ii:function(s,i){return s.setUTCMinutes(i)},i:function(s,i){return s.setUTCMinutes(i)},ss:function(s,i){return s.setUTCSeconds(i)},s:function(s,i){return s.setUTCSeconds(i)},yyyy:function(s,i){return s.setUTCFullYear(i)},yy:function(s,i){return s.setUTCFullYear(2000+i)},m:function(s,i){i-=1;while(i<0){i+=12}i%=12;s.setUTCMonth(i);while(s.getUTCMonth()!==i){if(isNaN(s.getUTCMonth())){return s}else{s.setUTCDate(s.getUTCDate()-1)}}return s},d:function(s,i){return s.setUTCDate(i)},p:function(s,i){return s.setUTCHours(i===1?s.getUTCHours()+12:s.getUTCHours())},z:function(){return r}},B,k,t;o.M=o.MM=o.mm=o.m;o.dd=o.d;o.P=o.p;o.Z=o.z;A=h(A.getFullYear(),A.getMonth(),A.getDate(),A.getHours(),A.getMinutes(),A.getSeconds());if(q.length===y.parts.length){for(var x=0,w=y.parts.length;x<w;x++){B=parseInt(q[x],10);t=y.parts[x];if(isNaN(B)){switch(t){case"MM":k=d(e[v].months).filter(function(){var i=this.slice(0,q[x].length),s=q[x].slice(0,i.length);return i===s});B=d.inArray(k[0],e[v].months)+1;break;case"M":k=d(e[v].monthsShort).filter(function(){var i=this.slice(0,q[x].length),s=q[x].slice(0,i.length);return i.toLowerCase()===s.toLowerCase()});B=d.inArray(k[0],e[v].monthsShort)+1;break;case"p":case"P":B=d.inArray(q[x].toLowerCase(),e[v].meridiem);break;case"z":case"Z":r;break}}m[t]=B}for(var x=0,n;x<z.length;x++){n=z[x];if(n in m&&!isNaN(m[n])){o[n](A,m[n])}}}return A},formatDate:function(l,q,m,p,o){if(l===null){return""}var k;if(p==="standard"){k={t:l.getTime(),yy:l.getUTCFullYear().toString().substring(2),yyyy:l.getUTCFullYear(),m:l.getUTCMonth()+1,M:e[m].monthsShort[l.getUTCMonth()],MM:e[m].months[l.getUTCMonth()],d:l.getUTCDate(),D:e[m].daysShort[l.getUTCDay()],DD:e[m].days[l.getUTCDay()],p:(e[m].meridiem.length===2?e[m].meridiem[l.getUTCHours()<12?0:1]:""),h:l.getUTCHours(),i:l.getUTCMinutes(),s:l.getUTCSeconds(),z:o};if(e[m].meridiem.length===2){k.H=(k.h%12===0?12:k.h%12)}else{k.H=k.h}k.HH=(k.H<10?"0":"")+k.H;k.P=k.p.toUpperCase();k.Z=k.z;k.hh=(k.h<10?"0":"")+k.h;k.ii=(k.i<10?"0":"")+k.i;k.ss=(k.s<10?"0":"")+k.s;k.dd=(k.d<10?"0":"")+k.d;k.mm=(k.m<10?"0":"")+k.m}else{if(p==="php"){k={y:l.getUTCFullYear().toString().substring(2),Y:l.getUTCFullYear(),F:e[m].months[l.getUTCMonth()],M:e[m].monthsShort[l.getUTCMonth()],n:l.getUTCMonth()+1,t:c.getDaysInMonth(l.getUTCFullYear(),l.getUTCMonth()),j:l.getUTCDate(),l:e[m].days[l.getUTCDay()],D:e[m].daysShort[l.getUTCDay()],w:l.getUTCDay(),N:(l.getUTCDay()===0?7:l.getUTCDay()),S:(l.getUTCDate()%10<=e[m].suffix.length?e[m].suffix[l.getUTCDate()%10-1]:""),a:(e[m].meridiem.length===2?e[m].meridiem[l.getUTCHours()<12?0:1]:""),g:(l.getUTCHours()%12===0?12:l.getUTCHours()%12),G:l.getUTCHours(),i:l.getUTCMinutes(),s:l.getUTCSeconds()};k.m=(k.n<10?"0":"")+k.n;k.d=(k.j<10?"0":"")+k.j;k.A=k.a.toString().toUpperCase();k.h=(k.g<10?"0":"")+k.g;k.H=(k.G<10?"0":"")+k.G;k.i=(k.i<10?"0":"")+k.i;k.s=(k.s<10?"0":"")+k.s}else{throw new Error("Invalid format type.")}}var l=[],r=d.extend([],q.separators);for(var n=0,j=q.parts.length;n<j;n++){if(r.length){l.push(r.shift())}l.push(k[q.parts[n]])}if(r.length){l.push(r.shift())}return l.join("")},convertViewMode:function(i){switch(i){case 4:case"decade":i=4;break;case 3:case"year":i=3;break;case 2:case"month":i=2;break;case 1:case"day":i=1;break;case 0:case"hour":i=0;break}return i},headTemplate:'<thead><tr><th class="prev"><i class="{iconType} {leftArrow}"/></th><th colspan="5" class="switch"></th><th class="next"><i class="{iconType} {rightArrow}"/></th></tr></thead>',headTemplateV3:'<thead><tr><th class="prev"><span class="{iconType} {leftArrow}"></span> </th><th colspan="5" class="switch"></th><th class="next"><span class="{iconType} {rightArrow}"></span> </th></tr></thead>',contTemplate:'<tbody><tr><td colspan="7"></td></tr></tbody>',footTemplate:'<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'};c.template='<div class="datetimepicker"><div class="datetimepicker-minutes"><table class=" table-condensed">'+c.headTemplate+c.contTemplate+c.footTemplate+'</table></div><div class="datetimepicker-hours"><table class=" table-condensed">'+c.headTemplate+c.contTemplate+c.footTemplate+'</table></div><div class="datetimepicker-days"><table class=" table-condensed">'+c.headTemplate+"<tbody></tbody>"+c.footTemplate+'</table></div><div class="datetimepicker-months"><table class="table-condensed">'+c.headTemplate+c.contTemplate+c.footTemplate+'</table></div><div class="datetimepicker-years"><table class="table-condensed">'+c.headTemplate+c.contTemplate+c.footTemplate+"</table></div></div>";c.templateV3='<div class="datetimepicker"><div class="datetimepicker-minutes"><table class=" table-condensed">'+c.headTemplateV3+c.contTemplate+c.footTemplate+'</table></div><div class="datetimepicker-hours"><table class=" table-condensed">'+c.headTemplateV3+c.contTemplate+c.footTemplate+'</table></div><div class="datetimepicker-days"><table class=" table-condensed">'+c.headTemplateV3+"<tbody></tbody>"+c.footTemplate+'</table></div><div class="datetimepicker-months"><table class="table-condensed">'+c.headTemplateV3+c.contTemplate+c.footTemplate+'</table></div><div class="datetimepicker-years"><table class="table-condensed">'+c.headTemplateV3+c.contTemplate+c.footTemplate+"</table></div></div>";d.fn.datetimepicker.DPGlobal=c;d.fn.datetimepicker.noConflict=function(){d.fn.datetimepicker=b;return this};d(document).on("focus.datetimepicker.data-api click.datetimepicker.data-api",'[data-provide="datetimepicker"]',function(j){var i=d(this);if(i.data("datetimepicker")){return}j.preventDefault();i.datetimepicker("show")});d(function(){d('[data-provide="datetimepicker-inline"]').datetimepicker()})}));
(function(window, undefined) {
    "use strict";

    // noinspection JSUnresolvedVariable
    /**
     * library instance - here and not in construct to be shorter in minimization
     * @return void
     */
    var $ = window.jQuery || window.Zepto,

    /**
     * unique plugin instance id counter
     * @type {number}
     */
    lazyInstanceId = 0,

    /**
     * helper to register window load for jQuery 3
     * @type {boolean}
     */    
    windowLoaded = false;

    /**
     * make lazy available to jquery - and make it a bit more case-insensitive :)
     * @access public
     * @type {function}
     * @param {object} settings
     * @return {LazyPlugin}
     */
    $.fn.Lazy = $.fn.lazy = function(settings) {
        return new LazyPlugin(this, settings);
    };

    /**
     * helper to add plugins to lazy prototype configuration
     * @access public
     * @type {function}
     * @param {string|Array} names
     * @param {string|Array|function} [elements]
     * @param {function} loader
     * @return void
     */
    $.Lazy = $.lazy = function(names, elements, loader) {
        // make second parameter optional
        if ($.isFunction(elements)) {
            loader = elements;
            elements = [];
        }

        // exit here if parameter is not a callable function
        if (!$.isFunction(loader)) {
            return;
        }

        // make parameters an array of names to be sure
        names = $.isArray(names) ? names : [names];
        elements = $.isArray(elements) ? elements : [elements];

        var config = LazyPlugin.prototype.config,
            forced = config._f || (config._f = {});

        // add the loader plugin for every name
        for (var i = 0, l = names.length; i < l; i++) {
            if (config[names[i]] === undefined || $.isFunction(config[names[i]])) {
                config[names[i]] = loader;
            }
        }

        // add forced elements loader
        for (var c = 0, a = elements.length; c < a; c++) {
            forced[elements[c]] = names[0];
        }
    };

    /**
     * contains all logic and the whole element handling
     * is packed in a private function outside class to reduce memory usage, because it will not be created on every plugin instance
     * @access private
     * @type {function}
     * @param {LazyPlugin} instance
     * @param {object} config
     * @param {object|Array} items
     * @param {object} events
     * @param {string} namespace
     * @return void
     */
    function _executeLazy(instance, config, items, events, namespace) {
        /**
         * a helper to trigger the 'onFinishedAll' callback after all other events
         * @access private
         * @type {number}
         */
        var _awaitingAfterLoad = 0,

        /**
         * visible content width
         * @access private
         * @type {number}
         */
        _actualWidth = -1,

        /**
         * visible content height
         * @access private
         * @type {number}
         */
        _actualHeight = -1,

        /**
         * determine possibly detected high pixel density
         * @access private
         * @type {boolean}
         */
        _isRetinaDisplay = false, 

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _afterLoad = 'afterLoad',

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _load = 'load',

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _error = 'error',

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _img = 'img',

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _src = 'src',

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _srcset = 'srcset',

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _sizes = 'sizes',

        /**
         * dictionary entry for better minimization
         * @access private
         * @type {string}
         */
        _backgroundImage = 'background-image';

        /**
         * initialize plugin
         * bind loading to events or set delay time to load all items at once
         * @access private
         * @return void
         */
        function _initialize() {
            // detect actual device pixel ratio
            // noinspection JSUnresolvedVariable
            _isRetinaDisplay = window.devicePixelRatio > 1;

            // prepare all initial items
            items = _prepareItems(items);

            // if delay time is set load all items at once after delay time
            if (config.delay >= 0) {
                setTimeout(function() {
                    _lazyLoadItems(true);
                }, config.delay);
            }

            // if no delay is set or combine usage is active bind events
            if (config.delay < 0 || config.combined) {
                // create unique event function
                events.e = _throttle(config.throttle, function(event) {
                    // reset detected window size on resize event
                    if (event.type === 'resize') {
                        _actualWidth = _actualHeight = -1;
                    }

                    // execute 'lazy magic'
                    _lazyLoadItems(event.all);
                });

                // create function to add new items to instance
                events.a = function(additionalItems) {
                    additionalItems = _prepareItems(additionalItems);
                    items.push.apply(items, additionalItems);
                };

                // create function to get all instance items left
                events.g = function() {
                    // filter loaded items before return in case internal filter was not running until now
                    return (items = $(items).filter(function() {
                        return !$(this).data(config.loadedName);
                    }));
                };

                // create function to force loading elements
                events.f = function(forcedItems) {
                    for (var i = 0; i < forcedItems.length; i++) {
                        // only handle item if available in current instance
                        // use a compare function, because Zepto can't handle object parameter for filter
                        // var item = items.filter(forcedItems[i]);
                        /* jshint loopfunc: true */
                        var item = items.filter(function() {
                            return this === forcedItems[i];
                        });

                        if (item.length) {
                            _lazyLoadItems(false, item);   
                        }
                    }
                };

                // load initial items
                _lazyLoadItems();

                // bind lazy load functions to scroll and resize event
                // noinspection JSUnresolvedVariable
                $(config.appendScroll).on('scroll.' + namespace + ' resize.' + namespace, events.e);
            }
        }

        /**
         * prepare items before handle them
         * @access private
         * @param {Array|object|jQuery} items
         * @return {Array|object|jQuery}
         */
        function _prepareItems(items) {
            // fetch used configurations before loops
            var defaultImage = config.defaultImage,
                placeholder = config.placeholder,
                imageBase = config.imageBase,
                srcsetAttribute = config.srcsetAttribute,
                loaderAttribute = config.loaderAttribute,
                forcedTags = config._f || {};

            // filter items and only add those who not handled yet and got needed attributes available
            items = $(items).filter(function() {
                var element = $(this),
                    tag = _getElementTagName(this);

                return !element.data(config.handledName) && 
                       (element.attr(config.attribute) || element.attr(srcsetAttribute) || element.attr(loaderAttribute) || forcedTags[tag] !== undefined);
            })

            // append plugin instance to all elements
            .data('plugin_' + config.name, instance);

            for (var i = 0, l = items.length; i < l; i++) {
                var element = $(items[i]),
                    tag = _getElementTagName(items[i]),
                    elementImageBase = element.attr(config.imageBaseAttribute) || imageBase;

                // generate and update source set if an image base is set
                if (tag === _img && elementImageBase && element.attr(srcsetAttribute)) {
                    element.attr(srcsetAttribute, _getCorrectedSrcSet(element.attr(srcsetAttribute), elementImageBase));
                }

                // add loader to forced element types
                if (forcedTags[tag] !== undefined && !element.attr(loaderAttribute)) {
                    element.attr(loaderAttribute, forcedTags[tag]);
                }

                // set default image on every element without source
                if (tag === _img && defaultImage && !element.attr(_src)) {
                    element.attr(_src, defaultImage);
                }

                // set placeholder on every element without background image
                else if (tag !== _img && placeholder && (!element.css(_backgroundImage) || element.css(_backgroundImage) === 'none')) {
                    element.css(_backgroundImage, "url('" + placeholder + "')");
                }
            }

            return items;
        }

        /**
         * the 'lazy magic' - check all items
         * @access private
         * @param {boolean} [allItems]
         * @param {object} [forced]
         * @return void
         */
        function _lazyLoadItems(allItems, forced) {
            // skip if no items where left
            if (!items.length) {
                // destroy instance if option is enabled
                if (config.autoDestroy) {
                    // noinspection JSUnresolvedFunction
                    instance.destroy();
                }

                return;
            }

            var elements = forced || items,
                loadTriggered = false,
                imageBase = config.imageBase || '',
                srcsetAttribute = config.srcsetAttribute,
                handledName = config.handledName
                imageAltAttribute = config.imageAltAttribute;

            // loop all available items
            for (var i = 0; i < elements.length; i++) {
                // item is at least in loadable area
                if (allItems || forced || _isInLoadableArea(elements[i])) {
                    var element = $(elements[i]),
                        tag = _getElementTagName(elements[i]),
                        attribute = element.attr(config.attribute),
                        imageAltAttribute = element.attr(config.imageAltAttribute),
                        elementImageBase = element.attr(config.imageBaseAttribute) || imageBase,
                        customLoader = element.attr(config.loaderAttribute);

                        // is not already handled 
                    if (!element.data(handledName) &&
                        // and is visible or visibility doesn't matter
                        (!config.visibleOnly || element.is(':visible')) && (
                        // and image source or source set attribute is available
                        (attribute || element.attr(srcsetAttribute)) && (
                            // and is image tag where attribute is not equal source or source set
                            (tag === _img && (elementImageBase + attribute !== element.attr(_src) || element.attr(srcsetAttribute) !== element.attr(_srcset))) ||
                            // or is non image tag where attribute is not equal background
                            (tag !== _img && elementImageBase + attribute !== element.css(_backgroundImage))
                        ) ||
                        // or custom loader is available
                        customLoader))
                    {
                        // mark element always as handled as this point to prevent double handling
                        loadTriggered = true;
                        element.data(handledName, true);

                        // load item
                        _handleItem(element, tag, elementImageBase, customLoader);
                    }

                    element.attr('alt', imageAltAttribute);
                }
            }

            // when something was loaded remove them from remaining items
            if (loadTriggered) {
                items = $(items).filter(function() {
                    return !$(this).data(handledName);
                });
            }
        }

        /**
         * load the given element the lazy way
         * @access private
         * @param {object} element
         * @param {string} tag
         * @param {string} imageBase
         * @param {function} [customLoader]
         * @return void
         */
        function _handleItem(element, tag, imageBase, customLoader) {
            // increment count of items waiting for after load
            ++_awaitingAfterLoad;

            // extended error callback for correct 'onFinishedAll' handling
            var errorCallback = function() {
                _triggerCallback('onError', element);
                _reduceAwaiting();

                // prevent further callback calls
                errorCallback = $.noop;
            };

            // trigger function before loading image
            _triggerCallback('beforeLoad', element);

            // fetch all double used data here for better code minimization
            var srcAttribute = config.attribute,
                srcsetAttribute = config.srcsetAttribute,
                sizesAttribute = config.sizesAttribute,
                retinaAttribute = config.retinaAttribute,
                imageAltAttribute = config.imageAltAttribute,
                removeAttribute = config.removeAttribute,
                loadedName = config.loadedName,
                elementRetina = element.attr(retinaAttribute);

            // handle custom loader
            if (customLoader) {
                // on load callback
                var loadCallback = function() {
                    // remove attribute from element
                    if (removeAttribute) {
                        element.removeAttr(config.loaderAttribute);
                    }

                    // mark element as loaded
                    element.data(loadedName, true);

                    // call after load event
                    _triggerCallback(_afterLoad, element);

                    // remove item from waiting queue and possibly trigger finished event
                    // it's needed to be asynchronous to run after filter was in _lazyLoadItems
                    setTimeout(_reduceAwaiting, 1);

                    // prevent further callback calls
                    loadCallback = $.noop;
                };

                // bind error event to trigger callback and reduce waiting amount
                element.off(_error).one(_error, errorCallback)

                // bind after load callback to element
                .one(_load, loadCallback);

                // trigger custom loader and handle response
                if (!_triggerCallback(customLoader, element, function(response) {
                    if(response) {
                        element.off(_load);
                        loadCallback();
                    }
                    else {
                        element.off(_error);
                        errorCallback();
                    }
                })) {
                    element.trigger(_error);
                }
            }

            // handle images
            else {
                // create image object
                var imageObj = $(new Image());

                // bind error event to trigger callback and reduce waiting amount
                imageObj.one(_error, errorCallback)

                // bind after load callback to image
                .one(_load, function() {
                    // remove element from view
                    element.hide();

                    // set image back to element
                    // do it as single 'attr' calls, to be sure 'src' is set after 'srcset'
                    if (tag === _img) {
                        element.attr(_sizes, imageObj.attr(_sizes))
                               .attr(_srcset, imageObj.attr(_srcset))
                               .attr(_src, imageObj.attr(_src));
                    }
                    else {
                        element.css(_backgroundImage, "url('" + imageObj.attr(_src) + "')");
                    }

                    // bring it back with some effect!
                    element[config.effect](config.effectTime);

                    // remove attribute from element
                    if (removeAttribute) {
                        element.removeAttr(srcAttribute + ' ' + srcsetAttribute + ' ' + retinaAttribute + ' ' + config.imageBaseAttribute + ' ' + imageAltAttribute);

                        // only remove 'sizes' attribute, if it was a custom one
                        if (sizesAttribute !== _sizes) {
                            element.removeAttr(sizesAttribute);
                        }
                    }

                    // mark element as loaded
                    element.data(loadedName, true);

                    // call after load event
                    _triggerCallback(_afterLoad, element);

                    // cleanup image object
                    imageObj.remove();

                    // remove item from waiting queue and possibly trigger finished event
                    _reduceAwaiting();
                });

                // set sources
                // do it as single 'attr' calls, to be sure 'src' is set after 'srcset'
                var imageSrc = (_isRetinaDisplay && elementRetina ? elementRetina : element.attr(srcAttribute)) || '';
                imageObj.attr(_sizes, element.attr(sizesAttribute))
                        .attr(_srcset, element.attr(srcsetAttribute))
                        .attr(_src, imageSrc ? imageBase + imageSrc : null);

                // call after load even on cached image
                imageObj.complete && imageObj.trigger(_load); // jshint ignore : line
            }
        }

        /**
         * check if the given element is inside the current viewport or threshold
         * @access private
         * @param {object} element
         * @return {boolean}
         */
        function _isInLoadableArea(element) {
            var elementBound = element.getBoundingClientRect(),
                direction    = config.scrollDirection,
                threshold    = config.threshold,
                vertical     = // check if element is in loadable area from top
                               ((_getActualHeight() + threshold) > elementBound.top) &&
                               // check if element is even in loadable are from bottom
                               (-threshold < elementBound.bottom),
                horizontal   = // check if element is in loadable area from left
                               ((_getActualWidth() + threshold) > elementBound.left) &&
                               // check if element is even in loadable area from right
                               (-threshold < elementBound.right);

            if (direction === 'vertical') {
                return vertical;
            }
            else if (direction === 'horizontal') {
                return horizontal;
            }

            return vertical && horizontal;
        }

        /**
         * receive the current viewed width of the browser
         * @access private
         * @return {number}
         */
        function _getActualWidth() {
            return _actualWidth >= 0 ? _actualWidth : (_actualWidth = $(window).width());
        }

        /**
         * receive the current viewed height of the browser
         * @access private
         * @return {number}
         */
        function _getActualHeight() {
            return _actualHeight >= 0 ? _actualHeight : (_actualHeight = $(window).height());
        }

        /**
         * get lowercase tag name of an element
         * @access private
         * @param {object} element
         * @returns {string}
         */
        function _getElementTagName(element) {
            return element.tagName.toLowerCase();
        }

        /**
         * prepend image base to all srcset entries
         * @access private
         * @param {string} srcset
         * @param {string} imageBase
         * @returns {string}
         */
        function _getCorrectedSrcSet(srcset, imageBase) {
            if (imageBase) {
                // trim, remove unnecessary spaces and split entries
                var entries = srcset.split(',');
                srcset = '';

                for (var i = 0, l = entries.length; i < l; i++) {
                    srcset += imageBase + entries[i].trim() + (i !== l - 1 ? ',' : '');
                }
            }

            return srcset;
        }

        /**
         * helper function to throttle down event triggering
         * @access private
         * @param {number} delay
         * @param {function} callback
         * @return {function}
         */
        function _throttle(delay, callback) {
            var timeout,
                lastExecute = 0;

            return function(event, ignoreThrottle) {
                var elapsed = +new Date() - lastExecute;

                function run() {
                    lastExecute = +new Date();
                    // noinspection JSUnresolvedFunction
                    callback.call(instance, event);
                }

                timeout && clearTimeout(timeout); // jshint ignore : line

                if (elapsed > delay || !config.enableThrottle || ignoreThrottle) {
                    run();
                }
                else {
                    timeout = setTimeout(run, delay - elapsed);
                }
            };
        }

        /**
         * reduce count of awaiting elements to 'afterLoad' event and fire 'onFinishedAll' if reached zero
         * @access private
         * @return void
         */
        function _reduceAwaiting() {
            --_awaitingAfterLoad;

            // if no items were left trigger finished event
            if (!items.length && !_awaitingAfterLoad) {
                _triggerCallback('onFinishedAll');
            }
        }

        /**
         * single implementation to handle callbacks, pass element and set 'this' to current instance
         * @access private
         * @param {string|function} callback
         * @param {object} [element]
         * @param {*} [args]
         * @return {boolean}
         */
        function _triggerCallback(callback, element, args) {
            if ((callback = config[callback])) {
                // jQuery's internal '$(arguments).slice(1)' are causing problems at least on old iPads
                // below is shorthand of 'Array.prototype.slice.call(arguments, 1)'
                callback.apply(instance, [].slice.call(arguments, 1));
                return true;
            }

            return false;
        }

        // if event driven or window is already loaded don't wait for page loading
        if (config.bind === 'event' || windowLoaded) {
            _initialize();
        }

        // otherwise load initial items and start lazy after page load
        else {
            // noinspection JSUnresolvedVariable
            $(window).on(_load + '.' + namespace, _initialize);
        }  
    }

    /**
     * lazy plugin class constructor
     * @constructor
     * @access private
     * @param {object} elements
     * @param {object} settings
     * @return {object|LazyPlugin}
     */
    function LazyPlugin(elements, settings) {
        /**
         * this lazy plugin instance
         * @access private
         * @type {object|LazyPlugin|LazyPlugin.prototype}
         */
        var _instance = this,

        /**
         * this lazy plugin instance configuration
         * @access private
         * @type {object}
         */
        _config = $.extend({}, _instance.config, settings),

        /**
         * instance generated event executed on container scroll or resize
         * packed in an object to be referenceable and short named because properties will not be minified
         * @access private
         * @type {object}
         */
        _events = {},

        /**
         * unique namespace for instance related events
         * @access private
         * @type {string}
         */
        _namespace = _config.name + '-' + (++lazyInstanceId);

        // noinspection JSUndefinedPropertyAssignment
        /**
         * wrapper to get or set an entry from plugin instance configuration
         * much smaller on minify as direct access
         * @access public
         * @type {function}
         * @param {string} entryName
         * @param {*} [value]
         * @return {LazyPlugin|*}
         */
        _instance.config = function(entryName, value) {
            if (value === undefined) {
                return _config[entryName];
            }

            _config[entryName] = value;
            return _instance;
        };

        // noinspection JSUndefinedPropertyAssignment
        /**
         * add additional items to current instance
         * @access public
         * @param {Array|object|string} items
         * @return {LazyPlugin}
         */
        _instance.addItems = function(items) {
            _events.a && _events.a($.type(items) === 'string' ? $(items) : items); // jshint ignore : line
            return _instance;
        };

        // noinspection JSUndefinedPropertyAssignment
        /**
         * get all left items of this instance
         * @access public
         * @returns {object}
         */
        _instance.getItems = function() {
            return _events.g ? _events.g() : {};
        };

        // noinspection JSUndefinedPropertyAssignment
        /**
         * force lazy to load all items in loadable area right now
         * by default without throttle
         * @access public
         * @type {function}
         * @param {boolean} [useThrottle]
         * @return {LazyPlugin}
         */
        _instance.update = function(useThrottle) {
            _events.e && _events.e({}, !useThrottle); // jshint ignore : line
            return _instance;
        };

        // noinspection JSUndefinedPropertyAssignment
        /**
         * force element(s) to load directly, ignoring the viewport
         * @access public
         * @param {Array|object|string} items
         * @return {LazyPlugin}
         */
        _instance.force = function(items) {
            _events.f && _events.f($.type(items) === 'string' ? $(items) : items); // jshint ignore : line
            return _instance;
        };

        // noinspection JSUndefinedPropertyAssignment
        /**
         * force lazy to load all available items right now
         * this call ignores throttling
         * @access public
         * @type {function}
         * @return {LazyPlugin}
         */
        _instance.loadAll = function() {
            _events.e && _events.e({all: true}, true); // jshint ignore : line
            return _instance;
        };

        // noinspection JSUndefinedPropertyAssignment
        /**
         * destroy this plugin instance
         * @access public
         * @type {function}
         * @return undefined
         */
        _instance.destroy = function() {
            // unbind instance generated events
            // noinspection JSUnresolvedFunction, JSUnresolvedVariable
            $(_config.appendScroll).off('.' + _namespace, _events.e);
            // noinspection JSUnresolvedVariable
            $(window).off('.' + _namespace);

            // clear events
            _events = {};

            return undefined;
        };

        // start using lazy and return all elements to be chainable or instance for further use
        // noinspection JSUnresolvedVariable
        _executeLazy(_instance, _config, elements, _events, _namespace);
        return _config.chainable ? elements : _instance;
    }

    /**
     * settings and configuration data
     * @access public
     * @type {object|*}
     */
    LazyPlugin.prototype.config = {
        // general
        name               : 'lazy',
        chainable          : true,
        autoDestroy        : true,
        bind               : 'load',
        threshold          : 500,
        visibleOnly        : false,
        appendScroll       : window,
        scrollDirection    : 'both',
        imageBase          : null,
        defaultImage       : 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==',
        placeholder        : null,
        delay              : -1,
        combined           : false,

        // attributes
        attribute          : 'data-src',
        srcsetAttribute    : 'data-srcset',
        sizesAttribute     : 'data-sizes',
        retinaAttribute    : 'data-retina',
        loaderAttribute    : 'data-loader',
        imageBaseAttribute : 'data-imagebase',
        imageAltAttribute  : 'data-alt',
        removeAttribute    : true,
        handledName        : 'handled',
        loadedName         : 'loaded',

        // effect
        effect             : 'show',
        effectTime         : 0,

        // throttle
        enableThrottle     : true,
        throttle           : 250,

        // callbacks
        beforeLoad         : undefined,
        afterLoad          : undefined,
        onError            : undefined,
        onFinishedAll      : undefined
    };

    // register window load event globally to prevent not loading elements
    // since jQuery 3.X ready state is fully async and may be executed after 'load' 
    $(window).on('load', function() {
        windowLoaded = true;
    });
})(window);
$(window).scroll(function() {
    $(this).scrollTop() > 300 ? $("#back-top").fadeIn() : $("#back-top").fadeOut();
    $(this).scrollTop() > 80 ? $('.main_menu_2').addClass('fixed') :  $('.main_menu_2').removeClass('fixed');
});

var prefixUrl = '/';

$("#back-top a").click(function() {
    return $("body,html").animate({
        scrollTop: 0
    }, 800), !1
});

$('.box_product_hot_slide').owlCarousel({
    loop:true,
    margin:30,
    nav:false,
    navText: ["",""],
    dots:true,
    autoplay:true,
    autoplayTimeout:2500,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            dots:false,
            loop:false,
        },
        380:{
            items:1,
            dots:false
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        }
    }
});

if (!$('.box_logo_company').hasClass('box_logo_company_center')) {
    $('.box_logo_company').owlCarousel({
        loop:true,
        margin:30,
        nav:true,
        navText: ["",""],
        dots:false,
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1,
                dots:false,
                loop:false,
                nav:false
            },
            320:{
                items:2,
                dots:false
            },
            480:{
                items:2,
            },
            768:{
                items:3,
            },
            992:{
                items:4
            },
            1200:{
                items:6
            }
        }
    });
}


$('.box_logo_company_center').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    navText: ["",""],
    dots:false,
    autoplay:true,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            dots:false,
            loop:false,
            nav:false
        },
        320:{
            items:2,
            dots:false
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        },
        1200:{
            items:5
        }
    }
});

$('#box_logo_company_inner').owlCarousel({
    loop:true,
    margin:30,
    nav:false,
    dots:false,
    autoplay:true,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            dots:false,
            loop:false,
            nav:false
        },
        320:{
            items:2,
            dots:false
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        },
        1200:{
            items:6
        }
    }
});

$('.list_product_index').owlCarousel({
    loop:true,
    margin:35,
    nav:true,
    dots:false,
    autoplay:false,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            loop:false,
            nav:false
        },
        420:{
            items:2,
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        },
        1200:{
            items:4
        }
    }
});

$('.list_product_care').owlCarousel({
    loop:false,
    margin:30,
    nav:true,
    dots:true,
    autoplay:false,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            nav:false,
            loop:false,
            dots:false,
        },
        420:{
            items:2,
            nav:false
        },
        480:{
            items:2,
            nav:false
        },
        768:{
            items:3,
            nav:false
        },
        992:{
            items:3
        },
        1200:{
            items:4
        }
    }
})

$(function() {
    $('.lazy').lazy({
        placeholder: "data:image/gif;base64,R0lGODlhEALAPQAPzl5uLr9Nrl8e7..."
    });
});

$(window).on('load',function(){
    var flagModal = 1;
    if (flagModal == 1) {
        setTimeout(function () {
            $('#modalItemYcbg').modal('show');
            flagModal = 0;
        },  15000);
    }
});

var lang = $('input[name=language]').val();
var langMessage = $('input[name=languageMessage]').val();
messageString = localStorage.getItem('messages_' + langMessage);
var messages = {};
if (messageString == null) {
    $.getJSON( "/messages/"+ langMessage +".json", function( data ) {
        $.each( data, function( key, val ) {
            messages[key] = val;   
        });
        messageString = JSON.stringify(data);
        localStorage.setItem('messages_' + langMessage, messageString);
    });
} else {
    $.each( JSON.parse(messageString), function( key, val ) {
        messages[key] = val;   
    });
}

$(document).ready(function(){
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "rtl": false,
      "positionClass": "toast-top-center",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": 300,
      "hideDuration": 1000,
      "timeOut": 3000,
      "extendedTimeOut": 1000,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    add_to_cart();
    add_to_cart_qty();
    update_cart();
    delete_cart();
    admin_login();
    category_sub_down();
    newsletter();
    customer_message();
    send_info_frm_ycbg();
    // mic_support();
    datePickerFormItem();
    collapse_menu_nav();
    showChildMenuTop2();
    support_request_popup();
    send_customer_comment();
    send_contact();
    searchSubdomain();
    pagination_ajax();
    sendFormSupportRequest();
    add_alias_sub();
    createDomain();
    marqueeSlider();
});

function createDomain() {
    var domainRegExp = /^[a-z-0-9]*$/;
    $('#btn-create-domain').click(function(e) {
        e.preventDefault();
        var domain = $('input[name=domain]').val();
        var username = $('input[name=r_username]').val();
        var password = $('input[name=r_password]').val();
        var email = $('input[name=r_email').val();
        if(email == '') {
        	$('input[name=r_email]').focus();
            toastr.error(''+ messages.email_is_required +'');
            return false;
        } else if(!emailRegExp.test(email)){
        	$('input[name=r_email]').focus();
            toastr.error(''+ messages.email_not_in_correct_format +'');
            return false;
        }

        var phone = $('input[name=r_phone').val();
        if(phone == ''){
        	$('input[name=r_phone]').focus();
            toastr.error(''+ messages.phone_is_required +'');
            return false;
        } else if(!$.isNumeric(phone)){
        	$('input[name=r_phone]').focus();
            toastr.error(''+ messages.phone_not_in_correct_format +'');
            return false;
        }

        var facebook = $('input[name=r_facebook]').val();
        if(facebook == ''){
            toastr.error('Bạn phải nhập Facebook');
            $('input[name=r_facebook]').focus();
            return false;
        }
        
        if (domain == '') {
            toastr.error('Bạn chưa nhập tên miền');
            $('input[name=domain]').focus();
            return false;
        } else if (!domainRegExp.test(domain)) {
            toastr.error('Tên miền phải nhập chữ thường viết liền không dấu');
            $('input[name=domain]').focus();
            return false;
        }

        if (username == '') {
            $('input[name=r_username]').focus();
            toastr.error('Bạn chưa nhập tên đăng nhập');
            return false;
        } else if (!domainRegExp.test(username)) {
            toastr.error('Tên đăng nhập phải nhập chữ thường viết liền không dấu');
            $('input[name=r_username]').focus();
            return false;
        }

        if (password == '') {
            $('input[name=r_password]').focus();
            toastr.error('Bạn chưa nhập tên mật khẩu');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: 'subdomain-exist' + prefixUrl,
            data: {'domain':domain},
            success:function(response) {
                if (response != 0) {
                    toastr.error('Tên miền đã tồn tại trên hệ thống. Vui lòng nhập tên miền khác!');
                    $('input[name=domain]').focus();
                    return false;
                }
            }
        });

        $.ajax({
            type: 'POST',
            url: 'user-exist' + prefixUrl,
            data: {'username':username},
            success:function(response) {
                if (response != 0) {
                    toastr.error('Tên đăng nhập đã tồn tại trên hệ thống. Vui lòng nhập tên đăng nhập khác!');
                    $('input[name=r_username]').focus();
                    return false;
                }
            }
        });

        $.ajax({
            type: 'POST',
            url: 'user-email-exist' + prefixUrl,
            data: {'email':email},
            success:function(response) {
                if (response != 0) {
                    toastr.error('Email đã được đăng ký. Vui lòng nhập email khác!');
                    $('input[name=r_email]').focus();
                    return false;
                }
            }
        });

        var l = Ladda.create(this);
        l.start();
        $.ajax({
            type: 'POST',
            url: 'subdomain-create' + prefixUrl,
            data: {'domain':domain, 'username':username, 'password':password, 'email':email, 'phone':phone, 'facebook':facebook},
            success:function(response) {
                let res = JSON.parse(response);
                if (res.url) {
                    toastr.success('Tạo website thành công!');
                    setTimeout(function () {
                       window.location.href = res.url
                       flag = 0;
                    }, 1500);
                    
                }
            }
        }).always(function() { l.stop();});

        return false;
    })
}

function support_request_popup() {
    $(".contentheader").click(function(){
        $('#supportRequestModal').modal('show');
        sendFormSupportRequest();
    })
}

var emailRegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$/;
function sendFormSupportRequest() {
    $('.btn-send-mic').click(function(e) {
        e.preventDefault();
        var formName = $(this).data('form');
        var form = $('form[name='+ formName +']');
        var formData = {name:"", phone:"", email:"", comment:""};  
        if (form.find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = form.find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                form.find('.errorMicName').html('<span>'+ messages.name_is_required +'</span>');
            }else{
                form.find('.errorMicName').html('');
                formData.name = c_mgs_name; 
            }
        }
        
        if (form.find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = form.find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                form.find('.errorMicPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                form.find('.errorMicPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                form.find('.errorMicPhone').html('');
                formData.phone = c_mgs_phone;
            }
        }

        if (form.find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = form.find('textarea[name=c_mgs_comment]').val();        
            if(c_mgs_comment == ''){
                form.find('.errorMicComment').html('<span>'+ messages.question_is_required +'</span>');
            }else{
                form.find('.errorMicComment').html('');
                formData.comment = c_mgs_comment;
            }
        }
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone))) || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == ''))) return false;
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: form.attr('action') + prefixUrl,
            type: 'POST',
            data: form.serialize(),
            success: function(result) {
                send_mail('yêu cầu tư vấn', formData);
                if (result == 1) {
                    $('#mic_support_message_' + formName).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#mic_support_message_' + formName).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                form.find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    });
}

function showChildMenuTop2() {
    $('#nav-menu-top-2 > li').hover(function() {
        $(this).addClass('over menu-active');
    }, function() {
        $(this).removeClass('over menu-active');
      })
}

function collapse_menu_nav(){
    var view_more = 'Xem thêm';
    var collapse = 'Thu gọn';
    $('#show_more_cate').click(function() {
        if($('.nav-main .menuItem').hasClass('hide')){
            $(this).children('span').text(collapse);
            $('#show_more_cate i').attr('class', 'fa fa-minus-circle');
            $('.nav-main .menuItem.hide').addClass('show');
            $('.nav-main .menuItem').removeClass('hide');
        }else{
            $('.nav-main .menuItem.show').addClass('hide');
            $('.nav-main .menuItem').removeClass('show');
            $(this).children('span').text(view_more);
            $('#show_more_cate i').attr('class', 'fa fa-plus-circle');
        }
    });
}

function add_to_cart() {
    $(".add-to-cart, .icon-giohang").click(function(){
        var numberCartItem = $('.number_cart_item');
        var id  = $(this).data("id");
        var name  = $(this).data("name");
        var cartType = $(this).data('cart-type');
        var numberItem = numberCartItem.text();

        var url = '/gio-hang/';
        if (lang != 'vi') {
            url = '/' + lang + '/cart'
        }
        
        $.ajax({
            type: "POST",
            url: "/add-to-cart" + prefixUrl,
            data: {"id":id},
            success:function(result){
                if(result == "success") {
                    if (cartType == 1) {
                        window.location.href = url;
                    } else {
                        toastr.options = {
                          "positionClass": "toast-top-right",
                        }

                        toastr.success('Thêm ' + name + ' vào giỏ hàng thành công');
                        numberCartItem.text(parseInt(numberItem) + 1);
                    }
                }
            }
        })
    });
}

function add_to_cart_qty() {
    $("#add_to_cart").click(function(){
        var options  = $(this).data("options");
        var id  = $(this).data("id");
        var qty  = $('input[name=product_qty]').val();
        var url = '/gio-hang/';
        if (lang != 'vi') {
            url = '/' + lang + '/cart'
        }

        $.ajax({
            type: "POST",
            url: "/add-to-cart" + prefixUrl,
            data: {"id":id, 'qty':qty, 'options':options},
            success:function(result){
                if(result == "success") {
                    window.location.href = url;
                }
            }
        })
    });
}

function update_cart() {
    $("input[name=cart_qty]").bind("change",function(event){
        var qty = $(this).val();
        var id = $(this).data("id");
        var price = $(this).data("price");
        var currency = $(this).data("currency");
    	$.ajax({
            type: "POST",
            url: "/update-cart" + prefixUrl,
            data: {"id":id, "qty":qty, "currency":currency, "price":price},
            success:function(result){
                if(result == "success") {
                    window.location.reload();
                }
            }
        })
    })
}

function delete_cart() {
    $(".event-remove-item").click(function(){
        if(confirm(messages.alert_delete_cart)) {
            var rowid = $(this).data("rowid");
            $.ajax({
                type: "POST",
                url: "/delete-cart" + prefixUrl,
                data: {"rowid":rowid},
                success:function(result){
                    if(result == "success") {
                        window.location.reload();
                    }
                }
            })
        } 
    })
}

function admin_login() {
    $('#adm_btn_left').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_left]').val();
        var c_password = $('input[name=adm_password_left]').val();

        if(c_username == ''){
            $('#error_adm_login_left').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_left').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_left').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_left').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_left').serialize(),
             beforeSend: function(){
                $('#adm_btn_left').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_left').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_left').val(''+ messages.login +'');
                    $('#frm_adm_login_left').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_right').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_right]').val();
        var c_password = $('input[name=adm_password_right]').val();

        if(c_username == ''){
            $('#error_adm_login_right').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_right').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_right').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_right').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_right').serialize(),
             beforeSend: function(){
                $('#adm_btn_right').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_right').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_right').val(''+ messages.login +'');
                    $('#frm_adm_login_right').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_header').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_header]').val();
        var c_password = $('input[name=adm_password_header]').val();

        if(c_username == ''){
            $('#error_adm_login_header').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_header').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_header').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_header').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_header').serialize(),
             beforeSend: function(){
                $('#adm_btn_header').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_header').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_header').val(''+ messages.login +'');
                    $('#frm_adm_login_header').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_footer').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_footer]').val();
        var c_password = $('input[name=adm_password_footer]').val();

        if(c_username == ''){
            $('#error_adm_login_footer').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_footer').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_footer').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_footer').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_footer').serialize(),
             beforeSend: function(){
                $('#adm_btn_footer').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_footer').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_footer').val(''+ messages.login +'');
                    $('#frm_adm_login_footer').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_center').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_center]').val();
        var c_password = $('input[name=adm_password_center]').val();

        if(c_username == ''){
            $('#error_adm_login_center').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_center').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_center').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_center').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_center').serialize(),
             beforeSend: function(){
                $('#adm_btn_center').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_center').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_center').val(''+ messages.login +'');
                    $('#frm_adm_login_center').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#btn_system_login').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=l_username]').val();
        var c_password = $('input[name=l_password]').val();

        if(c_username == ''){
            toastr.error(messages.username_is_required);
            return false;
        }

        if(c_password == ''){
            toastr.error(messages.password_is_required);
            return false;
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/system-login' + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_system_login]').serialize(),
             beforeSend: function(){
                $('#btn_system_login').val(''+ messages.sending +'');
            },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status == 500) {
                    toastr.error(messages.fail_username_or_password);
                    $('#btn_system_login').val(''+ messages.login +'');
                } else {
                    window.location.href = res.url;
                }
            }            
        });

        return false;
    });
}

function category_sub_down() {
    $(".subDropdown").on("click", function() {
        $(this).toggleClass("plus"), 
        $(this).toggleClass("minus"), 
        $(this).parent().find("ul").first().slideToggle()
    })
}

function newsletter() {
    $('.btn-send-newsletter').click(function(e){
        e.preventDefault();
        var position = $(this).data('position');
        var emailInput = $('input[name=newsletter_email_'+ position +']');
        var email = $('input[name=newsletter_email_'+ position +']').val();
        if(email == '') {
            toastr.error(''+ messages.email_is_required +'');
            return false;
        } else if(!emailRegExp.test(email)){
            toastr.error(''+ messages.email_not_in_correct_format +'');
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            type: 'POST',
            url: $('#frm-newsletter-' + position).attr('action') + prefixUrl,
            data: {'email':email},
            success:function(result){
                if (result == 1) {
                    toastr.success(''+ messages.register_success +'');
                    send_mail('email đăng ký');
                    emailInput.val('');
                } else if (result == -1) {
                    toastr.warning(''+ messages.email_already_registered +'');
                    emailInput.val('');
                }
            }
       }).always(function() { l.stop();});
    })
}

function customer_message()
{
    $(window).resize(function() {
      if ($(window).width() > 767) {
        var flag = 1;
        if (flag == 1) {
            setTimeout(function () {
               $('.btn-customer-message').trigger('click');
               flag = 0;
            }, 7000);
        }
      }
    });
    

    $('.btn-customer-message').click(function(){
        if($(this).hasClass('onlick')){
            $('.form_customer_message').css({'height':'0'});
            $(this).removeClass('onlick');
            $('.sms-mobile').removeClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-down');
            $('.shrink_icon').addClass('fa-angle-up');
            $('.icon_offline_button').css({'display':'inline-block'});
            if ($(window).width() < 768) {
                $('.customer_message_mobile').css({'bottom':'-33px'});
            }
            // $('.btn-customer-message').css({'width':'85%'});
        }else{
            $('.form_customer_message').css({'height':'auto'});
            $(this).addClass('onlick');
            $('.sms-mobile').addClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-up');
            $('.shrink_icon').addClass('fa-angle-down');
            $('.icon_offline_button').css({'display':'none'});
            if ($(window).width() < 768) {
                $('.customer_message_mobile').css({'bottom':'30px'});
            }
            // $('.btn-customer-message').css({'width':'100%'});
        }
    });

    $('.sms-mobile').click(function() {
        if($(this).hasClass('onlick')){
            $('.form_customer_message').css({'height':'0'});
            $(this).removeClass('onlick');
            $('.btn-customer-message').removeClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-down');
            $('.shrink_icon').addClass('fa-angle-up');
            $('.icon_offline_button').css({'display':'inline-block'});
            $('.customer_message_mobile').css({'bottom':'-33px'});
            // $('.btn-customer-message').css({'width':'85%'});
        }else{
            $('.form_customer_message').css({'height':'auto'});
            $(this).addClass('onlick');
            $('.btn-customer-message').addClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-up');
            $('.shrink_icon').addClass('fa-angle-down');
            $('.icon_offline_button').css({'display':'none'});
            if($(this).hasClass('sms-mobile-2')){
                $('.customer_message_mobile').css({'bottom':'36px'});
            } else {
                $('.customer_message_mobile').css({'bottom':'30px'});
            }
            // $('.btn-customer-message').css({'width':'100%'});
        }
    })

    $('#btn-send-cmgs').click(function(e) {
        var formData = {name:"", phone:"", email:"", comment:""};
        e.preventDefault();
        if ($('form[name=frm_customer_message]').find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = $('form[name=frm_customer_message]').find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                $('#errorCMgsName').html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorCMgsName').html('');
                formData.name = c_mgs_name;
            }
        }
        
        if ($('form[name=frm_customer_message]').find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = $('form[name=frm_customer_message]').find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                $('#errorCMgsPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                $('#errorCMgsPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorCMgsPhone').html('');
                formData.phone = c_mgs_phone;
            }
        }

        if ($('form[name=frm_customer_message]').find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = $('form[name=frm_customer_message]').find('textarea[name=c_mgs_comment]').val();        
            if(c_mgs_comment == ''){
                $('#errorCMgsComment').html('<span>'+ messages.question_is_required +'</span>');
            }else{
                $('#errorCMgsComment').html('');
                formData.comment = c_mgs_comment;
            }
        }
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone))) || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == ''))) return false;
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_customer_message]').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_customer_message]').serialize(),
            success: function(result) {
                send_mail('tin nhắn', formData);
                if (result == 1) {
                    $('#customer_message_success').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#customer_message_success').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                $('form[name=frm_customer_message]').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    });

    $('.btn-send-cmgs-module').click(function(e) {
        e.preventDefault();
        var formData = {
            name: "",
            phone: "",
            email: "",
            comment: "",
            boxOptionSelect: "",
            workProvince: "",
            birthday: "",
            homeTown: "",
            voice: "",
            address: "",
            portraitImage: "",
            certificateImage: "",
            graduationYear: "",
            major: "",
            level: "",
            gender: "",
            forte: "",
            class: "",
            otherRequest: "",
            salary: ""
        };
        var position = $(this).data('position');

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_box_option_select]').length > 0) {
            var c_mgs_box_option_select = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_box_option_select]').val();
            if(c_mgs_box_option_select == ''){
                $('#errorCMgsBoxSelectOption' + position).html('<span>'+ messages.province_is_required +'</span>');
            }else{
                $('#errorCMgsBoxSelectOption' + position).html('');
                formData.boxOptionSelect = c_mgs_box_option_select;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_work_province]').length > 0) {
            var c_mgs_work_province = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_work_province]').val();
            if(c_mgs_work_province == ''){
                $('#errorCMgsWorkProvince' + position).html('<span>'+ messages.province_is_required +'</span>');
            }else{
                $('#errorCMgsWorkProvince' + position).html('');
                formData.workProvince = c_mgs_work_province;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                $('#errorCMgsName' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorCMgsName' + position).html('');
                formData.name = c_mgs_name;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_birthday]').length > 0) {
            var c_mgs_birthday = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_birthday]').val();
            if(c_mgs_birthday == ''){
                $('#errorCMgsBirthday' + position).html('<span>'+ messages.birthday_is_required +'</span>');
            }else{
                $('#errorCMgsBirthday' + position).html('');
                formData.birthday = c_mgs_birthday;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_home_town]').length > 0) {
            var c_mgs_home_town = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_home_town]').val();
            if(c_mgs_home_town == ''){
                $('#errorCMgsHomeTown' + position).html('<span>'+ messages.hometown_is_required +'</span>');
            }else{
                $('#errorCMgsHomeTown' + position).html('');
                formData.homeTown = c_mgs_home_town;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_voice]').length > 0) {
            var c_mgs_voice = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_voice]').val();
            if(c_mgs_voice == ''){
                $('#errorCMgsVoice' + position).html('<span>'+ messages.voice_is_required +'</span>');
            }else{
                $('#errorCMgsVoice' + position).html('');
                formData.voice = c_mgs_voice;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_address]').length > 0) {
            var c_mgs_address = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_address]').val();
            if(c_mgs_address == ''){
                $('#errorCMgsAddress' + position).html('<span'+ messages.address_is_required +'</span>');
            }else{
                $('#errorCMgsAddress' + position).html('');
                formData.address = c_mgs_address;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_email]').length > 0) {
            var c_mgs_email = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_email]').val();
            if(c_mgs_email != '' && !emailRegExp.test(c_mgs_email)){
                $('#errorCMgsEmail' + position).html('<span>'+ messages.email_not_in_correct_format +'</span>');
            }else{
                $('#errorCMgsEmail' + position).html('');
            }
            formData.email = c_mgs_email;
        }
        
        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                $('#errorCMgsPhone' + position).html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                $('#errorCMgsPhone' + position).html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                $('#errorCMgsPhone' + position).html('');
                formData.phone = c_mgs_phone;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_portrait_image]').length > 0) {
            var c_mgs_portrait_image = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_portrait_image]').val();
            if(c_mgs_portrait_image == ''){
                $('#errorCMgsPortraitImage' + position).html('<span>'+ messages.image_is_required +'</span>');
            }else{
                $('#errorCMgsPortraitImage' + position).html('');
                formData.portraitImage = c_mgs_portrait_image;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_certificate_image]').length > 0) {
            var c_mgs_certificate_image = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_certificate_image]').val();
            if(c_mgs_certificate_image == ''){
                $('#errorCMgsCertificateImage' + position).html('<span>'+ messages.certificat_is_required +'</span>');
            }else{
                $('#errorCMgsCertificateImage' + position).html('');
                formData.certificateImage = c_mgs_certificate_image;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_college_address]').length > 0) {
            var c_mgs_college_address = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_college_address]').val();
            if(c_mgs_college_address == ''){
                $('#errorCMgsCollegeAddress' + position).html('<span>'+ messages.training_school_is_required +'</span>');
            }else{
                $('#errorCMgsCollegeAddress' + position).html('');
                formData.collegeAddress = c_mgs_college_address;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_major]').length > 0) {
            var c_mgs_major = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_major]').val();
            if(c_mgs_major == ''){
                $('#errorCMgsMajor' + position).html('<span>'+ messages.major_is_required +'</span>');
            }else{
                $('#errorCMgsMajor' + position).html('');
                formData.major = c_mgs_major;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_graduation_year]').length > 0) {
            var c_mgs_graduation_year = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_graduation_year]').val();
            if(c_mgs_graduation_year == ''){
                $('#errorCMgsGraduationYear' + position).html('<span>'+ messages.graduation_year_is_required +'</span>');
            }else{
                $('#errorCMgsGraduationYear' + position).html('');
                formData.graduationYear = c_mgs_graduation_year;
            }
        }


        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_level]').length > 0) {
            var c_mgs_level = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_voice]').val();
            if(c_mgs_level == ''){
                $('#errorCMgsLevel' + position).html('<span>'+ messages.level_is_required +'</span>');
            }else{
                $('#errorCMgsLevel' + position).html('');
                formData.level = c_mgs_level;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_gender]').length > 0) {
            var c_mgs_gender = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_gender]').val();
            if(c_mgs_gender == ''){
                $('#errorCMgsGender' + position).html('<span>'+ messages.gender_is_required +'</span>');
            }else{
                $('#errorCMgsGender' + position).html('');
                formData.gender = c_mgs_gender;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_forte]').length > 0) {
            var c_mgs_forte = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_forte]').val();
            if(c_mgs_forte == ''){
                $('#errorCMgsForte' + position).html('<span>'+ messages.advantages_is_required +'</span>');
            }else{
                $('#errorCMgsForte' + position).html('');
                formData.forte = c_mgs_forte;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_subjects[]"]').length > 0) {
            var c_mgs_subjects = $('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_subjects[]"]:checked').length;
            if(c_mgs_subjects == 0){
                $('#errorCMgsSubjects' + position).html('<span>'+ messages.subjects_is_required +'</span>');
            }else{
                $('#errorCMgsSubjects' + position).html('');
                formData.subjectsCustommer = c_mgs_subjects;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_class[]"]').length > 0) {
            var c_mgs_class = $('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_class[]"]:checked').length;
            if(c_mgs_class == 0){
                $('#errorCMgsClass' + position).html('<span>'+ messages.class_is_required +'</span>');
            }else{
                $('#errorCMgsClass' + position).html('');
                formData.class = c_mgs_class;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_training_time[]"]').length > 0) {
            var c_mgs_training_time = $('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_training_time[]"]:checked').length;
            if(c_mgs_training_time == 0){
                $('#errorCMgsTrainingTime' + position).html('<span>'+ messages.training_time_is_required +'</span>');
            }else{
                $('#errorCMgsTrainingTime' + position).html('');
                formData.trainingTime = c_mgs_training_time;
            }
        }
        
        if ($('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = $('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_comment]').val();
            if(c_mgs_comment == ''){
                $('#errorCMgsComment' + position).html('<span>'+ messages.question_is_required +'</span>');
            }else{
                $('#errorCMgsComment' + position).html('');
                formData.comment = c_mgs_comment;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_salary]').length > 0) {
            var c_mgs_salary = $('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_salary]').val();
            if(c_mgs_salary == ''){
                formData.salary = c_mgs_salary;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_other_request]').length > 0) {
            var c_mgs_other_request = $('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_other_request]').val();
            if(c_mgs_other_request == ''){
                formData.otherRequest = c_mgs_other_request;
            }
        }
        
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') 
            || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone)))) 
            || (typeof(c_mgs_email) != 'undefined' && c_mgs_email !='' && !emailRegExp.test(c_mgs_email))
            || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == '')
            || (typeof(c_mgs_birthday) != 'undefined' && c_mgs_birthday == '')
            || (typeof(c_mgs_box_option_select) != 'undefined' && c_mgs_box_option_select == '')
            || (typeof(c_mgs_work_province) != 'undefined' && c_mgs_work_province == '')
            || (typeof(c_mgs_home_town) != 'undefined' && c_mgs_home_town == '')
            || (typeof(c_mgs_voice) != 'undefined' && c_mgs_voice == '')
            || (typeof(c_mgs_portrait_image) != 'undefined' && c_mgs_portrait_image == '')
            || (typeof(c_mgs_college_address) != 'undefined' && c_mgs_college_address == '')
            || (typeof(c_mgs_graduation_year) != 'undefined' && c_mgs_graduation_year == '')
            || (typeof(c_mgs_level) != 'undefined' && c_mgs_level == '')
            || (typeof(c_mgs_gender) != 'undefined' && c_mgs_gender == '')
            || (typeof(c_mgs_forte) != 'undefined' && c_mgs_forte == '')
            || (typeof(c_mgs_subjects) != 'undefined' && c_mgs_subjects == 0)
            || (typeof(c_mgs_class) != 'undefined' && c_mgs_class == 0)
            || (typeof(c_mgs_training_time) != 'undefined' && c_mgs_training_time == 0)
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_customer_message_'+ position +']').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_customer_message_'+ position +']').serialize(),
            success: function(result) {
                send_mail('tin nhắn', formData);
                if (result == 1) {
                    $('#customer_message_module_success_' + position).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#customer_message_module_success_' + position).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                $('form[name=frm_customer_message_'+ position +']').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    })
}

function send_info_frm_ycbg() {
    $('#btn-send-ycbg').click(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:"", place_pic:"", place_arrive:"", day:""};
        var frm_popup = $('form[name=frm_item_ycbg]');
        var id = frm_popup.find($('input[name=frm_item_id]')).val();
        /*if ($('input[name=frm_item_name_'+ id +']').length > 0) {
            var name = $('input[name=frm_item_name_'+ id +']').val();
            if(name == ''){
                toastr.error(''+ messages.please_enter +' họ tên của bạn');
                $('input[name=frm_item_name_'+ id +']').focus();
                return false;
            }
        }*/
        var name = $('input[name=frm_item_name_'+ id +']').val();
        formData.name = name;

        if (frm_popup.find('input[name=frm_item_phone_'+ id +']')) {
            var phone = frm_popup.find('input[name=frm_item_phone_'+ id +']').val();
            if(phone == ''){
                toastr.error(''+ messages.phone_is_required +'');
                frm_popup.find('input[name=frm_item_phone_'+ id +']').focus();
                return false;
            }else if(!$.isNumeric(phone)){
                toastr.error(''+ messages.phone_not_in_correct_format +'');
                 frm_popup.find('input[name=frm_item_phone_'+ id +']').focus();
                return false;
            }
            formData.phone = phone;
        }

        if (frm_popup.find('input[name=frm_item_email_'+ id +']').length > 0) {
            var email = frm_popup.find('input[name=frm_item_email_'+ id +']').val();
            if(email != '' && !emailRegExp.test(email)){
                toastr.error(''+ messages.email_not_in_correct_format +'');
                frm_popup.find('input[name=frm_item_email_'+ id +']').focus();
                return false;
            }
            formData.email = email;
        }

        if (frm_popup.find($('select[name=frm_item_class_'+ id +']')).length > 0) {
            var studentClass = frm_popup.find('select[name=frm_item_class_'+ id +']').val();
            if(studentClass == ''){
                toastr.error(''+ messages.class_is_required +'');
                frm_popup.find('select[name=frm_item_class_'+ id +']').focus();
                return false;
            }
            formData.studentClass = studentClass;
        }

        if (frm_popup.find($('input[name=frm_item_subjects_'+ id +']')).length > 0) {
            var subjects = frm_popup.find('input[name=frm_item_subjects_'+ id +']').val();
            if(subjects == ''){
                toastr.error(''+ messages.subjects_learning_is_required +'');
                frm_popup.find('input[name=frm_item_subjects_'+ id +']').focus();
                return false;
            }
            formData.subjects = subjects;
        }
        
        if (frm_popup.find($('input[name=frm_item_student_number_'+ id +']')).length > 0) {
            var studentNumber = frm_popup.find('input[name=frm_item_student_number_'+ id +']').val();
            if(studentNumber == ''){
                toastr.error(''+ messages.student_number_is_required +'');
                frm_popup.find('input[name=frm_item_student_number_'+ id +']').focus();
                return false;
            }
            formData.studentNumber = studentNumber;
        }

        if (frm_popup.find($('input[name=frm_item_learning_level_'+ id +']')).length > 0) {
            var learningLevel = frm_popup.find('input[name=frm_item_learning_level_'+ id +']').val();
            if(learningLevel == ''){
                toastr.error(''+ messages.learning_level_is_required +'');
                frm_popup.find('input[name=frm_item_learning_level_'+ id +']').focus();
                return false;
            }
            formData.learningLevel = learningLevel;
        }

        if (frm_popup.find($('select[name=frm_item_learning_time_'+ id +']')).length > 0) {
            var learningTime = frm_popup.find('select[name=frm_item_learning_time_'+ id +']').val();
            if(learningTime == ''){
                toastr.error(''+ messages.learning_time_is_required +'');
                frm_popup.find('select[name=frm_item_learning_level_'+ id +']').focus();
                return false; 
            }
            formData.learningTime = learningTime;
        }

        if (frm_popup.find($('input[name=frm_item_learning_day_'+ id +']')).length > 0) {
            var learningDay = frm_popup.find('input[name=frm_item_learning_day_'+ id +']').val();
            if(learningDay == ''){
                toastr.error(''+ messages.learning_day_is_required +'');
                frm_popup.find('input[name=frm_item_learning_day_'+ id +']').focus();
                return false; 
            }
            formData.learningDay = learningDay;
        }

        if (frm_popup.find($('select[name=frm_item_request_'+ id +']')).length > 0) {
            var request = frm_popup.find('select[name=frm_item_request_'+ id +']').val();
            if(request == ''){
                toastr.error(''+ messages.request_is_required +'');
                frm_popup.find('select[name=frm_item_request_'+ id +']').focus();
                return false;  
            }
            formData.request = request;
        }

        if (frm_popup.find('input[name=frm_item_place_pic_'+ id +']').length > 0) {
            var place_pic = frm_popup.find('input[name=frm_item_place_pic_'+ id +']').val();
            var place_pic_name = frm_popup.find('input[name=frm_item_place_pic_'+ id +']').data('name');
            if(place_pic == ''){
                toastr.error(''+ messages.please_enter +' '+ place_pic_name +'');
                frm_popup.find('input[name=frm_item_place_pic_'+ id +']').focus();
                return false;
            }
            formData.place_pic = place_pic;
        }

        if (frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').length > 0) {
            var place_arrive = frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').val();
            var place_arrive_name = frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').data('name');
            if(place_arrive == ''){
                toastr.error(''+ messages.please_enter +' '+ place_arrive_name +'');
                frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').focus();
                return false;
            }
            formData.place_arrive = place_arrive;
        }

        if (frm_popup.find('input[name=frm_item_day_'+ id +']').length > 0) {
            var day = frm_popup.find('input[name=frm_item_day_'+ id +']').val();
            var day_name = frm_popup.find('input[name=frm_item_day_'+ id +']').data('name');
            if(day == ''){
                toastr.error(''+ messages.please_select +' '+ day_name +'');
                frm_popup.find('input[name=frm_item_day_'+ id +']').focus();
                return false;
            }
            formData.day = day;
        }
       
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: frm_popup.attr('action') + prefixUrl,
            type: 'POST',
            data: frm_popup.serialize(),
            success: function(result) {
                send_mail(frm_popup.data('name'), formData);
                if (result == 1) {
                    toastr.success(''+ messages.request_is_sended +'');
                } else {
                    toastr.error(''+ messages.request_is_sended +'');
                }
                frm_popup.find("input[type=text], input[type=email], textarea").val("");
                $('#modalItemYcbg').modal('toggle');
            }            
        }).always(function() { l.stop();});
        return false;
    });

    $("form[name='frm_item_ycbg_module']").submit(function(e) {
        e.preventDefault();
        var frm = $(this);
        var formData = {name:"", phone:"", email:"", comment:"", place_pic:"", place_arrive:"", day:"", learningLevel:"", learningTime:"", learningDay:"", request:"", studentNumber: "", subjects:"", teacherCode:"", studentClass:""};
        var position = $(this).data('position');
        var data = new FormData(this);
        var id = frm.find($('input[name=frm_item_id]')).val();
        if (frm.find($('input[name=frm_item_name_'+ id +']')).length > 0) {
            var name = frm.find('input[name=frm_item_name_'+ id +']').val();
            if(name == ''){
                $('#errorFrmItemName' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorFrmItemName' + position).html('');
                formData.name = name;
            }
        }
        
        if (frm.find($('input[name=frm_item_phone_'+ id +']')).length > 0) {
            var phone = frm.find('input[name=frm_item_phone_'+ id +']').val();
            if(phone == ''){
                $('#errorFrmItemPhone' + position).html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorFrmItemPhone' + position).html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorFrmItemPhone' + position).html('');
                formData.phone = phone;
            }
        }

        if (frm.find($('input[name=frm_item_email_'+ id +']')).length > 0) {
            var email = frm.find('input[name=frm_item_email_'+ id +']').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorFrmItemEmail' + position).html('<span>'+ messages.email_not_in_correct_format +'</span>'); 
            }else{
                $('#errorFrmItemEmail' + position).html('');
                formData.email = email;
            }
        }

        if (frm.find($('select[name=frm_item_class_'+ id +']')).length > 0) {
            var studentClass = frm.find('select[name=frm_item_class_'+ id +']').val();
            if(studentClass == ''){
                $('#errorFrmItemClass' + position).html('<span>'+ messages.class_is_required +'</span>'); 
            }else{
                $('#errorFrmItemClass' + position).html('');
                formData.studentClass = studentClass;
            }
        }

        if (frm.find($('input[name=frm_item_subjects_'+ id +']')).length > 0) {
            var subjects = frm.find('input[name=frm_item_subjects_'+ id +']').val();
            if(subjects == ''){
                $('#errorFrmItemSubjects' + position).html('<span>'+ messages.subjects_learning_is_required +'</span>'); 
            }else{
                $('#errorFrmItemSubjects' + position).html('');
                formData.subjects = subjects;
            }
        }
        
        if (frm.find($('input[name=frm_item_student_number_'+ id +']')).length > 0) {
            var studentNumber = frm.find('input[name=frm_item_student_number_'+ id +']').val();
            if(studentNumber == ''){
                $('#errorFrmItemStudentNumber' + position).html('<span>'+ messages.student_number_is_required +'</span>'); 
            }else{
                $('#errorFrmItemStudentNumber' + position).html('');
                formData.studentNumber = studentNumber;
            }
        }

        if (frm.find($('input[name=frm_item_learning_level_'+ id +']')).length > 0) {
            var learningLevel = frm.find('input[name=frm_item_learning_level_'+ id +']').val();
            if(learningLevel == ''){
                $('#errorFrmItemLearningLevel' + position).html('<span>'+ messages.learning_level_is_required +'</span>'); 
            }else{
                $('#errorFrmItemLearningLevel' + position).html('');
                formData.learningLevel = learningLevel;
            }
        }

        if (frm.find($('select[name=frm_item_learning_time_'+ id +']')).length > 0) {
            var learningTime = frm.find('select[name=frm_item_learning_time_'+ id +']').val();
            if(learningTime == ''){
                $('#errorFrmItemLearningTime' + position).html('<span>'+ messages.learning_time_is_required +'</span>'); 
            }else{
                $('#errorFrmItemLearningTime' + position).html('');
                formData.learningTime = learningTime;
            }
        }

        if (frm.find($('input[name=frm_item_learning_day_'+ id +']')).length > 0) {
            var learningDay = frm.find('input[name=frm_item_learning_day_'+ id +']').val();
            if(learningDay == ''){
                $('#errorFrmItemLearningDay' + position).html('<span>'+ messages.learning_day_is_required +'</span>'); 
            }else{
                $('#errorFrmItemLearningDay' + position).html('');
                formData.learningDay = learningDay;
            }
        }

        if (frm.find($('select[name=frm_item_request_'+ id +']')).length > 0) {
            var request = frm.find('select[name=frm_item_request_'+ id +']').val();
            if(request == ''){
                $('#errorFrmItemRequest' + position).html('<span>'+ messages.request_is_required +'</span>'); 
            }else{
                $('#errorFrmItemRequest' + position).html('');
                formData.request = request;
            }
        }

        var teacherCode = frm.find('input[name=teacher_code_'+ id +']').val();
        formData.teacherCode = teacherCode;

        if (frm.find($('input[name=frm_item_place_pic_'+ id +']')).length > 0) {
            var place_pic = frm.find('input[name=frm_item_place_pic_'+ id +']').val();
            var place_pic_name = frm.find('input[name=frm_item_place_pic_'+ id +']').data('name');
            if(place_pic == ''){
                $('#errorFrmItemPlacePic' + position).html('<span>'+ messages.please_enter +' '+ place_pic_name +'</span>');
            } else {
                $('#errorFrmItemPlacePic' + position).html('');
                formData.place_pic = place_pic;
            }
        }

        if (frm.find($('input[name=frm_item_place_arrive_'+ id +']')).length > 0) {
            var place_arrive = frm.find('input[name=frm_item_place_arrive_'+ id +']').val();
            var place_arrive_name = frm.find('input[name=frm_item_place_arrive_'+ id +']').data('name');
            if(place_arrive == ''){
                $('#errorFrmItemPlaceArrive' + position).html('<span>'+ messages.please_enter +' '+ place_arrive_name +'</span>');
            } else {
                $('#errorFrmItemPlaceArrive' + position).html('');
                formData.place_arrive = place_arrive;
            }
        }

        if (frm.find($('input[name=frm_item_day_'+ id +']')).length > 0) {
            var day = frm.find('input[name=frm_item_day_'+ id +']').val();
            var day_name = frm.find('input[name=frm_item_day_'+ id +']').data('name');
            if(day == ''){
                $('#errorFrmItemDay' + position).html('<span>'+ messages.please_select +' '+ day_name +'</span>');
            } else {
                $('#errorFrmItemDay' + position).html('');
                formData.day = day;
            }
        }

        if (frm.find($('input[name=frm_item_file_'+ id +']')).length > 0) {
            var fileInput = frm.find($('input[name=frm_item_file_'+ id +']'));
            var fileInputVal = fileInput.val();
            var fileData = fileInput.prop('files')[0];
            var ext = fileInputVal.split('.').pop();
            var validFile = true;
            if (fileInputVal != '') {
                if (ext != 'pdf' && ext != 'docx' && ext != 'doc' && ext != 'xls' && ext != 'xlsx') {
                    $('#errorFrmItemFile' + position).html('<span>'+ messages.file_in_correct_format +'</span>');
                    validFile = false;
                } else if (fileData.size > 5 * 1024 * 1024) {
                    $('#errorFrmItemFile' + position).html('<span>'+ messages.file_to_large +'</span>');
                    validFile = false;
                } else{
                    $('#errorFrmItemFile' + position).html('');
                    validFile = true;
                }
            }
        }

        if ((typeof(name) != 'undefined' && name == '') 
            || (typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email)) 
            || (typeof(place_pic) != 'undefined' && place_pic == '') 
            || (typeof(place_arrive) != 'undefined' && place_arrive == '') 
            || (typeof(studentClass) != 'undefined' && studentClass == '')
            || (typeof(subjects) != 'undefined' && subjects == '')
            || (typeof(studentNumber) != 'undefined' && studentNumber == '')
            || (typeof(learningLevel) != 'undefined' && learningLevel == '')
            || (typeof(learningTime) != 'undefined' && learningTime == '')
            || (typeof(learningDay) != 'undefined' && learningDay == '')
            || (typeof(request) != 'undefined' && request == '')
            || (typeof(validFile) != 'undefined' && !validFile)
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();

        $.ajax({
            url: frm.attr('action') + prefixUrl,
            type: 'POST',
            data: data,
            success: function (result) {
                send_mail(frm.data('name'),  formData);
                if (result == 1) {
                    $('#form_item_module_success_' + position).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.request_is_sended +'</div>');
                } else {
                    $('#form_item_module_success_' + position).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.error_try_again +'</div>');
                }
                frm.find("input[type=text], input[type=email], input[type=file], textarea").val("");
            },
            cache: false,
            contentType: false,
            processData: false
        }).always(function() { l.stop();});
        return false;
    });

    $('.btn-send-form-fast-register').click(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:"", place_pic:"", place_arrive:"", day:"", hour:"", method:""};
        var frm = $('form[name=frm_fast_register]')
        var id = frm.find($('input[name=frm_item_id]')).val();

        if (frm.find($('input[name=frm_item_name_'+ id +']')).length > 0) {
            var name = frm.find('input[name=frm_item_name_'+ id +']').val();
            if(name == ''){
                $('#errorFrmFastRegName').html('<span>'+ messages.name_is_required +'</span>');
            } else {
                $('#errorFrmFastRegName').html('');
                formData.name = name;
            }
        }
        
        if (frm.find($('input[name=frm_item_phone_'+ id +']')).length > 0) {
            var phone = frm.find('input[name=frm_item_phone_'+ id +']').val();
            if(phone == ''){
                $('#errorFrmFastRegPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorFrmFastRegPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorFrmFastRegPhone').html('');
                formData.phone = phone;
            }
        }

        if (frm.find($('input[name=frm_item_email_'+ id +']')).length > 0) {
            var email = frm.find('input[name=frm_item_email_'+ id +']').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorFrmFastRegEmamil').html('<span>'+ messages.email_not_in_correct_format +'</span>'); 
            }else{
                $('#errorFrmFastRegEmamil').html('');
                formData.email = email;
            }
        }

        if (frm.find($('select[name=frm_item_method_'+ id +']')).length > 0) {
            var method = frm.find('select[name=frm_item_method_'+ id +']').val();
            if(method == ''){
                $('#errorFrmFastRegMethod').html('<span>'+ messages.method_class_required +'</span>'); 
            }else{
                $('#errorFrmFastRegMethod').html('');
                formData.method = method;
            }
        }

        if (frm.find($('input[name=frm_item_day_'+ id +']')).length > 0) {
            var day = frm.find('input[name=frm_item_day_'+ id +']').val();
            var day_name = frm.find('input[name=frm_item_day_'+ id +']').data('name');
            if(day == ''){
                $('#errorFrmFastRegDay').html('<span>'+ messages.please_select +' '+ day_name +'</span>');
            } else {
                $('#errorFrmFastRegDay').html('');
                formData.day = day;
            }
        }

        if (frm.find($('select[name=frm_item_hour_one_'+ id +']')).length > 0) {
            var hour = frm.find('select[name=frm_item_hour_one_'+ id +']').val();
            if(hour == ''){
                $('#errorFrmFastRegHour').html('<span>'+ messages.time_receive_class_required +'</span>'); 
            }else{
                $('#errorFrmFastRegHour').html('');
                formData.hour = hour;
            }
        }

        if (frm.find($('input[name=frm_item_number_ticket_'+ id +']')).length > 0) {
            var number_ticket = frm.find('input[name=frm_item_number_ticket_'+ id +']').val();
            if(number_ticket == ''){
                $('#errorFrmFastRegNumberTicket').html('<span>'+ messages.number_ticket_required +'</span>');
            }else if(!$.isNumeric(number_ticket)){
                $('#errorFrmFastRegNumberTicket').html('<span>'+ messages.error_format +'</span>');
            }else{
                $('#errorFrmFastRegNumberTicket').html('');
                formData.numberTicket = number_ticket;
            }
        }

        if (frm.find($('input[name=frm_item_start_time_'+ id +']')).length > 0) {
            var start_time = frm.find('input[name=frm_item_start_time_'+ id +']').val();
            if(start_time == ''){
                formData.startTime = start_time;
            }
        }

        if (frm.find($('input[name=frm_item_end_time_'+ id +']')).length > 0) {
            var end_time = frm.find('input[name=frm_item_end_time_'+ id +']').val();
            if(end_time == ''){
                formData.endTime = end_time;
            }
        }

        if (frm.find($('input[name=frm_item_comment_'+ id +']')).length > 0) {
            var comment = frm.find('input[name=frm_item_comment_'+ id +']').val();
            if(comment == ''){
                formData.comment = comment;
            }
        }
        
        if ((typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email)) 
            || (typeof(name) != 'undefined' && name == '') 
            || (typeof(method) != 'undefined' && method == '') 
            || (typeof(day) != 'undefined' && day == '') 
            || (typeof(hour) != 'undefined' && hour == '')
            || (typeof(numberTicket) != 'undefined' && (numberTicket == '' || !($.isNumeric(numberTicket))))
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: frm.attr('action') + prefixUrl,
            type: 'POST',
            data: frm.serialize(),
            success: function(result) {
                send_mail(frm.data('name'),  formData);
                if (result == 1) {
                    toastr.success(''+ messages.register_success_message +'');
                } else {
                    toastr.error(''+ messages.error_try_again +'');
                }
                frm.find("input[type=text], input[type=email], textarea, select").val("");
            }            
        }).always(function() { l.stop();});
        return false;
    })
}

function send_customer_comment() {
    $("form[name='frm_customer_comment']").submit(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:"", address:"", address:""};
        var data = new FormData(this);
        var frm = $('form[name=frm_customer_comment]');

        if (frm.find($('input[name=cc_f_name]')).length > 0) {
            var name = frm.find('input[name=cc_f_name]').val();
            if(name == ''){
                $('#errorFrmCCFName').html('<span>'+ messages.name_is_required +'</span>');
            } else {
                $('#errorFrmCCFName').html('');
                formData.name = name;
            }
        }
        
        if (frm.find($('input[name=cc_f_phone]')).length > 0) {
            var phone = frm.find('input[name=cc_f_phone]').val();
            if(phone == ''){
                $('#errorFrmCCFPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorFrmCCFPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorFrmCCFPhone').html('');
                formData.phone = phone;
            }
        }

        if (frm.find($('input[name=cc_f_email]')).length > 0) {
            var email = frm.find('input[name=cc_f_email]').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorFrmCCFEmail').html('<span>'+ messages.email_not_in_correct_format +'</span>'); 
            }else{
                $('#errorFrmCCFEmail').html('');
                formData.email = email;
            }
        }

        if (frm.find($('textarea[name=cc_f_comment]')).length > 0) {
            var comment = frm.find('textarea[name=cc_f_comment]').val();
            if(comment == ''){
                $('#errorFrmCCFComment').html('<span>'+ messages.comment_is_required +'</span>'); 
            } else {
                $('#errorFrmCCFComment').html('');
                formData.comment = comment;
            }
        }

        if (frm.find($('input[name=cc_f_address]')).length > 0) {
            var address = frm.find('input[name=cc_f_address]').val();
            if(address == ''){
                formData.address = comment;
            }
        }

        if (frm.find($('input[name=file_images_custom_cmts]')).length > 0) {
            var fileInput = frm.find($('input[name=file_images_custom_cmts]'));
            var fileInputVal = fileInput.val();
            var fileData = fileInput.prop('files')[0];
            var fileType = fileData['type'];
            var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
            var validFile = true;
            if (fileInputVal != '') {
                if (!validImageTypes.includes(fileType)) {
                    $('#errorFrmItemFileCmt').html('<span>'+ messages.file_in_correct_format +'</span>');
                    validFile = false;
                } else if (fileData.size > 5 * 1024 * 1024) {
                    $('#errorFrmItemFileCmt').html('<span>'+ messages.file_to_large +'</span>');
                    validFile = false;
                } else{
                    $('#errorFrmItemFileCmt').html('');
                    validFile = true;
                }
            }
        }
        
        if ((typeof(name) != 'undefined' && name == '') 
            || (typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email)) 
            || (typeof(comment) != 'undefined' && comment == '')
            || (typeof(validFile) != 'undefined' && !validFile)
        ) {
            return false;
        }

        var imgPreview = frm.find('.img_review');
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: frm.attr('action') + prefixUrl,
            type: 'POST',
            data: data,
            success: function(result) {
                send_mail(frm.data('name'),  formData);
                if (result == 1) {
                    $('#customer_comment_success').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.thanks_customer +'</div>');
                    
                    // frm.find($("#files_cdn_customer_photo").html(''));
                } else {
                    $('#customer_comment_success').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.error_try_again +'</div>');
                }
                frm.find("input[type=text], input[type=email],input[type=file], textarea").val("");
                imgPreview.attr('src', '#');
                imgPreview.hide();
            },
            cache: false,
            contentType: false,
            processData: false         
        }).always(function() { l.stop();});
        return false;
    })
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.img_review').attr('src', e.target.result);
            $('.img_review').show();
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function send_contact() {
    $('.btn-send-contact-module').click(function(e) {
        e.preventDefault();
        var formData = {
            name: "",
            phone: "",
            email: "",
            comment: "",
            subject: "",
            address: "",
        };
        var position = $(this).data('position');

        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=name]').length > 0) {
            var name = $('form[name=frm_md_contact_'+ position +']').find('input[name=name]').val();
            if(name == ''){
                $('#errorContactName' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorContactName' + position).html('');
                formData.name = name;
            }
        }

        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=email]').length > 0) {
            var email = $('form[name=frm_md_contact_'+ position +']').find('input[name=email]').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorContactEmail' + position).html('<span>'+ messages.email_not_in_correct_format +'</span>');
            }else{
                $('#errorContactEmail' + position).html('');
            }
            formData.email = email;
        }
        
        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=phone]').length > 0) {
            var phone = $('form[name=frm_md_contact_'+ position +']').find('input[name=phone]').val();
            if(phone == ''){
                $('#errorContactPhone' + position).html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorContactPhone' + position).html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                $('#errorContactPhone' + position).html('');
                formData.phone = phone;
            }
        }

        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=address]').length > 0) {
            var address = $('form[name=frm_md_contact_'+ position +']').find('input[name=address]').val();
            if(address == ''){
                $('#errorContactAddress' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorContactAddress' + position).html('');
                formData.address = address;
            }
        }
        
       
        if((typeof(name) != 'undefined' && name == '') 
            || (typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email))
            || (typeof(address) != 'undefined' && address == '')
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_md_contact_'+ position +']').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_md_contact_'+ position +']').serialize(),
            success: function(result) {
                send_mail('liên hệ', formData);
                if (result == 1) {
                    $('#contact_module_success_' + position).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.send_contact_success +'</div>');
                } else {
                    $('#contact_module_success_' + position).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.error_try_again +'</div>');
                }
                $('form[name=frm_md_contact_'+ position +']').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    })
}

function send_mail(title, formData) {
    if (formData != null) {
        var data  = {'title':title, 'formData':formData}
    } else {
        var data = {'title':title}
    }
    $.ajax({
        type: 'POST',
        url: '/send-mail' + prefixUrl,
        data: data
    })
}

function mic_support() {
    $(".contentheader").click(function(){
        if ($('.livesupport').hasClass('livesupport-left')) {
            if ($('.livesupport').hasClass('livesupportOn')) {
                $('.livesupport').animate({left: -350}, 1200);
                $('.livesupport').removeClass('livesupportOn');
            } else {
                $('.livesupport').animate({left: 0}, 1200);
                $('.livesupport').addClass('livesupportOn');
            }  
        } else {
            if ($('.livesupport').hasClass('livesupportOn')) {
                $('.livesupport').animate({right: -350}, 1200);
                $('.livesupport').removeClass('livesupportOn');
            } else {
                $('.livesupport').animate({right: 0}, 1200);
                $('.livesupport').addClass('livesupportOn');
            }
        } 
    });


    $('#btn-send-mic').click(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:""};
        if ($('form[name=frm_mic_support]').find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = $('form[name=frm_mic_support]').find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                $('#errorMicName').html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorMicName').html('');
                formData.name = c_mgs_name;
            }
        }
        
        if ($('form[name=frm_mic_support]').find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = $('form[name=frm_mic_support]').find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                $('#errorMicPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                $('#errorMicPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                $('#errorMicPhone').html('');
                formData.phone = c_mgs_phone;
            }
        }

        if ($('form[name=frm_mic_support]').find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = $('form[name=frm_mic_support]').find('textarea[name=c_mgs_comment]').val();        
            if(c_mgs_comment == ''){
                $('#errorMicComment').html('<span>'+ messages.question_is_required +'</span>');
            }else{
                $('#errorMicComment').html('');
                formData.comment = c_mgs_comment;
            }
        }
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone))) || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == ''))) return false;
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_mic_support]').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_mic_support]').serialize(),
            success: function(result) {
                send_mail('yêu cầu tư vấn', formData);
                if (result == 1) {
                    $('#mic_support_message').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#mic_support_message').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                $('form[name=frm_mic_support]').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    });
}

function datePickerFormItem() {
    $('.form-item-datepicker').datetimepicker({
        startDate: new Date(),
        format: 'dd/mm/yyyy',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('.form-fast-register-datepicker').datetimepicker({
        startDate: new Date(),
        format: 'dd/mm/yyyy',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('.customer-message-datepicker').datetimepicker({
        format: 'dd/mm/yyyy',
        endDate: new Date(),
        changeMonth: true,
        changeYear: true,
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('.form-item-start-time').datetimepicker({
        startDate: new Date(),
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'dd-mm-yyyy hh:ii'
    });

    $('.form-item-end-time').datetimepicker({
        startDate: new Date(),
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'dd-mm-yyyy hh:ii'
    });
}

function searchSubdomain() {
    $('#btn-search-subdomain').click(function(e) {
        e.preventDefault();
        var keyword = $('#subdomainSearchFrm').find('input[name=keyword]').val();
        if (keyword == '') {
            toastr.error(''+ messages.keyword_is_required +'');
            $('#subdomainSearchFrm').find('input[name=keyword]').focus();
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            type: 'POST',
            url: $('#subdomainSearchFrm').attr('action') + prefixUrl,
            data: {'keyword':keyword},
            success:function(result) {
                if(result != '') {
                    $('#subdomainListResult').html(result);
                    $('#nav-tabs-list-subdomain').hide();
                } else {
                    $('#subdomainListResult').html('<div class="alert alert-warning">'+ messages.not_found_result +'</div>');
                    $('#nav-tabs-list-subdomain').show();
                }
            }
        }).always(function() {
            l.stop();
        })
    })
}

function pagination_ajax() {
    $('.pagination-ajax a').click(function(e) {
        e.preventDefault();
        var url = $(this).data('url') + prefixUrl;
        var page = $(this).data('page');
        var html_id = $(this).closest('.pagination-ajax').data('id');
        $.ajax({
            type: 'POST',
            url: url,
            data: {'page':page},
            beforeSend:function() {
            },
            success:function(html) {
                $('#' + html_id).html(html);
                pagination_ajax();
            }
        })
    })
}

function add_alias_sub() {
    $(".sub-domain").bind("change keyup input",function() {
        var obj = $(this).val();
        $(".sub-username").val(obj);
        // $(".sub-password").val(obj);
    });
}

function marqueeSlider() {
    $('.ctrlpg_6_Image1').click(function() {
        var marquee = $(this).closest('.banner-vertical');
        var marqueeContent = marquee.find('.marquee-content');
        marqueeContent.removeClass('marquee-2');
        marqueeContent.addClass('marquee-1');
    });

    $('.ctrlpg_6_Image2').click(function() {
        var marquee = $(this).closest('.banner-vertical');
        var marqueeContent = marquee.find('.marquee-content');
        marqueeContent.removeClass('marquee-1');
        marqueeContent.addClass('marquee-2');
    });
}
    
$(document).ready(function(){
    $("#mobile-menu").mobileMenu({
            MenuWidth: 250,
            SlideSpeed: 300,
            WindowsMaxWidth: 767,
            PagePush: !0,
            FromLeft: !0,
            Overlay: !0,
            CollapseMenu: !0,
            ClassName: "mobile-menu"
        })

    $("ul.accordion li.parent, ul.accordion li.parents, ul#magicat li.open").each(function() {
        $(this).append('<em class="open-close">&nbsp;</em>')
    }), $("ul.accordion, ul#magicat").accordionNew(), $("ul.accordion li.active, ul#magicat li.active").each(function() {
        $(this).children().next("div").css("display", "block")
    })

})

var isTouchDevice = "ontouchstart" in window || 0 < navigator.msMaxTouchPoints;
jQuery(window).on("load", function() {
        isTouchDevice && jQuery("#nav a.level-top").on("click", function() {
            if ($t = jQuery(this), $parent = $t.parent(), $parent.hasClass("parent")) {
                if (!$t.hasClass("menu-ready")) return jQuery("#nav a.level-top").removeClass("menu-ready"), $t.addClass("menu-ready"), !1;
                $t.removeClass("menu-ready")
            }
        })
    }), jQuery(window).scroll(function() {
        1 < jQuery(this).scrollTop() ? $("nav").addClass("sticky") : jQuery("nav").removeClass("sticky")
    }),
    function(s) {
        s.fn.extend({
            accordion: function(i) {
                var t = s.extend({
                        accordion: "true",
                        speed: 300,
                        closedSign: "[+]",
                        openedSign: "[-]"
                    }, i),
                    e = s(this);
                e.find("li").each(function() {
                    0 != s(this).find("ul").size() && (s(this).find("a:first").after("<em>" + t.closedSign + "</em>"), "#" == s(this).find("a:first").attr("href") && s(this).find("a:first").on("click", function() {
                        return !1
                    }))
                }), e.find("li em").on("click", function() {
                    0 != s(this).parent().find("ul").size() && (t.accordion && (s(this).parent().find("ul").is(":visible") || (parents = s(this).parent().parents("ul"), visible = e.find("ul:visible"), visible.each(function(e) {
                        var n = !0;
                        parents.each(function(i) {
                            return parents[i] == visible[e] ? n = !1 : void 0
                        }), n && s(this).parent().find("ul") != visible[e] && s(visible[e]).slideUp(t.speed, function() {
                            s(this).parent("li").find("em:first").html(t.closedSign)
                        })
                    }))), s(this).parent().find("ul:first").is(":visible") ? s(this).parent().find("ul:first").slideUp(t.speed, function() {
                        s(this).parent("li").find("em:first").delay(t.speed).html(t.closedSign)
                    }) : s(this).parent().find("ul:first").slideDown(t.speed, function() {
                        s(this).parent("li").find("em:first").delay(t.speed).html(t.openedSign)
                    }))
                })
            }
        })
    }(jQuery),
    function(c) {
        c.fn.extend({
            accordionNew: function() {
                return this.each(function() {
                    function i(i, e) {
                        c(i).parent(o).siblings().removeClass(t).children(a).slideUp(r), c(i).siblings(a)[e || s](!("show" != e) && r, function() {
                            c(i).siblings(a).is(":visible") ? c(i).parents(o).not(n.parents()).addClass(t) : c(i).parent(o).removeClass(t), "show" == e && c(i).parents(o).not(n.parents()).addClass(t), c(i).parents().show()
                        })
                    }
                    var n = c(this),
                        e = "accordiated",
                        t = "active",
                        s = "slideToggle",
                        a = "ul, div",
                        r = "fast",
                        o = "li";
                    if (n.data(e)) return !1;
                    c.each(n.find("ul, li>div"), function() {
                        c(this).data(e, !0), c(this).hide()
                    }), c.each(n.find("em.open-close"), function() {
                        c(this).on("click", function() {
                            i(this, s)
                        }), c(this).bind("activate-node", function() {
                            n.find(a).not(c(this).parents()).not(c(this).siblings()).slideUp(r), i(this, "slideDown")
                        })
                    });
                    var l = location.hash ? n.find("a[href=" + location.hash + "]")[0] : n.find("li.current a")[0];
                    l && i(l, !1)
                })
            }
        }), c(function() {
            function n() {
                var i = c('.navbar-collapse form[role="search"].active');
                i.find("input").val(""), i.removeClass("active")
            }
            c('header, .navbar-collapse form[role="search"] button[type="reset"]').on("click keyup", function(i) {
                (27 == i.which && c('.navbar-collapse form[role="search"]').hasClass("active") || "reset" == c(i.currentTarget).attr("type")) && n()
            }), c(document).on("click", '.navbar-collapse form[role="search"]:not(.active) button[type="submit"]', function(i) {
                i.preventDefault();
                var e = c(this).closest("form"),
                    n = e.find("input");
                e.addClass("active"), n.focus()
            }), c(document).on("click", '.navbar-collapse form[role="search"].active button[type="submit"]', function(i) {
                i.preventDefault();
                var e = c(this).closest("form").find("input");
                c("#showSearchTerm").text(e.val()), n()
            })
        })
    }(jQuery);