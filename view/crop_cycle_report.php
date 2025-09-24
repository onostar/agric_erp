<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    $store = $_SESSION['store_id'];

?>
<div id="purchaseReport" class="displays management" style="width:100%!important">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_crop_cycle.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data">
    <h2>Crop Cycles started today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Purchase report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Field</td>
                <td>Crop</td>
                <td>Crop Variety</td>
                <td>Area Used (Hec)</td>
                <td>Start Date</td>
                <td>Expected Harvest</td>
                <td>Status</td>
                <td>Created</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_curdateCon('crop_cycles', 'created_at', 'farm', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--primaryColor)">
                    <?php 
                        $str = $get_details->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $str->field_name;
                    ?>
                </td>
                <td>
                    <?php 
                        $str = $get_details->fetch_details_group('items', 'item_name', 'item_id', $detail->crop);
                        echo $str->item_name;
                    ?>
                </td>
                <td><?php echo $detail->variety?></td>
                <td style="color:red"><?php echo $detail->area_used?></td>
                <td><?php echo date("d-M-Y", strtotime($detail->start_date))?></td>
                <td style="color:var(--tertiaryColor)"><?php echo date("d-M-Y", strtotime($detail->expected_harvest))?></td>
                <td>
                    <?php
                        if($detail->cycle_status == 0){
                            echo "<span style='color:var(--moreColor)'>Ongoing <i class='fas fa-spinner'></i></span>";
                        }elseif($detail->cycle_status == -1){
                            echo "<span style='color:red'>Abandoned <i class='fas fa-close'></i></span>";
                        }else{
                            echo "<span style='color:green'>Completed <i class='fas fa-check'></i></span>";
                        }
                    ?>
                </td>
                <td><?php echo date("H:i:sa", strtotime($detail->created_at))?></td>
                <td><a href="javascript:void(0)" title="view details" style="background:var(--tertiaryColor); color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('cycle_details.php?cycle=<?php echo $detail->cycle_id?>')">View <i class="fas fa-eye"></i></a></td>
                
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

<script src="../jquery.js"></script>
<script src="../script.js"></script>