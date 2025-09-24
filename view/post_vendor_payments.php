<div id="post_debt">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="staff_list" style="width:70%!important;margin:50px!important">
    <h2>Vendor Payables</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Vendor</td>
                <td>Amount due</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_payables();
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        $get_vendor = new selects();
                        $ven = $get_vendor->fetch_details_group('vendors', 'vendor', 'account_no', $detail->account);
                        echo $ven->vendor
                    ?>
                </td>
                <!-- <td><?php echo $detail->phone_numbers?></td> -->
                <td style="color:green">
                    <?php
                        
                        echo "₦".number_format($detail->total_due, 2);
                    ?>
                </td>
                <td class="prices">
                    <?php
                        $get_vendor = new selects();
                        $ven = $get_vendor->fetch_details_group('vendors', 'vendor_id', 'account_no', $detail->account);
                        $vendor_id = $ven->vendor_id;
                    ?>
                    <a style="background:var(--moreColor)!important; color:#fff!important; padding:5px 8px; border-radius:15px;" href="javascript:void(0)" class="each_prices" onclick="getForm('<?php echo $vendor_id?>', 'get_vendor_details.php');"><i class="fas fa-pen"></i> Post payment</a>
                    <a style="background:var(--tertiaryColor)!important; color:#fff!important; padding:5px 8px; border-radius:15px;" href="javascript:void(0)" class="each_prices" onclick="showPage('vendor_invoices.php?vendor=<?php echo $vendor_id?>');" title="view vendor invoices"><i class="fas fa-eye"></i> View Invoices</a>
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