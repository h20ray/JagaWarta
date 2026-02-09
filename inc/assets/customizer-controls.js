(function ($) {
    'use strict';

    // Wait for the Customizer to be ready
    wp.customize.bind('ready', function () {

        // Target our specific control
        wp.customize.control('jagawarta_seed_primary', function (control) {

            // The content is embedded when the control is ready/embedded
            control.deferred.embedded.done(function () {
                initColorControl(control.container);
            });
        });
    });

    function initColorControl($container) {
        var $input = $container.find('.jagawarta-custom-color-input');

        // Init wpColorPicker
        $input.wpColorPicker({
            change: function (event, ui) {
                var color = ui.color.toString();

                // Update preset selection UI
                $container.find('.jagawarta-color-preset').removeClass('selected');
                $container.find('.jagawarta-color-preset').each(function () {
                    if ($(this).data('color').toLowerCase() === color.toLowerCase()) {
                        $(this).addClass('selected');
                    }
                });

                // Trigger change to update Setting
                $input.val(color).trigger('change');
            },
            clear: function () {
                $container.find('.jagawarta-color-preset').removeClass('selected');
                $input.val('').trigger('change');
            }
        });

        // Handle preset click
        $container.on('click', '.jagawarta-color-preset', function (e) {
            e.preventDefault();

            var $button = $(this);
            var color = $button.data('color');

            // Update UI selection
            $container.find('.jagawarta-color-preset').removeClass('selected');
            $button.addClass('selected');

            // Update wpColorPicker visual
            if ($input.iris) {
                $input.iris('color', color);
            }

            // Update input and trigger change
            $input.val(color).change();
        });
    }

})(jQuery);
