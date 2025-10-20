<?php
session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
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
    $pages = $get_pages->fetch_count('staffs');
    $total_pages = ceil($pages/$limit);
    //get second to last page
    $second_to_last = $total_pages - 1;

    //get user
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        //get user role
        $get_role = new selects();
        $roles = $get_role->fetch_details_group('users', 'user_role', 'username', $username);
        $role = $roles->user_role;

?>
   
    
    <div class="info"></div>
<div class="displays allResults" id="bar_items">
    
    <h2>List of Staffs</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'List of Staffs')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Staff</td>
                <td>Staff ID</td>
                <td>Phone number</td>
                <td>Gender</td>
                <td>Department</td>
                <td>Designation</td>
                <td>Employed</td>
                <td>Status</td>
                <td></td>
            </tr>
        </thead>
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_negCond1('staffs', 'last_name', 'Administrator');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->last_name ." ". $detail->other_names?></td>
                <td><?php echo $detail->staff_number?></td>
                <td><?php echo $detail->phone?></td>
                
                <td><?php echo $detail->gender?></td>
                <td>
                    <?php
                        //get sponsor
                        $get_reg = new selects();
                        $rows = $get_reg->fetch_details_cond('staff_departments', 'department_id', $detail->department);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->department;
                            }
                        }
                        
                    ?>
                </td>
                <td>
                    <?php
                        //get sponsor
                        $get_reg = new selects();
                        $rows = $get_reg->fetch_details_cond('designations', 'designation_id', $detail->designation);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->designation;
                            }
                        }
                        
                    ?>
                </td>
                <td><?php echo date("d-m-Y", strtotime($detail->employed))?></td>
                <td>
                    <?php
                        if($detail->staff_status == 0){
                            echo "<span style='color:green'>Active</span>";
                        }elseif($detail->staff_status == 1){
                            echo "<span style='color:var(--secondaryColor)'>Suspended</span>";
                        }else{
                            echo "<span style='color:red'>Resigned</span>";
                        }
                    ?>
                </td>
                <td>
                    <a style="padding:5px; border-radius:15px;background:var(--tertiaryColor);color:#fff;"href="javascript:void(0)" onclick="showPage('view_staff_details.php?customer=<?php echo $detail->staff_id?>')" title="view staff details"><i class="fas fa-eye"></i></a>
                    <a style="padding:5px; border-radius:15px;background:var(--otherColor);color:#fff;"href="javascript:void(0)" onclick="showPage('edit_staff_details.php?customer=<?php echo $detail->staff_id?>')" title="Edit staff details"><i class="fas fa-edit"></i></a>
                </td>
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
                <li><a href="javascript:void(0)" onclick="showPage('staff_list.php?page=1')"title="Go to first page"><< First page</a></li>
                <li><a href="javascript:void(0)" onclick="showPage('staff_list.php?page=<?php echo $previous_page?>')"title="Go to previous page">< Previous</a></li>
            <?php
            }
                if($page_number < $total_pages){
                   
            ?>
                <li><a href="javascript:void(0)" onclick="showPage('staff_list.php?page=<?php echo $next_page?>')" title="Go to next page">Next ></a></li>
                <li><a href="javascript:void(0)" onclick="showPage('staff_list.php?page=<?php echo $total_pages?>')" title="Go to last page">Last Page >></a></li>
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