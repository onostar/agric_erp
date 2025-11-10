<h2 style="font-size:.9rem; text-align:left;">Items Used for <?php echo $task_title?></h2>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item</td>
                <td>Quantity</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $details = $get_details->fetch_details_2cond('task_items', 'task_id', 'invoice', $task, $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        $str = $get_details->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $str->item_name;
                    ?>
                </td>
                <td style="color:green; text-align:center">
                    <?php 
                        echo $detail->quantity;
                    ?>
                </td>
                <td><a href="javascript:void(0)" title="delete item" style="color:red;" onclick="removeTaskItem('<?php echo $detail->task_item_id?>')"><i class="fas fa-trash"></i></a></td>
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    
    ?>