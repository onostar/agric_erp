<?php
    session_start();
    $store =$_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";

    $category = htmlspecialchars(stripslashes($_POST['dep_id']));
    
?>
<h2><strong><?php echo $category?></strong> stock balance</h2>
<hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', '<?php echo $category?> stock balance')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <!-- <td>Category</td> -->
                <td>Item name</td>
                <td>Quantity</td>
                <td>Unit cost</td>
                <td>Total cost</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_3cond1neg('inventory', 'quantity', 0, 'item_type', $category, 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
            <td style="text-align:center; color:red;"><?php echo $n?></td>
                
                <td style="color:var(--otherColor)"><?php 
                    //get item name
                    $get_name = new selects();
                    $name = $get_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                    echo strtoupper($name->item_name);
                ?></td>
                <td style="text-align:center;color:green"><?php echo $detail->quantity?></td>
                <td>
                    <?php 
                        //get cost price 
                        $get_cost = new selects();
                        $cost_prices = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $detail->item);
                        echo "₦".number_format($cost_prices->cost_price, 2);
                    ?>
                </td>
                <td>
                    <?php 
                        $total_cost = $cost_prices->cost_price * $detail->quantity;
                        echo "₦".number_format($total_cost, 2);
                    ?>
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
            
        }else{
            // get sum
            $get_total = new selects();
            $amounts = $get_total->fetch_sum_2col2Cond('inventory', 'cost_price', 'quantity', 'store', $store, 'item_type', $category);
            foreach($amounts as $amount){
                $total_amount = $amount->total;
            }
            // $total_worth = $total_amount * $total_qty;
            echo "<p class='total_amount'>$category worth: ₦".number_format($total_amount, 2)."</p>";
        }

    
    ?>