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
<h2>Consumables issued from <?php echo $store_name?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Item Issued report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="issued_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Request No.</td>
                <td>Requested By</td>
                <td>Department</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Requested On</td>
                <td>Issued time</td>
                <td>Issued by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2date2Con('issue_items', 'date(post_date)', $from, $to,'from_store', $store,'issue_status', 2);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
             <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)"><?php echo $detail->invoice?></td>
                <td>
                    <?php 
                        //get ITEM NAME
                        $staff = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $staff->full_name;
                    ?>
                </td>
                <td>
                    <?php 
                        //get department
                        $dep = $get_users->fetch_details_group('staff_departments', 'department', 'department_id', $detail->department);
                        echo $dep->department;
                    ?>
                </td>
                <td>
                    <?php 
                        //get ITEM NAME
                        $name = $get_users->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $name->item_name;
                    ?>
                </td>
                <td style="color:green; text-align:Center"><?php echo $detail->quantity?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y, H:ia", strtotime($detail->post_date));?></td>
                
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y, H:ia", strtotime($detail->date_issued));?></td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->issued_by);
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