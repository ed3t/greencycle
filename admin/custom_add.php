<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();


if(isset($_POST['title']))
{
    if(!check_allow()){
        ?>
        <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#sa-title').trigger('click');
            });
        </script>
    <?php
    }
    else {
        if ($_POST['type'] == 'text') {
           $query = "INSERT INTO `" . $config['db']['pre'] . "custom_fields` (`custom_page` ,`custom_catid` ,`custom_subcatid` ,`custom_name` ,`custom_title` ,`custom_type` ,`custom_content` ,`custom_min` ,`custom_max` ,`custom_required` ,`custom_options` ,`custom_default`) VALUES ( '" . validate_input($_POST['page'],$mysqli) . "','" . validate_input($_POST['category'],$mysqli) . "','" . validate_input($_POST['sub_category'],$mysqli) . "', '', '" . validate_input($_POST['title'],$mysqli) . "', '" . validate_input($_POST['type'],$mysqli) . "', '" . validate_input($_POST['content'],$mysqli) . "', '0', '" . validate_input($_POST['max'],$mysqli) . "', '" . validate_input($_POST['required'],$mysqli) . "', '', '')";
            mysqli_query($mysqli, $query);
        } else {
            if (!isset($_POST['optionslist'])) {
                $_POST['optionslist'] = array();
            }

            foreach ($_POST['optionslist'] as $key => $value) {
                $_POST['optionslist'][$key] = str_replace(',', '&#44;', $value);
            }

            $options = implode(',', $_POST['optionslist']);
            $query = "INSERT INTO `" . $config['db']['pre'] . "custom_fields` (`custom_page` ,`custom_catid` ,`custom_subcatid` ,`custom_name` ,`custom_title` ,`custom_type` ,`custom_content` ,`custom_min` ,`custom_max` ,`custom_required` ,`custom_options` ,`custom_default`) VALUES ( '" . validate_input($_POST['page'],$mysqli) . "','" . validate_input($_POST['category'],$mysqli) . "','" . validate_input($_POST['sub_category'],$mysqli) . "', '', '" . validate_input($_POST['title'],$mysqli) . "', '" . validate_input($_POST['type'],$mysqli) . "', '" . validate_input($_POST['content'],$mysqli) . "', '0', '" . validate_input($_POST['max'],$mysqli) . "', '" . validate_input($_POST['required'],$mysqli) . "', '" . validate_input($options,$mysqli) . "', '')";
            mysqli_query($mysqli,$query);
        }

        transfer($config, 'custom_view.php', 'Custom Field Added');
        exit;
    }
}

include('header.php');
?>

<script language="JavaScript">
<!-- Original:  Bob Rockers  -->
function move(fbox,tbox) {
    var i = 0;
    if(fbox.value != "") {
        var no = new Option();
        no.value = fbox.value;
        no.text = fbox.value;
        tbox.options[tbox.options.length] = no;
        fbox.value = "";
   }
}
function remove(box) {
    for(var i=0; i<box.options.length; i++) {
    if(box.options[i].selected && box.options[i] != "") {
        box.options[i].value = "";
        box.options[i].text = "";
   }
}
BumpUp(box);
} 
function BumpUp(abox) {
    for(var i = 0; i < abox.options.length; i++) {
    if(abox.options[i].value == "")  {
        for(var j = i; j < abox.options.length - 1; j++)  {
            abox.options[j].value = abox.options[j + 1].value;
            abox.options[j].text = abox.options[j + 1].text;
        }
        var ln = i;
        break;
   }
}
if(ln < abox.options.length)  {
    abox.options.length -= 1;
    BumpUp(abox);
   }
}
function Moveup(dbox) {
    for(var i = 0; i < dbox.options.length; i++) {
        if (dbox.options[i].selected && dbox.options[i] != "" && dbox.options[i] != dbox.options[0]) {
            var tmpval = dbox.options[i].value;
            var tmpval2 = dbox.options[i].text;
            dbox.options[i].value = dbox.options[i - 1].value;
            dbox.options[i].text = dbox.options[i - 1].text
            dbox.options[i-1].value = tmpval;
            dbox.options[i-1].text = tmpval2;
        }
    }
}
function Movedown(ebox) {
    for(var i = 0; i < ebox.options.length; i++) {
        if (ebox.options[i].selected && ebox.options[i] != "" && ebox.options[i+1] != ebox.options[ebox.options.length]) {
            var tmpval = ebox.options[i].value;
            var tmpval2 = ebox.options[i].text;
            ebox.options[i].value = ebox.options[i+1].value;
            ebox.options[i].text = ebox.options[i+1].text
            ebox.options[i+1].value = tmpval;
            ebox.options[i+1].text = tmpval2;
        }
    }
}

