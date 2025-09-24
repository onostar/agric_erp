<?php
session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
   

    //get user
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        //get user role
        $get_role = new selects();
        $roles = $get_role->fetch_details_group('users', 'user_role', 'username', $username);
        $role = $roles->user_role;

?>
    <div class="info"></div>
<div class="displays allResults" id="bar_items">
    <h2>Loans/Mortgage Received</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Loans Received')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Transaction Date</td>
                <td>Financier</td>
                <td>Contra Ledger</td>
                <td>Amount</td>
                <td>Post Date</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_cond('loans', 'trans_type', 'receiving');
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
                        $row = $get_loaner->fetch_details_group('ledgers', 'ledger', 'ledger_id', $detail->financier);
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
                <td style="color:red;"><?php echo "₦".number_format($detail->amount, 2)?></td>
                
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($detail->post_date))?></td>
              
                
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
<?php }?>