<?php
    session_start();
    $customer = $_SESSION['user_id'];
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
    <h2>Farm Fields</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Field</td>
                <td>Field Size (Plot)</td>
                <td>Soil Type</td>
                <td>Soil PH</td>
                <td>Topography</td>
                <!-- <td>Status</td> -->
                <td></td>
                <!-- <td>Created</td> -->
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_condorder('fields', 'customer',$customer, 'field_name');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->field_name?></td>
                
                <td style="color:var(--otherColor)"><?php echo $detail->field_size?> Plot (<?php echo $detail->field_size * 500?> m&sup2)</td>
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
                <!-- <td><?php echo date("d-m-Y", strtotime($detail->created_at))?></td> -->
                <td>
                    <a href="javascript:void(0)" onclick="window.open('../view/view_field_details.php?field=<?php echo $detail->field_id?>')" style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #cdcdcd; border-radius:15px;">View <i class="fas fa-eye"></i></a>
                    
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