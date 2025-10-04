<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_purchase = new selects();
    $details = $get_purchase->fetch_details_date2Con('crop_cycles', 'date(created_at)', $from, $to, 'farm', $store);
    $n = 1;  
?>
<h2>Crop Cycles Started between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchPurchase" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Purchase report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
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
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--primaryColor)">
                    <?php 
                        $str = $get_purchase->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $str->field_name;
                    ?>
                </td>
                <td>
                    <?php 
                        $str = $get_purchase->fetch_details_group('items', 'item_name', 'item_id', $detail->crop);
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
                <td><?php echo date("d-m-Y", strtotime($detail->created_at))?></td>
                <td><a href="javascript:void(0)" title="view details" style="background:var(--tertiaryColor); color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('cycle_report_details.php?cycle=<?php echo $detail->cycle_id?>')">View <i class="fas fa-eye"></i></a></td>
                
            </tr>
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
   
    
?>
