
<?php



$folder_name = "sfullfillment/storage";


$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage order by display_order desc,storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);

if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}



$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name,memo from product order by product_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);
$total_cnt = $sel_num;

if ($sel_num > 0) {
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_arr,$data);
    }
}




for ($i=0;$i<count($storage_arr);$i++ )
{


    for ($k=0;$k<count($product_arr);$k++ )
    {

        $sel = mysqli_query($dbcon, "select * from in_out where storage_idx='".$storage_arr[$i]['storage_idx']."'
        and product_idx='".$product_arr[$i]['product_idx']."' order by io_idx
        ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
         //$data = mysqli_fetch_assoc($sel);
            
            $snum = 0;
            $last_num = 0;
            while($data = mysqli_fetch_assoc($sel)) {


                $before_count = "";
                if($data['io_status'] == "미입고"){
                    $before_count = $data['current_count'];
                }else{
                    if($data['in_count'] > 0){
                        $before_count = $data['current_count'] - $data['in_count'];
                    }else if($data['out_count'] > 0){
                        $before_count = $data['current_count'] + $data['out_count'];

                    }else{
                        $before_count = $data['current_count'];
                    }
                
                }


                if($snum > 0){
                    if($before_count != $last_count){?>
                        <p style='color:red;'><?=print_r($data)?></p>
                    <?}

                }

                $last_count = $data['current_count'];

                $snum++;
            }
        }

    }
}

        

?>