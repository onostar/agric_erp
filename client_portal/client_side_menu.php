<aside class="main_menu" id="mobile_log">
    
    <nav>
        <h3><a href="users.php" title="Home"><i class="fas fa-home"></i> Main menus</a></h3>
        
        <ul>
            <?php 
                //get menus
                $get_menus = new selects();
                $menus = $get_menus->fetch_details_condOrder('sub_menus', 'menu', 14, 'sub_menu');
                if(gettype($menus) == "array"){
                    foreach($menus as $menu){
                        
                   
            ?>
            <li>
                <a href="javascript:void(0);" title="<?php echo $menu->sub_menu?>" class="page_navs" onclick="showPage('<?php echo $menu->url?>.php')"><i class="fas fa-arrow-alt-circle-right"></i> <?php echo $menu->sub_menu?></a>
            </li>
            <?php }};?>
            
            <li>
                <a href="javascript:void(0);"><i class="fas fa-rocket"></i> </a>
            </li>
        </ul>
        
    </nav>
</aside>