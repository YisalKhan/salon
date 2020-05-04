Number.prototype.formatMoney = function(c, d, t) {
    var n = this,
        c = isNaN((c = Math.abs(c))) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt((n = Math.abs(+n || 0).toFixed(c))) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return (
        s +
        (j ? i.substr(0, j) + t : "") +
        i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
        (c
            ? d +
              Math.abs(n - i)
                  .toFixed(c)
                  .slice(2)
            : "")
    );
};

jQuery(function($) {
    sln_init($);
    if (salon.has_stockholm_transition == "yes") {
        $("body").on(
            "click",
            'a[target!="_blank"]:not(.no_ajax):not(.no_link)',
            function() {
                setTimeout(function() {
                    sln_init(jQuery);
                }, 2000);
            }
        );
    }
});

function sln_init($) {
    if ($("#salon-step-services").length || $("#salon-step-secondary").length) {
        sln_serviceTotal($);
    }
    if ($("#salon-step-date").length) {
        sln_stepDate($);
    } else {
        if ($("#salon-step-details").length) {
            $("a.tec-link").click(function(e) {
                e.preventDefault();
                var href = $(this).attr("href");
                var locHref = window.location.href;
                var hrefGlue = href.indexOf("?") == -1 ? "?" : "&";
                var locHrefGlue = locHref.indexOf("?") == -1 ? "?" : "&";
                window.location.href =
                    href +
                    hrefGlue +
                    "redirect_to=" +
                    encodeURI(locHref + locHrefGlue + "sln_step_page=details");
            });
        }
        if ($('[data-salon-click="fb_login"]').length) {
            if (window.fbAsyncInit === undefined) {
                if (salon.fb_app_id !== undefined) {
                    facebookInit();
                } else {
                    jQuery("[data-salon-click=fb_login]").remove();
                }
            } else {
                jQuery("[data-salon-click=fb_login]")
                    .unbind("click")
                    .click(function() {
                        FB.login(
                            function() {
                                facebookLogin();
                            },
                            { scope: "email" }
                        );

                        return false;
                    });
            }
        }
        $('[data-salon-toggle="next"]').click(function(e) {
            var form = $(this).closest("form");
            $(
                "#sln-salon input.sln-invalid,#sln-salon textarea.sln-invalid,#sln-salon select.sln-invalid"
            ).removeClass("sln-invalid");
            if (form[0].checkValidity()) {
                sln_loadStep(
                    $,
                    form.serialize() + "&" + $(this).data("salon-data")
                );
            } else {
                $(
                    "#sln-salon input:invalid,#sln-salon textarea:invalid,#sln-salon select:invalid"
                )
                    .addClass("sln-invalid")
                    .attr("placeholder", salon.checkout_field_placeholder);
            }
            return false;
        });
    }
    $('[data-salon-toggle="direct"]').click(function(e) {
        e.preventDefault();
        sln_loadStep($, $(this).data("salon-data"));
        return false;
    });

    // CHECKBOXES
    $("#sln-salon input:checkbox").each(function() {
        $(this).change(function() {
            if ($(this).is(":checked")) {
                $(this)
                    .parent()
                    .addClass("is-checked");
            } else {
                $(this)
                    .parent()
                    .removeClass("is-checked");
            }
        });
    });
    // RADIOBOXES
    $("#sln-salon input:radio").each(function() {
        $(this).click(function() {
            //var selector = '.is-checked input[name="' + jQuery(this).attr('name').replace(/([\[\]])/g, '\\\\$1') + '"]';
            var name = jQuery(this).attr("name");
            jQuery(".is-checked").each(function() {
                if (
                    jQuery(this)
                        .find("input")
                        .attr("name") == name
                ) {
                    $(this).removeClass("is-checked");
                }
            });
            $(this)
                .parent()
                .toggleClass("is-checked");
        });
    });

    $(".sln-edit-text").change(function() {
        var data =
            "key=" +
            $(this).attr("id") +
            "&value=" +
            $(this).val() +
            "&action=salon&method=SetCustomText&security=" +
            salon.ajax_nonce;
        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: "POST",
            dataType: "json",
            success: function(data) {},
            error: function(data) {
                alert("error");
                console.log(data);
            },
        });
        return false;
    });

    $("div.editable").on("click", function() {
        var self = $(this);
        self.addClass("focus");
        var text = self.find(".text");
        var input = self.find("input");
        input.val(text.text().trim()).trigger("focus");
    });

    $("div.editable .input input").on("blur", function() {
        var self = $(this);
        var div = self.closest(".editable");
        div.removeClass("focus");
        var text = div.find(".text");
        text.html(self.val());
    });

    $("#sln_no_user_account")
        .on("change", function() {
            if ($(this).is(":checked")) {
                $("#sln_password")
                    .attr("disabled", "disabled")
                    .parent()
                    .css("display", "none");
                $("#sln_password_confirm")
                    .attr("disabled", "disabled")
                    .parent()
                    .css("display", "none");
                $(".sln-customer-fields").hide();
            } else {
                $("#sln_password")
                    .attr("disabled", false)
                    .parent()
                    .css("display", "block");
                $("#sln_password_confirm")
                    .attr("disabled", false)
                    .parent()
                    .css("display", "block");
                $(".sln-customer-fields").show();
            }
        })
        .change();

    sln_createRatings(true, "star");

    if (typeof sln_createSelect2Full !== "undefined") {
        sln_createSelect2Full($);
    }
    salonBookingCalendarInit();

    $(".sln-help-button").on("click", function() {
        window.Beacon("toggle");
        $(this).toggleClass("active");
        return false;
    });
}
function sln_loadStep($, data) {
    var loadingMessage =
        '<div class="sln-loader-wrapper"><div class="sln-loader">Loading...</div></div>';
    data += "&action=salon&method=salonStep&security=" + salon.ajax_nonce;
    $("#sln-notifications")
        .html(loadingMessage)
        .addClass("sln-notifications--active");
    $.ajax({
        url: salon.ajax_url,
        data: data,
        method: "POST",
        dataType: "json",
        success: function(data) {
            if (typeof data.redirect != "undefined") {
                window.location.href = data.redirect;
            } else {
                $("#sln-salon").replaceWith(data.content);
                salon.ajax_nonce = data.nonce;
                $("html, body").animate(
                    {
                        scrollTop: $("#sln-salon").offset().top,
                    },
                    700
                );
                sln_init($);
            }
        },
        error: function(data) {
            alert("error");
            console.log(data);
        },
    });
}

