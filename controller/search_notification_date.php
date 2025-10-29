<?php
    session_start();
    // $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));
    $client = $_SESSION['user_id'];

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_date2Con('notifications', 'date(post_date)', $from, $to, 'client', $client);
    $n = 1;  
?>
<h2 style="font-size:1rem">Notifications between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Notifications')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Subject</td>
                <td>Date</td>
                <!-- <td></td> -->
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
            <tr>
                <td style="text-align:center; color:red;font-size:.8rem"><?php echo $n?></td>
                <?php
                    if($detail->not_status == 0){
                ?>
                <td><a style="font-size:.8rem;color:#222; font-weight:bold;" href="javascript:void(0)" onclick="showPage('view_notification.php?notification=<?php echo $detail->notification_id?>')" title="view notification details"><i class="fas fa-envelope"></i> <?php echo $detail->subject?></a></td>
                <?php }else{?>
                <td><a style="font-size:.8rem;color:#222;" href="javascript:void(0)" onclick="showPage('view_notification.php?notification=<?php echo $detail->notification_id?>')" title="view notification details"><i class="fas fa-envelope-open"></i> <?php echo $detail->subject?></a></td>
                <?php }?>
                <td style="color:var(--primaryColor); font-size:.8rem"><?php echo date("Y-M-d, h:i:sa", strtotime($detail->post_date))?></td>
                <!-- <td>
                    <a style="padding:5px; border-radius:15px;background:var(--tertiaryColor);color:#fff;"href="javascript:void(0)" onclick="showPage('view_notification.php?notification=<?php echo $detail->notification_id?>')" title="view patient details">view <i class="fas fa-eye"></i></a>
                </td> -->
                
            </tr>
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
   
?>
