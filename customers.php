<?php
    session_start();
    ob_start();
    if(isset($_SESSION['caissier'])):
        $pageTitle = "Clients";
        include "init.php"; 
        $Customers = new Customers();
        echo "<div class='container'>";
        $today = Date("Y-m-d");
        
        $do = isset($_GET['do'])  ? htmlspecialchars($_GET['do']) : "Client";
            if($do == "Client"){
            ?>    
            <h1 class='text-center mt-3 mb-3'>Les Clients Fidéle</h1>

            <!-- Deposit money Modal -->
            <div class="modal fade" id="depositMoneyModal" tabindex="-1" aria-labelledby="depositMoneyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="depositMoneyModalLabel">Déposer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <input type="text" class="form-control" name="phone" placeholder="Numero telephone du client">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" class="form-control" name="amount" placeholder="Saisir le montant">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="btnDepositMoney" class="btn btn-success">Deposer</button>
                    </div>
                </form>
                </div>
            </div>
            </div>

            <?php
                if(isset($_POST['btnDepositMoney'])) {
                    $phone    = htmlspecialchars($_POST['phone'])  ;
                    $amount  = htmlspecialchars($_POST['amount']);
                    
                    $cust  = $Customers->getCustomer($phone);
                    $brnch = $Customers->getBrnch($_SESSION['userid']);

                    $formErrors = [];

                    if(empty($phone)){
                        $formErrors[] = "le numero de telephone est Obligatoire";
                    }

                    if(empty($amount)){
                        $formErrors[] = "le montant est Obligatoire";
                    }

                    if($cust == 0){
                        $formErrors[] = "le compte n'existe pas";
                    } 
                    if($Customers->checkCustomer($phone) > 0) {
                        $formErrors[] = "le compte n'est pas activé";
                    }

                    foreach($formErrors as $error):
                        echo "<div class='alert alert-danger msg'>{$error}</div>";
                    endforeach;

                    // For Customer
                    $newSoldeCust = 0;
                    if(!empty($cust)){
                        $prevSoldeCust  = $cust['ccSolde'];
                        $newSoldeCust   = (float)$prevSoldeCust + (float)$amount;
                        $fname          = $cust['ccFullName']; 
                        $idCust         = $cust['ccID'];
                        $type           = "versement";
                    }

                    // For Cashier
                    $prevBalanceCashier = $brnch['bbBalance'];
                    $newBalanceCashier  = (float)$prevBalanceCashier + (float)$amount;


                    if(empty($formErrors)):
                        try {
                            $update = $Customers->updateCustomerSolde($newSoldeCust,$phone);
                            if($update > 0) {
                                $Customers->insertTransCust($idCust,$phone,$fname,$amount,$type);
                                $Customers->updateBranchBalance($newBalanceCashier,$brnch['bbid']);
                                $theMsg = "<div class='alert alert-success msg'>Montant a èté déposer avec succés</div>";
                            } else {
                                echo "aa";
                            }
                        } catch(PDOException $e) {
                            $theMsg = "<div class='alert aler-danger msg'>{$e->getMessage()}</div>";
                        }    
                    endif;
                }

            ?>

            <!-- END Deposit Modal -->
                
            <!-- Start withDraw Modal -->

            <div class="modal fade" id="withDrawModal" tabindex="-1" aria-labelledby="withDrawModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withDrawModalLabel">Retrait d'argent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <input type="text" class="form-control" name="phone" placeholder="Numero telephone du client">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" class="form-control" name="amount" placeholder="Saisir le montant">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" name="withDrawBtn" class="btn btn-warning">Retirer</button>
                    </div>
                    </div>
                </form>
            </div>
            </div>
                
            <?php
                if(isset($_POST['withDrawBtn'])) {
                    $phone    = htmlspecialchars($_POST['phone'])  ;
                    $amount  = htmlspecialchars($_POST['amount']);
                    
                    $cust  = $Customers->getCustomer($phone);
                    $brnch = $Customers->getBrnch($_SESSION['userid']);

                    $formErrors = [];

                    if(empty($phone)){
                        $formErrors[] = "le numero de telephone est Obligatoire";
                    }

                    if(empty($amount)){
                        $formErrors[] = "le montant est Obligatoire";
                    }

                    if($cust == 0){
                        $formErrors[] = "le compte n'existe pas";
                    } 
                    if($Customers->checkCustomer($phone) > 0) {
                        $formErrors[] = "le compte n'est pas activé";
                    }

                    foreach($formErrors as $error):
                        echo "<div class='alert alert-danger msg'>{$error}</div>";
                    endforeach;

                    // For Customer
                    $newSoldeCust = 0;
                    if(!empty($cust)){
                        $prevSoldeCust  = $cust['ccSolde'];
                        $newSoldeCust   = (float)$prevSoldeCust - (float)$amount;
                        $fname          = $cust['ccFullName']; 
                        $idCust         = $cust['ccID'];
                        $type           = "retrait";
                    }


                    // For Cashier
                    $prevBalanceCashier = $brnch['bbBalance'];
                    $newBalanceCashier  = (float)$prevBalanceCashier - (float)$amount;


                    if(empty($formErrors)):
                        try {
                            $update = $Customers->updateCustomerSolde($newSoldeCust,$phone);
                            if($update > 0) {
                                $Customers->insertTransCust($idCust,$phone,$fname,$amount,$type);
                                $Customers->updateBranchBalance($newBalanceCashier,$brnch['bbid']);
                                $theMsg = "<div class='alert alert-success msg'>Montant a èté déposer avec succés</div>";
                            } else {
                                echo "error";
                            }
                        } catch(PDOException $e) {
                            $theMsg = "<div class='alert aler-danger msg'>{$e->getMessage()}</div>";
                        }    
                    endif;
                }

            ?>
            <!-- END withDraw Modal -->


            <a href="DashBoard.php" class="btn btn-secondary mb-2"><i class="fas fa-angle-left"></i> Retour</a>
            <!-- deposit Button -->
            <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#depositMoneyModal">
            <i class="fas fa-plus-square"></i> Déposer
            </button>

            <!-- WithDraw Button -->
            <button type="button" class="btn btn-warning mb-2" data-bs-toggle="modal" data-bs-target="#withDrawModal">
            <i class="fas fa-plus-square"></i> Rétrait d'argent
            </button>

            <div class="table-responsive">
            <p>
                <?php
                    if(isset($theMsg)){
                        echo $theMsg;
                        header("refresh:2 ; url=customers.php");
                    }
                ?>
            </p>
            <table class="table table-hover table-bordered">
                <thead class='thead-primary'>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Numero Telephone</th>
                        <th scope="col">Nom Complet</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Type</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $today = date("Y-m-d"); $brnch = $Customers->getBrnch($_SESSION['userid']);?>
                    <?php $transCusts = $Customers->getTransCust($today); foreach($transCusts as $transCust):?>
                    <tr>
                        <th scope="row"><?=$transCust['tcID'];?></th>
                        <td><?=$transCust['tcPhone'];?></td>
                        <td><?=$transCust['tcFullName'];?></td>
                        <td><?=removeComma($transCust['tcAmount']) . " " . $Customers->getRateWhereidRR($Customers->getBrnch($_SESSION['userid'])['bbCurrencyType'])['currencyRR'];?></td>
                        <td><?=$transCust['tcType'];?></td>
                        <td><?=explode(" ",$transCust['tcDate'])[1];?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>



                <!-- <div class='row'>
                    <?=AreaDisplayLayout("customers.php?do=ApproveCust","CLIENT Fidéle",$Customers->countApproveClient(),"GREESEAN","fas fa-user-check fa-5x");?>     
                    <?=AreaDisplayLayout("customers.php?do=SimpleCust","CLIENT SIMPLE",$Customers->countNoApproveClient($today),"POMEGRANATE","fas fa-users-slash fa-5x");?>     
                </div>    -->
            <?php
            } else if($do == "ApproveCust") { ?>
            
            <!-- End ApproveCust -->
            <?php } else if($do == "SimpleCust") {?>
            <h1 class='text-center mt-3 mb-3'>Les Simples Clients</h1>
            <a href="customers.php" class="btn btn-secondary mb-2"><i class="fas fa-angle-left"></i> Retour</a>
            
            <!-- Send Button modal -->
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#sendModal">
                <i class="fas fa-paper-plane"></i> Transférer
            </button>
            <a href="?do=Receipt" class="btn btn-success mb-2">
                <i class="fas fa-receipt"></i> Recevoir
            </a>
            
            <!--Send Modal -->

            <?php
                if(isset($_POST['btnSendMoney'])) {
                    $branchSender    = htmlspecialchars($_POST['branchSender'] );
                    $contactSender   = htmlspecialchars($_POST['contactSender']);
                    $branchReceipt   = htmlspecialchars($_POST['branchReceipt']);
                    $contactReceipt  = htmlspecialchars($_POST['contactReceipt']);
                    $nameReceipt     = htmlspecialchars($_POST['nameReceipt']);
                    $amountSender    = htmlspecialchars($_POST['amountSender']);
                    $amountReceipt   = htmlspecialchars($_POST['amountReceipt']);
                    $Benef           = htmlspecialchars($_POST['Benef']);
                    $type            = "Envoie";

                    $formErrors = [];

                    if(empty($branchSender)) {
                        $formErrors[] = "Errors";
                    }

                    if(!is_numeric($branchSender) && !empty($branchSender)) {
                        $formErrors[] = "BrnchSender must be numeric";
                    }

                    if(empty($contactSender)) {
                        $formErrors[] = "Ajouter le numero telephone de l'expediteur";
                    }

                    if(!is_numeric($contactSender) && !empty($contactSender)) {
                        $formErrors[] = "Le numero de l'expediteur doit contenir uniquement des numeros";
                    }

                    if(empty($branchReceipt)) {
                        $formErrors[] = "Ajouter la branche qui va recevoir de l'argent de lui";
                    }
                    
                    if(!is_numeric($branchReceipt) && !empty($branchReceipt)) {
                        $formErrors[] = "brnchReceipt must be numeric";
                    }

                    if(empty($contactReceipt)) {
                        $formErrors[] = "Ajouter le numero telephone du destinataire";
                    }

                    if(!is_numeric($contactReceipt) && !empty($contactReceipt)) {
                        $formErrors[] = "Le numero du destinataire doit contenir uniquement des numeros";
                    }

                    if(empty($nameReceipt)) {
                        $formErrors[] = "Ajouter le nom du destinataire";
                    }

                    if(empty($amountSender)) {
                        $formErrors[] = "Ajouter le montant que vous désirer envoyer";
                    }

                    
                    if(!is_numeric($amountSender) && !empty($amountSender)) {
                        $formErrors[] = "le montant doit contenir uniquement des numeros";
                    }

                    if(empty($amountReceipt)) {
                        $formErrors[] = "Errors";
                    }

                    if(!is_numeric($amountReceipt) && !empty($amountReceipt)) {
                        $formErrors[] = "le montant Receipt doit contenir uniquement des numeros";
                    }

                    if(empty($Benef)) {
                        $formErrors[] = "Errors";
                    }

                    if(!is_numeric($Benef) && !empty($Benef)) {
                        $formErrors[] = "le montant Benef doit contenir uniquement des numeros";
                    }

                    foreach($formErrors as $error):
                        echo "<div class='alert alert-danger msg'>{$error}</div>";
                    endforeach;

                    $Getbrnch = $Customers->getBrnch($_SESSION['userid']);
                    $prevBalance = $Getbrnch['bbBalance'];
                    $newBalance  = (float)$prevBalance + (float)$amountSender;
                    // echo $branchSender . "  "  . $contactSender . "  " .  $branchReceipt . "  " . $contactReceipt . "<br>" ;
                    // echo $nameReceipt . "  "  . $amountSender . "  " .  $amountReceipt . "  " . $Benef . " " ;
                    if(empty($formErrors)):
                        try {
                            $insert = $Customers->insertRecord($branchSender, $contactSender, $branchReceipt, $contactReceipt, $nameReceipt, $amountSender, $amountReceipt, $Benef, $type);
                            if($insert > 0) {
                                $Customers->updateBranchBalance($newBalance,$branchSender);
                                $theMsg = "<div class='alert alert-success msg'>Montant envoyé avec succés</div>";
                            }
                        } catch(PDOException $e){
                            $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                        }
                    endif;
            }
            ?>

            <div class="modal fade" id="sendModal" tabindex="-1" aria-labelledby="sendModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendModalLabel">Envoyer de l'argent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <?php
                                $brnch = $Customers->getBrnch($_SESSION['userid']);
                            ?>
                            <input type="text" name="branchSender" id="branchSender" class="form-control" hidden readonly value="<?=$brnch['bbid']?>" required>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="contactSender" class="form-control" placeholder="Donner le contact de l'expediteur" value="<?=$contactSender??null;?>" required>
                        </div>
                        <div class="form-group mb-2 p-select">
                            <select name="branchReceipt" id="branchReceipt" class="form-control" required>
                                <option value="">Choisir la branche de récupération</option>
                                <?php
                                    $branchs = $Customers->getBranchs($brnch['bbid']);
                                    foreach($branchs as $branch):
                                        echo "<option value='{$branch['bbid']}'";
                                            echo isset($branchReceipt) && ($branchReceipt == $branch['bbid']) ? "selected" : "";
                                        echo ">{$branch['bbBrancheName']}</option>";
                                    endforeach;
                                ?>
                            </select>
                            <i class='fas fa-angle-down p-arrow'></i>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="contactReceipt" class="form-control" placeholder="Donner le contact du destinataire" value="<?=$contactReceipt??null;?>" required>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="nameReceipt" class="form-control" placeholder="Donner le nom du destinataire" value="<?=$nameReceipt??null;?>" required>
                        </div>
                        <div class="form-group mb-2 montDevise">
                            <input type="text" name="amountSender" id="amountSender" class="form-control" placeholder="Donner le Montant" value="<?=$amountSender??null;?>" required>
                            <span><?=$Customers->getSymbol($brnch['bbCurrencyType'])['currencyRR'];?></span>
                        </div>
                        <div class="form-group mb-2 montDevise">
                            <input type="text" name="amountReceipt" id="amountReceipt" class="form-control" readonly value="<?=$amountReceipt??null;?>" required>
                            <span id="symbolDevise"></span>
                            <sniper id="rt_price" hidden></sniper>
                            <sniper id="ct_price" hidden></sniper>
                        </div>
                        <div class="form-group mb-2  montDevise">
                            <input type="text" name="Benef" id="Benef" class="form-control" readonly value="<?=$Benef??null;?>" required>
                            <span><?=$Customers->getSymbol($brnch['bbCurrencyType'])['currencyRR'];?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" name="btnSendMoney" class="btn btn-primary">Envoyer</button>
                    </div>
                </form>
                </div>
            </div>
            </div>

            <div class="table-responsive">
                <p>
                    <?php
                        if(isset($theMsg)){
                            echo $theMsg;
                            header("refresh: 2; customers.php?do=SimpleCust");
                        }
                    ?>
                </p>
                <table class="table table-hover table-bordered">
                    <thead class='thead-primary'>
                        <tr>
                            <th scope="col">#NO</th>
                            <!-- <th scope="col">De</th> -->
                            <th scope="col">Vers</th>
                            <th scope="col">Montant Envoyé</th>
                            <th scope="col">Montant Réçu</th>
                            <th scope="col">Benef</th>
                            <th scope="col">Telephone de l'expediteur</th>
                            <th scope="col">Telephone du destinateur</th>
                            <th scope="col">Nom du destinataire</th>
                            <th scope="col">Réçu</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $brnch = $Customers->getBrnch($_SESSION['userid']);
                            $today = date("Y-m-d");
                            $noCusts = $Customers->getTransNoCustomers($brnch['bbid'],$today);
                            
                            // echo $Customers->getRateWhereidRR($Customers->branch($nocust['nnBranchSender'])['bbid'])['currencyRR'];
                            foreach($noCusts as $nocust):
                        ?>
                        <tr>
                            <td scope="row"><?=$nocust['nnID'];?></td>
                            <!-- <td><?=$Customers->branch($nocust['nnBranchSender'])['bbBrancheName'];?></td> -->
                            <td><?=$Customers->branch($nocust['nnBranchReceipt'])['bbBrancheName'];?></td>
                            <td><?=removeComma($nocust['nnAmountSend']) . " " . $Customers->getRateWhereidRR($Customers->branch($nocust['nnBranchSender'])['bbCurrencyType'])['currencyRR'];?></td>
                            <td><?=removeComma($nocust['nnAmountReceipt']) . " " . $Customers->getRateWhereidRR($Customers->branch($nocust['nnBranchReceipt'])['bbCurrencyType'])['currencyRR'];?></td>
                            <td><?=removeComma($nocust['nnBenef']) . " " . $Customers->getRateWhereidRR($Customers->branch($nocust['nnBranchSender'])['bbCurrencyType'])['currencyRR'];?></td>
                            <td><?=$nocust['nnSenderContact'];?></td>
                            <td><?=$nocust['nnReceiptContact'];?></td>
                            <td><?=$nocust['nnReceiptName'];?></td>
                            <td><?=$nocust['nnValider'] == 0 ? "<span class='noRecu'>Non</span>" : "<span class='recu'>Oui</span>";?></td>
                            <td><?=$nocust['nnDate'];?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>



            <?php } 
            else if($do == "Receipt") { ?>

            <h1 class='text-center mt-3 mb-3'>Reception</h1>
            <a href="customers.php?do=SimpleCust" class="btn btn-secondary mb-2"><i class="fas fa-angle-left"></i> Retour</a>
            <div class="form-group cstmSearch">
                <input type="text" name="searchReceipt" id="searchReceipt" class="form-control" placeholder="Chercher par numero de l'expediteur">
                <i class="fas fa-search"></i>                
                <input type="hidden" name="branchReceipt" id="branchReceipt" value="<?=$Customers->getBrnch($_SESSION['userid'])['bbid'];?>">
            </div>

            <!-- WithDraw Money Modal -->
            <div class="modal fade" id="withDrwModal" tabindex="-1" aria-labelledby="withDrwModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withDrwModalLabel">Recevoir de l'argent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <input type="text" name="id" id="id" class="form-control" hidden>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" name="branchReceipt" id="branchReceipt" class="form-control" hidden>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" name="contactReceipt" id="contactReceipt" class="form-control" placeholder="Saisir son numero telephone" auto-complete="off">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" name="btnWithDrw" class="btn btn-warning">récupérer</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>

            <?php
                if(isset($_POST['btnWithDrw'])) {
                    $id             = htmlspecialchars($_POST['id']);
                    $branchReceipt  = htmlspecialchars($_POST['branchReceipt']);
                    $contactReceipt = htmlspecialchars($_POST['contactReceipt']);

                    $formErrors = [];

                    if(empty($branchReceipt)) {
                        $formErrors[] = "Branche de reception est obligatoire";
                    }

                    if(empty($contactReceipt)) {
                       $formErrors[] = "Numero telephone est obligatoire";
                    }

                    if(empty($id)) {
                        $formErrors[] = "Errors";
                     }

                    $branch = $Customers->getBranchWHereName($branchReceipt);    
                    $getCust = $Customers->getNoCust($contactReceipt);                    
                    $amount = 0;
                    $prevBalance = 0;

                    if( ($getCust>0) && !empty($contactReceipt)){
                        $amount = $getCust['nnAmountReceipt'];
                        if($amount  > $branch['bbBalance']) {
                            $formErrors[] = "Montant n'existe pas dans la caisse";
                        } else{
                            $prevBalance = $branch['bbBalance'] - $amount;    
                        }
                    } else {
                        $formErrors[] = "N'existe pas";
                    }

                    foreach($formErrors as $error):
                        echo "<div class='alert alert-danger msg'>{$error}</div>";
                    endforeach;

                    if(empty($formErrors)):
                        try {
                            $update = $Customers->updateValider($id);
                            if($update > 0){
                                // update branch
                                $Customers->updateBranchBalance($prevBalance,$branch['bbid']);
                                $theMsg = "<div class='alert alert-success'>Argent réccupérer avec succés</div>";
                            }else {
                                $theMsg = "<div class='alert alert-danger'>Error</div>";
                            }    
                        } catch(PDOException $e){
                            $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
                        }
                    endif;
               
                }
                    
            ?>


                <div class="table-responsive">
                <p>
                    <?php
                        if(isset($theMsg)){
                            echo $theMsg;
                            header("refresh: 2;url=customers.php?do=Receipt");
                        }
                    ?>
                </p>
                <table class="table table-hover table-bordered tbl-search">
                    <thead class='thead-primary'>
                        <tr>
                            <th scope="col">#NO</th>
                            <th scope="col">De</th>
                            <th scope="col" hidden>Vers</th>
                            <th scope="col">Montant Envoyé</th>
                            <th scope="col">Montant Réçu</th>
                            <!-- <th scope="col">Benef</th> -->
                            <th scope="col">Telephone de l'expediteur</th>
                            <th scope="col">Nom du destinataire</th>
                            <th scope="col">Réçu</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $brnch = $Customers->getBrnch($_SESSION['userid']);
                            $today = date("Y-m-d");
                            $noCusts = $Customers->getTransNoCustomersReceipt($brnch['bbid'],$today);
                            
                            foreach($noCusts as $nocust):
                        ?>
                        <tr>
                            <td scope="row"><?=$nocust['nnID'];?></td>
                            <td><?=$Customers->branch($nocust['nnBranchSender'])['bbBrancheName'];?></td>
                            <td hidden><?=$Customers->branch($nocust['nnBranchReceipt'])['bbBrancheName'];?></td>
                            <td><?=removeComma($nocust['nnAmountSend']) . " " . $Customers->getRateWhereidRR($Customers->branch($nocust['nnBranchSender'])['bbCurrencyType'])['currencyRR'];?></td>
                            <td><?=removeComma($nocust['nnAmountReceipt']) . " " . $Customers->getRateWhereidRR($Customers->branch($nocust['nnBranchReceipt'])['bbCurrencyType'])['currencyRR'];?></td>
                            <!-- <td><?=""//removeComma($nocust['nnBenef']) . " " . $Customers->getRateWhereidRR($Customers->branch($nocust['nnBranchSender'])['bbCurrencyType'])['currencyRR'];?></td> -->
                            <td><?=$nocust['nnSenderContact'];?></td>
                            <td><?=$nocust['nnReceiptName'];?></td>
                            <td>
                                <?=$nocust['nnValider'] == 0 ? 
                                    '<button type="button" class="btn btn-warning btnValider" data-bs-toggle="modal" data-bs-target="#withDrwModal">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    </button>' : 
                                    "<span class='recu'>Oui</span>";?>
                            </td>
                            <td><?=$nocust['nnDate'];?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


            <?php } else {
                header("Location: customers.php");
            }
        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: index.php");
    endif;
    ob_end_flush();
?>