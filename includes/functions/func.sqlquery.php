<?php
/**
 * Created by PhpStorm.
 * User: Bylancer
 * Date: 4/1/2017
 * Time: 10:26 AM
 */


function add_option( $config, $option, $value = '') {
    $option = trim($option);
    if ( empty($option) )
        return false;

    $query = "INSERT INTO ".$config['db']['pre']."options (`option_name`, `option_value`) VALUES ('$option', '$value')";
    $query_result = mysqli_query(db_connect($config),$query);

    return $option_id = db_connect($config)->insert_id;
}

function get_option( $config, $option ) {
    $option = trim($option);
    if ( empty($option) )
        return NULL;

    $query = "SELECT option_value FROM ".$config['db']['pre']."options WHERE option_name = '$option'";
    $query_result = mysqli_query(db_connect($config),$query);
    if ( ! $query_result )
        return NULL;
    else{
        $info = mysqli_fetch_assoc($query_result);
        return $info['option_value'];
    }
}

function check_option_exist( $config, $option ) {
    $option = trim($option);
    if ( empty($option) )
        return false;

    $query = "SELECT 1 FROM ".$config['db']['pre']."options WHERE option_name = '$option'";
    $query_result = mysqli_query(db_connect($config),$query);
    $num_rows = mysqli_num_rows($query_result);
    if($num_rows == 1)
        return true;
    else
        return false;
}

function update_option($config,$option,$value) {
    $option = trim($option);
    if ( empty($option) )
        return false;

    if(check_option_exist($config,$option )){
        $query = "UPDATE ".$config['db']['pre']."options set option_value = '$value' WHERE option_name = '$option'";
        $query_result = mysqli_query(db_connect($config),$query);
        if (!$query_result)
            return false;
        else
            return true;
    }
    else{
        add_option($config,$option,$value);
        return true;
    }
}

function delete_option( $config, $option ) {
    $option = trim($option);
    if ( empty($option) )
        return false;

    $query = "DELETE FROM ".$config['db']['pre']."options WHERE option_name = '$option'";
    $query_result = mysqli_query(db_connect($config),$query);
    if ( ! $query_result )
        return false;
    else
        return true;
}

function check_product_favorite($config,$product_id){
    if(checkloggedin()) {
        $query = "SELECT id FROM ".$config['db']['pre']."favads WHERE product_id='" . $product_id . "' and user_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
        $query_result = mysqli_query(db_connect($config), $query);
        $num_rows = mysqli_num_rows($query_result);
        if($num_rows == 1)
            return true;
        else
            return false;

    }else{
        return false;
    }

}

function check_valid_author($config,$product_id){
    if(checkloggedin()) {
        $query = "SELECT 1 FROM ".$config['db']['pre']."product WHERE id='" . $product_id . "' and user_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
        $query_result = mysqli_query(db_connect($config), $query);
        $num_rows = mysqli_num_rows($query_result);
        if($num_rows == 1)
            return true;
        else
            return false;

    }else{
        return false;
    }
}

function check_item_status($config,$product_id){
    $query = "SELECT status FROM ".$config['db']['pre']."product WHERE id='" . $product_id . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info['status'];
}

function check_valid_resubmission($config,$product_id){
    if(checkloggedin()) {
        $query = "SELECT 1 FROM ".$config['db']['pre']."product_resubmit WHERE product_id='" . $product_id . "' and user_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
        $query_result = mysqli_query(db_connect($config), $query);
        $num_rows = mysqli_num_rows($query_result);
        if($num_rows == 1)
            return false;
        else
            return true;

    }else{
        return false;
    }
}

function get_setting($config){
    $query = "SELECT * FROM ".$config['db']['pre']."setting WHERE `id` = '1' LIMIT 1";
    $query_result = mysqli_query(db_connect($config),$query);
    $info = mysqli_fetch_assoc($query_result);
    return $info;
}

function get_html_pages($config){
    $htmlPages = array();

    $query = "select * from ".$config['db']['pre']."html";
    $result = db_connect($config)->query($query);
    if (mysqli_num_rows($result) > 0) {
        while($info = mysqli_fetch_assoc($result))
        {
            $htmlPages[$info['html_id']]['id'] = $info['html_id'];
            $htmlPages[$info['html_id']]['title'] = $info['html_title'];

            if($config['mod_rewrite'] == 0)
                $htmlPages[$info['html_id']]['link'] = $config['site_url'].'html.php?id='.$info['html_id'];
            else
                $htmlPages[$info['html_id']]['link'] = $config['site_url'].'html/'.$info['html_id'];
        }
    }
    return $htmlPages;
}

