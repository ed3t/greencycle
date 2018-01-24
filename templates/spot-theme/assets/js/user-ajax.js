jQuery(function ($) {
    // Ads list delegated events.
    // On delete Ad clickc(Single Delete).
    $('#js-table-list').on('click', '.item-js-delete', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = {action: action, id: $item.data('item-id')};

        swal({
            title: "Are you sure?",
            text: "You want to delete this Ad.!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#c9302c",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $.post(ajaxurl + '?action=' + action, data, function (response) {
                if (response != 0) {
                    $item.remove();
                    swal("Deleted!", "Ad has been Deleted.", "success");
                } else {
                    swal("Error!", "Problem in Ad Delete, Please try again.", "error");
                }
            });
        });
    });

    $('#js-table-list').on('click', '.item-js-hide', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = {action: action, id: $item.data('item-id')};

        $.post(ajaxurl + '?action=' + action, data, function (response) {
            if (response == 1) {
                $item.addClass('opapcityLight');
                $item.find('.label').html('Hide')
                $item.find('.item-js-hide').html('<i class="fa  fa-eye"></i> Show');
            }
            else if (response == 2) {
                $item.removeClass('opapcityLight');
                $item.find('.label').html('Active')
                $item.find('.item-js-hide').html('<i class="fa  fa-eye-slash"></i> Hide');
            }
            else {
                alert("Problem in Ad Delete, Please try again.");
            }
        });
    });

    $("#category").change(function () {
        var catid = $(this).val();
        var action = $(this).data('ajax-action');
        var data = {action: action, catid: catid};
        $.ajax({
            type: "POST",
            url: ajaxurl + "?action=" + action + "?catid=" + catid,
            data: data,
            success: function (result) {
                //$("#sub_category").html(result);
                var $selectDropdown = $("#sub_category");
                $selectDropdown.empty().html('');
                $selectDropdown.append($("<option></option>").attr("value", "hi").text("tit"));
                $selectDropdown.trigger('contentChanged');

                $('#sub_category').on('contentChanged', function () {
                    // re-initialize (update)
                    $(this).material_select();
                });
            }
        });
        //$('#sub_category.meterialselect').material_select('destroy');
    });

    $('#serchlist').on('click', '#set-favorite li a', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var adId = $(this).data('item-id');
        var userId = $(this).data('userid');
        var action = $(this).data('action');
        var $item = $(this).closest('.quick-item');

        if (userId == 0) {
            //window.location.href = loginurl;
            $(".modal-trigger").trigger('click');
            return;
        }

        $('.fav_' + adId).removeClass('fa-heart').addClass('fa-spinner');

        var data = {action: action, id: adId, userId: userId};
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (result) {
                if (result == 1) {
                    if (action == 'removeFavAd') {
                        $item.remove();
                        var val = $('#favCount').text();
                        var favcount = val - 1;
                        $('#favCount').html(favcount);

                    }
                    else {
                        $('.fav_' + adId).removeClass('fa-spinner');
                        $('.fav_' + adId).addClass('fa-heart active');
                    }

                }
                else if (result == 2) {
                    $('.fav_' + adId).removeClass('fa-spinner active');
                    $('.fav_' + adId).addClass('fa-heart');
                }
                else {
                    //alert("else");
                }
            }
        });
    });

    $("#country").change(function () {
        var id = $(this).val();
        var action = $(this).data('ajax-action');
        var data = {action: action, id: id};
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (result) {
                $("#state").html(result);
                $("#city").html('<option value="">Select City...</option>');
            }
        });
    });

    $("#state").change(function () {
        var id = $(this).val();
        var action = $(this).data('ajax-action');
        var data = {action: action, id: id};
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (result) {
                $("#city").html(result);
            }
        });
    });

    $('#country-popup').on('click', '#getCities ul li .statedata', function (e) {

        e.stopPropagation();
        e.preventDefault();
        $('#getCities #results').hide();
        $('#getCities .loader').show();
        var $item = $(this).closest('.statedata');
        var id = $item.data('id');
        var action = "ModelGetCityByStateID";
        var data = {action: action, id: id};

        $.post(ajaxurl, data, function (result) {
            $("#getCities #results").html(result);
            $('#getCities .loader').hide();
            $('#getCities #results').show();
        });
    });

    $('#country-popup').on('click', '#getCities ul li #changeState', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        $('#getCities #results').hide();
        $('#getCities .loader').show();
        var $item = $(this).closest('.quick-states');
        var id = $item.data('country-id');
        var action = "ModelGetStateByCountryID";
        var data = {action: action, id: id};

        $.post(ajaxurl, data, function (result) {
            $("#getCities #results").html(result);
            $('#getCities .loader').hide();
            $('#getCities #results').show();
        });
    });

    $('#country-popup').on('click', 'ul li .selectme', function (e) {

        e.stopPropagation();
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var type = $(this).data('type');
        $('#inputStateCity').val(name);
        $('#searchStateCity').val(name);
        $('#headerStateCity').html(name + ' <i class="fa fa-pencil"></i>');
        $('#searchPlaceType').val(type);
        $('#searchPlaceId').val(id);
        $('#countryModal').modal('hide');

        /*$.cookie('Quick_placeText',name,"1","/");
         $.cookie('Quick_PlaceId',id,"1","/");
         $.cookie('Quick_PlaceType',type,"1","/");*/
        localStorage.Quick_placeText = name;
        localStorage.Quick_PlaceId = id;
        localStorage.Quick_PlaceType = type;
        $("#searchDisplay").html('').hide();
    });

});