function sln_stepDate($) {
    var isValid;
    var items = $("#salon-step-date").data("intervals");
    var updateFunc = function() {
        $("[data-ymd]").addClass("disabled");
        $.each(items.dates, function(key, value) {
            $('.day[data-ymd="' + value + '"]').removeClass("disabled");
        });
        $(".day[data-ymd]").removeClass("full");
        $.each(items.fullDays, function(key, value) {
            console.log(value);
            $('.day[data-ymd="' + value + '"]').addClass("disabled full");
        });

        $.each(items.times, function(key, value) {
            $('.minute[data-ymd="' + value + '"]').removeClass("disabled");
        });
    };
    var debounce = function(fn, delay) {
        var inDebounce;
        return function() {
            var context = this;
            var args = arguments;
            clearTimeout(inDebounce);
            inDebounce = setTimeout(function() {
                return fn.apply(context, args);
            }, delay);
        };
    };
    var func = debounce(updateFunc, 200);
    func();
    $("body").on("sln_date", func);

    function validate(obj, autosubmit) {
        var form = $(obj).closest("form");
        //var validatingMessage = '<img src="' + salon.loading + '" alt="loading .." width="16" height="16" /> '+salon.txt_validating;
        var validatingMessage =
            '<div class="sln-alert sln-alert--wait">' +
            salon.txt_validating +
            "</div>";
        var data = form.serialize();
        data += "&action=salon&method=checkDate&security=" + salon.ajax_nonce;
        $("#sln-notifications")
            .addClass("sln-notifications--active")
            .html(validatingMessage);
        $("#sln-salon").addClass("sln-salon--loading");
        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: "POST",
            dataType: "json",
            success: function(data) {
                $(".sln-alert").remove();
                if (!data.success) {
                    var alertBox = $(
                        '<div class="sln-alert sln-alert--problem"></div>'
                    );
                    $(data.errors).each(function() {
                        alertBox.append("<p>").html(this);
                    });
                    $("#sln-notifications")
                        .html("")
                        .append(alertBox);
                    // we bind a new interval so we needn't to disable
                    //                    $('#sln-step-submit').attr('disabled', true);
                    isValid = false;
                } else {
                    $("#sln-step-submit").attr("disabled", false);
                    $("#sln-notifications")
                        .html("")
                        .removeClass("sln-notifications--active");
                    $("#sln-salon").removeClass("sln-salon--loading");
                    isValid = true;
                    if (autosubmit) submit();
                }
                bindIntervals(data.intervals);
            },
        });
    }

    function bindIntervals(intervals) {
        //        putOptions($('#sln_date_day'), intervals.days, intervals.suggestedDay);
        //        putOptions($('#sln_date_month'), intervals.months, intervals.suggestedMonth);
        //        putOptions($('#sln_date_year'), intervals.years, intervals.suggestedYear);
        items = intervals;
        $("#salon-step-date").data("intervals", intervals);
        func();
        putOptions($("#sln_date"), intervals.suggestedDate);
        putOptions($("#sln_time"), intervals.suggestedTime);
    }

    function putOptions(selectElem, value) {
        selectElem.val(value);
    }

    function submit() {
        if ($("#sln-step-submit").data("salon-toggle").length)
            sln_loadStep(
                $,
                $("#salon-step-date").serialize() +
                    "&" +
                    $("#sln-step-submit").data("salon-data")
            );
        else $("#sln-step-submit").click();
    }

    $("#sln_date, #sln_time").change(function() {
        validate(this, false);
    });
    $("#salon-step-date").submit(function() {
        if (!isValid) {
            validate(this, true);
        } else {
            submit();
        }
        return false;
    });

    initDatepickers($);
    initTimepickers($);
}

