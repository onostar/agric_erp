<div id="cycles">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div class="displays allResults" id="crop_cycle" style="width:90%!important;margin:20px 50px!important">
    <h2>Active Crop Cycles</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)"> <a style="background:var(--tertiaryColor); color:#fff; padding:4px; margin:5px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('add_crop_cycle.php')" title="Add Crop Cycle">Add New Cycle <i class="fas fa-sync-alt"></i></a>
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Cycle ID</td>
                <td>Field</td>
                <!-- <td>Crop</td> -->
                <td>Area Used (Hec)</td>
                <td>Start Date</td>
                <td>Expected Harvest</td>
                <td>Expected Yield</td>
                <td>Created</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_condOrder('crop_cycles', 'cycle_status', 0, 'created_at');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>CY0<?php echo $detail->cycle_id?></td>
                <td style="color:var(--primaryColor)">
                    <?php 
                        $str = $get_details->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $str->field_name;
                    ?>
                </td>
                <!-- <td>
                    <?php 
                       /*  $str = $get_details->fetch_details_group('items', 'item_name', 'item_id', $detail->crop);
                        echo $str->item_name; */
                    ?>
                </td> -->
                <!-- <td><?php echo $detail->variety?></td> -->
                <td style="color:red"><?php echo $detail->area_used?></td>
                <td><?php echo date("d-M-Y", strtotime($detail->start_date))?></td>
                <td style="color:var(--tertiaryColor)">
                    <?php 
                        if($detail->expected_harvest == "0000-00-00" || $detail->expected_harvest == NULL){
                            echo "N/A";
                        }else{
                            echo date("d-M-Y", strtotime($detail->expected_harvest));
                        }
                    ?>
                </td>
                <td style="text-align:center"><?php echo $detail->expected_yield?></td>
                <!-- <td>
                    <?php
                        /* if($detail->cycle_status == 0){
                            echo "<span style='color:var(--moreColor)'>Ongoing <i class='fas fa-spinner'></i></span>";
                        }elseif($detail->cycle_status == -1){
                            echo "<span style='color:red'>Abandoned <i class='fas fa-close'></i></span>";
                        }else{
                            echo "<span style='color:green'>Completed <i class='fas fa-check'></i></span>";
                        } */
                    ?>
                </td> -->
                <td><?php echo date("d-m-Y", strtotime($detail->created_at))?></td>
                <td>
                    <a href="javascript:void(0)" title="view details" style="background:var(--tertiaryColor); color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('cycle_details.php?cycle=<?php echo $detail->cycle_id?>')">View <i class="fas fa-eye"></i></a>
                    <a href="javascript:void(0)" title="edit cycle details" style="color:#fff; background:var(--otherColor); padding:5px; font-size:.8rem; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('edit_crop_cycle.php?cycle=<?php echo $detail->cycle_id?>')"><i class="fas fa-edit"></i></a>
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
</div>