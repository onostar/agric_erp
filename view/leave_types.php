<div id="cycles">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div class="displays allResults" id="crop_cycle" style="width:90%!important;margin:20px 50px!important">
    <h2>Active Leave Types</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)"> <a style="background:var(--tertiaryColor); color:#fff; padding:4px; margin:5px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('add_leave_type.php')" title="Add Leave Type">Add Leave Type <i class="fas fa-plus-square"></i></a>
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Title</td>
                <td>Max. Days</td>
                <td>Description</td>
                <td>Status</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_order('leave_types', 'leave_title');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--primaryColor)">
                    <?php 
                        
                        echo $detail->leave_title;
                    ?>
                </td>
                <td style="text-align:center; color: green">
                    <?php 
                        echo $detail->max_days
                    ?>
                </td>
                <td><?php echo $detail->description?></td>
                <td>
                    <?php
                        if($detail->leave_status == 0){
                            echo "<span style='color:var(--tertiaryColor)'>Active <i class='fas fa-check-double'></i></span>";
                        }else{
                            echo "<span style='color:red'>Deactivated <i class='fas fa-ban'></i></span>";
                        }
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0)" title="edit leave details" style="color:#fff; background:var(--otherColor); padding:5px; font-size:.8rem; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('edit_leave_type.php?cycle=<?php echo $detail->leave_id?>')"><i class="fas fa-edit"></i></a>
                    <?php if($detail->leave_status == 0){?>
                    <a href="javascript:void(0)" title="Disable Leave" style="color:silver; font-size:1rem;" onclick="disableLeave('<?php echo $detail->leave_id?>')"><i class="fas fa-toggle-off"></i></a>
                    <?php }else{?>
                    <a href="javascript:void(0)" title="Activate Leave" style="color:green; font-size:1rem;" onclick="activateLeave('<?php echo $detail->leave_id?>')"><i class="fas fa-toggle-on"></i></a>
                    <?php }?>
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