function get_advertise($config,$slug){
    $query = "SELECT * FROM ".$config['db']['pre']."adsense WHERE `slug` = '".$slug."' LIMIT 1";
    $query_result = mysqli_query(db_connect($config),$query);
    $info = mysqli_fetch_assoc($query_result);

    $status = $info['status'];
    $large_track_code = $info['large_track_code'];
    $tablet_track_code = $info['tablet_track_code'];
    $phone_track_code = $info['phone_track_code'];
    $advertise_tpl = "";

    if($status=='1'){
        $advertise_tpl = '<div class="text-center visible-md visible-lg">'.$large_track_code.'</div>
        <div class="text-center visible-sm">'.$tablet_track_code.'</div>
        <div class="text-center visible-xs">'.$phone_track_code.'</div>';
    }
    return $advertise_tpl;
}

function get_countryID_by_state_id($config,$id){
    $query = "SELECT country_id FROM ".$config['db']['pre']."states WHERE id='".$id."' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info['country_id'];
}

function get_countryID_by_sortname($config,$sortname){
    $query = "SELECT id FROM ".$config['db']['pre']."countries WHERE sortname='".$sortname."' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info['id'];
}

function get_countryName_by_sortname($config,$sortname){
    $query = "SELECT name FROM ".$config['db']['pre']."countries WHERE sortname='".$sortname."' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info['name'];
}

function get_countryName_by_id($config,$id){
    $query = "SELECT name FROM ".$config['db']['pre']."countries WHERE id='" . $id . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info['name'];
}

function get_stateName_by_id($config,$id){
    $query = "SELECT name FROM ".$config['db']['pre']."states WHERE id='" . $id . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info['name'];
}

function get_cityName_by_id($config,$id){
    $query = "SELECT name FROM ".$config['db']['pre']."cities WHERE id='" . $id . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info['name'];
}

function get_country_list($config,$selected="",$selected_text='selected')
{
    $countries = array();
    $count = 0;

    $query = "SELECT * FROM ".$config['db']['pre']."countries ORDER BY name";
    $query_result = mysqli_query(db_connect($config),$query);
    while ($info = mysqli_fetch_array($query_result))
    {
        $countries[$count]['id'] = $info['id'];
        $countries[$count]['sortname'] = $info['sortname'];
        $countries[$count]['name'] = $info['name'];
        $countries[$count]['phonecode'] = $info['phonecode'];
        if($selected!="")
        {
            if(is_array($selected))
            {
                foreach($selected as $select)
                {

                    $select = strtoupper(str_replace('"','',$select));
                    if($select == $info['id'])
                    {
                        $countries[$count]['selected'] = $selected_text;
                    }
                }
            }
            else{
                if($selected==$info['id'] or $selected==$info['sortname'])
                {
                    $countries[$count]['selected'] = $selected_text;
                }
                else
                {
                    $countries[$count]['selected'] = "";
                }
            }
        }
        $count++;
    }

    return $countries;
}

