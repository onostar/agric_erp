
<div id="edit_item_price">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];

?>

    <div class="info" style="width:100%; margin:0!important"></div>
    <div class="displays allResults" style="width:80%;">
        <h2>Manage Item prices for <?php echo $_SESSION['store'];?></h2>

        <hr>
        <div class="search">
            <input type="search" id="searchGuestPayment" placeholder="Enter keyword" onkeyup="searchData(this.value)">
            <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('priceTable', 'Item Price list')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
        </div>
        <table id="priceTable" class="searchTable">
            <thead>
                <tr style="background:var(--otherColor)">
                    <td>S/N</td>
                    <td>item</td>
                    <!-- <td>pack size</td> -->
                    <td>Cost Price (₦)</td>
                    <td>Sales Price (₦)</td>
                    <!-- <td>Non-Industrial Price (₦)</td> -->
                    <td></td>
                </tr>
            </thead>

            <tbody>
            <?php
                $n = 1;
                $select_cat = new selects();
                $rows = $select_cat->fetch_item_price($store);
                if(gettype($rows) == "array"){
                foreach($rows as $row):
            ?>
                <tr>
                    <td style="text-align:center;"><?php echo $n?></td>
                    
                    <td><?php echo $row->item_name?></td>
                   
                    <td>
                        <?php echo number_format($row->cost_price);?>
                    </td>
                    <td>
                        <?php echo number_format($row->sales_price);?>
                    </td>
                   <!--  <td>
                        <?php echo number_format($row->other_price);?>
                    </td> -->
                    
                    <td class="prices">
                        <a style="background:var(--moreColor)!important; color:#fff!important; padding:5px 8px; border-radius:5px;" href="javascript:void(0)" title="modify price" data-form="check<?php echo $row->item_id?>" class="each_prices" onclick="getForm('<?php echo $row->item_id?>', 'get_item_details.php');"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
            <?php $n++; endforeach; }?>

            </tbody>

        </table>
        
        <?php
            if(gettype($rows) == "string"){
                echo "<p class='no_result'>'$rows'</p>";
            }
        ?>
    </div>
    <?php 
    }else{
        echo "Yoursession has expired! PleaseLogin again";
    }
    ?>
</div>