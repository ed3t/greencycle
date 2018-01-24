var ErrorArr = {};

jQuery(function ($) {
    $('.file-upload-previews').on('click', '#removeAdImg', function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.

        var id = $(this).data('item-id');
        var img = $(this).data('img-name');
        var action = 'removeAdImg';
        var $item = $(this).closest('.MultiFile-label');


        var delPrevImg = $('#deletePrevImg').val();
        $('.file-upload').show();
        if (delPrevImg != "") {
            $('#deletePrevImg').val(delPrevImg + ',' + img);
        } else {
            $('#deletePrevImg').val(img);
        }
        $item.remove();
        /*
         var data = { action: action, id: id, img : img };
         $.ajax({
         type: "POST",
         url: ajaxurl,
         data: data,
         success: function (result) {
         if(result == 1)
         {
         $item.remove();
         location.reload();
         }
         else
         {
         alert('Some error occurred.');
         }
         },
         error: function (result)
         {
         alert('Some error occurred.');
         }
         });*/
    });

});


function deleteImg(id, img) {
    var count = $('#imgCount').val();
    var previousImages = $('#previousImages').val();
    var previousImagesArray = previousImages.split(',');
    $.ajax({
        url: site_url + "/admin-ajax.php?action=removeAdImg&id=" + id + "&img=" + img,
        type: 'post',
        success: function (result) {
            if (result == 1) {
                $('#' + removeimgid).remove();
                var updateCount = count - 1;
                $('#imgCount').val(updateCount);
                $('.andsund').MultiFile({
                    // your options go here
                    max: updateCount
                });
                previousImagesArray = $.grep(previousImagesArray, function (value) {
                    return value != img;
                });
                $('#previousImages').val(previousImagesArray.join());

                if (updateCount < 6) {
                    $('#input-upload-img1').show();
                    $('#addMoreImg').show();
                }

            }
            else {
                alert('Some error occurred.');
            }
        },
        error: function (result) {
            alert('Some error occurred.');
        }
    });
}

function addImage() {
    var count = $('#imgCount').val();

    if (count < 5) {
        var newCount = ++count;
        $('#addInputFile').append('<div class="addmore-input" id="input-upload-img' + newCount + '"><input  name="img[]" class="file add-margin" data-preview-file-type="text" type="file" accept="image/*" onchange="checkFile(this)"><a class="pic-tage" href="javascript:void(0);" onclick="removeImg(' + newCount + ', this);"><i class="fa fa-times-circle" aria-hidden="true"></i></a></div>');
        $('#imgCount').val(newCount);
    }
    else {
        alert('You have reached your maximum limit.');
        $('#addMoreImg').hide();
    }
}

function removeImg(count, obj) {
    $('#input-upload-img' + count).remove();
    obj.remove();
    if (count < 6) {
        $('#addMoreImg').show();
        var count = $('#imgCount').val();
        $('#imgCount').val(count - 1);
    }

}
function fillPrice(obj, val) {
    if ($(obj).is(':checked')) {
        var a = $('#totalPrice').text();
        var c = parseInt(a, 10) + parseInt(val, 10);
    }
    else {
        var a = $('#totalPrice').text();
        var c = parseInt(a, 10) - parseInt(val, 10);
    }

    $('#totalPrice').html(c);
}

function setErrorMsg(msg) {
    $.each(msg, function (key, val) {
        if ($.type(val) == 'string') {
            $('#for_' + key).html(val);
        }
        else {
            var opt = $("[rel=" + key + "]");
            $.each(val, function (k, v) {
                var newId = opt[k].id;
                $('#' + newId).html(v);
            });
        }
    });
}
function removeErrorMsg() {
    $(".valError").empty();
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function submitAd() {
    removeErrorMsg();
    var ErrorArr = {};
    var category = $('#category').val();
    var title = $('#adtitle').val();
    var description = $('#description').val();
    var tags = $('#tags').val();
    var price = $('#price').val();
    var sellerName = $('#seller_name').val();
    var sellerEmail = $('#seller_email').val();
    var sellerPhone = $('#seller_phone').val();
    var sellerLocation = $('#address-autocomplete').val();
    var sellerArea = $('#seller_area').val();
    var adPrice = $('input[name="ad_price"]:checked').val();
    var paymentmethod = $('#paymentmethod').val();
    var agree = $('input[name="agree"]:checked').val();
    var ad_image = $('input[type="file"]').val();
    var ad_pre_image = $('#previousImages').val();

    if (category == '') {
        ErrorArr['category'] = '(Category required.)';
    }

    if (title == '') {
        ErrorArr['adtitle'] = '(Title required.)';
    }
    if (description == '') {
        ErrorArr['description'] = '(Description required.)';
    }
    if (price == '') {
        ErrorArr['price'] = '(Price required.)';
    }

    if (sellerName == '') {
        ErrorArr['seller_name'] = '(Name required.)';
    }
    if (sellerEmail == '') {
        ErrorArr['seller_email'] = '(Email required.)';
    }
    else if (!validateEmail(sellerEmail)) {
        ErrorArr['seller_email'] = '(Email not valid.)';
    }
    if (sellerPhone == '') {
        ErrorArr['seller_phone'] = '(Phone number required.)';
    }
    if (tags == '') {
        ErrorArr['tags'] = '(Ad tags required.)';
    }
    if (sellerLocation == '') {
        ErrorArr['address_autocomplete'] = '(Location required.)';
    }
    if (sellerArea == '') {
        ErrorArr['seller_area'] = '(City required.)';
    }
    if (adPrice > 0) {
        if (paymentmethod == '')
            ErrorArr['paymentmethod'] = '(Please select payment method.)';
    }
    if (agree == undefined) {
        ErrorArr['agree'] = '(Please accept agree.)';
    }
    if (ad_image == '' && ad_pre_image == '') {
        ErrorArr['ad_image'] = '(Please upload one image)';
    }
    if (Object.keys(ErrorArr).length == 0) {
        $('#adForm').submit();
    }
    else {
        setErrorMsg(ErrorArr);
        ErrorArr = {};
    }

}