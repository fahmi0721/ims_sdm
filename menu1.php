<?php 
$pages = isset($_GET['page']) ? $_GET['page'] : null;
echo "<ul class='sidebar-menu' data-widget='tree'>";
    echo "<li class='header'>MAIN NAVIGATION</li>";
    $cekAktif = $pages == null ? "class='active'" : "";
    echo "<li $cekAktif><a href='index.php'><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>";
    $MenuAkses = LoadMenu();
    for($i=0; $i < count($MenuAkses['root']); $i++){
        $RootMenu = $MenuAkses['root'][$i];
        if($RootMenu['Tipe'] == "single"){
            $cekAktif = $pages == $SubMenu['Direktori'] ? "class='active'" : "";
            echo "<li $cekAktif><a href='index.php'><i class='fa fa-".$RootMenu['Icon']."'></i> <span>".$RootMenu['NamaMenu']."</span></a></li>";
        }else{
            $ListMenu = getListMenu($RootMenu['Direktori']);
            $cekAktifRoot = in_array($pages, $ListMenu) ? "active" : "";
            echo "<li class='treeview $cekAktifRoot' >";
            echo "<a href='#'>
                    <i class='fa fa-".$RootMenu['Icon']."'></i> ".$RootMenu['NamaMenu']."<span></span>
                    <span class='pull-right-container'>
                    <i class='fa fa-angle-left pull-right'></i>
                    </span>
                </a>";
                echo "<ul class='treeview-menu'>";
                    for($j=0; $j < count($MenuAkses['sub']); $j++){
                        $SubMenu = $MenuAkses['sub'][$j];
                        if($SubMenu['ItemRoot'] == $RootMenu['Direktori']){
                            $cekAktif = $pages == $SubMenu['Direktori'] ? "class='active'" : "";
                            echo "<li $cekAktif><a href='index.php?page=".$SubMenu['Direktori']."'><i class='fa fa-".$SubMenu['Icon']."'></i> <span>".$SubMenu['NamaMenu']."</span></a></li>";
                        }
                    }
                echo "</ul>";
            echo "</li>";
        }
    }
    
    if($_SESSION['level'] == 0){
        echo "<li class='header'>MAIN SYSTEM</li>";
        $UserC = $pages == "Users" ? "class='active'" : "";
        $AksesMenuC = $pages == "AksesMenu" ? "class='active'" : "";
        $MenuC = $pages == "Menu" ? "class='active'" : "";
        echo "<li $UserC><a href='index.php?page=Users'><i class='fa fa-users'></i> <span>Users</span></a></li>";
        echo "<li $AksesMenuC><a href='index.php?page=AksesMenu'><i class='fa fa-gavel'></i> <span>Menu Akses</span></a></li>";
        echo "<li $MenuC><a href='index.php?page=Menu'><i class='fa fa-list'></i> <span>Main Menu</span></a></li>";
    }
   
echo "</ul>";

?>