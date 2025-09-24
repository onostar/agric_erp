<div id="edit_item_price">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['success'])){
        echo $_SESSION['success'];
    }

?>

    <div class="info" style="width:80%; margin:0!important"></div>
    <div class="displays allResults" style="width:80%;">
        <h2>Manage Item prices</h2>
        <hr>
        <div class="search">
            <input type="search" id="searchGuestPayment" placeholder="Enter keyword" onkeyup="searchData(this.value)">
            <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('priceTable', 'Item Price list')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
        </div>
        <table id="priceTable" class="searchTable">
            <thead>
                <tr style="background:var(--otherColor)">
                    <td>S/N</td>
                    <td>Category</td>
                    <td>item</td>
                    <!-- <td>pack size</td> -->
                    <td>Cost Price (₦)</td>
                    <!-- <td>Distributors (₦)</td> -->
                    <!-- <td>Retail pack (₦)</td> -->
                    <td>Sales Price (₦)</td>
                    <!-- <td>Wholesale Pack (₦)</td> -->
                    <td></td>
                </tr>
            </thead>

            <tbody>
            <?php
                $n = 1;
                $select_cat = new selects();
                $rows = $select_cat->fetch_details('items');
                if(gettype($rows) == "array"){
                foreach($rows as $row):
            ?>
                <tr>
                    <td style="text-align:center;"><?php echo $n?></td>
                    
                    <td>
                        <?php 
                             //get category
                             $get_cat = new selects();
                             $detail = $get_cat->fetch_details_group('categories', 'category', 'category_id', $row->category);
                             echo $detail->category;
                        ?>
                    </td>
                    <td><?php echo $row->item_name?></td>
                    <!-- <td style="color:otherColor; text-align:center"><?php echo $row->pack_size?></td>-->
                    <td>
                        <?php echo number_format($row->cost_price);?>
                    </td>
                    <td>
                        <?php echo number_format($row->sales_price);?>
                    </td>
                    <!-- <td>
                        <?php echo number_format($row->pack_price);?>
                    </td>
                    <td>
                        <?php echo number_format($row->wholesale);?>
                    </td>
                    <td>
                        <?php echo number_format($row->wholesale_pack);?>
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
</div>