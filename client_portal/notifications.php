
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
        // $role = $roles->user_role;
        $client_id = $_SESSION['user_id'];
    //pagination
    if(!isset($_GET['page'])){
        $page_number = 1;
    }else{
        $page_number = $_GET['page'];
    }
    //set limit
    $limit = 50;
    //calculate offset
    $offset = ($page_number - 1) * $limit;
    $prev_page = $page_number - 1;
    $next_page = $page_number + 1;
    $adjacents = "2";
    //get number of pages
    $get_pages = new selects();
    $pages = $get_pages->fetch_count_cond('notifications', 'client', $client_id);
    $total_pages = ceil($pages/$limit);
    //get second to last page
    $second_to_last = $total_pages - 1;
    
    

?>
   <style>
    .not_available{
        width: 50%;
    }
    @media screen and (max-width: 800px){
        table td{
            padding: 4px!important;
            font-size:.8rem!important;
        }
        table td a, table td a i{
            font-size:.7rem!important;
            padding:4px!important;
        }
    }
</style> 
    <div class="select_date" style="width:100%; margin: 10px 50px;">
        <!-- <form method="POST"> -->
        <section style="width:100%">    
            <div class="from_to_date" style="width:15%">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date" style="width:15%">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_notification_date.php')" style="width:15%">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="bar_items">
    <h2>Notifications</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchItems(this.value, 'search_notifications.php')">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'My Notifications')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Subject</td>
                <td>Date</td>
                <!-- <td></td> -->
            </tr>
        </thead>
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_pageCondOrder('notifications', 'client', $client_id, $limit, $offset, 'post_date');
                if(gettype($details) === 'array'){
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
                    <a style="padding:5px; border-radius:15px;background:var(--tertiaryColor);color:#fff;" href="javascript:void(0)" onclick="showPage('view_notification.php?notification=<?php echo $detail->notification_id?>')" title="view patient details">view <i class="fas fa-eye"></i></a>
                </td> -->
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    <div class="page_links">
        <?php
            if(gettype($details) == "array"){
                echo "<p><strong>Pages ".$page_number." of ".$total_pages."</strong></p>";
        ?>
        <ul class="pages">
            <?php
                if($page_number > 1){
            ?>
                <li><a href="javascript:void(0)" onclick="showPage('notifications.php?page=1')"title="Go to first page"><< First page</a></li>
                <li><a href="javascript:void(0)" onclick="showPage('notifications.php?page=<?php echo $previous_page?>')"title="Go to previous page">< Previous</a></li>
            <?php
            }
                if($page_number < $total_pages){
                   
            ?>
                <li><a href="javascript:void(0)" onclick="showPage('notifications.php?page=<?php echo $next_page?>')" title="Go to next page">Next ></a></li>
                <li><a href="javascript:void(0)" onclick="showPage('notifications.php?page=<?php echo $total_pages?>')" title="Go to last page">Last Page >></a></li>
                <?php }?>
        </ul>
        <?php }?>
    </div>
    <?php
        
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    
    ?>
</div>
<?php }?>