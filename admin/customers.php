<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        $pageTitle = "Clients";
        include "init.php";
        $Customers = new Customers();
        echo "<div class='container container-500 customers'>";

        $do = isset($_GET['do']) ? htmlspecialchars($_GET['do']) : "Clients";
        if($do == "Clients"){
            echo "<h1 class='text-center mt-3 mb-3'>Gestion de Clients</h1>";
        // print_r($Customers->getCustomers());
    ?>    

    <!-- Start Add Client -->
    <?php 
        //  Add New CLient

        if(isset($_POST['btnAddClient'])) {
            $fname      = htmlspecialchars($_POST['fname']);
            $phone      = htmlspecialchars($_POST['phone']);
            $carteid    = htmlspecialchars($_POST['carte']);
            $addr       = htmlspecialchars($_POST['address']);
            $solde      = htmlspecialchars($_POST['solde']);

            $formErrors = [];

            if($Customers->checkCustomer($phone,$carteid) > 0) {
                $formErrors[] = "Le compte existe deja";
            }

            if(empty($fname)) {
                $formErrors[] = "Le nom Est obligatoire";
            }

            if(empty($phone)) {
                $formErrors[] = "Le numero de telephone Est obligatoire";
            }
            
            if(empty($carteid)) {
                $formErrors[] = "Le numero de la carte d'identite Est obligatoire";
            }

            if(empty($addr)) {
                $formErrors[] = "L'addresse du client Est obligatoire";
            } 
            
            if(!is_numeric($solde) && !empty($solde)) {
                $formErrors[] = "Le solde doit contenir uniquement des nombres";
            }

            $AncienBalance = $Customers->getBranchWHereLoc($addr);    
            $newBalance = 0.0;
            if(!empty($AncienBalance)) {
                $newBalance = $AncienBalance['bbBalance'] + $solde;
            }
            
            foreach($formErrors as $error):
                echo "<div class='alert alert-danger msg'>{$error}</div>";
            endforeach;

            if(empty($formErrors)){
                try {
                    $insert = $Customers->insertCustomers($fname,$phone,$carteid,$addr,$solde,$_SESSION['userid']);
                    if($insert > 0){
                        $Customers->updateBranch($newBalance,$addr);
                        $theMsg = "<div class='alert alert-success msg'>Client Ajouter Avec succés</div>";
                    }
                } catch(PDOException $e) {
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }

            }


        }
                    
    ?>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Ajouter un Client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <div class="form-group mb-2">
                    <input type="text" class="form-control" name="fname" placeholder="Ajouter le nom" value="<?= $fname ??null?>">
                </div>
                <div class="form-group mb-2">
                    <input type="text" class="form-control" name="phone" placeholder="Ajouter le numero de telephone" value="<?= $phone ?? null?>">
                </div>
                <div class="form-group mb-2">
                    <input type="text" class="form-control" name="carte" placeholder="Ajouter le No de carte d'identité" value="<?= $carteid ?? null?>">
                </div>
                <div class="form-group mb-2 p-select">
                    <select class="form-control" name="address" >
                        <option value="">Choisir Son Address</option>
                        <?php
                            $locations = $Customers->getLocations();
                            foreach($locations as $location){
                                echo "<option value='{$location['bbid']}'"; 
                                    echo isset($addr)  && $addr == $location['bbid'] ? "selected" : null;
                                echo ">{$location['bbLocation']}</option>";
                            }
                        ?>
                    </select>
                    <i class="fas fa-angle-down p-arrow"></i>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="solde" placeholder="Ajouter le solde" value="<?=$solde??null?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" name="btnAddClient" class="btn btn-success">Enregistrer</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <!-- ENd Add Client -->

    <!-- Start Approve -->

    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="approveModalLabel">Approuver un client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="id" id="id">
                    <p>Vous Avez Vraiment Confiance en <client></client> ??</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="ApproverCLient" class="btn btn-success">Approuver</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['ApproverCLient'])){
            $id = htmlspecialchars($_POST['id']);
            try {
                $approve = $Customers->approveCustomer($id);
                $theMsg = "<div class='alert alert-success msg'>Client a èté Approver</div>";
            } catch(PDOException $e) {
                $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
            }
        }
    ?>

    <!-- End Approve -->

    <!-- Start DESApprove -->

    <div class="modal fade" id="desapproveModal" tabindex="-1" aria-labelledby="desapproveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="desapproveModalLabel">DesApprouver un client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="id" id="id">
                    <p>Vous n'avez plus Confiance en <client></client> ??</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="DesApproverCLient" class="btn btn-warning">DesApprouver</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['DesApproverCLient'])){
            $id = htmlspecialchars($_POST['id']);
            try {
                $approve = $Customers->desApproveCustomer($id);
                $theMsg = "<div class='alert alert-success msg'>Client a èté DesApprover</div>";
            } catch(PDOException $e) {
                $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
            }
        }
    ?>

    <!-- End DesApprove -->


    <!-- Start Edit Modal -->
    
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Modifier Un Client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <div class="form-group mb-2">
                    <input type="hidden" class="form-control" name="id" id="id">
                </div>
                <div class="form-group mb-2">
                    <label for="fname">Nom complet</label>
                    <input type="text" class="form-control" name="fname" id="fname">
                </div>
                <div class="form-group mb-2">
                    <label for="phone">Telephone</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                </div>
                <div class="form-group mb-2">
                    <label for="carteid">Numero de la carte d'identité</label>
                    <input type="text" class="form-control" name="carteid" id="carteid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" name="BtnEditClient" class="btn btn-primary">Modifier</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['BtnEditClient'])) {
            $id         = htmlspecialchars($_POST['id']);
            $fname      = htmlspecialchars($_POST['fname']);
            $phone      = htmlspecialchars($_POST['phone']);
            $carteid    = htmlspecialchars($_POST['carteid']);
            
            $client = $Customers->getCustomer($id);
            $formErrors= [];
        
            if($Customers->checkCustomerNotSameID($phone,$carteid,$id) > 0) {
                $formErrors[] = "le numero de telephone ou carte d'identité existe deja";
            }

            if(empty($fname)) {
                $fname = $client['ccFullName'];
            }
        
            if(empty($phone)) {
                $phone = $client['ccCellphone'];
            }

            if(empty($carteid)) {
                $carteid = $client['ccCarteID'];
            }

            foreach($formErrors as $error):
                echo "<div class='alert alert-danger msg'>{$error}</div>";
            endforeach;

            if(empty($formErrors)){
                try {
                    $update = $Customers->updateCustomer($fname,$phone,$carteid,$_SESSION['userid'],$id);
                    if($update > 0){
                        $theMsg = "<div class='alert alert-success msg'>Informations du client ont étè modifier</div>";
                    }
                } catch(PDOException $e){
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }
            }



        }
        
    ?>

    <!-- End Edit Modal -->

    <!-- Start Delete Modal -->
    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Supprimer le client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Vous-voulez vraiment supprimer le client ??</p>
                    <div class="form-group mb-2">
                        <input type="hidden" class="form-control" name="id" id="id">
                    </div>
                    <div class="form-group mb-2">
                        <input type="hidden" class="form-control" name="addr" id="addr">
                    </div>
                    <div class="form-group mb-2">
                        <input type="hidden" class="form-control" name="solde" id="solde">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="DeleteClient" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    

    <?php
        if(isset($_POST['DeleteClient'])) {
            $id = htmlspecialchars($_POST['id']);
            $addr = htmlspecialchars($_POST['addr']);
            $solde = htmlspecialchars($_POST['solde']);
            
            $formErrors = [];
            
            if(empty($id) || empty($addr) || empty($solde)) {
                $formErrors[] = "<div class='alert alert-danger msg'>There's an error</div>";
            }
            $branch = $Customers->getSoldeOfBranchWhereAddr($addr);
            $newBalance = 0.0;
            $idBrnch = 0;
            if(!empty($branch)) {
                $AncienBalance = $branch['bbBalance'];
                $newBalance = $AncienBalance - $solde;
                $idBrnch = $branch['bbid'];
            }

            if(empty($formErrors)) {
                try {
                    $delete = $Customers->deleteCustomer($id);
                    if($delete > 0) {
                        $Customers->updateBranch($newBalance,$idBrnch);
                        $theMsg = "<div class='alert alert-success msg'>Client a étè <strong>supprimer</strong></div>";
                    }
                } catch(PDOException $e) {
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }
            }
            
        }
    
    ?>


    <!-- ENd Delete Modal -->


    <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus"></i> Nouveau
    </button>

    <div class="table-responsive">
        <p>
            <?php
                if(isset($theMsg)){
                    echo $theMsg;
                    header("refresh: 2; url=customers.php");
                }
            ?>
        </p>
        <table class="table">
            <thead class="thead-primary">
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Nom et prenom</th>
                    <th scope="col">Telephone</th>
                    <th scope="col">Carte d'identification</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Solde</th>
                    <th scope="col">Ajouter par</th>
                    <th scope="col">Date d'ajout</th>
                    <th scope="col">Derniere mise à jour</th>
                    <th scope="col">Approuver</th>
                    <th scope="col">DésApprouver</th>
                    <th scope="col">Modifier</th>
                    <th scope="col">Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php $clients = $Customers->getCustomers(); foreach($clients as $client): ?>
                <tr>
                    <td scope="row"><?=$client['ccID'];?></td>
                    <td><a class="txt-decoration-none" href="?do=clientStats&idCust=<?=$client['ccID'];?>"><?=$client['ccFullName'];?></a></td>
                    <td><?=$client['ccCellphone'];?></td>
                    <td><?=$client['ccCarteID'];?></td>
                    <td><?=$Customers->getLoc($client['ccAddress'])['bbLocation'];?></td>
                    <td><?=removeComma($client['ccSolde']) . " ".$Customers->getCurrencyFromRates($Customers->getLoc($client['ccAddress'])['bbCurrencyType'])['currencyRR'];?></td>
                    <td><?=$client['uuFullName'];?></td>
                    <td><?=$client['ccAddAt'];?></td>
                    <td><?=$client['ccUpdateAt'];?></td>
                    <td>
                        <?php if($client['ccApprove'] == 0): ?>
                        <button type="button" class="btn btn-success btnApprove" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-user-check"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($client['ccApprove'] == 1): ?>
                        <button type="button" class="btn btn-warning btnDesApprove" data-bs-toggle="modal" data-bs-target="#desapproveModal">
                            <i class="fas fa-user-slash"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btnEditClient" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btnDeleteClient" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>        
    </div>
    <?php
    } // ENd CLients  
    else if($do == "clientStats") {
        $idCust = isset($_GET['idCust']) ? htmlspecialchars($_GET['idCust']) : null;
    ?>    
       <h1 class='text-center mt-3 mb-3'>Transactions de <?=$Customers->getCustomer($idCust)['ccFullName'];?></h1>
       <div class="row">
           <div class="col-md-8 offset-md-2">
            <a href="customers.php" class="btn btn-secondary mb-2"><i class="fas fa-angle-left"></i> Retour</a>
                <?php if(!empty($Customers->getTransCusts($idCust))):?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-primary">
                            <tr>
                                <th scope="col">NO</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Type</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach($Customers->getTransCusts($idCust) as $TransCust): ?>
                            <tr>
                                <th scope="row"><?=$i;?></th>
                                <td><?=removeComma($TransCust['tcAmount']) ." <strong>"  . $Customers->getRate($Customers->getBranch($Customers->getCustomer($TransCust['tcIdCust'])['ccAddress'])['bbCurrencyType'])['currencyRR'];?></strong></td>
                                <td><?=$TransCust['tcType'];?></td>
                                <td><?=$TransCust['tcDate'];?></td>
                            </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else:
                    echo "<div class='alert alert-info msg'>Aucune Transaction faite</div>";
                endif; ?>
           </div>
       </div>

    <?php
    } // client Statistiques
    else {
        header("Location: customers.php");
    }            
        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: index.php");
    endif;
    ob_end_flush();
?>