function get_customFields_by_catid($config,$con,$maincatid,$subcatid,$fields=array(),$data=array()){
    $custom_fields = array();

    $query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_catid='$maincatid' and custom_subcatid='$subcatid'  order by custom_id";
    $query_result = @mysqli_query ($con,$query) OR error(mysqli_error($con));
    while ($info = @mysqli_fetch_array($query_result))
    {
        $custom_fields[$info['custom_id']]['id'] = $info['custom_id'];
        $custom_fields[$info['custom_id']]['type'] = $info['custom_type'];
        $custom_fields[$info['custom_id']]['name'] = $info['custom_name'];
        $custom_fields[$info['custom_id']]['title'] = stripslashes($info['custom_title']);
        $custom_fields[$info['custom_id']]['maxlength'] = $info['custom_max'];

        $required = "";
        if($info['custom_required'] == 1){
            $required = "required";
            $custom_fields[$info['custom_id']]['required'] = "required";
        }
        else{
            $custom_fields[$info['custom_id']]['required'] = "";
        }



        if(isset($_POST['custom'][$info['custom_id']]))
        {
            if($custom_fields[$info['custom_id']]['type'] == "checkbox"){
                $checkbox1=$_POST['custom'][$info['custom_id']];
                if(is_array($checkbox1)){
                    $chk="";
                    $chkCount = 0;
                    foreach($checkbox1 as $chk1)
                    {
                        if($chkCount == 0)
                            $chk .= $chk1;
                        else
                            $chk .= "+".$chk1;

                        $chkCount++;
                    }
                    $custom_fields[$info['custom_id']]['default'] = $chk;
                }
                else{
                    $custom_fields[$info['custom_id']]['default'] = $_POST['custom'][$info['custom_id']];
                }

            }
            else{
                $custom_fields[$info['custom_id']]['default'] = substr(strip_tags($_POST['custom'][$info['custom_id']]),0,$info['custom_max']);
            }

            $custom_fields[$info['custom_id']]['userent'] = 1;
        }
        else
        {
            $custom_fields[$info['custom_id']]['default'] = $info['custom_default'];
            $custom_fields[$info['custom_id']]['userent'] = 0;
        }

        foreach($fields as $key=>$value)
        {
            if($value != '')
            {
                if($value == $info['custom_title']){
                    $custom_fields[$info['custom_id']]['default'] = $data[$key];
                    break;
                }

            }
        }

        //TextBox
        if($info['custom_type'] == 'text'){
            $textbox = '<input name="custom['.$info['custom_id'].']" id="custom['.$info['custom_id'].']" class="form-control"  type="text" size="30" maxlength="'.$info['custom_max'].'" value="'.$custom_fields[$info['custom_id']]['default'].'" '.$required.' placeholder="'.$custom_fields[$info['custom_id']]['title'].'"/>';
            $custom_fields[$info['custom_id']]['textbox'] = $textbox;
        }
        else{
            $custom_fields[$info['custom_id']]['textbox'] = '';
        }
        //Textarea
        if($info['custom_type'] == 'textarea'){
            $textarea= '<textarea class="materialize-textarea form-control" name="custom['.$info['custom_id'].']" id="custom['.$info['custom_id'].']" maxlength="'.$info['custom_max'].'" '.$required.' placeholder="'.$custom_fields[$info['custom_id']]['title'].'">'.$custom_fields[$info['custom_id']]['default'].'</textarea>';
            $custom_fields[$info['custom_id']]['textarea'] = $textarea;
        }
        else{
            $custom_fields[$info['custom_id']]['textarea'] = '';
        }
        //SelectList
        if($info['custom_type'] == 'select')
        {
            $options = explode(',',stripslashes($info['custom_options']));

            //$selectbox = '<select class="meterialselect" name="custom['.$info['custom_id'].']" '.$required.'><option value="" selected>'.$info['custom_title'].'</option>';
            $selectbox = '';
            foreach($options as $key3=>$value3)
            {
                if($value3 == $custom_fields[$info['custom_id']]['default'])
                {
                    $selectbox.= '<option value="'.$value3.'" selected>'.$value3.'</option>';
                }
                else
                {
                    $selectbox.= '<option value="'.$value3.'">'.$value3.'</option>';
                }
            }
            //$selectbox.= '</select>';

            $custom_fields[$info['custom_id']]['selectbox'] = $selectbox;
        }
        else
        {
            $custom_fields[$info['custom_id']]['selectbox'] = '';
        }
        //RadioButton
        if($info['custom_type'] == 'radio')
        {
            $options = explode(',',stripslashes($info['custom_options']));
            $radiobtn = "";
            $i = 0;
            foreach($options as $key3=>$value3)
            {
                if($value3 == $custom_fields[$info['custom_id']]['default'])
                {
                    $radiobtn .= '<div class="col-md-4 col-sm-4"><input class="with-gap" type="radio" name="custom['.$info['custom_id'].']" id="'.$value3.$i.'" value="'.$value3.'" checked />';
                    $radiobtn .= '<label for="'.$value3.$i.'">'.$value3.'</label></div>';
                }
                else
                {
                    $radiobtn .= '<div class="col-md-4 col-sm-4"><input class="with-gap" type="radio" name="custom['.$info['custom_id'].']" id="'.$value3.$i.'" value="'.$value3.'" />';
                    $radiobtn .= '<label for="'.$value3.$i.'">'.$value3.'</label></div>';
                }
                $i++;
            }
            $custom_fields[$info['custom_id']]['radio'] = $radiobtn;
        }
        else
        {
            $custom_fields[$info['custom_id']]['radio'] = '';
        }

        //Checkbox
        if($info['custom_type'] == 'checkbox')
        {
            $options = explode(',',stripslashes($info['custom_options']));
            $Checkbox = "";
            $CheckboxBootstrap = "";
            $j = 0;
            $selected = "";
            foreach($options as $key4=>$value4)
            {
                $checked = explode('+',$custom_fields[$info['custom_id']]['default']);
                foreach ($checked as $val)
                {
                    if($value4 == $val)
                    {
                        $selected = "checked";
                        break;
                    }
                    else{
                        $selected = "";
                    }
                }

                $Checkbox .= '<div class="col-md-4 col-sm-4"><input class="with-gap" type="checkbox" name="custom['.$info['custom_id'].'][]" id="'.$value4.$j.'" value="'.$value4.'" '.$selected.' />';
                $Checkbox .= '<label for="'.$value4.$j.'">'.$value4.'</label></div>';

                $CheckboxBootstrap .= '<label for="'.$value4.$j.'" class="'.$selected.'">'.$value4.'<input class="with-gap" type="checkbox" name="custom['.$info['custom_id'].'][]" id="'.$value4.$j.'" value="'.$value4.'" '.$selected.' /></label>';

                $j++;
            }
            $custom_fields[$info['custom_id']]['checkbox'] = $Checkbox;
            $custom_fields[$info['custom_id']]['checkboxBootstrap'] = $CheckboxBootstrap;
        }
        else
        {
            $custom_fields[$info['custom_id']]['checkbox'] = '';
            $custom_fields[$info['custom_id']]['checkboxBootstrap'] = '';
        }
    }

    return $custom_fields;
}

