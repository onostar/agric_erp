<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="staff_list" style="width:90%!important;margin:20px 50px!important">
    <h2>Farm Fields</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)"> <a style="background:brown; color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('add_field.php')" title="Add Farm Field">Add Field <i class="fas fa-seedling"></i></a>
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Field</td>
                <td>Farm</td>
                <td>Field Size (Hec)</td>
                <td>Soil Type</td>
                <td>Soil PH</td>
                <td>Topography</td>
                <td>Status</td>
                <td>Created</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details('fields');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->field_name?></td>
                <td style="color:var(--primaryColor)">
                    <?php 
                        //get store
                        $get_store = new selects();
                        $str = $get_store->fetch_details_group('stores', 'store', 'store_id', $detail->farm);
                        echo $str->store;
                    ?>
                </td>
                <td><?php echo $detail->field_size?></td>
                <td><?php echo $detail->soil_type?></td>
                <td><?php echo $detail->soil_ph?></td>
                <td><?php echo $detail->topography?></td>
                <td>
                    <?php
                        if($detail->field_status == 0){
                            echo "<span style='color:green'>Active</span>";
                        }else{
                            echo "<span style='color:red'>Inactive</span>";
                        }
                    ?>
                </td>
                <td><?php echo date("d-m-Y", strtotime($detail->created_at))?></td>
                
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    ?>
</div>