<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2dateConOrder('sent_mails', 'store', 'date(date_sent)', $from, $to, $store, 'date_sent');
    $n = 1;
?>
<h2>Mails Sent Between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Mail report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
		        <td>S/N</td>
                <td>Recipient</td>
                <td>Subject</td>
                <td>Message Body</td>
                <td>Date Sent</td>
                <td>Sent By</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){
        //get recipient name
            $recipient_name = $get_revenue->fetch_details_cond('customers',  'customer_id', $detail->recipient);
            if($is_array = is_array($recipient_name)){
                foreach($recipient_name as $recipient){
                    $full_name = $recipient->full_name;
                }
            }else{
                $full_name = "Bulk Mail";
            }
?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $full_name?></td>
                <td><?php echo $detail->subject?></td>
                <td>
                    <?php
                        echo $detail->message;
                    ?>
                </td>
                
                <td>
                    <?php echo date("d-M-Y, h:ia",strtotime($detail->date_sent))?>
                </td>
                
                <td>
                    <?php
                        //get done by
                        $checkedin_by = $get_revenue->fetch_details_group('users', 'full_name', 'user_id', $detail->sent_by);
                        echo $checkedin_by->full_name;
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