function get_maincategory($config,$selected="",$selected_text='selected'){
    $cat = array();
    $query = "SELECT * FROM ".$config['db']['pre']."catagory_main";
    $query_result = mysqli_query(db_connect($config),$query);
    while($info = mysqli_fetch_assoc($query_result)){
        $cat[$info['cat_id']]['id'] = $info['cat_id'];
        $cat[$info['cat_id']]['name'] = $info['cat_name'];
        $cat[$info['cat_id']]['icon'] = $info['icon'];

        if($selected!="") {
            if($selected==$info['cat_id'] || $selected==$info['cat_name'])
            {
                $cat[$info['cat_id']]['selected'] = $selected_text;
            }
            else
            {
                $cat[$info['cat_id']]['selected'] = "";
            }
        }
    }


    return $cat;
}

function get_maincat_by_id($config,$id){
    $query = "SELECT * FROM ".$config['db']['pre']."catagory_main WHERE `cat_id` = '".$id."' LIMIT 1";
    $query_result = mysqli_query(db_connect($config),$query);
    $info = mysqli_fetch_assoc($query_result);
    return $info;
}

function get_subcat_by_id($config,$id){
    $query = "SELECT * FROM ".$config['db']['pre']."catagory_sub WHERE `sub_cat_id` = '".$id."' LIMIT 1";
    $query_result = mysqli_query(db_connect($config),$query);
    $info = mysqli_fetch_assoc($query_result);
    return $info;
}

function get_categories_dropdown($config){
    $dropdown = '<ul class="dropdown-menu category-change" id="category-change">
                          <li><a href="#" data-cat-type="all"><i class="fa fa-th"></i>All Categories</a></li>';

    $query1 = "SELECT * FROM ".$config['db']['pre']."catagory_main";
    $query_result1 = mysqli_query(db_connect($config),$query1);
    while ($info1 = mysqli_fetch_array($query_result1))
    {
        $cat_icon = $info1['icon'];
        $catname = $info1['cat_name'];
        $cat_id = $info1['cat_id'];

        $dropdown .= '<li><a href="#" data-ajax-id="'.$cat_id.'" data-cat-type="maincat"><i class="'.$cat_icon.'"></i>'.$catname.'</a><ul>';

        $query = "SELECT * FROM ".$config['db']['pre']."catagory_sub WHERE `main_cat_id` = '".$cat_id."'";
        $query_result = mysqli_query(db_connect($config),$query);
        while ($info = mysqli_fetch_array($query_result))
        {
            $subcat_name = $info['sub_cat_name'];
            $subcat_id = $info['sub_cat_id'];

            $dropdown .= '<li><a href="#" data-ajax-id="'.$subcat_id.'" data-cat-type="subcat">'.$subcat_name.'</a></li>';
        }

        $dropdown .= '</ul></li>';
    }

    $dropdown .= '</ul>';

    return $dropdown;
}

