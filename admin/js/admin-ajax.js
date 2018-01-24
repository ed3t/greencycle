jQuery(function($) {
    //Delete Single Ad From Detail Page
    $('#js-delete-single').on('click', '.item-js-action', function(e) {
            // Keep ads item click from being executed.
            e.stopPropagation();
            // Prevent navigating to '#'.
            e.preventDefault();
            // Ask user if he is sure.

            var action = $(this).data('ajax-action');
            var $item = $(this).closest('.ajax-item-listing');
            var data = { action: action, id: $item.data('item-id') };

            var color;
            var type = $(this).data('ajax-type');
            if(type == "approve"){
                color = "#8BC34A";
            }
            else if(type == "delete" || type == "reject"){
                color = "#f44336";
            }
            swal({
                title: "Are you sure?",
                text: "You want to "+type+" this.!",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: color,
                confirmButtonText: "Yes, "+type+"!",
                closeOnConfirm: false
            }, function(){
                $.post(ajaxurl+'?action='+action, data, function(response) {
                    // Remove Ads item from DOM.
                    if(response != 0) {
                        swal(type+"!", "Item has been "+type+".", "success");
                        window.location.href = 'posts.php';
                    }else{
                        swal("Error!", "Problem in "+type+", Please try again.", "error");
                    }
                });

            });
        });

    // Ads list delegated events.
    $('#js-table-list').on('click', '.item-js-delete', function(e) {
            // Keep ads item click from being executed.
            e.stopPropagation();
            // Prevent navigating to '#'.
            e.preventDefault();
            // Ask user if he is sure.
            var action = $(this).data('ajax-action');
            var $item = $(this).closest('.ajax-item-listing');
            var data = { action: action, id: $item.data('item-id') };
            swal({
                title: "Are you sure?",
                text: "You want to delete this.!",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#f44336",
                confirmButtonText: "Yes, Delete!",
                closeOnConfirm: false
            }, function(){
                $.post(ajaxurl+'?action='+action, data, function(response) {
                    // Remove Ads item from DOM.
                    if(response != 0) {
                        $item.remove();
                        swal("Deleted!", "Item has been Deleted.", "success");
                    }else{
                        swal("Error!", "Problem in deleting, Please try again.", "error");
                    }
                });

            });
        });

    //Delete Marked.
    $("[data-ajax-response='deletemarked']").on("click", function (e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.
        var action = $(this).data('ajax-action');
        var $for_delete = $('.service-checker:checked'),
            data = { action: action},
            services = [],
            $panels = [];

        $for_delete.each(function(){
            var panel = $(this).parents('.ajax-item-listing');
            $panels.push(panel);
            services.push(this.value);
        });
        data['list[]'] = services;

        swal({
            title: "Are you sure?",
            text: "You want to delete this.!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#f44336",
            confirmButtonText: "Yes, Delete!",
            closeOnConfirm: false
        }, function(){
            $.post(ajaxurl+'?action='+action, data, function(response) {
                if(response != 0) {
                    $.each($panels.reverse(), function (index) {
                        $(this).delay(500 * index).fadeOut(200, function () {
                            $(this).remove();
                        });
                    });
                    swal("Deleted!", "Item has been Deleted.", "success");
                }else{
                    swal("Error!", "Problem in deleting, Please try again.", "error");
                }
            });

        });


    });

    //Approve Ads
    $('#js-table-list').on('click', '.item-approve', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var action = $(this).data('ajax-action');
            var $item = $(this).closest('.ajax-item-listing');
            var data = { action: action, id: $item.data('item-id') };
            swal({
                title: "Are you sure?",
                text: "You want to Approve this Item.!",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#8BC34A",
                confirmButtonText: "Yes, Approve!",
                closeOnConfirm: false
            }, function(){
                $.post(ajaxurl+'?action='+action, data, function(response) {
                    if(response != 0) {
                        $item.find('.label').html("Approved");
                        $item.find('.label').removeClass('label-warning');
                        $item.find('.label').addClass('label-success');
                        $item.find('.item-approve').remove();

                        swal("Approved!", "Item has been Approved.", "success");
                    }else{
                        swal("Error!", "Problem in Item Approved, Please try again.", "error");
                    }
                });
            });
        });

    //ACTIVE BANNED USER
    $('#js-table-list').on('click', '.user-js-active', function(e) {
            e.stopPropagation();
            e.preventDefault();

            //Parameter
            var action = $(this).data('ajax-action');
            var $item = $(this).closest('.ajax-item-listing');
            var data = { action: action, id: $item.data('item-id') };
            swal({
                title: "Are you sure?",
                text: "You want to activate this user.!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#8BC34A",
                confirmButtonText: "Yes, Activate it!",
                closeOnConfirm: false
            }, function(){
                $.post(ajaxurl+'?action='+action, data, function(response) {
                    if(response != 0) {
                        $item.find('.label').html("ACTIVE");
                        $item.find('.label').removeClass('label-warning');
                        $item.find('.label').addClass('label-info');
                        $item.find('.user-js-active').remove();

                        swal("Activated!", "User has been Activated.", "success");
                    }else{
                        swal("Error!", "Problem in User Activate, Please try again.", "error");
                    }
                });
            });
        });

    //BANNED USER
    $('#js-table-list').on('click', '.user-js-ban', function(e) {
            e.stopPropagation();
            e.preventDefault();

            //Parameter
            var action = $(this).data('ajax-action');
            var $item = $(this).closest('.ajax-item-listing');
            var data = { action: action, id: $item.data('item-id') };
            swal({
                title: "Are you sure?",
                text: "You want to ban this user.!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#f44336",
                confirmButtonText: "Yes, Ban it!",
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm){
                if (isConfirm) {
                    $.post(ajaxurl+'?action='+action, data, function(response) {
                        if(response != 0) {
                            $item.find('.label').html("BANNED");
                            $item.find('.label').removeClass('label-info');
                            $item.find('.label').addClass('label-warning');
                            $item.find('.user-js-ban').remove();
                            swal("Banned!", "User has been Banned.", "success");
                        }else{
                            swal("Error!", "Problem in User Ban, Please try again.", "error");
                        }
                    });
                } else {
                    swal("Cancelled", "This User is safe :)", "error");
                }
            });

        });

    $("#category").change(function(){
        var catid = $(this).val();
        var action = $(this).data('ajax-action');
        var data = { action: action, catid: catid };
        $.ajax({
            type: "POST",
            url: ajaxurl+"?action="+action,
            data: data,
            success: function(result){
                $("#sub_category").html(result);
            }
        });
    });

    $('#js-table-list').on('click', '.editAjaxCountry', function(e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.

        var $item = $(this).closest('.ajax-item-listing');
        var id = $item.data('item-id');
        var name = $item.data('item-name');
        var sortname = $item.data('item-sortname');
        var phonecode = $item.data('item-phonecode');

        $('#countryid').val(id);
        $('#countryname').val(name);
        $('#sortname').val(sortname);
        $('#phonecode').val(phonecode);
        $('#editEntry').modal('show');

    });

    $('#js-table-list').on('click', '.editAjaxState', function(e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.

        var $item = $(this).closest('.ajax-item-listing');
        var id = $item.data('item-id');
        var name = $item.data('item-name');

        $('#stateid').val(id);
        $('#statename').val(name);
        $('#editEntry').modal('show');

    });

    $('#js-table-list').on('click', '.editAjaxCity', function(e) {
        // Keep ads item click from being executed.
        e.stopPropagation();
        // Prevent navigating to '#'.
        e.preventDefault();
        // Ask user if he is sure.

        var $item = $(this).closest('.ajax-item-listing');
        var id = $item.data('item-id');
        var name = $item.data('item-name');

        $('#cityid').val(id);
        $('#cityname').val(name);
        $('#editEntry').modal('show');

    });

    //ACTIVE BANNED USER
    $('#js-table-list').on('click', '.install-country', function(e) {
        e.stopPropagation();
        e.preventDefault();

        //Parameter
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = { action: action, id: $item.data('item-id') };
        swal({
            title: "Are you sure?",
            text: "You want to install this country.!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8BC34A",
            confirmButtonText: "Yes, Install it!",
            closeOnConfirm: true
        }, function(){
            $.post(ajaxurl+'?action='+action, data, function(response) {
                if(response != 0) {
                    $item.find('.label').html("Installed");
                    $item.find('.label').removeClass('label-warning');
                    $item.find('.label').addClass('label-info');
                    $item.find('.install-country').remove();

                    swal("Installed!", "Country has been Installed.", "success");
                }else{
                    swal("Error!", "Problem in Installation, Please try again.", "error");
                }
            });
        });
    });

    //BANNED USER
    $('#js-table-list').on('click', '.uninstall-country', function(e) {
        e.stopPropagation();
        e.preventDefault();

        //Parameter
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = { action: action, id: $item.data('item-id') };
        swal({
            title: "Are you sure?",
            text: "You want to Uninstall Country.!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f44336",
            confirmButtonText: "Yes, Uninstall it!",
            cancelButtonText: "No, cancel plz!",
            closeOnConfirm: true
        }, function(){
            $.post(ajaxurl+'?action='+action, data, function(response) {
                if(response != 0) {
                    $item.find('.label').html("Uninstall");
                    $item.find('.label').removeClass('label-info');
                    $item.find('.label').addClass('label-warning');
                    $item.find('.uninstall-country').remove();
                    swal("Uninstalled!", "Country has been Uninstalled.", "success");
                }else{
                    swal("Error!", "Problem in Uninstall, Please try again.", "error");
                }
            });
        });

    });


    $('#js-table-list').on('click', '.install-payment', function(e) {
        e.stopPropagation();
        e.preventDefault();

        //Parameter
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = { action: action, id: $item.data('item-id') };
        swal({
            title: "Are you sure?",
            text: "You want to install this Payment.!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8BC34A",
            confirmButtonText: "Yes, Install it!",
            closeOnConfirm: true
        }, function(){
            $.post(ajaxurl+'?action='+action, data, function(response) {
                if(response != 0) {
                    $item.find('.label').html("Installed");
                    $item.find('.label').removeClass('label-warning');
                    $item.find('.label').addClass('label-info');
                    $item.find('.install-payment').remove();

                    swal("Installed!", "Payment has been Installed.", "success");
                }else{
                    swal("Error!", "Problem in Installation, Please try again.", "error");
                }
            });
        });
    });

    $('#js-table-list').on('click', '.uninstall-payment', function(e) {
        e.stopPropagation();
        e.preventDefault();

        //Parameter
        var action = $(this).data('ajax-action');
        var $item = $(this).closest('.ajax-item-listing');
        var data = { action: action, id: $item.data('item-id') };
        swal({
            title: "Are you sure?",
            text: "You want to Uninstall Payment.!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f44336",
            confirmButtonText: "Yes, Uninstall it!",
            cancelButtonText: "No, cancel plz!",
            closeOnConfirm: true
        }, function(){
            $.post(ajaxurl+'?action='+action, data, function(response) {
                if(response != 0) {
                    $item.find('.label').html("Uninstall");
                    $item.find('.label').removeClass('label-info');
                    $item.find('.label').addClass('label-warning');
                    $item.find('.uninstall-payment').remove();
                    swal("Uninstalled!", "Payment has been Uninstalled.", "success");
                }else{
                    swal("Error!", "Problem in Uninstall, Please try again.", "error");
                }
            });
        });

    });

});

