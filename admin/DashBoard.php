<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        $pageTitle = "Table De Board";
        include "init.php";
        $Dashborad = new DashBoard();
        echo "<div class='container container-500 dashboard'>";
            echo "<h1 class='text-center mt-3 mb-3'>Table de Board</h1>";
    ?>   
        <div class="row">
                <?=AreaDisplayLayout("rates.php","DEVISE",$Dashborad->countRates(),"WETASPHALT","fas fa-chart-line fa-5x");?>
                <?=AreaDisplayLayout("users.php","UTILISATEURS",$Dashborad->countUsers(),"GREESEAN","fas fa-users fa-5x");?>
                <?=AreaDisplayLayout("branch.php","BRANCHES",$Dashborad->countBranchs(),"BLUE","fas fa-laptop-house fa-5x ");?>
                <?=AreaDisplayLayout("suppliers.php","FOURNISSEURS",$Dashborad->countSuppliers(),"POMEGRANATE","fas fa-user fa-5x");?>            
                <?=AreaDisplayLayout("chargeBranch.php","CHARGER BRANCHES",$Dashborad->countChargerBranch(),"CARROT","fas fa-cart-plus fa-5x");?>
                <?=AreaDisplayLayout("customers.php","CLIENTS",$Dashborad->countCustomers(),"LIGHTPURPLE","fas fa-user-tie fa-5x");?>
                <?=AreaDisplayLayout("benefStats.php","BENEFICE","","HIGHBLUE","fas fa-chart-bar fa-5x");?>
                

        </div>
    <?php    
        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: index.php");
    endif;
    ob_end_flush();
?>