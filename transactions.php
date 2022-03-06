<?php
    session_start();
    ob_start();
    if(isset($_SESSION['caissier'])):
        $pageTitle = "Transactions";
        include "init.php";
        $Transactions = new Transactions();
        
        echo "<div class='container'>";
                echo "<h1 class='text-center mt-3 mb-3'>Transactions</h1>";

        ?>
        <!-- Buy Currency -->

        <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel">Acheter du devise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                            <?php
                                $getbrnch   = $Transactions->getBrnch($_SESSION['userid']);
                                $getRate    = $Transactions->getRate($getbrnch['bbid']);
                                $getMyCurr  = $Transactions->getBrnch($_SESSION['userid']);
                                $getSymFromRateTable = $Transactions->getSymbol($getMyCurr['bbCurrencyType'])['currencyRR'];
                            ?>
                        <input type="number" step="any" class="form-control" name="fromCurr" hidden readonly required value="<?=$Transactions->getidOfSymbol($getbrnch['bbid'],$getSymFromRateTable)['idRR'];?>">
                    </div>
                    <!-- TO -->
                    <div class="form-group mb-2 p-select">
                        <select name="toCurr" id="toCurr" class="form-control" required>
                            <option value="">Choisir le devise qu'il veut</option>
                            <?php
                                $getCurr = $Transactions->getCurrency($getbrnch['bbid'],$getSymFromRateTable);
                                foreach($getCurr as $curr):
                                    echo "<option value='{$curr['idRR']}'>{$curr['currencyRR']}</option>";
                                endforeach;
                            ?>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                        <label id="costPrice" hidden></label>
                        <label id="retailPrice" hidden></label>
                    </div>
                    <div class="form-group convertMontant mb-2">
                        <input type="number" step="any" id="amountMain" name="amountMain" class="form-control" placeholder="donner le montant" autocomplete="off" required>
                        <span><?=$Transactions->getSymbol($getMyCurr['bbCurrencyType'])['currencyRR'];?></span>
                    </div>
                    <div class="form-group convertMontant mb-2">
                        <label for="amountConvert">Resultat en Devise</label>
                        <input type="number" step="any" id="amountConvert" name="amountConvert" class="form-control" readonly>
                        <span id='symbol' class="sp"></span>
                    </div>
                    <div class="form-group convertMontant mb-2">
                        <label for="amountBenef">benefice</label>
                        <input type="number" step="any" id="amountBenef" name="amountBenef" class="form-control" readonly>
                        <span class="sp"><?=$Transactions->getSymbol($getMyCurr['bbCurrencyType'])['currencyRR'];?></span>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="buyCurrency" class="btn btn-success">Acheter</button>
                </div>
                </div>
            </form>
            </div>
        </div>
        </div>

        <?php
            if(isset($_POST['buyCurrency'])) {
                $idBranch       = $getbrnch['bbid'];
                $fromCurr       = htmlspecialchars($_POST['fromCurr']);
                $toCurr         = htmlspecialchars($_POST['toCurr']); 
                $amountMain     = htmlspecialchars($_POST['amountMain']); 
                $amountConvert  = htmlspecialchars($_POST['amountConvert']); 
                $amountBenef    = htmlspecialchars($_POST['amountBenef']); 
                $type = "Achat";


                // $idBranch, $fromCurr, $toCurr, $amountMain, $amountConvert, $amountConvert, $type

                $formErrors = [];
                if(empty($fromCurr)) {
                    $formErrors[] = "Error!!";
                }

                if(empty($toCurr)){
                    $formErrors [] = "Vous etes obligé de choisir le devise que vous souhaitez faire la transaction";
                }

                if(empty($amountMain)) {
                    $formErrors [] = "Ajouter le montant";
                }
                
                if(!is_numeric($amountMain) && !empty($amountMain)) {
                    $formErrors [] = "Error";
                }

                if(empty($amountConvert)) {
                    $formErrors [] = "Error au niveau de conversion";
                }

                if(!is_numeric($amountConvert) && !empty($amountConvert)) {
                    $formErrors [] = "Error au niveau de conversion";
                }

                
                if(empty($amountBenef)) {
                    $formErrors [] = "Error au niveau du benefice";
                }

                if(!is_numeric($amountBenef) && !empty($amountBenef)) {
                    $formErrors [] = "Error au niveau du benefice";
                }
                
                foreach($formErrors as $error):
                    echo "<div class='alert alert-danger msg'>{$error}</div>";
                endforeach;

                $getbrnch = $Transactions->getBrnch($_SESSION['userid']);
                $prevBalance = 0;
                $newBalance = 0;
                if(!empty($getbrnch)) {
                    $prevBalance = $getbrnch['bbBalance'];
                    $newBalance = (float)$prevBalance + (float)$amountMain;
                }

                if(empty($formErrors)) {
                    try{
                        $insert = $Transactions->insertTrans($idBranch, $fromCurr, $toCurr, $amountMain, $amountConvert, $amountBenef, $type);
                        if($insert > 0) {
                            $Transactions->updateBranch($newBalance,$idBranch);
                            $theMsg = "<div class='alert alert-success msg'>Transaction Ajouté</div>";
                        }
                    } catch(PDOException $e) {
                        $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                    }
                }
               
            }

        ?>


        <!-- END BUY Currency  -->
        
        <!-- Start Delete Currency -->
        
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Supprimer la transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Voulez-Vous vraiment supprimé cette transaction ?? </p>
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="DeleteTrans" class="btn btn-danger">Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <?php
            if(isset($_POST['DeleteTrans'])) {
                $id = $_POST['id'] && is_numeric($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
                try {
                    $delete  = $Transactions->deleteTrans($id);
                    if($delete>0){
                        $theMsg = "<div class='alert alert-success'>Transaction Supprimer</div>";
                    }
                } catch(PDOException $e){
                    $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
                }
            }

        ?>

        <!-- END Delete Currency -->


        <!-- START Sale Currency  -->
        
        <div class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saleModalLabel">Vendre du Devise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                            <?php
                                $getbrnch   = $Transactions->getBrnch($_SESSION['userid']);
                                $getRate    = $Transactions->getRate($getbrnch['bbid']);
                                $getMyCurr  = $Transactions->getBrnch($_SESSION['userid']);
                                $getSymFromRateTable = $Transactions->getSymbol($getMyCurr['bbCurrencyType'])['currencyRR'];
                            ?>
                        <input type="number" step="any" class="form-control" name="toCurrSale" hidden readonly required value="<?=$Transactions->getidOfSymbol($getbrnch['bbid'],$getSymFromRateTable)['idRR'];?>">
                    </div>
                    <!-- TO -->
                    <div class="form-group mb-2 p-select">
                        <select name="fromCurrSale" id="fromCurrSale" class="form-control" required>
                            <option value="">Choisir le devise qu'il a</option>
                            <?php
                                $getCurr = $Transactions->getCurrency($getbrnch['bbid'],$getSymFromRateTable);
                                foreach($getCurr as $curr):
                                    echo "<option value='{$curr['idRR']}'>{$curr['currencyRR']}</option>";
                                endforeach;
                            ?>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                        <label id="costPrice" hidden></label>
                        <label id="retailPrice" hidden></label>
                    </div>
                    <div class="form-group convertMontant mb-2">
                        <input type="number" step="any" id="amountMainSale" name="amountMainSale" class="form-control" placeholder="donner le montant" autocomplete="off" required>
                        <span id='symbol'></span>
                    </div>
                    <div class="form-group convertMontant mb-2">
                        <label for="amountConvert">Resultat en Devise</label>
                        <input type="number" step="any" id="amountConvertSale" name="amountConvertSale" class="form-control" readonly required>
                        <span class="sp"><?=$Transactions->getSymbol($getMyCurr['bbCurrencyType'])['currencyRR'];?></span>                        
                    </div>
                    <div class="form-group convertMontant mb-2">
                        <label for="amountBenef">benefice</label>
                        <input type="number" step="any" id="amountBenefSale" name="amountBenefSale" class="form-control" readonly required>
                        <span class="sp"><?=$Transactions->getSymbol($getMyCurr['bbCurrencyType'])['currencyRR'];?></span>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="saleCurrency" class="btn btn-success">Acheter</button>
                </div>
                </div>
            </form>         
            </div>
        </div>
        </div>

        <!-- END Sale Currency  -->

        <?php
            if(isset($_POST['saleCurrency'])) {

                $idBranchSale       = $getbrnch['bbid'];
                $fromCurrSale       = htmlspecialchars($_POST['fromCurrSale']);
                $toCurrSale         = htmlspecialchars($_POST['toCurrSale']); 
                $amountMainSale     = htmlspecialchars($_POST['amountMainSale']); 
                $amountConvertSale  = htmlspecialchars($_POST['amountConvertSale']); 
                $amountBenefSale    = htmlspecialchars($_POST['amountBenefSale']); 
                $type = "Vente";


                // echo "idbrnch : " . $idBranchSale . "  idFromCurr : " . $fromCurrSale . "  idToCurr : " . $toCurrSale . "  " . $amountMainSale . "  " . $amountConvertSale . "  " . $amountConvertSale . "  Type : " . $type;

                $formErrors = [];
                if(empty($fromCurrSale)) {
                    $formErrors[] = "Error!!";
                }

                if(empty($toCurrSale)){
                    $formErrors [] = "Vous etes obligé de choisir le devise que vous souhaitez faire la transaction";
                }

                if(empty($amountMainSale)) {
                    $formErrors [] = "Ajouter le montant";
                }
                
                if(!is_numeric($amountMainSale) && !empty($amountMainSale)) {
                    $formErrors [] = "Error";
                }

                if(empty($amountConvertSale)) {
                    $formErrors [] = "Error au niveau de conversion";
                }

                if(!is_numeric($amountConvertSale) && !empty($amountConvertSale)) {
                    $formErrors [] = "Error au niveau de conversion";
                }

                
                if(empty($amountBenefSale)) {
                    $formErrors [] = "Error au niveau du benefice";
                }

                if(!is_numeric($amountBenefSale) && !empty($amountBenefSale)) {
                    $formErrors [] = "Error au niveau du benefice";
                }
                
                foreach($formErrors as $error):
                    echo "<div class='alert alert-danger msg'>{$error}</div>";
                endforeach;
    
                $getbrnch = $Transactions->getBrnch($_SESSION['userid']);
                $prevBalance = 0;
                $newBalance = 0;
                if(!empty($getbrnch)) {
                    $prevBalance = $getbrnch['bbBalance'];
                    $newBalance = (float)$prevBalance + (float)$amountMainSale;
                }

                if(empty($formErrors)) {
                    try{
                        $insert = $Transactions->insertTrans($idBranchSale, $fromCurrSale, $toCurrSale, $amountMainSale, $amountConvertSale, $amountBenefSale, $type);
                        if($insert > 0) {
                            $Transactions->updateBranch($newBalance,$idBranchSale);
                            $theMsg = "<div class='alert alert-success msg'>Transaction Ajouté</div>";
                        }
                    } catch(PDOException $e) {
                        $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                    }
                }


        }
        
        ?>

        <a href="DashBoard.php" class='btn btn-secondary mb-2'>
            <span class='fas fa-angle-left'></span> Retour
        </a>
        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#buyModal">
            <i class="fas fa-cart-arrow-down"></i> Acheter
        </button>

        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#saleModal">
            <i class='fas fa-hand-holding-usd'></i> Vendre            
        </button>

        <div class="table-responsive">
            <p>
                <?php
                    if(isset($theMsg)){
                        echo $theMsg;
                        header("refresh: 2; url=transactions.php");
                    }    
                ?>
            </p>
            <table class="table table-hover table-bordered">
                <thead class='thead-primary'>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Montant</th>
                        <th scope="col">net Convert</th>
                        <th scope="col">Benefice</th>
                        <th scope="col">Type</th>
                        <th scope="col">Date</th>
                        <!-- <th scope="col">supprimer</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $getbrnch = $Transactions->getBrnch($_SESSION['userid']); $today = date("Y-m-d");?>
                    <?php $getTrans = $Transactions->getTrans($getbrnch['bbid'],$today); $tot = 0; foreach($getTrans as $trans): ?>
                    <tr>
                        <td scope="row"><?=$trans['ttID'];?></td>
                        <td><?=removeComma($trans['ttMontant']) . " <strong>" . $Transactions->getRateBrnchWheridRR($trans['ttFromCurrency'])['currencyRR'] . "</strong>" ;?></td>
                        <td><?=removeComma($trans['ttNetConvert']) . " <strong>" . $Transactions->getRateBrnchWheridRR($trans['ttToCurrency'])['currencyRR'] . "</strong>" ;?></td>
                        <td><?=removeComma($trans['ttBenef']) . " <strong>" . $Transactions->getSymbol($getbrnch['bbCurrencyType'])['currencyRR'] . "</strong>" ;?></td>
                        <td><?=$trans['ttType'];?></td>
                        <td><?=$trans['ttDate'];?></td> 
                        <!-- <td>
                            <button type="button" class="btn btn-danger btnDeleteTrans" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class='fas fa-trash'></i>
                            </button>
                        </td> -->
                    </tr>
                    <?php $tot += $trans['ttBenef'];endforeach; ?>
                    <tr>
                        <td class='total-solde'>Total:</td>
                        <td colspan="3" class="text-end"><?=$tot . " <strong>" . $Transactions->getSymbol($getbrnch['bbCurrencyType'])['currencyRR'] . "</strong>";?></td>
                    </tr>
                </tbody>
            </table>
        </div>



        <?php    
        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: index.php");
    endif;
    ob_end_flush();
?>