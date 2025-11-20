<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<style>
    table td{
        font-size:.75rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div class="displays allResults" id="farm_fields" style="width:90%!important;margin:20px 50px!important">
    <h2>Assign Farm Field to Client</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Field</td>
                <td>Location</td>
                <td>Field Size (Hec)</td>
                <td>Soil Type</td>
                <td>Soil PH</td>
                <td>Topography</td>
                <td></td>
                <!-- <td>Created</td> -->
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_fields();
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->field_name?></td>
                <td><?php echo $detail->location?></td>
               <!--  <td style="color:var(--primaryColor)">
                    <?php 
                        //get customer
                        /* $strs = $get_details->fetch_details_cond('customers', 'customer_id', $detail->customer);
                        if(is_array($strs)){
                            foreach($strs as $str){
                                $customer = $str->customer;
                            }
                        }else{
                            $customer = "Not Assigned";
                        }
                        echo $customer; */
                    ?>
                </td> -->
                <td>
                    <?php 
                        //convert to square meters
                        $sqm = $detail->field_size * 7500;
                        echo $detail->field_size." (".number_format($sqm)." m&sup2;)";
                    ?>
                </td>
                <td><?php echo $detail->soil_type?></td>
                <td><?php echo $detail->soil_ph?></td>
                <td><?php echo $detail->topography?></td>
                <!-- <td>
                    <?php
                        /* if($detail->field_status == 0){
                            echo "<span style='color:green'>Available</span>";
                        }else{
                            echo "<span style='color:red'>Unavailable</span>";
                        } */
                    ?>
                </td> -->
                <td>
                    <a href="javascript:void(0)" onclick="showPage('assign_farm_field.php?field=<?php echo $detail->field_id?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #cdcdcd; border-radius:15px;">Assign <i class="fas fa-user-tag"></i></a>
                    
                </td>
                
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