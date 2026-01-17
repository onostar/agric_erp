<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    $user = $_SESSION['user_id'];


?>
<div id="pendingIssued" class="displays management" style="width:90%!important;margin:10px 20px!important">
<div class="displays allResults new_data" id="revenue_report">
    <h2>Issue Item Requests</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Pending issued items')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Request No.</td>
                <td>Requested by</td>
                <td>Department</td>
                <td>Total items</td>
                <td>Post Date</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2condGroup('issue_items', 'from_store', 'issue_status', $store, 1, 'invoice');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)"><?php echo $detail->invoice?></td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_users->fetch_details_group('staff_departments', 'department', 'department_id', $detail->department);
                        echo $checkedin_by->department;
                    ?>
                </td>
                <td style="color:green; text-align:Center">
                    <?php 
                        //get total items with that invoice
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_count_2cond('issue_items', 'invoice', $detail->invoice, 'issue_status', 1);
                        echo $sums;
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("jS M, Y", strtotime($detail->post_date));?></td>
                
                <td>
                    <a style="color:green; background:var(--otherColor); padding:5px; border-radius:5px; color:#fff" href="javascript:void(0)" title="View invoice details" onclick="showPage('view_requests.php?invoice=<?php echo $detail->invoice?>')">View <i class="fas fa-eye"></i></a>
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

<script src="../jquery.js"></script>
<script src="../script.js"></script>