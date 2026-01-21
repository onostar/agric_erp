<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_date('customers', 'date(reg_date)', $from, $to);
    $n = 1;
?>
<h2>Clients created between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Client creation report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--labColor)">
		        <td>S/N</td>
                <td>Customer name</td>
                <td>Ledger No.</td>
                <td>Phone number</td>
                <td>Address</td>
                <td>Email</td>
                <td>Type</td>
                <td>Date reg</td>
                <td>Added By</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){
       
?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->customer?></td>
                <td><?php echo $detail->acn?></td>
                <td><?php echo $detail->phone_numbers?></td>
                <td><?php echo $detail->customer_address?></td>
                <td><?php echo $detail->customer_email?></td>
                
                <td>
                    <?php 
                      
                        echo $detail->customer_type;
                    ?>
                <?php /* } */?>
                </td>
                <td><?php echo date("d-M-Y", strtotime($detail->reg_date))?></td>
                <td>
                    <?php
                        //added by
                        $pst = $get_revenue->fetch_details_group('users', 'full_name','user_id', $detail->created_by);
                        echo $pst->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
    
?>
