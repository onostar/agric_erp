<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get store name
    $get_store = new selects();
    $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $strs->store;

?>
<div id="transfer_report" class="displays management" style="width:80%!important;">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_issued_report.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Items issued from <?php echo $store_name?> Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Issued items report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <!-- <td>Invoice</td> -->
                <td>Item</td>
                <td>Qty</td>
                <!-- <td>Store</td> -->
                <td>Post time</td>
                <td>Posted by</td>
                <!-- <td></td> -->
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_date2Cond('issue_items', 'date(post_date)', 'from_store', $store, 'issue_status', 1);
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
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <!-- <td>
                    <a style="color:green; background:var(--otherColor); padding:5px; border-radius:5px; color:#fff" href="javascript:void(0)" title="View invoice details" onclick="showPage('transfer_details.php?invoice=<?php echo $detail->invoice?>')"> <i class="fas fa-eye"></i> View</a>
                </td> -->
                
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

<script src="../jquery.js"></script>
<script src="../script.js"></script>