function sln_serviceTotal($) {
    var $checkboxes = $('.sln-service-list input[type="checkbox"]');
    var $totalbox = $("#services-total");
    function evalTot() {
        var tot = 0;
        $checkboxes.each(function() {
            if ($(this).is(":checked")) {
                tot += $(this).data("price");
            }
        });
        var decimals = parseFloat(tot) === parseFloat(parseInt(tot)) ? 0 : 2;
        $totalbox.text(
            $totalbox.data("symbol-left") +
                tot.formatMoney(
                    decimals,
                    $totalbox.data("symbol-decimal"),
                    $totalbox.data("symbol-thousand")
                ) +
                $totalbox.data("symbol-right")
        );
    }

    function checkServices($) {
        var form, data;
        if ($("#salon-step-services").size()) {
            form = $("#salon-step-services");
            data =
                form.serialize() +
                "&action=salon&method=CheckServices&part=primaryServices&security=" +
                salon.ajax_nonce;
        } else if ($("#salon-step-secondary").size()) {
            form = $("#salon-step-secondary");
            data =
                form.serialize() +
                "&action=salon&method=CheckServices&part=secondaryServices&security=" +
                salon.ajax_nonce;
        } else {
            return;
        }

        $.ajax({
            url: salon.ajax_url,
            data: data,
            method: "POST",
            dataType: "json",
            success: function(data) {
                if (!data.success) {
                    var alertBox = $(
                        '<div class="sln-alert sln-alert--problem sln-service-error"></div>'
                    );
                    $.each(data.errors, function() {
                        alertBox.append("<p>").html(this);
                    });
                } else {
                    $(".sln-alert.sln-service-error").remove();
                    if (data.services)
                        $.each(data.services, function(index, value) {
                            var checkbox = $("#sln_services_" + index);
                            var errorsArea = $("#sln_services_" + index)
                                .closest(".sln-service")
                                .find(".errors-area");
                            if (value.status == -1) {
                                var alertBox = $(
                                    '<div class="sln-alert sln-alert-medium sln-alert--problem sln-service-error"><p>' +
                                        value.error +
                                        "</p></div>"
                                );
                                checkbox
                                    .attr("checked", false)
                                    .attr("disabled", "disabled")
                                    .change();
                                errorsArea.html(alertBox);
                            } else if (value.status == 0) {
                                checkbox
                                    .attr("checked", false)
                                    .attr("disabled", false)
                                    .change();
                            } else if (value.status == 1) {
                                checkbox.attr("checked", true).change();
                            }
                        });
                    evalTot();
                }
            },
        });
    }

    $checkboxes.click(function() {
        checkServices($);
    });
    checkServices($);
    evalTot();
}

