function showModal(id, bgclose) {
    var modal = UIkit.modal(id, { 'bgclose': bgclose||false, 'keyboard': false, 'center': true});
    modal.show();
    setTimeout(function () {
        $(window).resize();
    }, 200);
}

 $(function () {
    //Select input height fix on MAC
    $("select.md-input").each(function () {
        if ($(this).height() < 30) {
            $(this).css("margin-top", "20px");
        }
    });
    
    //Show form input value in specified element on each change
    $("[data-show-change]").on("input change", function() {
        $("#"+$(this).attr("data-show-change")).text($(this).val());
    });
    
    /**
    * Format input value to slug value on each keypress and show in specified input
    * Disabled if the slug is manually changed
    */
    var slugInput = $('input[data-slugify]'),
    slugOutput = $('input[name='+slugInput.attr('data-slugify')+']');
    var changed = false;

    var slugify = function (str) {
        str = str.replace(/^\s+|\s+$/g, '').toLowerCase();

        // remove accents
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;",
                to = "aaaaeeeeiiiioooouuuunc------f";

        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        var mnFrom = ['ф', 'ц', 'у', 'ж', 'э', 'н', 'г', 'ш', 'ү', 'з', 'к', 'ъ', 'й', 'ы', 'б', 'ө', 'а', 'х', 'р', 'о', 'л', 'д', 'п', 'я', 'ч', 'ё', 'с', 'м', 'и', 'т', 'ь', 'в', 'ю', 'е', 'щ'];
        var enTo = ['f', 'ts', 'u', 'j', 'e', 'n', 'g', 'sh', 'u', 'z', 'k', 'i', 'i', 'ii', 'b', 'u', 'a', 'kh', 'r', 'o', 'l', 'd', 'p', 'ya', 'ch', 'yo', 's', 'm', 'i', 't', 'i', 'w', 'yu', 'e', 'sh'];

        for (var i = 0, l = mnFrom.length; i < l; i++) {
            str = str.replace(new RegExp(mnFrom[i], 'g'), enTo[i]);
        }

        return str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes
    }

    slugOutput.bind('keyup', function () {
        changed = true;
    });

    slugInput.bind('keyup', function () {
        if (!changed) {
            slugOutput.parent().addClass("md-input-focus");
            slugOutput.val(slugify(slugInput.val()));
            slugOutput.trigger('change');
        }
    });
});
