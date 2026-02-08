<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<style>
    table td{
        font-size:.7rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div id="revenueReport" class="displays management" style="margin:0!important;width:100%!important">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_mails.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Mail Reports for Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Mail reports')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
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
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon('sent_mails', 'date_sent', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get recipient name
                    $recipient_name = $get_users->fetch_details_cond('customers',  'customer_id', $detail->recipient);
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
                    <?php echo date("h:i:sa",strtotime($detail->date_sent))?>
                </td>
                
                <td>
                    <?php
                        //get done by
                        $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->sent_by);
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
       
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>