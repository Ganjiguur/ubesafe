String.prototype.replaceAll = function (search, replacement) {
  var target = this;
  return target.replace(new RegExp(search, 'g'), replacement);
};

if ($("iframe")) {
  $("iframe").addClass("uk-responsive-width");
  $("iframe").css("min-height", "300px");
}

function handleInnerNav() {
  if ($(".inner-nav a.nav-item").length) {
    $(".inner-nav a.nav-item").on('click', function () {
      $(".inner-nav a.nav-item").removeClass("active");
      $(this).addClass("active");
    });

    $(document).on("scroll", function(){
      var scrollPos = $(document).scrollTop();
      $('.inner-item').each(function() {
        var el = $(this);
        if ((el.offset().top - 80) <= scrollPos) {
          if(!$('.inner-nav a.nav-item[href=#' + el.attr('id') + ']').hasClass('active')) {
            $('.inner-nav a.nav-item.active').removeClass('active');
            $('.inner-nav a.nav-item[href=#' + el.attr('id') + ']').addClass('active');
          }
        }
      });
    });
  }
}

function innerNavScroll() {
  if($('.scrollbar-macosx').length) {
    $('.scrollbar-macosx').scrollbar();
  }
}

handleInnerNav();

function scrollToNavItem(item) {
  var scroller = item.parent();
  var scrollerWidth = scroller.width();
  var itemLeftOffset = item.position().left;
  if(itemLeftOffset < 0) {
    scroller.animate({'scrollLeft': (scroller.scrollLeft() + itemLeftOffset - 50)});
  } else { 
    var itemRightOffset = itemLeftOffset + item.outerWidth(true);
    if (itemRightOffset > scrollerWidth){
      scroller.animate({'scrollLeft': (scroller.scrollLeft() + itemRightOffset - scrollerWidth + 50)});
    }
  }
}

//preparing accordion data
$("table.rw-accordion").each(function () {
  var clazz = '';
  if ($(this).hasClass('narrow')) {
    clazz = 'narrow';
  }
  var accordion = '<div class="uk-accordion ' + clazz + '" data-uk-accordion=\'{collapse: false}\'>';
  var n = 0;
  $(this.rows).each(function (index) {
    if (index % 2 === 0) {
      accordion += index > 0 ? '<hr>' : '';
      var clazz = "uk-accordion-title";
      if($(this.cells[0]).hasClass("accordion-open")) {
        clazz += ' accordion-open';
      }
      
      accordion += '<h3 class="' + clazz + '">'
              + '<i class="transition uk-icon-angle-right accordion-icon"></i>'
              + this.cells[0].innerHTML
              + '</h3>';
      
    } else {
      accordion += '<div class="uk-accordion-content">'
              + this.cells[0].innerHTML
              + '</div>';
    }
  });
  accordion += "</div>";
  if ($(this).parent().hasClass("uk-overflow-container") && $(this).parent().children().length === 1) {
    $(this).parent().replaceWith(accordion);
  } else {
    $(this).replaceWith(accordion);
  }
});

//preparing accordion data
$("table.rw-tab").each(function (index) {
  var tabs = '';
  var tab_contents = '';
  var tabs_count = Math.floor($(this).find('td').length / 2);
  $(this).find('td').each(function (index) {
    if (index % 2 === 0) {
      tabs += '<li class="uk-width-1-2 uk-width-small-1-3 uk-width-medium-1-' + tabs_count + '"><a href="">'
              + $(this).html()
              + '</a></li>';
    } else {
      tab_contents += '<li>'
              + $(this).html()
              + '</li>';
    }
  });
  
  var html = '<ul class="uk-tab uk-tab-grid" data-uk-tab="{connect: \'#tab-contents' + index + '\' , animation:\'fade\', swiping:false}" data-uk-grid-match="{target:\'a\'}">'
              + tabs
              + '</ul>'
              + '<ul id="tab-contents' + index + '" class="uk-switcher">'
              + tab_contents
              + '</ul>';
      
  if ($(this).parent().hasClass("uk-overflow-container") && $(this).parent().children().length === 1) {
    $(this).parent().replaceWith(html);
  } else {
    $(this).replaceWith(html);
  }
});

//preparing timeline data
$("table.rw-timeline").each(function () {
  var html = '';
  var innerNav = '';

  var body = $(this).children('tbody')[0];
  $(body).children('tr').each(function (index) {
    if (index % 2 === 0) {
      var yearTxt = $(this).text();
      var year = yearTxt.substr(0,4);
      innerNav += '<a href="#'+year+'" data-uk-smooth-scroll="{offset: 60}" class="nav-item '+ (index == 0 ? 'active' : '') +'">'+year+'</a>';
      html += '<li><div id="' + year + '" class="year inner-item">' + yearTxt + '</div>';
    } else {
      html += '<ul class="inner uk-list">';
      $(this).find('tr').each(function () {
        var content = $(this).html();
        html += '<li><div class="content transition">' + content + '</div></li>';
      });
      html += '</ul></i>';
    }
  });
  
  html = '<ul class="timeline">' + html + '</ul>';
  

  if(html !== '') {
    html = '<div class="inner-nav uk-hidden-small" data-uk-sticky="{media: 640}"><div class="scrollbar-macosx">' + innerNav + '</div></div>' + html;
    
    if ($(this).parent().hasClass("uk-overflow-container") && $(this).parent().children().length === 1) {
      $(this).parent().replaceWith(html);
    } else {
      $(this).replaceWith(html);
    }
    handleInnerNav();
    innerNavScroll();
  }    
});

