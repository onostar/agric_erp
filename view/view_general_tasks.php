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
    
<div class="displays allResults new_data" id="bar_items">
    <h2>Active Tasks</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Task Report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Task No.</td>
                <td>Task</td>
                <td>Field</td>
                <td>Started</td>
                <td>Date</td>
                <td>Posted by</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_3cond('tasks', 'task_status', 'task_type', 'farm', 0, 'General Maintenance', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        echo $detail->title
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php echo $detail->task_number?></td>
                <td>
                    <?php 
                        $rows = $get_items->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $rows->field_name
                    ?>
                </td>
                
                <td><?php echo date("d-M-Y, H:ia", strtotime($detail->start_date))?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y, h:ia", strtotime($detail->post_date))?></td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_items->fetch_details_group('users', 'full_name', 'user_id', $detail->done_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0)" title="view details" style="background:var(--tertiaryColor); color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('general_task_details.php?task=<?php echo $detail->task_id?>')">View <i class="fas fa-eye"></i></a>

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
 <?php
     }else{
        echo "<p class='no_result'>Session has expired. Please log in again</p>";
        exit();
     }
?>