<div class="displays allResults" id="attendance" style="margin:10px 50px!important; width:90%!important">
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
        $role = $roles->user_role;
        $store = $_SESSION['store_id'];
?>
   
    
    <div class="info" style="margin: 10px!important"></div>
    <!-- showing staffs that are not resigned in selected store -->
    <h2>Check out Staff for the Day</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Staffs present on <?php echo date('d-M-Y')?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Staff</td>
                <td>Staff ID</td>
                <td>Gender</td>
                <td>Department</td>
                <td>Designation</td>
                <td>Checked in</td>
                <td></td>
            </tr>
        </thead>
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_staff_checkout($store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->last_name ." ". $detail->other_names?></td>
                <td><?php echo $detail->staff_number?></td>
                
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
                <td><?php echo date("h:ia", strtotime($detail->time_in))?></td>
                <td>
                    <a style="padding:5px 8px; border-radius:5px;background:var(--tertiaryColor); color:#fff;" href="javascript:void(0)" onclick="getForm('<?php echo $detail->attendance_id?>', 'get_checkout_time.php')" title="check out"><i class="fas fa-check-square"></i></a>
                    
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
<?php 
    }else{
        echo "Your session has expired! Please login again to continue";
    }    
?>
</div>