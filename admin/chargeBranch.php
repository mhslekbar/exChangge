<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        $pageTitle = "Chager Branche";
        include "init.php";
        echo "<div class='container'>";
            echo "<h1 class='text-center mt-3 mb-3'>Charger Les Branches</h1>";
        $ChargerBranch = new ChargerBranch();
        
    ?>


    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Charger une branche</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <div class="form-group mb-2 p-select">
                    <select name="supplier" class="form-control">
                        <option value="">choisir le fournisseur</option>
                        <?php
                            $suppliers = $ChargerBranch->suppliers();
                            foreach($suppliers as $supplier):
                                echo "<option value='{$supplier['id']}'>{$supplier['BoutiqueName']}</option>";
                            endforeach;
                        ?>
                    </select>
                    <i class='fas fa-angle-down p-arrow'></i>
                </div>
                <div class="form-group mb-2 p-select">
                    <select name="branch" class="form-control branchOnChange">
                        <option value="">choisir la branche</option>
                        <?php
                            $branchs = $ChargerBranch->branchs();
                            foreach($branchs as $branch):
                                echo "<option value='{$branch['bbid']}'>{$branch['bbBrancheName']}</option>";
                            endforeach;
                        ?>
                    </select>
                    <i class='fas fa-angle-down p-arrow'></i>
                </div>
                <div class="form-group mb-2 montDevise">
                    <input type="number" step="0.1" name="amountDevise" id="amountDevise" class="form-control" placeholder="donner le montant en Devise">
                    <span class="ss"></span>
                    <label class="cost_price" hidden></label>
                </div>
                <div class="form-group mb-2 montDevise">
                    <input type="number" step="0.1" readonly name="amountMRU" id="amountMRU" class="form-control">
                    <span>MRU</span>
                </div>
                <div class="form-group mb-2 montDevise">
                    <input type="number" step="0.1" name="pay" id="pay" class="form-control" placeholder="Net à payer">
                    <span>MRU</span>
                </div>
                <div class="form-group mb-2 montDevise">
                    <input type="number" step="0.1" readonly name="dette" id="restant" class="form-control">
                    <span>MRU</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" name="btnAddChargeBrnch" class="btn btn-success">Ajouter</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['btnAddChargeBrnch'])) {
            $supplier       = htmlspecialchars($_POST['supplier']);
            $branch         = htmlspecialchars($_POST['branch']);
            $amountDevise   = htmlspecialchars($_POST['amountDevise']); 
            $amountMRU      = htmlspecialchars($_POST['amountMRU']); 
            $pay            = htmlspecialchars($_POST['pay']); 
            $dette          = htmlspecialchars($_POST['dette']); 

            $formErrors = [];

            if(empty($supplier)) {
                $formErrors[] = "Ajouter le fournisseur";
            }

            if(!is_numeric($supplier) && !empty($supplier)) {
                $formErrors[] = "Ajouter un fournisseur valide";
            }

            if(empty($branch)) {
                $formErrors[] = "Ajouter la branche";
            }

            if(!is_numeric($branch) && !empty($branch)) {
                $formErrors[] = "Ajouter une branche valide";
            }

            if(empty($amountDevise)) {
                $formErrors[] = "Ajouter le montant en devise";
            }

            if(empty($amountMRU) && !empty($amountDevise)) {
                $formErrors[] = "Some Errors";
            }

            if(empty($pay)) {
                $formErrors[] = "Donner le montant que vous avez à payer";
            }

            if($pay > $amountMRU) {
                $formErrors[] = "le montant payé ne peut pas etre superieur au montant demandé";
            }

            if(empty($dette) && !empty($pay)) {
                $formErrors[] = "Some errors";
            }

            foreach($formErrors as $error):
                echo "<div class='alert alert-danger msg'>{$error}</div>";
            endforeach;

            // GET SUPPLIER
            $getFourn  = $ChargerBranch->getFourn($supplier);
            if(!empty($getFourn)){
                $prevDette = $getFourn['ssDette'];
                $newDette  = (float)$dette + (float)$prevDette;
            }


            // GET BRANCH 
            $getBrnch     = $ChargerBranch->getBranch($branch); 
            if(!empty($getBrnch)) {
                $prevBalance  = $getBrnch['bbBalance'];
                $newBalance   = (float)$prevBalance + (float)$amountDevise;    
            }

            if(empty($formErrors)) {
                try {
                    $insert = $ChargerBranch->insertChargeBranch($supplier ,$branch, $amountDevise, $amountMRU, $pay);
                    if($insert > 0) {
                        $ChargerBranch->updateBranchBalance($newBalance,$branch);
                        $ChargerBranch->updateSupplierDette($newDette,$supplier);
                        $ChargerBranch->insertInPaySupplier($supplier,$pay);
                        $theMsg = "<div class='alert alert-success'>Branche a èté charger avec succés</div>";
                    }
                } catch(PDOException $e) {
                    echo "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }
            }

        }                
    
    ?>


    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Supprimer le charge</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Voulez-Vous Supprimer ??</p>
                    <div class="form-group mb-2">
                        <input type="text" hidden name="id" id="id" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" hidden name="supp" id="supp" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" hidden name="branch" id="branch" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" hidden name="amountDevise" id="amountDevise" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" hidden name="reste" id="reste" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="DeleteChargeBrnch" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['DeleteChargeBrnch'])) {
            $id = htmlspecialchars($_POST['id']);
            $supp = htmlspecialchars($_POST['supp']);
            $branch = htmlspecialchars($_POST['branch']);
            $amountDevise = htmlspecialchars($_POST['amountDevise']);
            $reste = htmlspecialchars($_POST['reste']);
    
            $formErrors = [];

            // GET SUPPLIER
            $getIdFourn = $ChargerBranch->getIDFourn($supp);
            if(!empty($getIdFourn)) {
                $getFourn  = $ChargerBranch->getFourn($getIdFourn['id']);
                $newDette = 0.0;
                if(!empty($getFourn)){
                    $prevDette = $getFourn['ssDette'];
                    $newDette  = (float)$prevDette - (float)$reste;
                }
            }
    
            // GET BRANCH 
            $getIdBrnch   = $ChargerBranch->getIDBranch($branch);
            if(!empty($getIdBrnch)) {
                $getBrnch     = $ChargerBranch->getBranch($getIdBrnch['bbid']); 
                $newBalance = 0.0;
                if(!empty($getBrnch)) {
                    $prevBalance  = $getBrnch['bbBalance'];
                    $newBalance   = (float)$prevBalance - (float)$amountDevise;    
                }    
            }

            if(empty($id) || empty($supp) || empty($branch) || empty($amountDevise) || empty($reste)):
                $formErrors[] = "<div class='alert alert-danger'>Some Errors</div>";    
            endif;

            if(empty($formErrors)) {
                try {
                    $remove = $ChargerBranch->deleteChargeBrnch($id);
                    if($remove > 0){
                        $ChargerBranch->updateBranchBalance($newBalance,$getIdBrnch['bbid']);
                        $ChargerBranch->updateSupplierDette($newDette,$getIdFourn['id']);
                        $theMsg = "<div class='alert alert-success'>la charge a èté Supprimer</div>";
                    }
                } catch(PDOException $e){
                    $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
                }
            }
        }


    ?>

    <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class='fas fa-plus'></i> Nouveau
    </button>
    <div class="table-responsive">
        <p>
            <?php
                if(isset($theMsg)) {
                    echo $theMsg;
                    header("refresh: 2;url=chargeBranch.php");
                }
            ?>
        </p>
        <table class="table table-hover table-bordered">
            <thead class="thead-primary">
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Fournisseur</th>
                    <th scope="col">Branche</th>
                    <th scope="col">Montant en devise</th>
                    <th scope="col">Montant en MRU</th>
                    <th scope="col">Montant Payé</th>
                    <th scope="col">Reste</th>
                    <th scope="col">Date d'Ajout</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $charges = $ChargerBranch->getChargeBranchs(); foreach($charges as $charge):?>
                <tr>
                    <td scope="row"><?=$charge['idChargBrnch'];?></td>
                    <td><?=$charge['BoutiqueName'];?></td>
                    <td><?=$charge['bbBrancheName'];?></td>
                    <td><?=removeComma($charge['amountDevise']) . " " . $ChargerBranch->getCurrencyRR($charge['bbCurrencyType'])['currencyRR'] ;?></td>
                    <td><?=removeComma($charge['amountMRU']);?></td>
                    <td><?=removeComma($charge['amountPaye']);?></td>
                    <td><?=($charge['amountMRU'] - $charge['amountPaye']) ;?></td>
                    <td><?=$charge['add_at'];?></td>
                    <td>
                        <button type="button" class="btn btn-danger btnDeleteCharge" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class='fas fa-trash'></i>
                        </button>                        
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <?php   
        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: ../index.php");
    endif;

    ob_end_flush();

?>