$(document).ready(function () {
    $("#editAjaxForm").on('submit', function() {

        $("#editAjaxForm #login-status").show();
        var action = $("#editAjaxForm").attr('action');
        var form_data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: ajaxurl+'?action='+action,
            data: form_data,
            success: function (response) {
                if (response == "success") {
                    $("#editAjaxForm #login-status").removeClass('info-notice').addClass('success-notice');
                    $("#editAjaxForm #login-status #login-status-message").html('Setting Saved....');
                    location.reload();
                }
                else {
                    $("#editAjaxForm #login-status").removeClass('info-notice').addClass('error-notice');
                    $("#editAjaxForm #login-status #login-status-message").html(response);
                }
            }
        });
        return false;
    });

    $("#addAjaxForm").on('submit', function() {

        $("#addAjaxForm #login-status").show();
        var action = $("#addAjaxForm").attr('action');
        var form_data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: ajaxurl+'?action='+action,
            data: form_data,
            success: function (response) {
                if (response == "success") {
                    $("#addAjaxForm #login-status").removeClass('info-notice').addClass('success-notice');
                    $("#addAjaxForm #login-status #login-status-message").html('Setting Saved....');
                    location.reload();
                }
                else {
                    $("#addAjaxForm #login-status").removeClass('info-notice').addClass('error-notice');
                    $("#addAjaxForm #login-status #login-status-message").html(response);
                }
            }
        });
        return false;
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

});

function getStateSelected(countryid, action, selectid) {
    var data = {action: action, id: countryid, selectid: selectid};
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (result) {
            $("#state").html(result);
            $("#state").select2();
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
            $("#city").select2();
        }
    });
}

function getsubcat(catid,action,selectid){
    var data = { action: action, catid: catid, selectid : selectid };
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function(result){
            $("#sub_category").html(result);
        }
    });
}