function initDatepickers($) {
    $(".sln_datepicker input").each(function() {
        $(this).attr("readonly", "readonly");
        if ($(this).hasClass("started")) {
            return;
        } else {
            $(this)
                .addClass("started")
                .datetimepicker({
                    format: $(this).data("format"),
                    weekStart: $(this).data("weekstart"),
                    minuteStep: 60,
                    autoclose: true,
                    minView: 2,
                    maxView: 4,
                    todayBtn: true,
                    language: $(this).data("locale"),
                })
                .on("show", function() {
                    $("body").trigger("sln_date");
                })
                .on("place", function() {
                    $("body").trigger("sln_date");
                })
                .on("changeMonth", function() {
                    $("body").trigger("sln_date");
                })
                .on("changeYear", function() {
                    $("body").trigger("sln_date");
                })
                .on("hide", function() {
                    if ($(this).is(":focus"));
                    $(this).blur();
                });
        }
    });
    var elementExists = document.getElementById("sln-salon");
    if (elementExists) {
        setTimeout(function() {
            $(".datetimepicker.sln-datetimepicker").wrap(
                "<div class='sln-salon-bs-wrap'></div>"
            );
        }, 50);
    }
}

function initTimepickers($) {
    $(".sln_timepicker input").each(function() {
        $(this).attr("readonly", "readonly");
        if ($(this).hasClass("started")) {
            return;
        } else {
            var picker = $(this)
                .addClass("started")
                .datetimepicker({
                    format: $(this).data("format"),
                    minuteStep: $(this).data("interval"),
                    autoclose: true,
                    minView: 0,
                    maxView: 0,
                    startView: 0,
                    showMeridian: $(this).data("meridian") ? true : false,
                })
                .on("show", function() {
                    $("body").trigger("sln_date");
                })
                .on("place", function() {
                    sln_renderAvailableTimeslots($);

                    $("body").trigger("sln_date");
                })
                .on("hide", function() {
                    if ($(this).is(":focus"));
                    $(this).blur();
                })

                .data("datetimepicker").picker;
            picker.addClass("timepicker");
        }
    });
}
/* ========================================================================
 * Bootstrap: transition.js v3.2.0
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+(function($) {
    "use strict";

    // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
    // ============================================================

    function transitionEnd() {
        var el = document.createElement("bootstrap");

        var transEndEventNames = {
            WebkitTransition: "webkitTransitionEnd",
            MozTransition: "transitionend",
            OTransition: "oTransitionEnd otransitionend",
            transition: "transitionend",
        };

        for (var name in transEndEventNames) {
            if (el.style[name] !== undefined) {
                return { end: transEndEventNames[name] };
            }
        }

        return false; // explicit for ie8 (  ._.)
    }

    // http://blog.alexmaccaw.com/css-transitions
    $.fn.emulateTransitionEnd = function(duration) {
        var called = false;
        var $el = this;
        $(this).one("bsTransitionEnd", function() {
            called = true;
        });
        var callback = function() {
            if (!called) $($el).trigger($.support.transition.end);
        };
        setTimeout(callback, duration);
        return this;
    };

    $(function() {
        $.support.transition = transitionEnd();

        if (!$.support.transition) return;

        $.event.special.bsTransitionEnd = {
            bindType: $.support.transition.end,
            delegateType: $.support.transition.end,
            handle: function(e) {
                if ($(e.target).is(this))
                    return e.handleObj.handler.apply(this, arguments);
            },
        };
    });
})(jQuery);

/* ========================================================================
 * Bootstrap: collapse.js v3.2.0
 * http://getbootstrap.com/javascript/#collapse
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+(function($) {
    "use strict";

    // COLLAPSE PUBLIC CLASS DEFINITION
    // ================================

    var Collapse = function(element, options) {
        this.$element = $(element);
        this.options = $.extend({}, Collapse.DEFAULTS, options);
        this.transitioning = null;

        if (this.options.parent) this.$parent = $(this.options.parent);
        if (this.options.toggle) this.toggle();
    };

    Collapse.VERSION = "3.2.0";

    Collapse.DEFAULTS = {
        toggle: true,
    };

    Collapse.prototype.dimension = function() {
        var hasWidth = this.$element.hasClass("width");
        return hasWidth ? "width" : "height";
    };

    Collapse.prototype.show = function() {
        if (this.transitioning || this.$element.hasClass("in")) return;

        var startEvent = $.Event("show.bs.collapse");
        this.$element.trigger(startEvent);
        if (startEvent.isDefaultPrevented()) return;

        var actives = this.$parent && this.$parent.find("> .panel > .in");

        if (actives && actives.length) {
            var hasData = actives.data("bs.collapse");
            if (hasData && hasData.transitioning) return;
            Plugin.call(actives, "hide");
            hasData || actives.data("bs.collapse", null);
        }

        var dimension = this.dimension();

        this.$element
            .removeClass("collapse")
            .addClass("collapsing")
            [dimension](0);

        this.transitioning = 1;

        var complete = function() {
            this.$element
                .removeClass("collapsing")
                .addClass("collapse in")
                [dimension]("");
            this.transitioning = 0;
            this.$element.trigger("shown.bs.collapse");
        };

        if (!$.support.transition) return complete.call(this);

        var scrollSize = $.camelCase(["scroll", dimension].join("-"));

        this.$element
            .one("bsTransitionEnd", $.proxy(complete, this))
            .emulateTransitionEnd(350)
            [dimension](this.$element[0][scrollSize]);
    };

    Collapse.prototype.hide = function() {
        if (this.transitioning || !this.$element.hasClass("in")) return;

        var startEvent = $.Event("hide.bs.collapse");
        this.$element.trigger(startEvent);
        if (startEvent.isDefaultPrevented()) return;

        var dimension = this.dimension();

        this.$element[dimension](this.$element[dimension]())[0].offsetHeight;

        this.$element
            .addClass("collapsing")
            .removeClass("collapse")
            .removeClass("in");

        this.transitioning = 1;

        var complete = function() {
            this.transitioning = 0;
            this.$element
                .trigger("hidden.bs.collapse")
                .removeClass("collapsing")
                .addClass("collapse");
        };

        if (!$.support.transition) return complete.call(this);

        this.$element[dimension](0)
            .one("bsTransitionEnd", $.proxy(complete, this))
            .emulateTransitionEnd(350);
    };

    Collapse.prototype.toggle = function() {
        this[this.$element.hasClass("in") ? "hide" : "show"]();
    };

    // COLLAPSE PLUGIN DEFINITION
    // ==========================

    function Plugin(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data("bs.collapse");
            var options = $.extend(
                {},
                Collapse.DEFAULTS,
                $this.data(),
                typeof option == "object" && option
            );

            if (!data && options.toggle && option == "show") option = !option;
            if (!data)
                $this.data("bs.collapse", (data = new Collapse(this, options)));
            if (typeof option == "string") data[option]();
        });
    }

    var old = $.fn.collapse;

    $.fn.collapse = Plugin;
    $.fn.collapse.Constructor = Collapse;

    // COLLAPSE NO CONFLICT
    // ====================

    $.fn.collapse.noConflict = function() {
        $.fn.collapse = old;
        return this;
    };

    // COLLAPSE DATA-API
    // =================

    $(document).on(
        "click.bs.collapse.data-api",
        '[data-toggle="collapse"]',
        function(e) {
            var href;
            var $this = $(this);
            var target =
                $this.attr("data-target") ||
                e.preventDefault() ||
                ((href = $this.attr("href")) &&
                    href.replace(/.*(?=#[^\s]+$)/, "")); // strip for ie7
            var $target = $(target);
            var data = $target.data("bs.collapse");
            var option = data ? "toggle" : $this.data();
            var parent = $this.attr("data-parent");
            var $parent = parent && $(parent);

            if (!data || !data.transitioning) {
                if ($parent)
                    $parent
                        .find(
                            '[data-toggle="collapse"][data-parent="' +
                                parent +
                                '"]'
                        )
                        .not($this)
                        .addClass("collapsed");
                $this[$target.hasClass("in") ? "addClass" : "removeClass"](
                    "collapsed"
                );
            }

            Plugin.call($target, option);
        }
    );

    function reattachEvents() {
        $(".sln-datetimepicker-close")
            .unbind("click")
            .click(function() {
                $(".datetimepicker.sln-datetimepicker").hide();
            });
    }
    setTimeout(function() {
        $(".datetimepicker.sln-datetimepicker div").append(
            '<em class="sln-datetimepicker-close">' + salon.txt_close + "</em>"
        );
        reattachEvents();
    }, 500);
})(jQuery);

function facebookInit() {
    window.fbAsyncInit = function() {
        FB.init({
            appId: salon.fb_app_id,
            cookie: true,
            xfbml: true,
            version: "v2.8",
        });
        FB.AppEvents.logPageView();

        jQuery("[data-salon-click=fb_login]")
            .unbind("click")
            .click(function() {
                FB.login(
                    function() {
                        facebookLogin();
                    },
                    { scope: "email" }
                );

                return false;
            });
    };

    (function(d, s, id) {
        var js,
            fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;

        var locale =
            typeof salon.fb_locale !== "undefined" ? salon.fb_locale : "en_US";

        js.src = "//connect.facebook.net/" + locale + "/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    })(document, "script", "facebook-jssdk");
}

function facebookLogin() {
    var auth = FB.getAuthResponse();

    if (!auth) {
        return;
    }

    var $form = jQuery("#salon-step-details");

    if ($form.length) {
        $form.append(
            '<input type="hidden" name="fb_access_token" value="' +
                auth.accessToken +
                '" />'
        );
        $form.find("[name=submit_details]").click();
        return;
    }

    jQuery.ajax({
        url: salon.ajax_url,
        data: {
            accessToken: auth.accessToken,
            action: "salon",
            method: "FacebookLogin",
            security: salon.ajax_nonce,
        },
        method: "POST",
        dataType: "json",
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert("error");
                console.log(response);
            }
        },
        error: function(data) {
            alert("error");
            console.log(data);
        },
    });
}

function salonBookingCalendarInit() {
    if (jQuery(".sln-salon-booking-calendar-wrap").size() === 0) {
        return;
    }
    salonBookingCalendarInitTooltip();

    setInterval(function() {
        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: "salon",
                method: "salonCalendar",
                security: salon.ajax_nonce,
                attrs: JSON.parse(
                    jQuery(".sln-salon-booking-calendar").attr("data-attrs")
                ),
            },

            method: "POST",
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    jQuery(".sln-salon-booking-calendar-wrap").html(
                        data.content
                    );
                    salonBookingCalendarInitTooltip();
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.errors) {
                    // TODO: display errors
                }
            },
            error: function(data) {
                alert("error");
                console.log(data);
            },
        });
    }, 10 * 1000);
}

function salonBookingCalendarInitTooltip() {
    jQuery('[data-toggle="tooltip"]').tooltip();
}

function sln_createRatings(readOnly, view) {
    jQuery("[name=sln-rating]").each(function() {
        if (jQuery(this).val()) {
            sln_createRaty(jQuery(this), readOnly, view);
        }
    });
}

function sln_createRaty($rating, readOnly, view) {
    readOnly = readOnly == undefined ? false : readOnly;
    view = view == undefined ? "star" : view;

    var starOnClass = "glyphicon";
    var starOffClass = "glyphicon";

    if (view === "circle") {
        starOnClass += " sln-rate-service-on";
        starOffClass += " sln-rate-service-off";
    } else {
        starOnClass += " glyphicon-star";
        starOffClass += " glyphicon-star-empty";
    }

    var $ratyElem = $rating.parent().find(".rating");
    $ratyElem.raty({
        score: jQuery($rating).val(),
        space: false,
        path: salon.images_folder,
        readOnly: readOnly,
        starType: "i",
        starOff: starOffClass,
        starOn: starOnClass,
    });
    $ratyElem.css("display", "block");
}

function sln_renderAvailableTimeslots($) {
    $(".datetimepicker-minutes table tr td").html("");

    var datetimepicker = $(".sln_timepicker input").data("datetimepicker");

    var html = [];

    var date = datetimepicker.getDate();

    var items = $("#salon-step-date").data("intervals");

    $.each(items.workTimes, function(value) {
        var hours = parseInt(value, 10) || 0;
        var minutes = parseInt(value.substr(value.indexOf(":") + 1), 10) || 0;

        date.setUTCHours(hours);
        date.setUTCMinutes(minutes);

        html.push(
            '<span data-ymd="' +
                value +
                '" class="minute disabled">' +
                $.fn.datetimepicker.DPGlobal.formatDate(
                    date,
                    datetimepicker.format,
                    datetimepicker.language,
                    datetimepicker.formatType
                ) +
                "</span>"
        );
    });

    $(".datetimepicker-minutes table tr td").html(html.join(""));

    $(".datetimepicker-minutes table tr td .minute").on("click", function() {
        var datetimepicker = $(".sln_timepicker input").data("datetimepicker");

        var time = $(this).attr("data-ymd");

        var hours = parseInt(time, 10) || 0;
        datetimepicker.viewDate.setUTCHours(hours);

        var minutes = parseInt(time.substr(time.indexOf(":") + 1), 10) || 0;
        datetimepicker.viewDate.setUTCMinutes(minutes);
    });
}
jQuery(function($) {
    $(document).ready(function() {
        if ($(".sln-customcolors").length) {
            $("body").addClass("sln-salon-page-customcolors");
        }
    });
});
// DIVI THEME ACCORDION FIX SNIPPET
jQuery(function($) {
    if ($("body.theme-Divi").length) {
        $(".sln-panel-heading").unbind("click");
    }
});
// DIVI THEME ACCORDION FIX SNIPPET // END
