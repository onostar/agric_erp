
<div class="displays allResults" id="disable_user">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    
    <h2>Resign/Retire  Employee</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchDisable" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="disable_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Full Name</td>
                <td>Staff ID</td>
                <td>Department</td>
                <td>Designation</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_negCond1('staffs', 'staff_status', 2);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                 <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->last_name ." ". $detail->other_names?></td>
                <td><?php echo $detail->staff_number?></td>
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
                        //get designation
                        $get_reg = new selects();
                        $rows = $get_reg->fetch_details_cond('designations', 'designation_id', $detail->designation);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->designation;
                            }
                        }
                        
                    ?>
                </td>
               
                <td>
                    <a style="padding:5px; border-radius:15px;background:var(--secondaryColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="resignStaff('<?php echo $detail->staff_id?>')" title="Suspend Staff">Resign <i class="fas fa-user-slash"></i></a>
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