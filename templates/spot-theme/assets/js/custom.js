jQuery(function ($) {

    'use strict';

    bgTransfer();

    // -------------------------------------------------------------
    //  ScrollUp Minimum setup
    // -------------------------------------------------------------

    (function () {

        $.scrollUp();

    }());

    // -------------------------------------------------------------
    //  Placeholder
    // -------------------------------------------------------------

    (function () {

        var textAreas = document.getElementsByTagName('textarea');

        Array.prototype.forEach.call(textAreas, function (elem) {
            elem.placeholder = elem.placeholder.replace(/\\n/g, '\n');
        });

    }());


    // -------------------------------------------------------------
    //  Show 
    // -------------------------------------------------------------

    (function () {

        $("document").ready(function () {
            $(".more-category.one").hide();
            $(".show-more.one").click(function () {
                $(".more-category.one").show();
                $(".show-more.one").hide();
            });
        });

        $("document").ready(function () {
            $(".more-category.two").hide();
            $(".show-more.two").click(function () {
                $(".more-category.two").show();
                $(".show-more.two").hide();
            });
        });

        $("document").ready(function () {
            $(".more-category.three").hide();
            $(".show-more.three").click(function () {
                $(".more-category.three").show();
                $(".show-more.three").hide();
            });
        });

    }());

    /*===================
     * Modal
     * =================*/
    $(".modal-overlay,.close_modal").on("click", function (e) {
        e.preventDefault();
        $(this).parents(".modal-container").removeClass("active");
    });

    $(".modal-trigger").on("click", function (e) {
        e.preventDefault();
        $(".modal-container").removeClass("active");
        $($(this).attr("href")).addClass("active");
    });
    // -------------------------------------------------------------
    //  Tooltip
    // -------------------------------------------------------------

    (function () {

        $('[data-toggle="tooltip"]').tooltip();

    }());


    // -------------------------------------------------------------
    // Accordion
    // -------------------------------------------------------------

    (function () {
        $('.collapse').on('show.bs.collapse', function () {
            var id = $(this).attr('id');
            $('a[href="#' + id + '"]').closest('.panel-heading').addClass('active-faq');
            $('a[href="#' + id + '"] .panel-title span').html('<i class="fa fa-minus"></i>');
        });

        $('.collapse').on('hide.bs.collapse', function () {
            var id = $(this).attr('id');
            $('a[href="#' + id + '"]').closest('.panel-heading').removeClass('active-faq');
            $('a[href="#' + id + '"] .panel-title span').html('<i class="fa fa-plus"></i>');
        });
    }());


    // -------------------------------------------------------------
    //  Checkbox Icon Change
    // -------------------------------------------------------------

    (function () {

        $('input[type="checkbox"]').change(function () {
            if ($(this).is(':checked')) {
                $(this).parent("label").addClass("checked");
            } else {
                $(this).parent("label").removeClass("checked");
            }
        });

    }());


    // -------------------------------------------------------------
    //   Show Mobile Number
    // -------------------------------------------------------------  

    (function () {

        $('.show-number').on('click', function () {
            $('.hide-text').fadeIn(500, function () {
                $(this).addClass('hide');
            });
            $('.hide-number').fadeIn(500, function () {
                $(this).addClass('show');
            });
        });


    }());


// script end
});


// -------------------------------------------------------------
//  Owl Carousel
// -------------------------------------------------------------


