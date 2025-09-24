<?php
session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    //pagination
    if(!isset($_GET['page'])){
        $page_number = 1;
    }else{
        $page_number = $_GET['page'];
    }
    //set limit
    $limit = 50;
    //calculate offset
    $offset = ($page_number - 1) * $limit;
    $prev_page = $page_number - 1;
    $next_page = $page_number + 1;
    $adjacents = "2";
    //get number of pages
    $get_pages = new selects();
    $pages = $get_pages->fetch_count('ledgers');
    $total_pages = ceil($pages/$limit);
    //get second to last page
    $second_to_last = $total_pages - 1;

    //get user
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        //get user role
        $get_role = new selects();
        $roles = $get_role->fetch_details_group('users', 'user_role', 'username', $username);
        $role = $roles->user_role;

?>
    <div class="info"></div>
<div class="displays allResults" id="bar_items">
    <h2>List of Account Ledgers</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Chart of Account')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Account group</td>
                <td>Account sub-group</td>
                <!-- <td>Class Code</td> -->
                <td>Account Class</td>
                <td>Account Name</td>
                <td>Account Number</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details('ledgers', $limit, $offset);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td >
                    <?php
                        //get account group
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('account_groups', 'account_group', 'account_id', $detail->account_group);
                        echo $cat_name->account_group;
                    ?>
                </td>
                <td >
                    <?php
                        //get class number
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('account_sub_groups', 'sub_group', 'sub_group_id', $detail->sub_group);
                        echo $cat_name->sub_group;
                    ?>
                </td>
                <!-- <td style="color:var(--moreColor);">
                    <?php
                        //get class number
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('account_class', 'class_code', 'class_id', $detail->class);
                        echo $cat_name->class_code;
                    ?>
                </td> -->
                <td>
                     <?php
                        //get class name
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('account_class', 'class', 'class_id', $detail->class);
                        echo $cat_name->class;
                    ?>
                </td>
                
                <td><?php echo $detail->ledger?></td>
                <td>
                    <?php 
                        echo $detail->acn;
                    ?>
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    <div class="page_links">
        <?php
            if(gettype($details) == "array"){
                echo "<p><strong>Pages ".$page_number." of ".$total_pages."</strong></p>";
        ?>
        <ul class="pages">
            <?php
                if($page_number > 1){
                    echo "<li><a href='javascript:void(0)' onclick='showPage('chart_of_account.php?page=1')' title='Go to first page'>First page</a></li>
                    <li><a href='javascript:void(0)' onclick='showPage('chart_of_account.php?page='$previous_page)' title='Go to previous page'>Previous</a></li>";
                }
            ?>
            <?php
                if($page_number < $total_pages){
                    echo "<li><a href='javascript:void(0)' onclick='showPage('chart_of_account.php?page='".$next_page."')' title='Go to Next page'>Next</a></li><li><a href='javascript:void(0)' onclick='showPage('chart_of_account.php?page='$total_pages)' title='Go to last page'>Last page</a></li>";
                }
            ?>
        </ul>
    </div>
    <?php
        }
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    
    ?>
</div>
<?php }?>