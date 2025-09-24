<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="staff_list" style="width:70%!important;margin:50px!important">
    <h2>Post Customer payments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Customer name</td>
                <td>Phone number</td>
                <!-- <td>Account balance</td> -->
                <!-- <td>Amount due</td> -->
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details('customers');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get customer accouunt balance from transactions
                    $get_balance = new selects();
                    $bals = $get_balance->fetch_account_balance($detail->acn);
                    if(gettype($bals) == 'array'){
                        foreach($bals as $bal){
                            $balance = $bal->balance;
                        }
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->customer?></td>
                <td><?php echo $detail->phone_numbers?></td>
                <?php /* if($detail->wallet_balance < 0){ */?>
                <!-- <td style="color:red">
                    <?php echo "₦".number_format(($balance), 2);?>
                </td> -->
                <?php /* }else{ */?>
                <!-- <td style="color:green">
                    <?php echo "₦".number_format($detail->wallet_balance, 2);?>
                </td> -->
                <?php /* } */?>
                <!-- <td style="color:red"><?php echo "₦".number_format($detail->amount_due, 2);?></td> -->
                <td><a style="padding:5px 8px;border-radius:5px;background:var(--otherColor); color:#fff;" href="javascript:void(0)" title="fund account" onclick="showPage('fund_account.php?customer=<?php echo $detail->customer_id?>')"><i class="fas fa-pen"></i></a></td>
                
                
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