(function () {

    $("#featured-slider").owlCarousel({
        items: 3,
        nav: true,
        autoplay: true,
        dots: true,
        autoplayHoverPause: true,
        nav: true,
        navText: [
            "<i class='fa fa-angle-left '></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
        responsive: {
            0: {
                items: 1,
                slideBy: 1
            },
            500: {
                items: 2,
                slideBy: 1
            },
            991: {
                items: 2,
                slideBy: 1
            },
            1200: {
                items: 3,
                slideBy: 1
            },
        }

    });

    $("#latest-slider").owlCarousel({
        items: 3,
        nav: true,
        autoplay: true,
        dots: true,
        autoplayHoverPause: true,
        nav: true,
        navText: [
            "<i class='fa fa-angle-left '></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
        responsive: {
            0: {
                items: 1,
                slideBy: 1
            },
            500: {
                items: 2,
                slideBy: 1
            },
            991: {
                items: 2,
                slideBy: 1
            },
            1200: {
                items: 3,
                slideBy: 1
            },
        }

    });

    $("#recent-slider-id").owlCarousel({
        items: 4,
        nav: true,
        autoplay: true,
        dots: true,
        autoplayHoverPause: true,
        nav: true,
        navText: [
            "<i class='fa fa-angle-left '></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
        responsive: {
            0: {
                items: 1,
                slideBy: 1
            },
            480: {
                items: 2,
                slideBy: 1
            },
            991: {
                items: 3,
                slideBy: 1
            },
            1000: {
                items: 4,
                slideBy: 1
            },
        }

    });

    $("#recommended-slider-id").owlCarousel({
        items: 4,
        nav: true,
        autoplay: true,
        dots: true,
        autoplayHoverPause: true,
        nav: true,
        navText: [
            "<i class='fa fa-angle-left '></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
        responsive: {
            0: {
                items: 1,
                slideBy: 1
            },
            480: {
                items: 2,
                slideBy: 1
            },
            991: {
                items: 3,
                slideBy: 1
            },
            1000: {
                items: 4,
                slideBy: 1
            },
        }

    });

}());

(function () {

    $(".testimonial-carousel").owlCarousel({
        items: 1,
        autoplay: true,
        autoplayHoverPause: true
    });

}());

var s = localStorage.listGrid;
if (s) {
    if (s == 'grid') {
        $('#serchlist .searchresult.grid').fadeIn();
        $('#grid').addClass('btn-success').children('i').addClass('icon-white');
        $('#list').removeClass('btn-success').children('i').removeClass('icon-white');
    } else {
        $('#serchlist .searchresult.list').fadeIn();
        $('#list').addClass('btn-success').children('i').addClass('icon-white');
        $('#grid').removeClass('btn-success').children('i').removeClass('icon-white');
    }
} else {
    $('#serchlist .searchresult:first').show();
}
$('#list').click(function () {
    $(this).addClass('btn-success').children('i').addClass('icon-white');
    $('.grid').fadeOut();
    $('.list').fadeIn();
    $('#grid').removeClass('btn-success').children('i').removeClass('icon-white');
    localStorage.listGrid = 'list';
});
$('#grid').click(function () {
    $(this).addClass('btn-success').children('i').addClass('icon-white');
    $('.list').fadeOut();
    $('.grid').fadeIn();
    $('#list').removeClass('btn-success').children('i').removeClass('icon-white');
    localStorage.listGrid = 'grid';
});

//  Transfer "img" into CSS background-image

function bgTransfer() {
    //disable-on-mobile
    if (viewport.is('xs')) {

    }
    $(".bg-transfer").each(function () {
        $(this).css("background-image", "url(" + $(this).find("img").attr("src") + ")");
    });
}
// -------------------------------------------------------------
//  select-category Change
// -------------------------------------------------------------
$('.select-category.post-option ul li a').on('click', function () {
    $('.select-category.post-option ul li.link-active').removeClass('link-active');
    $(this).closest('li').addClass('link-active');
});

$('.subcategory.post-option ul li a').on('click', function () {
    $('.subcategory.post-option ul li.link-active').removeClass('link-active');
    $(this).closest('li').addClass('link-active');
});

// -------------------------------------------------------------
//  language Select
// -------------------------------------------------------------

(function () {

    $('.navbar-dropdown').on('click', '.language-change a', function (ev) {
        if ("#" === $(this).attr('href')) {
            ev.preventDefault();
            var parent = $(this).parents('.navbar-dropdown');
            parent.find('.change-text').html($(this).html());
        }
    });



    $('#styleswitch').styleSwitcher();
    $("#styleswitch h3").click(function () {
        if ($(this).parent().css("left") == "-200px") {
            $(this).parent().animate({left: '0px'}, {queue: false, duration: 500});
        } else {
            $(this).parent().animate({left: '-200px'}, {queue: false, duration: 500});
        }
    });
    $('.styleswitch .toggler').on('click', function (event) {
        event.preventDefault();
        $(this).closest('.styleswitch').toggleClass('opened');
    });

}());

/*function changeCategory(catid) {
 alert(catid);
 $('#input-catid').val(catid);
 //$('#input-subcatid').val('');
 };
 function changeSubCategory(subcatid) {
 $('#input-subcatid').val(subcatid);
 };*/

