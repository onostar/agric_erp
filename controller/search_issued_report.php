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
<h2>Items issued from <?php echo $store_name?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Item Issued report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="issued_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
            <td>S/N</td>
                <!-- <td>Invoice</td> -->
                <td>Item</td>
                <td>Qty</td>
                <!-- <td>Store</td> -->
                <td>Date</td>
                <td>Post time</td>
                <td>Posted by</td>
                <!-- <td></td> -->
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2date2Con('issue_items', 'date(post_date)', $from, $to,'from_store', $store,'issue_status', 1);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
            <td style="text-align:center; color:red;"><?php echo $n?></td>
                <!-- <td style="color:var(--otherColor)"><?php echo $detail->invoice?></td> -->
                <td>
                    <?php 
                        //get ITEM NAME
                        $get_name = new selects();
                        $name = $get_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $name->item_name;
                    ?>
                </td>
                <td style="color:green; text-align:Center"><?php echo $detail->quantity?></td>
                <!-- <td style="color:green;">
                    <?php 
                        //get store name
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_details_group('stores', 'store', 'store_id', $detail->to_store);
                        echo $sums->store;
                    ?>
                </td> -->
                
                <td style="color:var(--moreColor)"><?php echo date("H:ia", strtotime($detail->post_date));?></td>
                <td style="color:var(--otherColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
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