function get_categories($config,$con,$selected=array(),$selected_text='selected')
{

    $k = 1;
    $k2 = 2;
    $jobtypes = array();
    $jobtypes2 = array();
    $parents = array();

    $query = "SELECT * FROM ".$config['db']['pre']."catagory_sub ORDER BY main_cat_id";
    $query_result = mysqli_query($con,$query);
    while ($info = mysqli_fetch_array($query_result))
    {
        if(!isset($info['parent_id']))
        {
            $info['parent_id'] = 0;
        }
        else
        {
            if(isset($parents[$info['parent_id']]))
            {
                $parents[$info['parent_id']] = ($parents[$info['parent_id']]+1);
            }
            else
            {
                $parents[$info['parent_id']] = 1;
            }
        }

        if($info['main_cat_id'] == $k2)
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['sec'] = 'show';
            $k2++;
        }
        else
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['sec'] = $k2;
        }
        if($info['main_cat_id'] == $k)
        {

            $query1 = "SELECT * FROM ".$config['db']['pre']."catagory_main WHERE `cat_id` = '".$info['main_cat_id']."' LIMIT 1";
            $query_result1 = mysqli_query($con,$query1);
            while ($info1 = mysqli_fetch_array($query_result1))
            {
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['icon'] = $info1['icon'];
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['main_title'] = $info1['cat_name'];
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['main_id'] = $info1['cat_id'];
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['show'] = 'yes';
            }

            if($k == 1)
            {
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['select'] = 'show';
            }

            $k++;

        }
        else
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['show'] = 'no';
        }

        if($info['main_cat_id']++)
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['section'] = 'show';
        }
        else
        {
            $jobtypes[$info['parent_id']][$info['sub_cat_id']]['section'] = 'notshow';
        }

        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['title'] = stripslashes($info['sub_cat_name']);
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['id'] = $info['sub_cat_id'];
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['selected'] = '';
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['parent_id'] = $info['parent_id'];
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['catcount'] = 0;
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['counter'] = 0;
        $jobtypes[$info['parent_id']][$info['sub_cat_id']]['totalads'] = get_items_count($config,false,"active",$info['sub_cat_id']);
        foreach($selected as $select)
        {
            if($select==$info['sub_cat_id'])
            {
                $jobtypes[$info['parent_id']][$info['sub_cat_id']]['selected'] = $selected_text;
            }
        }
    }

    foreach($jobtypes as $key=>$value)
    {
        foreach($value as $key2=>$value2)
        {
            if(isset($parents[$key2]))
            {
                $jobtypes[$key][$key2]['catcount']  = $parents[$key2];
            }
        }
    }

    $counter = 1;

    foreach($jobtypes[0] as $key=>$value)
    {
        $value['counter'] = $counter;
        if($value['catcount'])
        {
            $value['ctype'] = 1;
        }
        else
        {
            $value['ctype'] = 0;
        }

        $jobtypes2[$key] =  $value;
        $counter++;

        if(isset($jobtypes[$key]))
        {
            foreach($jobtypes[$key] as $key2=>$value2)
            {
                $value2['counter'] = $counter;
                $value2['ctype'] = 2;

                $jobtypes2[$key2] =  $value2;

                $counter++;
            }
        }
    }

    return $jobtypes2;

}

