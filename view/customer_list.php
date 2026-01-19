<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="staff_list" style="width:90%!important;margin:50px!important">
    <h2>Customer list</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Customer name</td>
                <td>Ledger No.</td>
                <td>Phone number</td>
                <td>Address</td>
                <td>Email</td>
                <td>Type</td>
                <td>Date reg</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details('customers');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->customer?></td>
                <td><?php echo $detail->acn?></td>
                <td><?php echo $detail->phone_numbers?></td>
                <td><?php echo $detail->customer_address?></td>
                <td><?php echo $detail->customer_email?></td>
                <?php /* if($detail->wallet_balance >= 0){ */ ?>
                <!-- <td style="color:green"><?php echo "₦".number_format($detail->wallet_balance, 2);?>
                </td> -->
                <?php /* }else{ */?>
                <!-- <td style="color:red"><?php echo "₦".number_format($detail->wallet_balance * (-1), 2);?> -->
                <td>
                    <?php 
                       /*  $fetch_bal = new selects();
                        $bals = $fetch_bal->fetch_account_balance($detail->acn);
                        foreach($bals as $bal){
                            $wallet_balance = $bal->balance;
                        }
                        echo "₦".number_format($wallet_balance, 2); */
                        echo $detail->customer_type;
                    ?>
                <?php /* } */?>
                </td>
                <td><?php echo date("d-m-Y", strtotime($detail->reg_date))?></td>
                
                
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