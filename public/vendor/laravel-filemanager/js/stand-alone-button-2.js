(function ($) {
    $.fn.filemanager = function (type, options) {
        type = type || "file";

        this.on("click", function (e) {
            console.log(this);
            var route_prefix =
                options && options.prefix
                    ? options.prefix
                    : "/laravel-filemanager";
            var target_mode = $("#" + $(this).data("mode"));
            var target_input = $("#" + $(this).data("input"));
            var target_preview = $("#" + $(this).data("preview"));
            var target_text = $("#" + $(this).data("text"));
            window.open(
                route_prefix + "?type=" + type,
                "FileManager",
                "width=900,height=600"
            );
            window.SetUrl = function (url) {
                var img = new Image();
                img.onload = function () {
                    $(target_input).val(url).trigger("change");
                    $(target_text).html(url).trigger("change");

                    target_preview.html("");

                    $(target_preview).attr("src", url);

                    target_preview.trigger("change");

                    $("#image_div").show();
                };
                img.src = url;
            };
            return false;
        });
    };
})(jQuery);
