<style>
    .sales_receipt{
    padding:10px;
}
.sales_receipt h2{
    font-size:.9rem;
}
.sales_receipt h2, .sales_receipt p{
    text-align:center;
    font-size:.8rem;
    padding:0;
    margin:0;
}
.receipt_head{
    margin:5px;
}
.receipt_head{
    display:flex;
    justify-content: space-between;
    align-items: center;
    gap:.5rem;
    margin:2px 0;
    border-bottom: 3px solid #424141ff;
}
.headers{
    display:flex;
    align-items: center;
    justify-content: left;
    gap:.3rem;
}
.headers img{
    width:90px;
    height:80px;
}
.headers h2{
    font-size: 1.2rem;
    margin:0;
    padding:0;
}
.headers p{
    font-size: .9rem;
    margin:0;
    padding:0;
}
.po h3, .po p{
    margin:0;
    padding:0;
}
.vendor_delivery{
    display:flex;
    align-items: flex-start;
    justify-content: space-between;
    border-bottom: 3px solid #424141ff;
    padding:10px 0;
}
.vendor_details{
    width:35%;
}
.vendor_details h3{
    background:rgb(15, 140, 161);
    color:#fff;
    margin:5px 0 0 0;
    padding:4px;
    font-size:.8rem;
    text-transform: uppercase;
}
.vendor_details p{
    margin:2px;
    padding:0 5px 0 0;
}
.sales_receipt .total_amount{
    text-align: right;
    font-size:.8rem;
    margin:5px 0;
}

.sales_receipt .sold_by{
    text-align: left;
    font-size:.9rem;
    padding:4px;
}
.sales_receipt table{
    width:100%!important;
    margin:10px auto!important;
    box-shadow:none;
    border:1px solid #222;
    border-collapse: collapse;
}
.sales_receipt table thead tr td{
    font-size:.8rem;
    padding:2px;

}
.sales_receipt table td{
    border:1px solid #222;
    padding:2px;
}
.item_categories{
    padding:20px;
}
</style>
<?php
    
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
    session_start();
    if(isset($_GET['receipt'])){
        $user = $_SESSION['user_id'];
        $invoice = $_GET['receipt'];
        
        $get_items = new selects();
        //get store address
        $strs = $get_items->fetch_details_cond('stores', 'store_id', $_SESSION['store_id']);
        foreach($strs as $str){
            $store = $str->store;
            $store_address = $str->store_address;
            $phone_num = $str->phone_number;
        }
        //get po details
        $rows = $get_items->fetch_details_cond('purchase_order', 'invoice', $invoice);
        foreach($rows as $row){
            $date = $row->post_date;
            $vendor = $row->vendor;
        }
        //get vendor name
        $vends = $get_items->fetch_details_cond('vendors', 'vendor_id', $vendor);
        foreach($vends as $vend){
            $vendor_name = $vend->vendor;
            $contact = $vend->contact_person;
            $phone = $vend->phone;
            $address = $vend->biz_address;
        }
?>
<div class="receipt_head">
    <div class="headers">
        <img src="../images/<?php echo $_SESSION['company_logo']?>" alt="company logo">
        <div class="comp">
            <h2><?php echo $_SESSION['company'];?></h2>
            <p><?php echo $store_address?></p>
        </div>
    </div>
    <div class="po">
        <h3>PURCHASE ORDER</h3>
        <p><strong>PO Number:</strong> <?php echo $invoice?></p>
        <p><strong>Date:</strong> <?php echo date("d-M-Y", strtotime($date))?></p>
    </div>
</div>
<div class="vendor_delivery">
    <div class="vendor_details">
        <h3>Vendor Details</h3>
        <p><strong>Supplier Name: </strong><?php echo $vendor_name?></p>
        <p><strong>Contact Person: </strong><?php echo $contact?></p>
        <p><strong>Phone No.: </strong><?php echo $phone?></p>
        <p><strong>Address: </strong><?php echo $address?></p>
    </div>
    <div class="vendor_details">
        <h3>Delivery Details</h3>
        <p>Deliver to <?php echo $store?></p>
        <p><strong>Address: </strong><?php echo $store_address?></p>
        <p><strong>Phone No.: </strong><?php echo $phone_num?></p>
    </div>
</div>
<div class="displays allResults sales_receipt">
    
    <!-- <h4 style="background:rgb(15, 140, 161); padding:5px; color:#fff;">Items Details</h4> -->
    <table id="postsales_table" class="searchTable">
        <thead>
            <tr style="background:rgb(15, 140, 161); color:#fff;">
                <td>S/N</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Rate</td>
                <td>Total</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $details = $get_items->fetch_details_cond('purchase_order', 'invoice', $invoice);
                if(is_array($details)){
                foreach($details as $detail):
            ?>
            <tr style="font-size:.9rem">
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        //get category name
                        $item_name = $get_items->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $item_name->item_name;
                    ?>
                </td>
                <td style="text-align:center; color:red;"><?php echo $detail->quantity?>
                    
                </td>
                <td>
                    <?php
                        //get item price
                       echo $detail->cost_price;
                    ?>
                </td>
                <td>
                    <?php
                       
                        echo number_format($detail->quantity * $detail->cost_price, 2);
                    ?>
                </td>
                
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        
        // get sum;
        $amounts = $get_items->fetch_sum_con('purchase_order', 'cost_price', 'quantity', 'invoice', $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        echo "<p class='total_amount' style='color:green'>Total amount: ₦".number_format($total_amount, 2)."</p>";

        echo "<p class='sold_by'><strong>Delivery Date:</strong> Within 7 working days of order confirmation</p>
        <p class='sold_by'><strong>Mode of Delivery:</strong> Vendor-arranged courier</p>";
        //prepared by
        $row = $get_items->fetch_details_group('users', 'full_name', 'user_id', $user);
        echo ucwords("<p class='sold_by'>Prepared by: <strong>$row->full_name</strong></p>");
        echo "<p class='sold_by'><strong>Approved By:</strong> ..........................................</p>
        <p class='sold_by'><strong>Signature:</strong> .........................................</p>
        <p class='sold_by'><strong>Date:</strong> ..........................................</p>";
    ?>
    <p style="margin-top:20px;text-align:center"><strong>Thank you for your prompt attention to this order.<br>For inquiries, please contact procurement@davidorlah.com!</strong></p>
</div> 
   
<?php
    echo "<script>window.print();
    window.close();</script>";
                    // }
                }
            // }
        
    // }
?>