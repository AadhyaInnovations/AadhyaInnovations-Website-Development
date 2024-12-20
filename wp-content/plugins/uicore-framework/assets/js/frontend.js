uicoreJsonp([5],{

/***/ 175:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(176);

__webpack_require__(177);

__webpack_require__(178);

__webpack_require__(179);

__webpack_require__(180);

/***/ }),

/***/ 176:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 177:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


jQuery(function ($) {
    $('.uicore-cart-icon.uicore-link').click(function () {
        $('body').addClass('uicore-cart-active');
    });
    $('#cart-wrapper').click(function () {
        $('body').removeClass('uicore-cart-active');
    });

    $('#uicore-cart-close').click(function () {
        $('body').removeClass('uicore-cart-active');
    });
});

/***/ }),

/***/ 178:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


jQuery(document).ready(function () {

    if (jQuery('.uicore-sticky').length || jQuery('.uicore-will-be-sticky').length) {
        var uiCoreOnScroll = function uiCoreOnScroll() {
            var mq = window.matchMedia("(max-width: 1025px)");
            var isMobile = mq.matches;
            var winTop = jQuery(window).scrollTop();
            var h = jQuery('.uicore-top-bar').innerHeight();
            var defaultH = 100;
            if (jQuery('.uicore-transparent .ui-header-row1').length) {
                defaultH = 10;
            } else if (jQuery('.ui-header-row1').length) {
                defaultH = 400;
            }
            if (h == null) h = isMobile ? 25 : defaultH;

            //scroll direction
            if (winTop > lastScrollTop && lastScrollTop > 0 && winTop > h - 10 / 100 * h) {
                jQuery('.ui-smart-sticky').addClass('ui-hide');
            } else {
                jQuery('.ui-smart-sticky').removeClass('ui-hide');
            }
            //show menu at scroll to bottom
            if (winTop + jQuery(window).height() > jQuery(document).height() - 50) {
                jQuery('.ui-smart-sticky').removeClass('ui-hide');
            }
            lastScrollTop = winTop;

            //scrolled
            if (winTop > h) {
                jQuery('.uicore-navbar').addClass('uicore-scrolled');
            } else {
                jQuery('.uicore-navbar').removeClass('uicore-scrolled');
            }
        };
        //Trigger on laod and on scroll


        uiCoreOnScroll();
        var lastScrollTop = 0;
        jQuery(window).scroll(uiCoreOnScroll);
        jQuery(document.body).on('touchmove', uiCoreOnScroll);
    }
});

if (navigator.appVersion.indexOf('Win') != -1) {
    //Add win class on body if is windows
    jQuery('body').addClass('win');
}

jQuery(function ($) {
    $('.uicore-search-btn').click(function () {
        //menu search
        $('body').addClass('uicore-search-active');
        $('.uicore-search .search-field').focus();
    });
    $('.uicore-search .uicore-close').click(function () {
        $('body').removeClass('uicore-search-active');
    });

    $('.uicore-search-btn').click(function () {
        $('body').addClass('uicore-search-active');
        $('.uicore-search .search-field').focus();
    });

    $(document).keydown(function (e) {
        if (e.keyCode === 27) {
            $('body').removeClass('uicore-search-active');
        }
    });

    $('.uicore-h-classic .menu-item-has-children:not(.menu-item-has-megamenu.custom-width)').on('mouseenter mouseleave', function (e) {
        if ($('ul', this).length) {
            var elm = $('.sub-menu', this);
            var off = elm.offset();
            var l = off.left;
            var w = elm.width();
            var docW = $('body').width();

            if (l + w > docW) {
                $(this).addClass('uicore-edge');
            }
        }
    });

    ////////////////////////////////////////////////////////////// back to top
    var btn = $('#uicore-back-to-top');

    jQuery(window).scroll(function () {
        if (jQuery(window).scrollTop() > 300) {
            btn.addClass('uicore-visible');
        } else {
            btn.removeClass('uicore-visible');
        }
    });

    btn.on('click', function (e) {
        e.preventDefault();
        jQuery('html').animate({ scrollTop: 0 }, '300');
        jQuery('body').animate({ scrollTop: 0 }, '300'); //FOR SAFARI
    });
});

