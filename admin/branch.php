<?php
    session_start();
    ob_start();
    $pageTitle = "Branches";
    if(isset($_SESSION['username'])):
        include "init.php";
        $Branches = new Branchs();
        $Transactions = new Transactions();
        $do = isset($_GET['do']) ? htmlspecialchars($_GET['do']) : "Branch";
        echo '<div class="container branch">';
        if($do == "Branch"){
            echo '<h1 class="text-center mt-3 mb-3">Gestion des Branches</h1>';
    ?>    

        <!-- ADD Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter Une Branche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="name" placeholder="Nom de la branche" autocomplete="off">
                    </div>
                    <div class="form-group mb-2 p-select">
                        <select name="caissier" class="form-control">
                            <option value="">Choisir le caissier</option>
                            <?php
                                $caissiers = $Branches->getCashier();
                                foreach($caissiers as $usr):
                                    echo "<option value='{$usr['uuid']}'>{$usr['uuUsername']}</option>";
                                endforeach;
                            ?>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="location" placeholder="Localisation" autocomplete="off">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="solde" placeholder="donner le solde" autocomplete="off">
                    </div>
                    <div class="form-group mb-2 p-select">
                        <select name="devise" class="form-control">
                        <option value="">choisir son type du devise</option>
                        <?php    
                            $ratesObj = new Rates();
                            $Rates = $ratesObj->getRates();
                            foreach($Rates as $rt){
                                echo "<option value='{$rt['idRR']}'>{$rt['currencyRR']}</option>";
                            }
                        ?>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="btnAddBranch" class="btn btn-success">Ajouter</button>
                </div>
            </form>
            </div>
        </div>
        </div>

        
        <?php
            if(isset($_POST['btnAddBranch'])):
                $name       = htmlspecialchars($_POST['name']);
                $caissier   = htmlspecialchars($_POST['caissier']);
                $location   = htmlspecialchars($_POST['location']);
                $solde      = htmlspecialchars($_POST['solde']);
                $devise     = htmlspecialchars($_POST['devise']);
                
                $formErrors = [];
                $checkbrnch = $Branches->checkBranch($name);
                
                if($checkbrnch > 0):
                    $formErrors[] = "le branch deja existe";
                endif;

                $checkCaissier = $Branches->checkCaissierBranch($caissier);

                if($checkCaissier > 0):
                    $formErrors[] = "le Caissier deja existe";
                endif;

                if(empty($caissier)):
                    $formErrors[] = "le choix du caissier est obligatoire";
                endif;

                if(empty($name)):
                    $formErrors[] = "le nom de la branche est Obligatoire";
                endif;
                
                if(empty($location)):
                    $formErrors[] = "la localisation est Obligatoire";
                endif;

                if(empty($solde)):
                    $solde = 0.0;
                endif;

                if(!is_numeric($solde) && !empty($solde)):
                    $formErrors[] = "le solde doit contenir uniquement de nombres";
                endif;

                if(empty($devise)):
                    $formErrors[] = "Ajouter Son devise";
                endif;

                foreach($formErrors as $error):
                    echo "<div class='alert alert-danger msg'>{$error}</div>";
                endforeach;

                if(empty($formErrors)) {
                    try {
                        $insert = $Branches->insertBranch($name,$caissier,$location,$solde,$devise);
                        if($insert > 0) {
                            $theMsg = "<div class='alert alert-success'>Branche Ajouter Avec succes</div>";
                        }
                    }catch(PDOException $e) {
                        $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
                    }
                }

            endif;
        ?>

        

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modification de la branche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="text" hidden id="id" name="id">
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group mb-2 p-select">
                        <select class="form-control" name="caissier" id="caissier">
                            <?php
                                $caissiers = $Branches->getCashier();
                                foreach($caissiers as $caissier):
                                    echo "<option value='{$caissier['uuid']}'>{$caissier['uuUsername']}</option>";
                                endforeach;
                            ?>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="location" id="location">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="solde" id="solde">
                    </div>
                    <div class="form-group p-select mb-2">
                        <select name="devise" id="devise" class="form-control">
                            <?php    
                                $ratesObj = new Rates();
                                $Rates = $ratesObj->getRates();
                                foreach($Rates as $rt){
                                    echo "<option value='{$rt['idRR']}'>{$rt['currencyRR']}</option>";
                                }
                            ?>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="editBranch" class="btn btn-primary">Modification</button>
                </div>
            </form>
            </div>
        </div>
        </div>


        <?php
            if(isset($_POST['editBranch'])) {
                $id         = htmlspecialchars($_POST['id']); 
                $name       = htmlspecialchars($_POST['name']);
                $caissier   = htmlspecialchars($_POST['caissier']);
                $location   = htmlspecialchars($_POST['location']);
                $solde      = htmlspecialchars($_POST['solde']);
                $devise     = htmlspecialchars($_POST['devise']);

                $brnch = $Branches->getBranch($id);

                $formErrors = [];

                $checkbrnch = $Branches->checkBranchAndNotSameid($name,$id);
                
                if($checkbrnch > 0):
                    $formErrors[] = "le branch deja existe";
                endif;

                $checkCaissier = $Branches->checkCaissierBranchAndNotSameid($caissier,$id);

                if($checkCaissier > 0):
                    $formErrors[] = "le Caissier deja existe";
                endif;

                if(empty($name)):
                    $name = $brnch['bbBrancheName'];
                endif;
                
                if(empty($caissier)):
                    $caissier = $brnch['uuCaissier'];
                endif;

                if(empty($location)):
                    $location = $brnch['bbLocation'];
                endif;

                if(empty($solde)):
                    $solde = $brnch['bbBalance'];
                endif;


                if(!is_numeric($solde) && !empty($solde)):
                    $formErrors[] = "le solde doit contenir uniquement de nombres";
                endif;

                if(empty($devise)):
                    $devise = $brnch['bbCurrencyType'];
                endif;

                foreach($formErrors as $error):
                    echo "<div class='alert alert-danger msg'>{$error}</div>";
                endforeach;

                if(empty($formErrors)) {
                    try {
                        $update = $Branches->updateBranch($name,$caissier,$location,$solde,$devise,$id);
                        if($update > 0){
                            $theMsg = "<div class='alert alert-success msg'>Branche Modifier avec succes</div>";
                        }
                    } catch(PDOException $e) {
                        $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                    }
                }

            }
            
        ?>


        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Supprimer Une branche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" name="deleteBranch" class="btn btn-danger">Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <?php
            if(isset($_POST['deleteBranch'])) {
                $id = htmlspecialchars($_POST['id']);
                try {
                    $delete = $Branches->deleteBranch($id);
                    if($delete > 0){
                        $theMsg = "<div class='alert alert-success msg'>Branche a ete supprimer</div>";
                    }
                } catch(PDOException $e) {
                    $theMsg = "<div class='alert alert-warning msg'>{$e->getMessage()}</div>";
                }
            }

        ?>
    
        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class='fas fa-plus'></i> Nouveau
        </button>
        <div class="table-responsive">
            <?php
                if(isset($theMsg)) {
                    echo $theMsg;
                    header("refresh:2 ;url=branch.php");
                }
            ?>
            <table class="table table-hover table-bordered">
            <thead class="thead-primary">
                <tr>
                    <th scope="col">#ID</th>
                    <th>NomBranche</th>
                    <th>Caissier</th>
                    <th>Localisation</th>
                    <th>Solde</th>
                    <th>TypeDeDevise</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php $getbr =$Branches->getBranches(); $tot = 0; foreach($getbr as $Branche): ?>
                <tr>
                    <td scope="row"><?=$Branche['bbid'];?></td>
                    <td><a class="txt-decoration-none" href="?do=rateBranchs&idbranch=<?=$Branche['bbid'];?>"><?=$Branche['bbBrancheName'];?></a></td>
                    <td><?=$Branche['uuUsername'];?></td>
                    <td><?=$Branche['bbLocation'];?></td>
                    <td><?=($Branche['bbBalance'] - (int)$Branche['bbBalance']) == 0 ? (int)$Branche['bbBalance'] : $Branche['bbBalance'];?></td>
                    <td><?=$Branche['currencyRR'];?></td>
                    <td hidden><?=$Branche['bbCurrencyType'];?></td>
                    <td hidden><?=$Branche['uuid'];?></td>
                    <td>
                        <button type="button" class="btn btn-primary btnEditBranch" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btnDeleteBranch" data-del="<?=$Branche['bbid'];?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php $tot += ($Branche['bbBalance'] * $Branche['cost_price']); endforeach; ?>
                <tr>
                    <td class="total-solde">TOTAL:</td>
                    <td colspan="4" class="text-end">
                        <p><?=$tot;?> MRU</p>
                    </td>
                    <td colspan="4"></td>
                </tr>
            </tbody>
            </table>
        </div>
    
    <?php
        } //end Branch
        else if($do == "rateBranchs") {
            $idbranch = isset($_GET['idbranch']) && is_numeric($_GET['idbranch']) ? htmlspecialchars($_GET['idbranch']) : null;
            $RateBranchs = new RateBranchs();

            if(!empty($idbranch)){
                $bb = $RateBranchs->getBranch($idbranch);
                echo "<h1 class='text-center mt-3 mb-3'>Devises qu'utilise la branche ( {$bb['bbBrancheName']} ) à <strong>{$bb['bbLocation']}</strong></h1>";
            }


       ?>

            
        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter Un Devise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="name" placeholder="Nom du devise" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="symbol" placeholder="donner le symbole" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="cost" placeholder="Prix d'achat" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="retail" placeholder="Prix de vente" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="addRates" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
            </div>
        </div>
        </div>


        <?php

            if(isset($_POST['addRates'])){
                $name       = htmlspecialchars($_POST['name']);
                $symbol     = htmlspecialchars($_POST['symbol']);
                $cost       = htmlspecialchars($_POST['cost']);
                $retail     = htmlspecialchars($_POST['retail']);

                $formErrors = [];
                $checkRate = $RateBranchs->checkRate($name,$symbol,$idbranch);
                
                if($checkRate > 0) {
                    $formErrors[] = "le devise existe deja";
                }

                if(empty($name)) {
                    $formErrors[] = "le nom est obligatoire";
                }
                
                if(empty($symbol)) {
                    $formErrors[] = "Ajouter le symbole de votre devise";
                }

                if(empty($cost)) {
                    $formErrors[] = "le prix d'achat est obligatoire";
                }

                if(!is_numeric($cost) && !empty($cost)) {
                    $formErrors[] = "le prix d'achat doit contenir uniquement de nombres";
                }

                if(empty($retail)) {
                    $formErrors[] = "le prix de vente est obligatoire";
                }

                if(!is_numeric($retail) && !empty($retail)) {
                    $formErrors[] = "le prix de vente doit contenir uniquement de nombres";
                }

                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger msg'>{$error}</div>";
                }

                if(empty($formErrors)) {
                    try {
                        $insertRate = $RateBranchs->insertRate($idbranch,$name,$symbol,$cost,$retail);
                        if($insertRate > 0){
                            $theMsg = "<div class='alert alert-success msg'>le devise est ajouté</div>";
                        }
                    }catch(PDOException $e) {
                        $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
                    }
                }

            }

        
        ?>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier une devise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="id" name="id">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" id="symbol" name="symbol">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" id="cost" name="cost">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" id="sale" name="sale">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="btnEditForm" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
            </div>
        </div>
        </div>

        <?php
            if(isset($_POST['btnEditForm'])) {
                $id     = htmlspecialchars($_POST['id']);
                $name   = htmlspecialchars($_POST['name']);
                $symbol = htmlspecialchars($_POST['symbol']);
                $cost   = htmlspecialchars($_POST['cost']);
                $sale   = htmlspecialchars($_POST['sale']);
                
                
                $getRate = $RateBranchs->getRate($id); 
                
                $formErrors = [];

                if($RateBranchs->checkRateForUpdate($name,$symbol,$idbranch,$id) > 0){
                    $formErrors[] = "Devise Existe deja";
                }

                if(empty($name)) {
                    $name   = $getRate['nameRR'];
                }

                if(empty($symbol)) {
                    $symbol = $getRate['currencyRR'];
                }

                if(empty($cost)) {
                    $cost   = $getRate['cost_price'];
                }
                
                if(empty($sale)) {
                    $sale   =  $getRate['retail_price'];
                }

                if(!is_numeric($cost) && !empty($cost)) {
                    $formErrors[] = "le prix d'achat doit contenir uniquement de nombres";
                }

                if(!is_numeric($sale) && !empty($sale)) {
                    $formErrors[] = "le prix de vente doit contenir uniquement de nombres";
                }

                foreach($formErrors as $error):
                    echo "<div class='alert alert-danger msg'>{$error}</div>";
                endforeach;

                if(empty($formErrors)) {
                    try {
                        $update = $RateBranchs->updateRate($name,$symbol,$cost,$sale,$id);
                        if($update > 0):
                            $theMsg = "<div class='alert alert-success msg'>Devise a èté modifier</div>";
                        endif;
                    } catch(PDOException $e){
                        $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                    }
                } // end empty formErrors

            }
        
        ?>

        
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Supprimer la devise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="btnDeleteRate" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
            </div>
        </div>
        </div>


        <?php
            if(isset($_POST['btnDeleteRate'])) {
                $id = htmlspecialchars($_POST['id']);
                try {
                    $delete = $RateBranchs->deleteRate($id);
                    if($delete >0){
                        $theMsg = "<div class='alert alert-success msg'>Devise a èté supprimer</div>";
                    }
                }catch(PDOException $e) {
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }
            }
        ?>

        <a href="branch.php" class="btn btn-secondary mb-2"><i class="fas fa-angle-left"></i> Retour</a>
        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class='fas fa-plus'></i> Nouveau
        </button>
        
        <div class="table-responsive">
            <?php
                if(isset($theMsg)) { 
                    echo $theMsg;
                    header("refresh: 2; url=branch.php?do=rateBranchs&idbranch=$idbranch");
                }
            ?>
            <table class="table table-hover">
                <thead class="thead-primary">
                    <tr>
                        <th scope="col">#ID</th>
                        <th>NomDevise</th>
                        <th>Symbole</th>
                        <th>prixD'achat</th>
                        <th>prixDeVente</th>
                        <th>derniereModification</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                    $idbranch  = isset($_GET['idbranch']) && is_numeric($_GET['idbranch']) ? htmlspecialchars($_GET['idbranch']) : null;
                    $getRates = $RateBranchs->getRateBranchs($idbranch); foreach($getRates as $rate): ?>
                    <tr>
                        <td scope="row"><?=$rate['idRR'];?></td>
                        <td><?=$rate['nameRR'];?></td>
                        <td><?=$rate['currencyRR'];?></td>
                        <td><?=removecomma($rate['cost_price']);?></td>
                        <td><?=removecomma($rate['retail_price']);?></td>
                        <td><?=$rate['update_at'];?></td>
                        <td>
                            <button type="button" class="btn btn-primary editRate mb-2" data-bs-toggle="modal" data-bs-target="#editModal"><i class='fas fa-edit'></i></button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger deleteRate mb-2" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class='fas fa-trash'></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- Start DeleteTransModal -->
        
        <div class="modal fade" id="deleteTransModal" tabindex="-1" aria-labelledby="deleteTransModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTransModalLabel">Supprimer La transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="montant" id="montant">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" name="DeleteTrans" class="btn btn-danger">Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
        </div>


        
        <?php
            if(isset($_POST['DeleteTrans'])) {
                $id = $_POST['id'] && is_numeric($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
                $montant = htmlspecialchars($_POST['montant']);
                
                $formErrors = [];

                if(empty($montant)) {
                    $formErrors[] = "errors";
                }

                foreach($formErrors as $error):
                    echo "<div class='alert alert-danger'>{$error}</div>";
                endforeach;

                $prev = $Transactions->getUserWhereBrnch($idbranch)['bbBalance'];
                $newBalance = $prev - $montant;

                if(empty($formErrors)) {
                    try {
                        $delete  = $Transactions->deleteTrans($id);
                        if($delete>0){
                            $Transactions->updateBranch($newBalance,$idbranch);
                            $theMsg = "<div class='alert alert-success'>Transaction Supprimer</div>";
                        }
                    } catch(PDOException $e){
                        $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
                    }
                }

            }

        ?>


        <!-- END DeleteTransModal -->


        <div class="table-responsive">
        <h2 class='text-center mt-3 mb-5'>Les Transactions</h2>
        <table class="table table-hover table-bordered">
                <thead class='table-dark'>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Montant</th>
                        <th scope="col">net Convert</th>
                        <th scope="col">Benefice</th>
                        <th scope="col">Type</th>
                        <th scope="col">Date</th>
                        <th scope="col">
                            supprimer                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $getbrnch = $Transactions->getUserWhereBrnch($idbranch);?>
                    <?php $getTrans = $Transactions->getTrans($idbranch); $tot = 0; foreach($getTrans as $trans): ?>
                    <tr>
                        <td scope="row"><?=$trans['ttID'];?></td>
                        <td><?=removeComma($trans['ttMontant']) . " <strong>" . $Transactions->getRateBrnchWheridRR($trans['ttFromCurrency'])['currencyRR'] . "</strong>" ;?></td>
                        <td><?=removeComma($trans['ttNetConvert']) . " <strong>" . $Transactions->getRateBrnchWheridRR($trans['ttToCurrency'])['currencyRR'] . "</strong>" ;?></td>
                        <td><?=removeComma($trans['ttBenef']) . " <strong>" . $Transactions->getSymbol($getbrnch['bbCurrencyType'])['currencyRR'] . "</strong>" ;?></td>
                        <td><?=$trans['ttType'];?></td>
                        <td><?=$trans['ttDate'];?></td> 
                        <td>
                            <button type="button" class="btn btn-danger btnDeleteTrans" data-bs-toggle="modal" data-bs-target="#deleteTransModal">
                                <i class='fas fa-trash'></i>
                            </button>
                        </td>
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
        }

        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: ../index.php");
    endif;

    ob_end_flush();
?>