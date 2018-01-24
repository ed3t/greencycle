<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

if(isset($_POST['Submit']))
{
	//print_r($_POST);

	foreach ($_POST['id'] as $value) 
	{
		if($_POST['type'] == 'text')
		{
			mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."custom_fields` SET `custom_page` = '" . addslashes($_POST['page'][$value]) . "',`custom_catid` = '" . addslashes($_POST['category'][$value]) . "',`custom_subcatid` = '" . addslashes($_POST['sub_category'][$value]) . "',`custom_title` = '" . addslashes($_POST['title'][$value]) . "',`custom_type` = '" . addslashes($_POST['type'][$value]) . "',`custom_content` = '" . addslashes($_POST['content'][$value]) . "',`custom_min` = '0',`custom_max` = '" . addslashes($_POST['max'][$value]) . "',`custom_required` = '" . addslashes($_POST['required'][$value]) . "' WHERE `custom_id` = '" . $value . "' LIMIT 1 ;");
		}
		else
		{
			if(!isset($_POST['optionslist'][$value]))
			{
				$_POST['optionslist'][$value] = array();
			}
		
			foreach ($_POST['optionslist'][$value] as $key2 => $value2)
			{
				$_POST['optionslist'][$value][$key2] = str_replace(',','&#44;',$_POST['optionslist'][$value][$key2]);
			}
			
			$options = implode(',',$_POST['optionslist'][$value]);

            $sql = "UPDATE `".$config['db']['pre']."custom_fields` SET `custom_page` = '" . addslashes($_POST['page'][$value]) . "',`custom_catid` = '" . addslashes($_POST['category'][$value]) . "',`custom_subcatid` = '" . addslashes($_POST['sub_category'][$value]) . "',`custom_title` = '" . addslashes($_POST['title'][$value]) . "',`custom_type` = '" . addslashes($_POST['type'][$value]) . "',`custom_content` = '" . addslashes($_POST['content'][$value]) . "',`custom_min` = '0',`custom_max` = '" . addslashes($_POST['max'][$value]) . "',`custom_required` = '" . addslashes($_POST['required'][$value]) . "',`custom_options` = '".$options."' WHERE `custom_id` = '" . $value . "' LIMIT 1";
			mysqli_query($mysqli,$sql);
		}
	}
 
	header("Location: custom_view.php");
	exit;
}

if(isset($_GET['id']))
{
	$_POST['list'][] = $_GET['id'];
}


$field_list = array();