jQuery(function ($) {
    $(document).ready(function () {
        //run function only if exists - TODO - move this in a micro component js
        if (jQuery('.uicore-progress-bar').length) {
            var getMax = function getMax() {
                var value = document.body.scrollHeight - window.innerHeight;
                var postContainer = jQuery('.uicore-post-content article');
                if (postContainer.length) {
                    value = postContainer.height() + postContainer.offset().top - window.innerHeight;
                }

                return value;
            };

            var getValue = function getValue() {
                return jQuery(window).scrollTop();
            };

            var progressBar = jQuery('.uicore-progress-bar'),
                max = getMax(),
                value,
                width;

            var getWidth = function getWidth() {
                // Calculate width in percentage
                value = getValue();
                width = value / max * 100;
                if (width > 100) {
                    width = 100;
                }

                width = width + '%';
                return width;
            };

            var setWidth = function setWidth() {
                progressBar.css({ width: getWidth() });
            };

            jQuery(window).scroll(setWidth);
            $(window).on('resize', function () {
                // Need to reset the Max attr
                max = getMax();
                setWidth();
            });
        }
    });
});

jQuery(function ($) {
    $(document).ready(function () {

        var menuItems = $('.menu-item-has-megamenu.custom-width');

        menuItems.on('mouseenter mouseleave', function (e) {
            setOffset($(this));
        });

        var setOffset = function setOffset(li) {
            var mq = window.matchMedia("(max-width: 1025px)");
            var isMobile = mq.matches;
            var dropdown = li.find('ul.uicore-megamenu');

            //reset
            dropdown.css({
                left: 'auto'
            });

            if (!isMobile) {
                dropdown.css({
                    left: 0
                });
                var dropdownWidth = dropdown.outerWidth(),
                    dropdownOffset = dropdown.offset(),
                    viewportWidth = $(window).width(),
                    extraSpace = 0;

                if (!dropdownWidth || !dropdownOffset) return;

                if (dropdownOffset.left + dropdownWidth >= viewportWidth) {
                    // If right point is not in the viewport
                    var toRight = dropdownOffset.left + dropdownWidth - viewportWidth;

                    dropdown.css({
                        left: -toRight - extraSpace
                    });
                }
            }
        };

        menuItems.each(function () {
            setOffset($(this));
            $(this).addClass('with-offsets');
        });
    });
});

/***/ }),

