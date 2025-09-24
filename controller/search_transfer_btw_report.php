<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    //get store name
    $get_store = new selects();
    $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $strs->store;

    
?>
<h2>Quantity Transfered between items from <?php echo $store_name?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Item Transfer report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
            <td>S/N</td>
                <!-- <td>Invoice</td> -->
                <td>Transferred from</td>
                <td>Qty</td>
                <td>Transferred to</td>
                <td>Qty received</td>
                <!-- <td>Store</td> -->
                <td>Date</td>
                <td>Time</td>
                <td>Posted by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2dateCon('item_transfers', 'store', 'date(post_date)', $from, $to, $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
            <td style="text-align:center; color:red;"><?php echo $n?></td>
                
                <td style="color:green; text-align:Center">
                    <?php 
                        //get item name
                        $get_item = new selects();
                        $names = $get_item->fetch_details_group('items', 'item_name', 'item_id', $detail->item_from);
                        echo $names->item_name;
                    ?>
                </td>
                <td style="color:green;">
                    <?php 
                        echo $detail->removed_qty;
                    ?>
                </td>
                <td style="color:green; text-align:Center">
                    <?php 
                        //get item name
                        $get_item = new selects();
                        $names = $get_item->fetch_details_group('items', 'item_name', 'item_id', $detail->item_to);
                        echo $names->item_name;
                    ?>
                </td>
                <td><?php echo $detail->added_qty?></td>
                
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:ia", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
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
    ?>