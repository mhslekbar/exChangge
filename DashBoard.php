<?php
    session_start();
    ob_start();
    if(isset($_SESSION['caissier'])):
        $pageTitle = "Table De Board";
        include "init.php";
        $Dashborad = new DashBoard();
        $getBrnch = $Dashborad->getBrnch($_SESSION['userid']);
        $today = date("Y-m-d");
        
        echo "<div class='container container-500 dashboard'>";
            echo "<h1 class='text-center mt-3 mb-3'>Tableau de bord</h1>";
    ?>   
        <div class="row">
        
            <?=AreaDisplayLayout("transactions.php","TRANSACTIONS",$Dashborad->countTrans($getBrnch['bbid'],$today),"BRIGHTYARROW","fas fa-exchange-alt fa-5x");?>    
            <!-- Count the customers has same town of this branch  -->
            <?=AreaDisplayLayout("customers.php","CLIENT",$Dashborad->countCustomers($getBrnch['bbid']),"LIGHTPURPLE","fas fa-user-tie fa-5x");?>            
            <?=AreaDisplayLayout("transfere.php","Transfere d'argent",$Dashborad->countTransferNoCust($getBrnch['bbid'],$getBrnch['bbid'],$today),"GREESEAN","fas fa-comments-dollar fa-5x");?>            
            

        </div>
    <?php    
        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: index.php");
    endif;
    ob_end_flush();
?>