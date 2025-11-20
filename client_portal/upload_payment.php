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
    <h2>Upload payment receipts</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Field</td>
                <td>Field Size (Hec)</td>
                <td>Purchase Amount</td>
                <td>Amount Due</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_customer_due_fields($customer);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->field_name?></td>
                
                <td style="color:var(--otherColor)"><?php echo $detail->field_size?></td>
                <td><?php echo "₦".number_format($detail->total_due, 2)?></td>
                <td>
                    <?php
                        //get amount due from payment shedule
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0)" onclick="showPage('upload_receipt.php?assignment=<?php echo $detail->assign_id?>')" style="color:#fff; background:var(--primaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #cdcdcd; border-radius:15px;" title="upload payment receipt">Upload <i class="fas fa-upload"></i></a>
                    
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