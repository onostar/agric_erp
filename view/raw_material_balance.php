<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    $role = $_SESSION['role'];

?>
    <div class="info"></div>
    <div class="change_dashboard" style="margin: 0 50px">
        <!-- filter stock balance by inventory -->
        <!-- <form method="POST"> -->
        <section>
            <label>Filter Category</label><br>
            <select name="dep_id" id="dep_id" required onchange="filterStockBalance(this.value)">
                <option value="">Select Category</option>
                <option value="Farm Input">Farm Input</option>
                <option value="Product">Product</option>
                <!-- <option value="Livestock">Livestock</option> -->
                <option value="Consumable">Consumable</option>
                
            </select>
        </section>
    </div>
<div class="displays allResults" id="all_balance">
    <h2>All Stock balances</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Stock balance')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Category</td>
                <td>Item name</td>
                <td>Quantity</td>
                <td>Unit cost</td>
                <td>Total cost</td>
                <!-- <td>Expiry date</td> -->
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                // $details = $get_items->fetch_details_negCond('inventory', 'quantity', '0', 'store', $store);
                $details = $get_items->fetch_inventory_balance( $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        //get item category first
                        
                        $cat_name = $get_items->fetch_details_group('departments', 'department', 'department_id', $detail->department);
                        echo $cat_name->department;
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php 
                    //get item name
                    
                    echo $detail->item_name;
                ?></td>
                <td style="text-align:center;color:green"><?php echo $detail->quantity?></td>
                <td>
                    <?php 
                        //get cost price 
                       /*  $cost_prices = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $detail->item); */
                        echo "₦".number_format($detail->cost_price, 2);
                    ?>
                </td>
                <td>
                    <?php 
                        $total_cost = $detail->cost_price * $detail->quantity;
                        echo "₦".number_format($total_cost, 2);
                    ?>
                </td>
                <!-- <td>
                    <?php
                        echo date("d-m-Y", strtotime($detail->expiration_date));
                    ?>
                </td> -->
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
            
        }
        if($role == "Admin"){
            // get sum
            $get_total = new selects();
            $amounts = $get_total->fetch_sum_2colCond('inventory', 'cost_price', 'quantity', 'store', $store);
            foreach($amounts as $amount){
                $total_amount = $amount->total;
            }
            // $total_worth = $total_amount * $total_qty;
            echo "<p class='total_amount'>Store worth: ₦".number_format($total_amount, 2)."</p>";
        }

        
    ?>
</div>