//preparing members data
$("table.rw-members").each(function () {
  var html = "";
  var modals ="";
  var rows = $(this).find('tr');
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    var cells = $(row).find('td');
    if(cells.length !== 4) {
      continue;
    }
    var img = $(cells[0]).find('img').attr('src');
    var name = $(cells[1]).text();
    var position = $(cells[2]).text();
    var bio = $(cells[3]).html();
    if (name == '' || img == '') {
      continue;
    }
    html += '<div class="uk-width-medium-1-3 uk-width-small-1-2 uk-width-1-2">'
         +     '<a href="#member' + i + '" class="member-item" data-uk-modal="{center:true}">'
         +         '<figure class="uk-overlay uk-overlay-hover"><img src="'+img+'" class="uk-overlay-scale uk-width-1-1"></figure>'
         +         '<h4 class="text-darkblue uk-text-bold uk-text-uppercase">' + name + '</h4>'
         +         '<span class="text-grape text-14">' + position + '</span>'
         +       '<span class="line"></span><span class="transition"></span></a>'
         +     '</div>';
    if (bio !== '') {
      modals += '<div id="member' + i + '" class="uk-modal">'
          +  '<div class="uk-modal-dialog ck-modal">'
          +    '<a class="uk-modal-close uk-close"></a>'
          +      '<div class="uk-grid">'
          +        '<div class="uk-width-1-3 uk-text-center">'
          +          '<img class="uk-overlay-scale uk-width-1-1 left-top-round" src="' + img + '">'
          +           '<h4 class="text-darkblue uk-text-bold uk-text-uppercase uk-margin-small-bottom">' + name + '</h4>'
          +           '<span class="text-grape text-14">' + position + '</span>'
          +        '</div>'
          +        '<div class="uk-width-2-3">' + bio + '</div>'
          +      '</div>'
          +    '</div>'
          +  '</div>';
    }
  }
  if(html !== '') {
    html = '<div class="uk-grid member-grid" data-uk-grid-margin data-uk-grid-match="{target:\'.member-item\'}">' + html + '</div>';
  
    if ($(this).parent().hasClass("uk-overflow-container") && $(this).parent().children().length === 1) {
      $(this).parent().replaceWith(html);
    } else {
      $(this).replaceWith(html);
    }
    $('.maintext').after(modals);
  }
});

//preparing images as lightbox gallery
$(".lightbox-grid img").each(function () {
  $(this).wrap('<a href="' + $(this).attr('src') + '" data-uk-lightbox="{group:\'lightbox-grid\'}"></a>');
});

$(".fr-view p img").each(function (index) {
  var attr = $(this).parent().attr('data-uk-lightbox');
  if (typeof attr !== typeof undefined && attr !== false || $(this).parent().is('figure') || $(this).parent().is('a')) {
    // Element has this attribute
  } else {
    $(this).wrap("<a href='" + $(this).attr('src') + "' data-uk-lightbox></a>");
    UIkit.lightbox($(this), {});
  }
});






if (typeof Object.assign != 'function') {
  // Must be writable: true, enumerable: false, configurable: true
  Object.defineProperty(Object, "assign", {
    value: function assign(target, varArgs) { // .length of function is 2
      'use strict';
      if (target == null) { // TypeError if undefined or null
        throw new TypeError('Cannot convert undefined or null to object');
      }

      var to = Object(target);

      for (var index = 1; index < arguments.length; index++) {
        var nextSource = arguments[index];

        if (nextSource != null) { // Skip over if undefined or null
          for (var nextKey in nextSource) {
            // Avoid bugs when hasOwnProperty is shadowed
            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
              to[nextKey] = nextSource[nextKey];
            }
          }
        }
      }
      return to;
    },
    writable: true,
    configurable: true
  });
}

function slugify(str) {
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
          .replace(/\s+/g, '_') // collapse whitespace and replace by -
          .replace(/-+/g, '_'); // collapse dashes
}

function openAccordions(el) {
  el.find('.uk-accordion-title.accordion-open:not(.uk-active)').each(function () {
    $(this).trigger("click");
  });
}

$(".uk-accordion").on("toggle.uk.accordion", function() {
  if(!$(this).hasClass("open-done")) {
    $(this).addClass("open-done");
    openAccordions($(this));
  }
});

function toggleLoadButton(btn, loading, hide_text, icon_class) {
  loading = typeof loading !== 'undefined' ? loading : true;
  hide_text = typeof hide_text !== 'undefined' ? hide_text : true;
  icon_class = typeof icon_class !== 'undefined' ? icon_class : true;

  if(!loading) {
    if(icon_class === null) {
      btn.find('i').hide();
    } else {
      btn.find('i').removeClass();
      btn.find('i').addClass(icon_class);
    }
    hide_text && btn.find('span').show();
    return;
  }
  
  if(hide_text && !btn.find('span').length) {
    var txt = btn.text();
    var width = btn.outerWidth();
    btn.css('min-width', width + 'px');
    btn.html('<i class="uk-icon-refresh uk-icon-spin"></i><span style="display:none">'+txt+'</span>');
  }

  if(btn.find('i').length) {
    hide_text && btn.find('span').hide();
    btn.find('i').removeClass();
    btn.find('i').addClass('uk-icon-refresh uk-icon-spin');
    btn.find('i').show();
  } else {
    btn.prepend('<i class="uk-icon-refresh uk-icon-spin"></i>');
  }
}

