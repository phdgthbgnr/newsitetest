<?php
require_once('../../../../wp-load.php');

if($_POST)
{
    if(isset($_POST['cid']))
    {
        // $ret=substr($_POST['cid'],2);
        $ret=$_POST['cid'];
        $ret=intval($ret);
        if(is_numeric($ret))
        {
            global $wpdb;
            $table=$wpdb->prefix.'tech';
            $res=$wpdb->delete($table,array('id'=>$ret));
            echo json_encode($res);

        }
    }
}

?>