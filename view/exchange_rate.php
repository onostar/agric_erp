<div id="exchange_rate">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div class="displays allResults" id="crop_cycle" style="width:90%!important;margin:20px 50px!important">
    
    <?php
        //check for exchange rate
        $get_details = new selects();
        $check = $get_details->fetch_count('exchange_rates');
        if($check > 0){
    ?>
    <h2>Dollar - Naira exchange rate</h2>
    <hr>
    <table id="room_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
                <td>S/N</td>
                <td>Dollar</td>
                <td>Naira value</td>
                <td>Last Updated</td>
                <td>Updated By</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details('exchange_rates');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--primaryColor)">
                    $1.00
                </td>
                <td style="color:var(--otherColor)">
                    <?php 
                        echo "₦".number_format($detail->rate, 2)
                    ?>
                </td>
                <td>
                    <?php 
                        if($detail->updated_at == NULL){
                            echo date("d-M-Y, H:ia", strtotime($detail->added_at));
                        }else{
                            echo date("d-M-Y, H:ia", strtotime($detail->updated_at));

                        }
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->updated_by == 0){
                            //get added by
                            $ads = $get_details->fetch_details_group('users', 'full_name', 'user_id', $detail->added_by);
                            echo $ads->full_name;
                        }else{
                            //get updated by
                            $ads = $get_details->fetch_details_group('users', 'full_name', 'user_id', $detail->updated_by);
                            echo $ads->full_name;
                        }
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0)" title="edit exhcnage rate" style="color:#fff; background:var(--otherColor); padding:5px; font-size:.8rem; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" onclick="showPage('edit_exchange_rate.php?rate=<?php echo $detail->exhcnage_id?>')"><i class="fas fa-edit"></i></a>
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        }else{
        //add ner exchange rate
    ?>
       <div class="add_user_form" style="width:50%; margin:20px">
        <h3 style="background:var(--tertiaryColor); text-align:left">Add Dollar Exchange Rate</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                
                <div class="data" style="width:50%">
                    <label for="rate">Rate (NGN)</label>
                    <input type="number" id="rate" name="rate" required>
                </div>
                
                <div class="data" style="width:auto">
                    <button type="button" id="add_item" name="add_item" onclick="addExchangeRate()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div> 
    <?php }?>
</div>
</div>