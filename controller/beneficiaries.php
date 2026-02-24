<a href="javascript:void(0)" onclick="showBeneficiaryForm('<?php echo $customer?>')" style="float:right; background:brown; padding:5px; border-radius:15px; color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;">Add <i class="fas fa-user-plus"></i></a>
            <h3 style="background:var(--tertiaryColor); text-align:left">Beneficiaries</h3>
            
            <div class="displays allResults new_data" style="width:100%!important;margin:0!important">
                <table id="data_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--otherColor)">
                            <td>S/N</td>
                            <td>Beneficiary</td>
                            <td>Gender</td>
                            <td>Relationship</td>
                            <td>Phone</td>
                            <td>Address</td>
                            <td>Entitlement</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $get_bens = new selects();
                            $bens = $get_bens->fetch_details_Cond('beneficiaries', 'staff',  $customer);
                            if(gettype($bens) === 'array'){
                            foreach($bens as $ben):
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            
                            <td>
                                <?php
                                    echo $ben->beneficiary;
                                ?>
                                </td>
                            <td><?php echo $ben->gender;?></td>
                            <td><?php echo $ben->relation;?></td>
                            <td><?php echo $ben->phone;?></td>
                            <td><?php echo $ben->address;?></td>
                            <td><?php echo $ben->entitlement;?>%</td>
                            <td>
                                <a href="javascript:void(0)" onclick="editBeneficiaryForm('<?php echo $ben->beneficiary_id?>')" style="color:var(--primaryColor)"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="deleteBeneficiary('<?php echo $ben->beneficiary_id?>')" style="color:brown"><i class="fas fa-trash"></i></a>
                            </td>
                        
                            
                        </tr>
                        <?php $n++; endforeach;}?>
                    </tbody>
                </table>
                <?php
                    if(gettype($bens) == 'string'){
                        echo "<p class='not_result'; style='text-align:center;font-size:.9rem;'>$bens</p>";
                    }
                ?>
                
            </div>