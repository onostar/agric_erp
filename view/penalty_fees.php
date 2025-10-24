<div id="cycles">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div class="displays allResults" id="crop_cycle" style="width:60%!important;margin:20px 50px!important">
    <h2>Staff Penalty fees</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)"> <a style="background:var(--tertiaryColor); color:#fff; padding:4px; margin:5px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('add_penalty_fee.php')" title="Add Crop Cycle">Add New Penalty <i class="fas fa-clipboard"></i></a>
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Penalty</td>
                <td>Fee</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_order('penalty_fees', 'penalty');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        echo $detail->penalty;
                    ?>
                </td>
                <td style="color:var(--otherColor)">
                    <?php 
                        echo "₦".number_format($detail->amount, 2)
                    ?>
                </td>
                
                <td>
                    <a href="javascript:void(0)" title="edit tax rule" style="color:#fff; background:var(--otherColor); padding:5px; font-size:.8rem; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('edit_penalty.php?penalty=<?php echo $detail->penalty_id?>')"><i class="fas fa-edit"></i></a>
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