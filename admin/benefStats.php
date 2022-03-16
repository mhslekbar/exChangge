<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        $pageTitle = "Benefice";
        include "init.php";
        $BenefStats = new BenefStats();       
        $today = date("Y-m-d");
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        
        echo "<div class='container'>";
            echo "<h1 class='text-center mt-3 mb-3'>Statistiques du Benefice</h1>";
        ?>    
        
        <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4 showDate mb-2">
                    <div class="form-group p-select">
                        <select class='form-control mb-2 chooseBrnch'> 
                            <option value="">Choisir une branche</option>
                            <?php 
                                foreach ($BenefStats->getAllBranchs() as $value) {
                                    echo "<option value='{$value['bbid']}'>{$value['bbBrancheName']}</option>";
                                }
                            ?>
                        </select>   
                        <i class='fas fa-angle-down p-arrow'></i>
                    </div>
                    <div class="form-group mb-2">
                        <label for="today">Entre</label>
                        <input type="date" id="today" class="form-control" value="<?=$today?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="tomorrow">Et</label>
                        <input type="date" id="tomorrow" class="form-control" value="<?=$tomorrow?>">
                    </div>
                </div>
                <div class='col-sm-12 col-md-6 col-lg-4'>
                    <div class='area BRIGHTYARROW mb-2'>
                        <i class='fas fa-exchange-alt fa-5x'></i>
                        <div class='content'>
                            <strong>TRANSACTIONS</strong>
                            <p class='text-center' id="trans">
                                <?php
                                    $totTrans = 0; 
                                    
                                    foreach($BenefStats->sumBenefOfTransExchange($today,$tomorrow) as $benefArr) {
                                        $sum        = $benefArr['sum'];
                                        $brnchid    = $benefArr['ttidBranch'];
                                        $idRR       = $BenefStats->getBrnch($brnchid)['bbCurrencyType'];
                                        $rt_price   = $BenefStats->getDevise($idRR)['retail_price'];
                                        $totTrans  += $sum * $rt_price;
                                    }
                                    echo $totTrans;
                                    ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class='col-sm-12 col-md-6 col-lg-4'>
                    <div class='area GREESEAN mb-2'>
                        <i class='fas fa-share fa-5x'></i>
                        <div class='content'>
                            <strong>TRANSFERE D'ARGENT</strong>
                            <p class='text-center' id="noCust">
                                <?php
                                $totOfnoCust = 0; 
                                    foreach($BenefStats->sumBenefOfnoCustomer($today,$tomorrow) as $benefArr) {
                                        $sum        = $benefArr['sum'];
                                        $brnchid    = $benefArr['nnBranchSender'];
                                        $idRR       = $BenefStats->getBrnch($brnchid)['bbCurrencyType'];
                                        $rt_price   = $BenefStats->getDevise($idRR)['retail_price'];
                                        $totOfnoCust += $sum * $rt_price;     
                                    }
                                    echo $totOfnoCust;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <span class="text-center spanTot">
                    <strong>TOTAL : </strong><total><?=$totTrans + $totOfnoCust?><total> MRU
                </span>

        </div>

        <?php
        echo "</div>";

        include $tpl . "footer.php";
    else:
        header("Location: ../index.php");
    endif;
    ob_end_flush();
?>