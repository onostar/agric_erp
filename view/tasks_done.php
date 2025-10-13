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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_task_report.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="bar_items">
    <h2>Tasks Done Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Task Report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Task</td>
                <td>Type</td>
                <td>Field</td>
                <td>Cost Incurred</td>
                <td>Started</td>
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_curdateCon('tasks', 'post_date', 'farm', $store);
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
                <td><?php echo $detail->task_type?></td>
                <td>
                    <?php 
                        $rows = $get_items->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $rows->field_name
                    ?>
                </td>
                <td style="color:red;">
                    <?php
                        //get total cost incurred
                        //get total of items used for the task
                        $itms = $get_items->fetch_sum_single('task_items', 'total_cost', 'task_id', $detail->task_id);
                        if(is_array($itms)){
                            foreach($itms as $itm){
                                $items_cost = $itm->total;
                            }
                        }else{
                            $items_cost = 0;
                        }
                        //get total cost of task done
                        $total_cost = $items_cost + $detail->labour_cost;
                        echo "₦".number_format($total_cost, 2)
                    ?>
                </td>
                <td><?php echo date("d-M-Y, H:ia", strtotime($detail->start_date))?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date))?></td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_items->fetch_details_group('users', 'full_name', 'user_id', $detail->done_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0)" title="view details" style="background:var(--tertiaryColor); color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('task_details.php?task=<?php echo $detail->task_id?>')">View <i class="fas fa-eye"></i></a>

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
    <div class="all_modes">
        <?php
            if(is_array($details)){
            //get total cost incurred today
            $get_cash = new selects();
            //get total of items used for the task
            $itms = $get_cash->fetch_sum_curdateCon('task_items', 'total_cost', 'post_date', 'farm', $store);
            if(is_array($itms)){
                foreach($itms as $itm){
                    $total_item_cost = $itm->total;
                }
            }else{
                $total_item_cost = 0;
            }
            // get total labour cost
            $cashs = $get_cash->fetch_sum_curdateCon('tasks', 'labour_cost', 'post_date', 'farm', $store);
            if(is_array($cashs)){
                foreach($cashs as $cash){
                    $labour = $cash->total;
                }
            }else{
                $labour = 0;
            }
            $overall_cost = $total_item_cost + $labour;
        ?>
            <p class="sum_amount" style="background:var(--otherColor); border-radius:15px; padding:5px;"><strong>Total Cost</strong>: ₦ <?php echo number_format($overall_cost, 2)?></p>
        <?php } ?>
       
        
    </div>
</div>
 <?php
     }else{
        echo "<p class='no_result'>Session has expired. Please log in again</p>";
        exit();
     }
?>