$count = 0;
$sql = "SELECT * FROM ".$config['db']['pre']."custom_fields ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE custom_id='" . $value . "'";
	}
	else
	{
		$sql.= " OR custom_id='" . $value . "'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);


$result = $mysqli->query($sql);
while ($info = mysqli_fetch_assoc($result)) {
	$field_list[$info['custom_id']] = $info;
}

include("header.php");
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<SCRIPT LANGUAGE="JavaScript">

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

function change_type(val,id) {
	if(val == 'text')
		document.getElementById('selectoptions'+id).style.display = 'none';
    else if(val == 'textarea')
        document.getElementById('selectoptions'+id).style.display = 'none';
	else if(val == 'select')
		document.getElementById('selectoptions'+id).style.display = '';
    else if(val == 'radio')
        document.getElementById('selectoptions'+id).style.display = '';
    else if(val == 'checkbox')
        document.getElementById('selectoptions'+id).style.display = '';
}
function formSubmitted(form) {
	<?php
	foreach ($field_list as $info)
	{
	?>
	for (var i = 0; i < form.optionslist<?=$info['custom_id'];?>.length; i++) 
	{
        form.optionslist<?=$info['custom_id'];?>.options[i].selected = true;
    }
	<?php
	}
	?>
}

jQuery(function($) {
    change_type("<?php echo $info['custom_type']; ?>","<?php echo $info['custom_id']; ?>");

    getsubcat("<?php echo $info['custom_catid']; ?>","getsubcatbyid","<?php echo $info['custom_subcatid']; ?>")
});
//  End -->
</script>

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Edit Custom Field</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li class="active">Edit Field</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <form name="f1" id="f1" method="post" action="" onSubmit="return formSubmitted(this)" class="form-horizontal">
                            <?php
                            foreach ($field_list as $info)
                            {
                            $options = array();

                            if($info['custom_options'])
                            {
                                $options = explode(',',$info['custom_options']);
                            }
                            ?>
                            <div class="form-group"><label class="col-sm-2 control-label">Custom ID</label>
                                <div class="col-sm-10">
                                    <input name="id[<?php echo $info['custom_id']; ?>]" type="Text" class="form-control"  value="<?php echo $info['custom_id']; ?>" disabled>
                                    <input name="id[<?php echo $info['custom_id']; ?>]" type="hidden" class="form-control" value="<?php echo $info['custom_id']; ?>">
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Title</label>
                                <div class="col-sm-10">
                                    <input name="title[<?php echo $info['custom_id']; ?>]" type="text" class="form-control" value="<?php echo $info['custom_title']; ?>">
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Page</label>
                                <div class="col-sm-10">
                                    <select name="page[<?php echo $info['custom_id']; ?>]" class="form-control">
                                        <option value="post_ad" <?php if($info['custom_page'] == 'post_ad'){ echo 'selected'; } ?>>Post ad</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Category</label>
                                <div class="col-sm-10">
                                    <select name="category[<?php echo $info['custom_id']; ?>]" id="category" class="form-control" data-ajax-action="getsubcatbyid">
                                        <option value="">Select a Category...</option>
                                        <?php
                                        $cat =  get_maincategory($config,$info['custom_catid']);
                                        foreach($cat as $option){
                                            echo '<option value="'.$option['id'].'" '.$option['selected'].'>'.$option['name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field SubCategory</label>
                                <div class="col-sm-10">
                                    <select name="sub_category[<?php echo $info['custom_id']; ?>]" id="sub_category" class="form-control">
                                        <option value="">Select a Subcategory...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Type</label>
                                <div class="col-sm-10">
                                    <select name="type[<?php echo $info['custom_id']; ?>]" class="form-control" onChange="change_type(this.value,'<?php echo $info['custom_id']; ?>');" >
                                        <option value="text" <?php if($info['custom_type'] == 'text'){ echo 'selected'; } ?>>Textfield</option>
                                        <option value="select" <?php if($info['custom_type'] == 'select'){ echo 'selected'; } ?>>Select Box</option>
                                        <option value="radio" <?php if($info['custom_type'] == 'radio'){ echo 'selected'; } ?>>Radio Button</option>
                                        <option value="checkbox" <?php if($info['custom_type'] == 'checkbox'){ echo 'selected'; } ?>>CheckBox</option>
                                        <option value="textarea" <?php if($info['custom_type'] == 'textarea'){ echo 'selected'; } ?>>TextArea</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Content</label>
                                <div class="col-sm-10">
                                    <select name="content[<?php echo $info['custom_id']; ?>]" class="form-control">
                                        <option value="all" <?php if($info['custom_content'] == 'all'){ echo 'selected'; } ?>>Anything</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Field Required</label>
                                <div class="col-sm-10">
                                    <select name="required[<?php echo $info['custom_id']; ?>]" class="form-control">
                                        <option value="0" <?php if($info['custom_required'] == 0){ echo 'selected'; } ?>>No</option>
                                        <option value="1" <?php if($info['custom_required'] == 1){ echo 'selected'; } ?>>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <!--Hidden Part-->
                            <div class="hidden">

                                <div class="form-group"><label class="col-sm-2 control-label">Field Min Characters</label>
                                    <div class="col-sm-10">
                                        <input name="min[<?php echo $info['custom_id']; ?>]" type="Text" class="form-control" id="min" value="<?php echo $info['custom_min']; ?>" disabled>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-sm-2 control-label">Field Max Characters</label>
                                    <div class="col-sm-10">
                                        <input name="max[<?php echo $info['custom_id']; ?>]" type="Text" class="form-control" id="max" value="<?php echo $info['custom_max']; ?>">
                                    </div>
                                </div>
                            </div>
                            <!--Hidden Part-->



                            <div class="form-group" id="selectoptions<?php echo $info['custom_id']; ?>" style="<? if($info['custom_type'] == 'text'){ ?>display:none;<? } ?>"><label class="col-sm-2 control-label">Field Options</label>
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="7" valign="top"></td>
                                        <td width="100%">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="col-sm-8" style="margin-bottom:10px;">
                                                        <input type="text" name="optioninsert<?php echo $info['custom_id']; ?>" value="" class="form-control">
                                                    </div>
                                                    <div class="col-sm-4" style="margin-bottom:10px;">
                                                        <input type="button" value="Add To List" onClick="move(this.form.optioninsert<?php echo $info['custom_id']; ?>,this.form.optionslist<?php echo $info['custom_id']; ?>)" name="addtoitems" id="addtoitems" class="btn btn-info">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-8">
                                                        <select name="optionslist[<?php echo $info['custom_id']; ?>][]" id="optionslist<?php echo $info['custom_id']; ?>" size="7" multiple style="height:100px;" class="form-control">
                                                            <?php
                                                            foreach ($options as $key => $value)
                                                            {
                                                                echo '<option value="'.stripslashes($value).'">'.stripslashes($value).'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="button" value="&uarr;" onClick="Moveup(this.form.optionslist<?php echo $info['custom_id']; ?>)" name="moveup" id="moveup" class="btn btn-default"><br><br>
                                                        <input type="button" value="&darr;" onClick="Movedown(this.form.optionslist<?php echo $info['custom_id']; ?>)" name="movedown" class="btn btn-default">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12" style="margin-top:10px;">
                                                    <div class="col-sm-8">
                                                        <input type="button" value="Delete" onClick="remove(this.form.optionslist<?php echo $info['custom_id']; ?>)" name="deleteitems" class="btn btn-danger" id="deleteitems">
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                                }
                                ?>
                            </div>



                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <input name="Reset" type="reset" class="btn btn-default" value="Reset">
                                    <input name="Submit" type="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <?php include("footer.php"); ?>

            <script src="js/admin-ajax.js"></script>
            <script src="js/alert.js"></script>