function get_items($config,$userid=false,$status=null,$premium=false,$page=null,$limit=null,$sort="id",$location=false){
    $where = '';
    $item = array();
    if($userid){
        if($where == '')
            $where .= "where user_id = '".$userid."'";
        else
            $where .= " AND user_id = '".$userid."'";
    }
    if($status != null){
        if($where == '')
            $where .= "where status = '".$status."'";
        else
            $where .= " AND status = '".$status."'";
    }
    if($premium){
        if($where == '')
            $where .= "where (featured = '1' or urgent = '1' or highlight = '1')";
        else
            $where .= " AND (featured = '1' or urgent = '1' or highlight = '1')";
    }

    if($location){
        $sortname = check_user_country($config);
        $country_id = get_countryID_by_sortname($config,$sortname);
        if($where == '')
            $where .= "where country = '".$country_id."'";
        else
            $where .= " AND country = '".$country_id."'";
    }

    $pagelimit = "";
    if($page != null && $limit != null){
        $pagelimit = "LIMIT  ".($page-1)*$limit.",".$limit;
    }
    $query = "SELECT * FROM `".$config['db']['pre']."product` $where ORDER BY $sort DESC $pagelimit";
    $result = db_connect($config)->query($query);
    if (mysqli_num_rows($result) > 0) {
        while($info = mysqli_fetch_assoc($result)) {
            //$item[$info['id']]['product_name'] = strlimiter($info['product_name'],16);
            $item[$info['id']]['id'] = $info['id'];
            $item[$info['id']]['product_name'] = $info['product_name'];
            $item[$info['id']]['desc'] = strlimiter($info['description'],80);
            $item[$info['id']]['featured'] = $info['featured'];
            $item[$info['id']]['urgent'] = $info['urgent'];
            $item[$info['id']]['highlight'] = $info['highlight'];
            $item[$info['id']]['price'] = $info['price'];
            $item[$info['id']]['address'] = strlimiter($info['location'],20);
            $item[$info['id']]['location'] = get_cityName_by_id($config,$info['city']);
            $item[$info['id']]['city'] = get_cityName_by_id($config,$info['city']);
            $item[$info['id']]['state'] = get_stateName_by_id($config,$info['state']);
            $item[$info['id']]['country'] = get_countryName_by_id($config,$info['country']);
            $item[$info['id']]['status'] = $info['status'];
            $item[$info['id']]['created_at'] = timeago($info['created_at']);
            $item[$info['id']]['author_id'] = $info['user_id'];

            $item[$info['id']]['cat_id'] = $info['category'];
            $item[$info['id']]['sub_cat_id'] = $info['sub_category'];

            $get_main = get_maincat_by_id($config,$info['category']);
            $get_sub = get_subcat_by_id($config,$info['sub_category']);
            $item[$info['id']]['category'] = $get_main['cat_name'];
            $item[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];

            $item[$info['id']]['favorite'] = check_product_favorite($config,$info['id']);

            $tag = explode(',', $info['tag']);
            $tag2 = array();
            foreach ($tag as $val)
            {
                //REMOVE SPACE FROM $VALUE ----
                $val = preg_replace("/[\s_]/","-", trim($val));
                $tag2[] = '<li><a href="'.$config['site_url'].'listing/keywords/'.$val.'">'.$val.'</a> </li>';
            }
            $item[$info['id']]['tag'] = implode('  ', $tag2);

            $picture     =   explode(',' ,$info['screen_shot']);
            $picture     =   $picture[0];
            $item[$info['id']]['picture'] = $picture;



            $userinfo = get_user_data($config,null,$info['user_id']);

            $item[$info['id']]['username'] = $userinfo['username'];
            $author_url = preg_replace("/[\s_]/","-", $userinfo['username']);

            $item[$info['id']]['author_link'] = $config['site_url'].'profile/'.$author_url;

            $pro_url = preg_replace("/[\s_]/","-", $info['product_name']);
            $item[$info['id']]['link'] = $config['site_url'].'ad/' . $info['id'] . '/'.$pro_url.'/';

            $cat_url = preg_replace("/[\s_]/","-", $get_main['cat_name']);
            $item[$info['id']]['catlink'] = $config['site_url'].'listing/cat/'.$info['category'].'/'.$cat_url.'/';

            $subcat_url = preg_replace("/[\s_]/","-", $get_sub['sub_cat_name']);
            $item[$info['id']]['subcatlink'] = $config['site_url'].'listing/subcat/'.$info['sub_category'].'/'.$subcat_url.'/';

            $city = preg_replace("/[\s_]/","-", $item[$info['id']]['city']);
            $item[$info['id']]['citylink'] = $config['site_url'].'listing/city/'.$info['city'].'/'.$city.'/';

        }
    }
    else {
        //echo "0 results";
    }
    return $item;
}

