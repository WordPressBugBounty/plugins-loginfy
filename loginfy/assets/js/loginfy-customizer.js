/**
 * String.prototype.includes polyfill.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/includes
 */
if (!String.prototype.includes) {
    String.prototype.includes = function (search, start) {
        'use strict';

        if (search instanceof RegExp) {
            throw TypeError('first argument must not be a RegExp');
        }
        if (start === undefined) {
            start = 0;
        }
        return this.indexOf(search, start) !== -1;
    };
}

/**
 * Scripts within customizer control panel.
 *
 * Used global objects:
 * - jQuery
 * - wp
 * - LoginfyCustomizer
 */
(function ($) {
    'use strict';

    var events = {};

    wp.customize.bind(
        'ready',
        function () {
            wpLoginfyListen();
            $('body').addClass('loginfy');
            if (!LoginfyCustomizer.isProActive) {
                loginfyUpgradeProLink();
            }
        }
    );

    /**
     * Fix Firefox focus issue
     * Auto Focus on Loginfy Panel
     */
    $(window).on('load', function () {
      if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            wp.customize.panel("loginfy_panel").focus();
        }
    });

    function wpLoginfyListen() {
        events.switchLoginPreview();
        events.switchFormPreview();
        events.focusSection();
        if (!LoginfyCustomizer.isProActive) {
            events.templateFieldsChange();
        }
    }

    events.focusSection = function () {
        wp.customize.previewer.bind(
            'loginfy-focus-section',
            function (sectionName) {
                var section = wp.customize.section(sectionName);

                if (undefined !== section) {
                    section.focus();
                }
            }
        );
    }

    events.switchFormPreview = function () {

        wp.customize.section(
            'loginfy_register-form',
            function (section) {
                section.expanded.bind(
                    function (isExpanding) {
                        // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
                        if (isExpanding) {
                            wp.customize.previewer.send('change-form', 'register');
                        } else {
                            wp.customize.previewer.send('change-form', 'login');
                        }
                    }
                );
            }
        );

        wp.customize.section(
            'loginfy_lostpassword-form',
            function (section) {
                section.expanded.bind(
                    function (isExpanding) {
                        // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
                        if (isExpanding) {
                            wp.customize.previewer.send('change-form', 'lostpassword');
                        } else {
                            wp.customize.previewer.send('change-form', 'login');
                        }
                    }
                );
            }
        );
    }
    /**
     * Change the page when the "Loginfy Login Customizer" panel is expanded (or collapsed).
     */
    events.switchLoginPreview = function () {
        wp.customize.panel(
            'loginfy_panel',
            function (section) {
                section.expanded.bind(
                    function (isExpanding) {
                        var loginURL = LoginfyCustomizer.siteurl + '?loginfy-login-customizer=true';
                        // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
                        if (isExpanding) {
                            wp.customize.previewer.previewUrl.set(loginURL);
                        } else {
                            wp.customize.previewer.previewUrl.set(LoginfyCustomizer.siteurl);
                        }
                    }
                );
            }
        );
    }

    events.templateFieldsChange = function () {
        wp.customize.section(
            'jlt_loginfy_customizer_template_section',
            function (section) {
                section.expanded.bind(
                    function (isExpanded) {
                        if (isExpanded) {
                            var figur_input = $(
                                "#customize-control-_loginfy_-templates .loginfy--image-group .loginfy--image figure input"
                            );

                            for (let index = 0; index < figur_input.length; index++) {
                                var element = figur_input[index];
                                var val = element.value;
                                // console.log('element', element.value);
                                var hasDivAlready = element.parentNode.querySelector(
                                    ".loginfy-templates-pro"
                                );

                                if (
                                    val === "template-01" ||
                                    val === "template-02" ||
                                    val === "template-03" ||
                                    val === "template-04"
                                ) {
                                    continue;
                                }

                                if (hasDivAlready !== null) {
                                    continue;
                                }

                                var _div = document.createElement("div");
                                var _a = document.createElement("a");
                                _div.classList = "loginfy-templates-pro";
                                _a.setAttribute(
                                    "href",
                                    "https://wpadminify.com/loginfy/pricing/?utm_source=plugin&utm_medium=login_customizer_link&utm_campaign=loginfy"
                                );
                                _a.setAttribute("target", "_blank");
                                _a.innerHTML = "Upgrade to Pro";

                                _div.appendChild(_a);
                                element.parentNode.appendChild(_div);
                            }
                            // figur_input.forEach(element => {
                            // console.log('figur_input_val', element.val());
                            // });

                            // var value = wp.customize('_loginfy_[templates]').get();
                            // console.log('value', value);
                            // if (value !== 'template-01' ) {
                            // $('<div class="loginfy-templates-pro"><a href="https://wpadminify.com/loginfy/pricing/?utm_source=plugin&utm_medium=login_customizer_link&utm_campaign=loginfy" target="_blank">Upgrade to Pro</a></div>').appendTo('#customize-control-_loginfy_-templates .loginfy--image-group .loginfy--image figure');
                            // }
                            // $("#customize-control-_loginfy_-templates .loginfy--image-group .loginfy--image").insertAt(0, "<li>as fasd fads fsd</li>");
                            // $('<li>fgdf</li>').appendToWithIndex($("#customize-control-_loginfy_-templates .loginfy--image-group .loginfy--image > figure"),0)
                        }
                    }
                )
            }
        );
    }

    $.fn.appendToWithIndex = function (to, index) {
        // console.log('firesd');
        // var lastIndex = this.children().size();
        // if (index < 0) {
        // index = Math.max(0, lastIndex + 1 + index);
        // }
        // this.append(element);
        // if (index < lastIndex) {
        // this.children().eq(index).before(this.children().last());
        // }
        // return this;
        if (!to instanceof jQuery) {
            to = $(to);
        };
        if (index === 0) {
            $(this).prependTo(to)
        } else {
            $(this).insertAfter(to.children().eq(index - 1));
        }
    }

    function loginfyUpgradeProLink() {

        var proLink = '\
		<li class="accordion-section control-section loginfy-pro-control-section">\
			<a \
			href="https://wpadminify.com/loginfy/?utm_source=plugin&utm_medium=customizer_link&utm_campaign=loginfy" \
			style="display: block; font-weight: 600; color: #fff !important; font-size: 16px; text-decoration: none; border-left-color: #0347FF; background: #0347FF;"\
				class="accordion-section-title" target="_blank" tabindex="0">\
				Upgrade to Pro ›\
			</a>\
		</li>\
		';

        $('<li class="accordion-section control-section control-section-default control-subsection">\
			<h4 class="accordion-section-title">\
				<a href="https://wordpress.org/support/plugin/loginfy/reviews/#new-post" target="_blank">\
					Like our plugin? Leave a review here!\
				</a>\
			</h4>\
		</li>\
		<li style="padding: 10px; text-align: center;">\
			Made with ❤ by \
			<a href="https://wpadminify.com/loginfy/?utm_source=loginfy-free&utm_medium=customizer" target="_blank">\
				Loginfy\
			</a>\
		</li>' ).appendTo('#sub-accordion-panel-loginfy_panel');

        $(proLink).insertBefore('#accordion-section-jlt_loginfy_customizer_template_section');
    }

})(jQuery, wp.customize);
