<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<style>
    table td{
        font-size:.7rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div class="displays allResults" id="farm_fields" style="width:90%!important;margin:20px 50px!important">
    <h2>Lands/Fields Assigned to Customers</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <!-- <a style="background:brown; color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('add_field.php')" title="Add Farm Field">Add Field <i class="fas fa-seedling"></i></a> -->
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Land</td>
                <td>Owned By</td>
                <td>Land Size (Plot)</td>
                <td>Soil Type</td>
                <td>Soil PH</td>
                <td>Topography</td>
                <td>Location</td>
                <td>Status</td>
                <td>Date</td>
                <td></td>
                <!-- <td>Created</td> -->
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_order('assigned_fields', 'assigned_date');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get field name
                    $names = $get_details->fetch_details_cond('fields', 'field_id', $detail->field);
                    foreach($names as $name){
                        $field_name = $name->field_name;
                        $location = $name->location;
                        $soil_type = $name->soil_type;
                        $soil_ph = $name->soil_ph;
                        $topography = $name->topography;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $field_name?></td>
                <td style="color:var(--primaryColor)">
                    <?php 
                        //get customer
                        $strs = $get_details->fetch_details_cond('customers', 'customer_id', $detail->customer);
                        if(is_array($strs)){
                            foreach($strs as $str){
                                $customer = $str->customer;
                            }
                        }else{
                            $customer = "Not Assigned";
                        }
                        echo $customer;
                    ?>
                </td>
                <td>
                    <?php 
                        //convert to square meters
                        $sqm = $detail->field_size * 500;
                        echo $detail->field_size." Plot (".number_format($sqm)." m&sup2;)";
                    ?>
                </td>
                <td><?php echo $soil_type?></td>
                <td><?php echo $soil_ph?></td>
                <td><?php echo $topography?></td>
                <td><?php echo $detail->location?></td>
                <td>
                    <?php
                        if($detail->contract_status == 1){
                            echo "<span style='color:blue'>Pending</span>";
                        }else{
                            echo "<span style='color:green'>Active</span>";
                        }
                    ?>
                </td>
                <td><?php echo date("d-m-Y", strtotime($detail->assigned_date))?></td>
                <td>
                    <a href="javascript:void(0)" onclick="window.open('view_field_details.php?assigned=<?php echo $detail->assigned_id?>')" style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #cdcdcd; border-radius:15px;"><i class="fas fa-eye"></i></a>
                    <a href="javascript:void(0)" onclick="showPage('view_field.php?field=<?php echo $detail->assigned_id?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #cdcdcd; border-radius:15px;"><i class="fas fa-edit"></i></a>
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