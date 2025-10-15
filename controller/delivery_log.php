
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_GET['purchase_id'])){
        $id = $_GET['purchase_id'];
        $get_items = new selects();
        //get item
        $results = $get_items->fetch_details_group('accept_po', 'item', 'purchase_id', $id);
        //get item name
        $nms = $get_items->fetch_details_group('items', 'item_name', 'item_id', $results->item);
        $item_name = $nms->item_name;

?>


<div class="displays all_details">
    <!-- <div class="info"></div> -->
    
    <div class="guest_name">
        <h4>Delivery Log for  => <?php echo $item_name?> </h4>
        <div class="displays allResults" id="payment_det">
        <!-- <a style="color:#fff;background:brown;padding:5px; margin:5px;border-radius:15px; box-shadow:1px 1px 1px #222; float:right" href="javascript:void(0)" title="update invoice" onclick="showPage('../controller/update_invoices.php?invoice=<?php echo $invoice?>')">Update <i class="fas fa-pen"></i></a> -->
            <div class="payment_details">
                <table id="guest_payment_table" class="searchTable">
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Balance Qty</td>
                            <td>Qty Received</td>
                            <td>Accepted By</td>
                            <td>Date</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $rows = $get_items->fetch_details_cond('accept_po', 'purchase_id', $id);
                            foreach($rows as $row){
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td style="text-align:center;">
                                <?php 
                                    echo $row->balance_qty
                                ?>
                            </td>
                            <td style="text-align:center; color:green"><?php echo $row->supplied?></td>
                            <td>
                                <?php
                                    //accepted by
                                    $accept = $get_items->fetch_details_group('users', 'full_name', 'user_id', $row->accepted_by);
                                    echo $accept->full_name;
                                ?>
                            </td>
                            <td><?php echo date("d-m-Y, H:ia", strtotime($row->accept_date))?></td>
                        </tr>
                        
                        <?php $n++; }?>
                    </tbody>
                </table>
            </div>
                
            
    </div>
    
</div>
<?php
            }
        
   
?>