function get_resubmited_items($config,$userid=false,$status=null,$page=null,$limit=null,$sort="id"){
    $where = '';
    $item = '';
    if($userid){
        if($where == '')
            $where .= "where user_id = '".$userid."'";
        else
            $where .= " AND user_id = '".$userid."'";
    }
    if($status != null){
        if($where == '')
            $where .= "where status = '".$status."'";
        else
            $where .= " AND status = '".$status."'";
    }

    $pagelimit = "";
    if($page != null && $limit != null){
        $pagelimit = "LIMIT  ".($page-1)*$limit.",".$limit;
    }
    $query = "SELECT * FROM `".$config['db']['pre']."product_resubmit` $where ORDER BY $sort DESC $pagelimit";
    $result = db_connect($config)->query($query);
    if (mysqli_num_rows($result) > 0) {
        while($info = mysqli_fetch_assoc($result)) {
            //$item[$info['id']]['product_name'] = strlimiter($info['product_name'],16);
            $item[$info['id']]['id'] = $info['id'];
            $item[$info['id']]['product_id'] = $info['product_id'];
            $item[$info['id']]['product_name'] = $info['product_name'];
            $item[$info['id']]['desc'] = strlimiter($info['description'],80);
            $item[$info['id']]['featured'] = $info['featured'];
            $item[$info['id']]['urgent'] = $info['urgent'];
            $item[$info['id']]['highlight'] = $info['highlight'];
            $item[$info['id']]['price'] = $info['price'];
            $item[$info['id']]['location'] = strlimiter($info['location'],20);
            $item[$info['id']]['city'] = $info['city'];
            $item[$info['id']]['state'] = $info['state'];
            $item[$info['id']]['country'] = $info['country'];
            $item[$info['id']]['status'] = $info['status'];
            $item[$info['id']]['created_at'] = timeago($info['created_at']);
            $item[$info['id']]['author_id'] = $info['user_id'];

            $item[$info['id']]['cat_id'] = $info['category'];
            $item[$info['id']]['sub_cat_id'] = $info['sub_category'];

            $get_main = get_maincat_by_id($config,$info['category']);
            $get_sub = get_subcat_by_id($config,$info['sub_category']);
            $item[$info['id']]['category'] = $get_main['cat_name'];
            $item[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];

            $item[$info['id']]['favorite'] = check_product_favorite($config,$info['id']);

            $tag = explode(',', $info['tag']);
            $tag2 = array();
            foreach ($tag as $val)
            {
                //REMOVE SPACE FROM $VALUE ----
                $val = trim($val);
                $tag2[] = '<li><a href="listing.php?keywords='.$val.'">'.$val.'</a> </li>';
            }
            $item[$info['id']]['tag'] = implode('  ', $tag2);

            $picture     =   explode(',' ,$info['screen_shot']);
            $picture     =   $picture[0];
            $item[$info['id']]['picture'] = $picture;

            $pro_url = preg_replace("/[\s_]/","-", $info['product_name']);

            if($config['mod_rewrite'] == 0)
                $item[$info['id']]['link'] = $config['site_url'].'ad-detail.php?id=' . $info['id'] . '/'.$pro_url.'/';
            else
                $item[$info['id']]['link'] = $config['site_url'].'ad/' . $info['id'] . '/'.$pro_url.'/';

            $userinfo = get_user_data($config,null,$info['user_id']);

            $item[$info['id']]['username'] = $userinfo['username'];
            $author_url = preg_replace("/[\s_]/","-", $userinfo['username']);

            $item[$info['id']]['author_link'] = $config['site_url'].'profile/'.$author_url;

            $pro_url = preg_replace("/[\s_]/","-", $info['product_name']);
            $item[$info['id']]['link'] = $config['site_url'].'ad/' . $info['id'] . '/'.$pro_url.'/';

            $cat_url = preg_replace("/[\s_]/","-", $get_main['cat_name']);
            $item[$info['id']]['catlink'] = $config['site_url'].'listing/cat/'.$info['category'].'/'.$cat_url.'/';

            $subcat_url = preg_replace("/[\s_]/","-", $get_sub['sub_cat_name']);
            $item[$info['id']]['subcatlink'] = $config['site_url'].'listing/subcat/'.$info['sub_category'].'/'.$subcat_url.'/';

            $city = $item[$info['id']]['city'];
            $item[$info['id']]['citylink'] = $config['site_url'].'listing/city/'.$info['city'].'/'.$city.'/';
        }
    }
    else {
        //echo "0 results";
    }
    return $item;
}

