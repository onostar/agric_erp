<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_items = new selects();
    $details = $get_items->fetch_details_2dateCon('tasks', 'farm', 'date(post_date)', $from, $to,  $store);
    $n = 1;
?>
<h2>Tasks Done between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Task Report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Task</td>
                <td>Type</td>
                <td>Field</td>
                <td>Cost Incurred</td>
                <td>Post Date</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

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
            <?php $n++; }}?>
        </tbody>
    </table>
    <div class="all_modes">

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
            $itms = $get_cash->fetch_sum_2dateCond('task_items', 'total_cost', 'farm', 'date(post_date)', $from, $to, $store);
            if(is_array($itms)){
                foreach($itms as $itm){
                    $total_item_cost = $itm->total;
                }
            }else{
                $total_item_cost = 0;
            }
            // get total labour cost
            $cashs = $get_cash->fetch_sum_2dateCond('tasks', 'labour_cost', 'farm', 'date(post_date)', $from, $to, $store);
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