function change_type(val)
{
	if(val == 'text')
		document.getElementById('selectoptions').style.display = 'none';
    else if(val == 'textarea')
        document.getElementById('selectoptions').style.display = 'none';
	else if(val == 'select')
		document.getElementById('selectoptions').style.display = '';
    else if(val == 'radio')
        document.getElementById('selectoptions').style.display = '';
    else if(val == 'checkbox')
        document.getElementById('selectoptions').style.display = '';
}

function formSubmitted(form)
{
	for (var i = 0; i < form.optionslist.length; i++) 
	{
        form.optionslist.options[i].selected = true;
    }
}
//  End -->
</script>


    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Add Custom Field</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li class="active">Add Field</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <form name="form1" method="post" class="form-horizontal" onSubmit="return formSubmitted(this)">
                            <div class="form-group"><label class="col-sm-2 control-label">Field Title</label>
                                <div class="col-sm-10"><input name="title" type="Text" class="form-control" id="title" value=""></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Field Page</label>
                                <div class="col-sm-10">
                                    <select name="page" class="form-control">
                                        <option value="post_ad" selected>Post ad</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Category</label>
                                <div class="col-sm-10">
                                    <select name="category" id="category" class="form-control" data-ajax-action="getsubcatbyid">
                                        <option value="">Select a Category...</option>
                                        <?php
                                        $cat =  get_maincategory($config);
                                        foreach($cat as $option){
                                            echo '<option value="'.$option['id'].'">'.$option['name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field SubCategory</label>
                                <div class="col-sm-10">
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Select a Subcategory...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Type</label>
                                <div class="col-sm-10">
                                    <select name="type" onChange="change_type(this.value);" class="form-control">
                                        <option value="text" selected>Text Field</option>
                                        <option value="select">Select Box</option>
                                        <option value="radio">Radio Button</option>
                                        <option value="checkbox">CheckBox</option>
                                        <option value="textarea">TextArea</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Field Content</label>
                                <div class="col-sm-10">
                                    <select name="content" class="form-control">
                                        <option value="all" selected>Anything</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Field Required</label>
                                <div class="col-sm-10">
                                    <select name="required" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1" selected>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <!--Hidden Part-->
                            <div class="hidden">
                                <div class="form-group"><label class="col-sm-2 control-label">Field Min Characters</label>
                                    <div class="col-sm-10"><input name="min" type="Text" class="form-control" id="min" value="0" disabled></div>
                                </div>
                                <div class="form-group"><label class="col-sm-2 control-label">Field Max Characters</label>
                                    <div class="col-sm-10"><input name="max" type="Text" class="form-control" id="max" value="50"></div>
                                </div>
                            </div>
                            <!--Hidden Part-->

                            <div class="form-group" id="selectoptions" style="display:none;"><label class="col-sm-2 control-label">Field Options</label>
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="7" valign="top"></td>
                                        <td width="100%">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="col-sm-8" style="margin-bottom:10px;">
                                                        <input type="text" name="optioninsert" value="" class="form-control" placeholder="Add To List">
                                                    </div>
                                                    <div class="col-sm-4" style="margin-bottom:10px;">
                                                        <input type="button" value="Add To List" onClick="move(this.form.optioninsert,this.form.optionslist)" class="btn btn-info" name="addtoitems" id="addtoitems">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-8">
                                                        <select name="optionslist[]" id="optionslist" size="7" multiple style="height:100px;" class="form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="button" value="&uarr;" onClick="Moveup(this.form.optionslist)" name="moveup" id="moveup" class="btn btn-default"><br><br>
                                                        <input type="button" value="&darr;" onClick="Movedown(this.form.optionslist)" name="movedown" class="btn btn-default">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12" style="margin-top:10px;">
                                                    <div class="col-sm-8">
                                                        <input type="button" value="Delete" onClick="remove(this.form.optionslist)" name="deleteitems"  id="deleteitems" class="btn btn-danger">
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <input name="Reset" type="reset" class="btn btn-default" value="Reset">
                                    <input name="Submit" type="submit" class="btn btn-primary" value="Add Field">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


<?php include("footer.php"); ?>

<script src="js/admin-ajax.js"></script>
<script src="js/alert.js"></script>