/***/ 179:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/////////////////////////////////////////////////////////// Mobile Menu
jQuery(function ($) {

    $(document).ready(function () {
        var mq = window.matchMedia("(max-width: " + window.uicore_frontend.mobile_br + "px)");
        var isMobile = mq.matches;
        //Init on load
        initMobileOrHam();
        //Init on Window resize
        window.onresize = initMobileOrHam;

        //Main
        function initMobileOrHam() {
            //Let's start by unbinding all
            $('.uicore-mobile-menu-wrapper .menu-item-has-children > a').unbind('click', mobileSubmenuOpen);
            $('.uicore-mobile-menu-wrapper .uicore-menu-container .sub-menu .back > a').unbind('click', mobileSubmenuClose);
            $('.uicore-mobile-menu-wrapper .menu-item-has-children > a:not(.ui-custom-tap)').unbind('click', mobileSubmenuExpand);
            // $('.uicore-mobile-menu-wrapper .menu-item-has-children > a:not(.ui-custom-tap) span').unbind('click', mobileSubmenuExpand)
            $('.uicore-mobile-menu-wrapper li:not(.menu-item-has-children):not(.back) > a').unbind('click', menuToggle);
            $('.uicore-mobile-menu-wrapper.uicore-ham-classic .menu-item-has-children').unbind('mouseenter', hamClassicSubmenuOpen);
            $('.uicore-mobile-menu-wrapper.uicore-ham-classic .menu-item-has-children').unbind('mouseleave', hamClassicSubmenuClose);
            $('.uicore-mobile-menu-wrapper.uicore-ham-center .menu-item-has-children a, .uicore-mobile-menu-wrapper.uicore-ham-creative .menu-item-has-children a').unbind('click', hamSubmenuToggle);
            $(".uicore-menu-focus .uicore-menu li").unbind('mouseenter', menuFocusIn);
            $(".uicore-menu-focus .uicore-menu li").unbind('mouseleave', menuFocusOut);

            //BIND EVENTS
            if (isMobile) {

                //slide effect
                if (document.body.classList.contains('ui-a-dsmm-slide')) {
                    addBackLink();
                    // Mobile Submenu
                    $('.uicore-mobile-menu-wrapper .menu-item-has-children > a:not(.ui-custom-tap)').bind('click', mobileSubmenuOpen);
                    $('.uicore-mobile-menu-wrapper .uicore-menu-container .sub-menu .back > a').bind('click', mobileSubmenuClose);
                } else {
                    $('.uicore-mobile-menu-wrapper .menu-item-has-children > a:not(.ui-custom-tap)').bind('click', mobileSubmenuExpand);
                    // $('.uicore-mobile-menu-wrapper .menu-item-has-children > a:not(.ui-custom-tap) span').bind('click', mobileSubmenuExpand)
                }

                //Close Menu on link click (mobile)
                $('.uicore-mobile-menu-wrapper li:not(.menu-item-has-children):not(.back) > a').bind('click', menuToggle);

                addSubmenuParentClick();
            } else {
                // Desktop Ham Menu Classic
                $('.uicore-mobile-menu-wrapper.uicore-ham-classic .menu-item-has-children').bind('mouseenter', hamClassicSubmenuOpen);
                $('.uicore-mobile-menu-wrapper.uicore-ham-classic .menu-item-has-children').bind('mouseleave', hamClassicSubmenuClose);
                // Desktop Ham Menu Other
                $('.uicore-mobile-menu-wrapper.uicore-ham-center .menu-item-has-children a, .uicore-mobile-menu-wrapper.uicore-ham-creative .menu-item-has-children a').bind('click', hamSubmenuToggle);

                //MENU FOCUS
                $(".uicore-menu-focus .uicore-menu li").bind('mouseenter', menuFocusIn);
                $(".uicore-menu-focus .uicore-menu li").bind('mouseleave', menuFocusOut);
            }
        }

        $('.uicore-toggle').click(function () {
            menuToggle();
        });

        function addSubmenuParentClick() {
            if (!window.uicoreParent) {
                window.uicoreParent = true;
                $('.uicore-mobile-menu-wrapper .uicore-menu-container .menu-item-has-children').each(function (i, obj) {
                    var url = $(this).find(">a").attr('href');
                    if (url != '#') {
                        var a = $('<a>', {
                            href: url,
                            text: "",
                            class: "ui-custom-tap",
                            style: "min-height:" + $(this).height() + "px;transform:translate3d(0,-" + $(this).height() + "px,0)"
                        });
                        $(this).children('a').after(a);
                    }
                });
            }
        }
        function addBackLink() {

            if (!window.uicoreBackLinks) {
                var hasChildren = jQuery('.menu-item-has-children');

                for (var i = 0; i < hasChildren.length; i++) {
                    var element = hasChildren[i];
                    var a = $('<a>', {
                        href: '#',
                        text: uicore_frontend.back ? uicore_frontend.back : "Back"
                    });
                    var li = $('<li>', {
                        class: 'menu-item back'
                    });
                    li.append(a).prependTo(element.children[1]);
                }
                window.uicoreBackLinks = true;
            }
        }

        function mobileSubmenuExpand(e) {
            e.preventDefault();
            $(this).siblings('.sub-menu').slideToggle();
            $(this).toggleClass('ui-expanded');
        }

        function mobileSubmenuOpen(e) {
            e.preventDefault();
            var leftMove = '';
            $('.uicore-mobile-menu-wrapper .uicore-menu ').addClass('uicore-animating');
            $(this).siblings().addClass('uicore-active');
            var left = $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu')[0].style.left;
            if (left == '0' || left == '0%' || left == '') {
                leftMove = '-100%';
            } else {
                left = left.replace('-', '').replace('%', '');
                leftMove = '-' + left * 2 + '%';
            }
            if (uicore_frontend.rtl === '1') {
                leftMove = leftMove.replace('-', '+');
            }
            $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu').delay(200).animate({ left: leftMove }, 300, function () {
                fadeItem();
                $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu ').removeClass('uicore-animating');
            });
        }

        function mobileSubmenuClose(e) {
            e.preventDefault();
            var leftMove = '';
            $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu').addClass('uicore-animating');
            var left = $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu')[0].style.left;
            if (left == '-100%' || left == '0%' || left == '' || left == '+100%') {
                leftMove = '0%';
            } else {
                left = left.replace('-', '').replace('%', '');
                leftMove = '-' + left / 2 + '%';
            }
            if (uicore_frontend.rtl === '1') {
                leftMove = leftMove.replace('-', '+');
            }
            var _this = this;
            setTimeout(function () {
                $(_this).parent().parent().removeClass('uicore-active');
            }, 400);

            $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu').delay(200).animate({ left: leftMove }, 300, function () {
                $('.uicore-mobile-menu-wrapper .uicore-menu .sub-menu:not(.uicore-active) li').removeClass('uicore-visible');
                fadeItem();
                $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu ').removeClass('uicore-animating');
            });
        }

        function hamClassicSubmenuOpen(e) {
            $(this).find('.sub-menu:first').addClass('uicore-active');
            fadeItem();
        }

        function hamClassicSubmenuClose(e) {
            $(this).children('.sub-menu:last').removeClass('uicore-active');
            $(this).find('li').removeClass('uicore-visible');
        }

        function hamSubmenuToggle(e) {
            $(this).parent().find('.sub-menu:first').toggleClass('uicore-active');
            $(this).parent().find('.sub-menu:first').slideToggle();
            if ($(this).parent().find('.sub-menu:first').hasClass('uicore-active')) {
                fadeItem();
            } else {
                $(this).parent().find('li').removeClass('uicore-visible');
            }
        }

        function menuFocusIn() {
            // Mouse over
            $(this).siblings('li').stop().fadeTo(300, 0.4);
            $(this).parent().siblings('li').stop().fadeTo(300, 0.3);
        }
        function menuFocusOut() {
            // Mouse out
            $(this).siblings('li').stop().fadeTo(300, 1);
            $(this).parent().siblings('li').stop().fadeTo(300, 1);
        }

        function menuToggle(e) {
            // e.preventDefault();

            if (!window.uicoreAnimation) {
                window.uicoreAnimation = true;
                var rev = $('.uicore-ham-reveal');

                //HIDE
                //add deelay only on hide page and show menu
                if (document.body.classList.contains('uicore-overflow-hidden')) {
                    $('.uicore-mobile-menu-overflow').removeClass('uicore-overflow-hidden');
                    $('body').removeClass('uicore-overflow-hidden');
                    $('body').removeClass('uicore-mobile-nav-show');

                    //on fade hide it without anny deelay
                    if (isMobile) {
                        if ($('.uicore-animate-fade').length > 0) {
                            $('.uicore-navigation-wrapper').animate({ opacity: 0 }, 200);
                        } else {
                            setTimeout(function () {
                                $('.uicore-navigation-wrapper').animate({ opacity: 0 }, 50);
                            }, 300);
                        }
                    } else {
                        $('.uicore-navigation-wrapper').animate({ opacity: 0 }, 100);
                    }
                    //reset animation
                    $('.uicore-mobile-menu-wrapper li').removeClass('uicore-visible');
                    $('.uicore-ham-reveal').css('animation-name', 'none');

                    //SHOW
                } else {
                    var time = 0;

                    //reveal
                    if (rev.length) {
                        if (!isMobile) {
                            $('.uicore-ham-reveal').css('animation-name', 'uiCoreAnimationsHamReveal');
                            time = 200;
                        }
                        setTimeout(function () {
                            $('.uicore-navigation-wrapper').css('opacity', 1);
                        }, 0 + time);

                        //fade
                    } else {
                        $('.uicore-navigation-wrapper').animate({ opacity: 1 }, 200);
                    }
                    $('body').addClass('uicore-overflow-hidden');

                    setTimeout(function () {
                        $('body').addClass('uicore-mobile-nav-show');
                        $('.uicore-mobile-menu-overflow').addClass('uicore-overflow-hidden');
                        $('.uicore-mobile-menu-wrapper .uicore-menu-container').css('left', '0%');
                        fadeItem();
                    }, 50 + time);
                }
                $(this).toggleClass('collapsed');
                $('.uicore-mobile-menu-wrapper .uicore-menu-container .uicore-menu').toggleClass('uicore-active');
                setTimeout(function () {
                    window.uicoreAnimation = false;
                }, 300);
            }
        }

        // -- menu item fade in up
        function fadeItem() {
            var menuItem = $('ul.uicore-active > li:not(.uicore-visible):first');
            if (menuItem.length > 0) {
                menuItem.addClass('uicore-visible');
                setTimeout(function () {
                    fadeItem();
                }, 100);
            }
        }
    });
});

