<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    //get store name
    $get_store = new selects();
    $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $strs->store;

    
?>
<h2>Concentrate Production between <?php echo $store_name?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Concentrate Production report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Production No.</td>
                <td>Concentrate (Ltr)</td>
                <td>Pineapple Used (kg)</td>
                <td>Crown (kg)</td>
                <td>Peels (kg)</td>
                <td>Total Cost</td>
                <td>Post Date</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2dateCon('concentrate_production', 'store', 'date(date_produced)', $from, $to,  $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                    <td style="color:var(--otherColor)"><?php echo $detail->production_num?></td>
                    
                    <td style="color:green">
                        <?php
                            echo number_format($detail->concentrate, 2)
                        ?>
                    </td>
                   
                    <td style="color:var(--otherColor); text-align:center">
                        <?php 
                           echo number_format($detail->pineapple, 2)
                        ?>
                    </td>
                    <td style="color:var(--otherColor); text-align:center">
                        <?php 
                           echo number_format($detail->pineapple_crown, 2)
                        ?>
                    </td>
                    <td style="color:var(--otherColor); text-align:center">
                        <?php 
                           echo number_format($detail->pineapple_peel, 2)
                        ?>
                    </td>
                   
                    <td>
                        <?php
                            $total_cost = ($detail->total_pineapple_cost) - ($detail->total_crown_value + $detail->total_peel_value);
                            echo "₦".number_format($total_cost, 2);
                        ?>
                    </td>
                    <td style="color:var(--moreColor)"><?php echo date("H:ia", strtotime($detail->date_produced));?></td>
                    <td>
                        <?php
                            //get posted by
                            $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                            echo $checkedin_by->full_name;
                        ?>
                    </td>
                
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        $get_prds = new selects();
        //pineaplle cost
        $prds = $get_prds->fetch_sum_2dateCond('concentrate_production', 'total_pineapple_cost', 'store', 'date(date_produced)', $from, $to, $store);
        foreach($prds as $prd){
            $pineapple_cost = $prd->total;
        }
        
        //pineapple crown value
        $crws = $get_prds->fetch_sum_2dateCond('concentrate_production', 'total_crown_value', 'store', 'date(date_produced)', $from, $to, $store);
        foreach($crws as $crw){
            $crown_value = $crw->total;
        
        }
        //pineapple peel value
        $crws = $get_prds->fetch_sum_2dateCond('concentrate_production', 'total_peel_value', 'store', 'date(date_produced)', $from, $to, $store);
        foreach($crws as $crw){
            $peel_value = $crw->total;
        }
        
        $total_production = $pineapple_cost - ($peel_value + $crown_value);
        echo "<p class='total_amount' style='color:green; text-align:center;'>Total cost: ₦".number_format($total_production, 2)."</p>";
    ?>