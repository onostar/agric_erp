<div id="kyc_details" style="margin-top:20px">

<?php
    session_start();
    // $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['notification'])){
            $notif = $_GET['notification'];
            //get kyc
            $get_notif = new selects();
            $rows = $get_notif->fetch_details_cond('notifications', 'notification_id', $notif);
            foreach($rows as $row){
                //update notification status
                $update = new Update_table();
                $update->update('notifications','not_status', 'notification_id', 1, $notif);

                
?>
<a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('notifications.php')"><i class="fas fa-close"></i> Close</a>
    <div id="patient_details">
        <h3 style="background:var(--menuColor)"><?php echo strtoupper($row->subject)." | ".date("jS M, Y, h:i:sa", strtotime($row->post_date))?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="consultation" style="height:auto;">
            
            <textarea style="width:100%; margin:auto;padding:20px; border:none;min-height:200px"readonly><?php echo $row->message?></textarea>
              
        </section>    
      
    </div>

<?php
            }
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>
