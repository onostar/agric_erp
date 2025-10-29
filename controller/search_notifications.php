<?php
    session_start();
    // $store = $_SESSION['store_id'];
    $client = $_SESSION['user_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get user
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        //get user role
        /* $get_role = new selects();
        $roles = $get_role->fetch_details_group('users', 'user_role', 'username', $username);
        $role = $roles->user_role; */
    }
    $n = 1;
    $get_item = new selects();
    $details = $get_item->fetch_details_like1Cond('notifications', 'subject', $item, 'client', $client);
     if(gettype($details) == 'array'){
        foreach($details as $detail):
           
    ?>
    <tr>
        <td style="text-align:center; color:red;font-size:.8rem"><?php echo $n?></td>
        <?php
            if($detail->not_status == 0){
        ?>
        <td><a style="font-size:.8rem; font-weight:bold; color:#222;" href="javascript:void(0)" onclick="showPage('view_notification.php?notification=<?php echo $detail->notification_id?>')" title="view notification details"><i class="fas fa-envelope"></i> <?php echo $detail->subject?></a></td>
        <?php }else{?>
        <td><a style="font-size:.8rem;color:#222;" href="javascript:void(0)" onclick="showPage('view_notification.php?notification=<?php echo $detail->notification_id?>')" title="view notification details"><i class="fas fa-envelope-open"></i> <?php echo $detail->subject?></a></td>
        <?php }?>
        <td style="color:var(--primaryColor); font-size:.8rem"><?php echo date("Y-M-d, h:i:sa", strtotime($detail->post_date))?></td>
        <!-- <td>
            <a style="padding:5px; border-radius:15px;background:var(--tertiaryColor);color:#fff;"href="javascript:void(0)" onclick="showPage('view_notification.php?notification=<?php echo $detail->notification_id?>')" title="view patient details">view <i class="fas fa-eye"></i></a>
        </td> -->
    </tr>
    
<?php
        $n++; endforeach;
            
     }else{
        echo "No resullt found";
     }
?>