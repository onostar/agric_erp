<div id="cycles">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div class="displays allResults" id="crop_cycle" style="width:90%!important;margin:20px 50px!important">
    <h2>Employee Tax Rules</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchStaff" placeholder="Enter keyword" onkeyup="searchData(this.value)"> <a style="background:var(--tertiaryColor); color:#fff; padding:4px; margin:5px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('add_tax_rule.php')" title="Add Crop Cycle">Add New Tax Rule <i class="fas fa-clipboard"></i></a>
    </div>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Title</td>
                <td>Min. Income</td>
                <td>Max. Income</td>
                <td>Tax Rate</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_order('tax_rules', 'title');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--primaryColor)">
                    <?php 
                        echo $detail->title;
                    ?>
                </td>
                <td style="color:var(--otherColor)">
                    <?php 
                        echo "₦".number_format($detail->min_income, 2)
                    ?>
                </td>
                <td>
                    <?php 
                        echo "₦".number_format($detail->max_income, 2)
                    ?>
                </td>
                <td style="color:var(--tertiaryColor)"><?php echo $detail->tax_rate?>%</td>
                <td>
                    <a href="javascript:void(0)" title="edit tax rule" style="color:#fff; background:var(--otherColor); padding:5px; font-size:.8rem; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('edit_tax_rule.php?tax=<?php echo $detail->tax_id?>')"><i class="fas fa-edit"></i></a>
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
</div>