function get_items_count($config,$userid=false,$status=null,$premium=false,$getbysubcat=null,$getbymaincat=null,$location=false){
    $where = '';
    if($userid){
        if($where == '')
            $where .= "where user_id = '".$userid."'";
        else
            $where .= " AND user_id = '".$userid."'";
    }

    if($status != null){
        if($where == '')
            $where .= "where status = '".$status."'";
        else
            $where .= " AND status = '".$status."'";
    }

    if($premium){
        if($where == '')
            $where .= "where (featured = '1' or urgent = '1' or highlight = '1')";
        else
            $where .= " AND (featured = '1' or urgent = '1' or highlight = '1')";
    }

    if($getbysubcat != null){
        if($where == '')
            $where .= "where sub_category = '".$getbysubcat."'";
        else
            $where .= " AND sub_category = '".$getbysubcat."'";
    }

    if($getbymaincat != null){
        if($where == '')
            $where .= "where category = '".$getbymaincat."'";
        else
            $where .= " AND category = '".$getbymaincat."'";
    }

    if($location){
        $sortname = check_user_country($config);
        $country_id = get_countryID_by_sortname($config,$sortname);
        if($where == '')
            $where .= "where country = '".$country_id."'";
        else
            $where .= " AND country = '".$country_id."'";
    }

    $query = "SELECT 1 FROM `".$config['db']['pre']."product` $where ORDER BY id";
    $result = db_connect($config)->query($query);
    $item_count = mysqli_num_rows($result);
    return $item_count;
}

function resubmited_ads_count($config,$id){
    $query = "SELECT id FROM `".$config['db']['pre']."product_resubmit` where user_id = '".$id."'";
    $result = db_connect($config)->query($query);
    $num_rows = mysqli_num_rows($result);
    return $num_rows;
}

function myads_count($config,$id){
    $query = "SELECT id FROM ".$config['db']['pre']."product WHERE `user_id` = '".$id."'";
    $query_result = mysqli_query(db_connect($config),$query);
    $num_rows = mysqli_num_rows($query_result);
    return $num_rows;
}

function pending_ads_count($config,$id){
    $query = "SELECT id FROM ".$config['db']['pre']."product WHERE `user_id` = '".$id."' and status = 'pending'";
    $query_result = mysqli_query(db_connect($config),$query);
    $num_rows = mysqli_num_rows($query_result);
    return $num_rows;
}

function hidden_ads_count($config,$id){
    $query = "SELECT id FROM ".$config['db']['pre']."product WHERE `user_id` = '".$id."' and status = 'hide'";
    $query_result = mysqli_query(db_connect($config),$query);
    $num_rows = mysqli_num_rows($query_result);
    return $num_rows;
}

function favorite_ads_count($config,$id){
    $query = "SELECT id FROM ".$config['db']['pre']."favads WHERE `user_id` = '".$id."'";
    $query_result = mysqli_query(db_connect($config),$query);
    $num_rows = mysqli_num_rows($query_result);
    return $num_rows;
}

function update_itemview($product_id,$config)
{
    mysqli_query(db_connect($config), "UPDATE `".$config['db']['pre']."product` SET `view` = view+1 WHERE `id` = '".$product_id."' LIMIT 1 ;");

}

function timeAgo($timestamp){
    //$time_now = mktime(date('h')+0,date('i')+30,date('s'));
    $datetime1 = new DateTime("now");
    $datetime2 = date_create($timestamp);
    $diff=date_diff($datetime1, $datetime2);
    $timemsg='';
    if($diff->y > 0){
        $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');

    }
    else if($diff->m > 0){
        $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
    }
    else if($diff->d > 0){
        $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
    }
    else if($diff->h > 0){
        $timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
    }
    else if($diff->i > 0){
        $timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
    }
    else if($diff->s > 0){
        $timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
    }
    if($timemsg == "")
        $timemsg = "Just now";
    else
        $timemsg = $timemsg.' ago';

    return $timemsg;
}
?>