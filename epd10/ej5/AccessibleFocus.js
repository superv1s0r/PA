(function ($) {
    $.fn.AccessibleFocus = function () {
        const focusClass = "focus-visible";
        const keyboardClass = "teclado-activo";

        let isKeyboardNavigation = false;

        $(window).on("keydown", function (e) {
            isKeyboardNavigation = true;
            $("body").addClass(keyboardClass);
        });

        $(window).on("mousedown", function (e) {
            isKeyboardNavigation = false;
            $("body").removeClass(keyboardClass);
        });

        this.each(function () {
            $(this)
                .on("focus", function () {
                    $(this).addClass(focusClass);
                })
                .on("blur", function () {
                    $(this).removeClass(focusClass);
                });
        });

        return this;
    };
})(jQuery);

$(document).ready(function () {
    // Escenario 1
    // $("body").AccessibleFocus();

    // Escenario 2
    $(".accessible-item").AccessibleFocus();
});