/***/ }),

/***/ 180:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// function waitForElm(selector) {
//   return new Promise(resolve => {
//       if (document.querySelector(selector)) {
//           return resolve(document.querySelector(selector));
//       }

//       const observer = new MutationObserver(mutations => {
//           if (document.querySelector(selector)) {
//               resolve(document.querySelector(selector));
//               observer.disconnect();
//           }
//       });

//       observer.observe(document.body, {
//           childList: true,
//           subtree: true
//       });
//   });
// }
// //Better Fluid Gradient
// waitForElm('.ui-fluid-gradient-pre').then((el) => {
//   var elem=jQuery(el).next(".has-ui-fluid-gradient")[0];
//   jQuery(el).prependTo(elem);
//   jQuery(el).removeClass('ui-fluid-gradient-pre');
//   jQuery(el).addClass('ui-fluid-gradient-wrapper');
// });


jQuery(document).ready(function () {

    // Highlighted Text Animations
    var shopItems = document.querySelectorAll(".elementor-widget-highlighted-text.ui-e-a-animate .ui-e--highlighted-text");
    [].forEach.call(shopItems, function (element, index) {
        var lines = element.querySelectorAll('.uicore-svg-wrapper path');
        var extra_delay = element.getAttribute('data-delay');
        extra_delay = extra_delay ? extra_delay : 0;
        [].forEach.call(lines, function (line, index2) {
            new Waypoint({
                element: line,
                handler: function handler(direction) {
                    var delay = index2 * 300 + 400 + parseInt(extra_delay);
                    setTimeout(function () {
                        line.style.animationPlayState = "running";
                    }, delay);
                },
                offset: "90%"
            });
        });
    });

    //Theme Builder animation retrigger
    jQuery(".menu-item-object-uicore-tb").on('mouseenter mouseleave', function () {
        jQuery(this).find(".elementor-element").each(function () {
            elementorFrontend.elementsHandler.runReadyTrigger(jQuery(this));
        });
    });

    // //Fluid Gradient
    // jQuery(".ui-fluid-gradient-pre").each(function(){
    //     try{
    //         var elem=jQuery(this).next(".has-ui-fluid-gradient")[0];
    //         jQuery(this).prependTo(elem);
    //     }catch(e){
    //         console.log(e.message);
    //     }
    //     jQuery(this).removeClass('ui-fluid-gradient-pre');
    //     jQuery(this).addClass('ui-fluid-gradient-wrapper');
    // });
});

/***/ })

},[175]);