<?php
session_start();
    $store = $_SESSION['store'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['invoice'])){
        $invoice = $_GET['invoice'];
    
?>
<div class="displays allResults" id="stocked_items" style="width:80%!important;margin:10px 50px!important">
    <h2 style="font-size:1rem; background:var(--otherColor); color:#fff; padding:5px">Items requested on invoice <?php echo $invoice?></h2>
    <table id="stock_items_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Quantity</td>
                <!-- <td>Unit cost</td> -->
                <!-- <td>Unit sales</td> -->
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_cond('issue_items', 'invoice', $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    $get_ind = new selects();
                    $alls = $get_ind->fetch_details_cond('items', 'item_id', $detail->item);
                    foreach($alls as $all){
                        // $sales_price = $all->sales_price;
                        $itemname = $all->item_name;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        echo $itemname;
                    ?>
                </td>
                <td style="text-align:center"><?php echo $detail->quantity?></td>
                
                <td>
                    <a style="color:#fff; font-size:.8rem; background:green; padding:4px; border-radius:10px; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0) "title="issue item" onclick="issueItem('<?php echo $detail->issue_id?>', <?php echo $detail->item?>)">Issue <i class="fas fa-upload"></i></a>
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    }
        ?>
</div>