<?php
session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
   

    //get user
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        $store = $_SESSION['store_id'];
        //get user role
        $get_role = new selects();
        $roles = $get_role->fetch_details_group('users', 'user_role', 'username', $username);
        $role = $roles->user_role;

?>
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_other_transactions.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="bar_items">
    <h2>Other Daily Transactions</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Other Transactions')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Transaction Date</td>
                <td>Ledger</td>
                <td>Contra Ledger</td>
                <td>Type</td>
                <td>Amount</td>
                <td>Post Time</td>
                <td>Posted by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_curdateCon('other_transactions', 'date(post_date)', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:green">
                    <?php echo date("d-M-Y", strtotime($detail->trans_date))?>
                </td>
                <td>
                    <?php 
                        $get_loaner = new selects();
                        $row = $get_loaner->fetch_details_group('ledgers', 'ledger', 'ledger_id', $detail->ledger);
                        echo $row->ledger
                    ?>
                </td>
                <td>
                    <?php 
                        $get_loanee = new selects();
                        $rows = $get_loanee->fetch_details_group('ledgers', 'ledger', 'ledger_id', $detail->contra_ledger);
                        echo $rows->ledger
                    ?>
                </td>
                <td><?php echo $detail->trans_type?></td>
                <td style="color:red;"><?php echo "₦".number_format($detail->amount, 2)?></td>
                
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date))?></td>
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
    <div class="all_modes" style="display:none">
        <?php
             //get contribution
            $get_cash = new selects();
            $cashs = $get_cash->fetch_sum_curdateCon('other_transactions', 'amount', 'date(post_date)', 'store', $store);
            if(gettype($cashs) === "array"){
                foreach($cashs as $cash){
                ?>
                    <p class="sum_amount" style="background:var(--primaryColor)"><strong>Total </strong>: ₦ <?php echo number_format($cash->total, 2)?></p>

                <?php
                }
            }
        ?>
        
    </div>
</div>
<?php }?>