$(function () {

    $('.category-dropdown').on('click', '#category-change a', function (ev) {
        if ("#" === $(this).attr('href')) {
            ev.preventDefault();
            var parent = $(this).parents('.category-dropdown');
            parent.find('.change-text').html($(this).html());
            var id = $(this).data('ajax-id');
            var type = $(this).data('cat-type');

            if (type == "all") {
                $('#input-subcat').val('');
                $('#input-maincat').val('');
            }
            else if (type == "maincat") {
                $('#input-subcat').val('');
            }
            else {
                $('#input-maincat').val('');
            }
            $('#input-' + type).val(id);
        }
    });

    $('#searchStateCity').focus(function () {
        $('#change-city').trigger('click');
    });

    if (localStorage.Quick_placeText != "") {
        var placeText = localStorage.Quick_placeText;
        var PlaceId = localStorage.Quick_PlaceId;
        var PlaceType = localStorage.Quick_PlaceType;

        if (placeText != null) {
            $('#inputStateCity').val(placeText);
            $('#searchStateCity').val(placeText);
            $('#headerStateCity').html(placeText + ' <i class="fa fa-pencil"></i>');
            $('#searchPlaceId').val(PlaceId);
            $('#searchPlaceType').val(PlaceType);
        }
    }


    /*$("#inputStateCity").focusout(function () {
     $("#inputStateCity").val('');
     $("#searchDisplay").html('').hide();
     });*/

    $("#inputStateCity").keyup(function () {
        var searchbox = $(this).val();
        var dataString = 'searchword1=' + searchbox;

        var action = "searchStateCountry";
        var data = {action: action, dataString: searchbox};

        if (searchbox == '') {
            $('#searchDisplay').hide();
        }
        else {
            $('#searchDisplay').show();
            $.post(ajaxurl, data, function (result) {
                $("#searchDisplay").html(result).show();
            });
        }
        return false;
    });

    $("#findCityStateCountry").keyup(function () {
        var searchbox = $(this).val();
        var dataString = 'searchword1=' + searchbox;
        var action = "searchCityStateCountry";
        var data = {action: action, dataString: searchbox};

        if (searchbox == '') {
            $('#FindResultDisplay').hide();
        }
        else {
            $('#FindResultDisplay').show();
            $.post(ajaxurl, data, function (result) {
                $("#FindResultDisplay").html(result).show();
            });
        }
        return false;
    });

    $('#select-post-ad-city').on('click', 'ul li .selectme', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var cityId = $(this).data('cityid');
        var stateId = $(this).data('stateid');
        var countryId = $(this).data('countryid');
        $('#findCityStateCountry').val(name);
        $('#searchPlaceId').val(cityId);
        $('#searchplaceState').val(stateId);
        $('#searchplaceCountry').val(countryId);

        $("#FindResultDisplay").html('').hide();
    });
});

$(document).ready(function () {
    $("#login").click(function () {
        $("#login-status").show();
        var action = $("#lg-form").attr('action');
        var form_data = {
            action: action,
            username: $("#username").val(),
            password: $("#password").val(),
            is_ajax: 1
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form_data,
            success: function (response) {
                if (response == "success") {
                    $("#lg-form").slideUp('slow', function () {
                        $("#login-status").removeClass('info-notice').addClass('success-notice');
                        $("#login-status #login-status-message").html('Logged in success. Redirecting....');
                        location.reload();
                    });
                }
                else {
                    $("#login-status").removeClass('info-notice').addClass('error-notice');
                    $("#login-status #login-status-message").html(response);
                }
            }
        });
        return false;
    });
});

function getStateSelected(countryid, action, selectid) {
    var data = {action: action, id: countryid, selectid: selectid};
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (result) {
            $("#state").html(result);
        }
    });
}

function getCitySelected(stateid, action, selectid) {
    var data = {action: action, id: stateid, selectid: selectid};
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (result) {
            $("#city").html(result);
        }
    });
}

function getsubcat(catid, action, selectid) {
    var data = {action: action, catid: catid, selectid: selectid};
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (result) {
            $("#sub_category").html(result);
        }
    });
}

function removeFav(adId, userId) {
    $.ajax({
        url: ajaxurl + "?action=removeFavAd&id=" + adId + "&userId=" + userId,
        type: 'post',
        success: function (result) {
            if (result == '1') {
                window.location.href = "listing.php";
            }
        }
    });
}


var w = 400;
var h = 580;
var left = (screen.width / 2) - (w / 2);
var top = (screen.height / 2) - (h / 2);
function fblogin() {
    var newWin = window.open(siteurl+"includes/social_login/facebook/index.php", "fblogin", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no,display=popup, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

function gmlogin() {
    var newWin = window.open(siteurl+"includes/social_login/google/index.php", "gmlogin", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

function twlogin() {
    var newWin = window.open(siteurl+"twlogin.php", "twlogin", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);


}
$(document).ready(function () {
    $('#button').click(function (e) { // Button which will activate our modal
        $('.modal').reveal({ // The item which will be opened with reveal
            animation: 'fade',                   // fade, fadeAndPop, none
            animationspeed: 600,                       // how fast animtions are
            closeonbackgroundclick: true,              // if you click background will modal close?
            dismissmodalclass: 'close'    // the class of a button or element that will close an open modal
        });
        return false;
    });

});