<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        $pageTitle = "Fournisseurs";
        include "init.php";
        echo "<div class='container'>";
            echo "<h1 class='text-center mt-3 mb-3'>Fournisseurs</h1>";
        $Suppliers = new Suppliers();
    ?>



    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Ajouter un fournisseur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="fname" placeholder="Donner le nom de fournisseur" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="boutiqueName" placeholder="donner le nom de sa boutique" required>
                    </div>
                    <div class="form-group mb-2">
                        <input type="number" step="any" class="form-control" name="dette" placeholder="donner la dette">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="addSupplier" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['addSupplier'])) {
            $fname      = htmlspecialchars($_POST['fname']);
            $butikName  = htmlspecialchars($_POST['boutiqueName']);
            $dette      = htmlspecialchars($_POST['dette']);
            
            $check = $Suppliers->checkSupp($butikName);

            $formErrors = [];

            if($check > 0) {
                $formErrors[] = "la boutique existe deja";
            }

            if(empty($fname)):
                $formErrors[] = "Le nom du fournisseur est obligatoire";
            endif;

            if(empty($butikName)):
                $formErrors[] = "Le Nom de la boutique est obligatoire";
            endif;

            if(!is_numeric($dette) && !empty($dette)):
                $formErrors[] = "Le montant doit etre numerique";
            endif;

            foreach($formErrors as $error):
                echo "<div class='alert alert-danger msg'>{$error}</div>";
            endforeach;

            if(empty($formErrors)):
                try {
                    $insert = $Suppliers->insertSupplier($fname,$butikName,$dette);
                    if($insert > 0) {
                        $theMsg = "<div class='alert alert-success msg'>Fournisseur Ajouter avec succes</div>";
                    }
                } catch(PDOException $e) {
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }
            endif;

        }
    ?>
    

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Modifier Fournisseur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <input type="hidden" class="form-control" name="id" id="id">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="fname" id="fname">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="butikName" id="butikName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="editSupplier" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <?php

        if(isset($_POST['editSupplier'])) {
            $id         = htmlspecialchars($_POST['id']);
            $fname      = htmlspecialchars($_POST['fname']);
            $butikName  = htmlspecialchars($_POST['butikName']);
            
            $supp = $Suppliers->getSupp($id);
            
            if(empty($fname)) {
                $fname = $supp['FullName'];
            }

            if(empty($butikName)) {
                $butikName = $supp['BoutiqueName'];
            }

            try {
                $update = $Suppliers->updateSupp($fname,$butikName,$id);
                if($update > 0):
                    $theMsg = "<div class='alert alert-success msg'>Information Modifier Avec Succes</div>";
                endif;
            } catch(PDOException $e) {
                $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
            }
        }
        
    ?>



    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Modifier Fournisseur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <input type="hidden" class="form-control" name="id" id="id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="deleteSupplier" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['deleteSupplier'])) {
            $id = htmlspecialchars($_POST['id']);

            try {
                $delete = $Suppliers->deleteSupp($id);
                if($delete > 0):
                    $theMsg = "<div class='alert alert-success msg'>Fournisseur Supprimer</div>";
                endif;
            } catch(PDOException $e) {
                $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
            }
        }
    
    
    ?>


    <!-- Modal -->
    <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="payModalLabel">Payer la dette</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="id" id="id" class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" name="dette" id="dette" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <input type="number" step=".01" name="amount" id="amount" class="form-control" placeholder="Saisir le montant que vous voulez avancer">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" name="successPayBtn" class="btn btn-success successPay">Payer</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <?php
        if(isset($_POST['successPayBtn'])) {
            $id     = htmlspecialchars($_POST['id']);
            $dette  = htmlspecialchars($_POST['dette']);
            $amount = htmlspecialchars($_POST['amount']);
            
            $formErrors = [];
            if(empty($dette)){
                $formErrors[] = "Some Errors";
            }
            if(empty($amount)){
                $formErrors[] = "Ajouter le montant de vous allez avancer au fournisseur";
            }
            
            if(($amount < 0) && !empty($amount)) {
                $formErrors[] = "le montant ne peut pas etre negative";
            }
            
            // if(($amount > $dette)) {
            //     $formErrors[] = "le montant ne peut pas etre superieur a la dette";
            // }

            foreach($formErrors as $error):
                echo "<div class='alert alert-danger msg'>{$error}</div>";
            endforeach;

            if(empty($formErrors)){
                $newDette    = (float)$dette - (float)$amount;
                try {
                    $setDett = $Suppliers->setDette($newDette,$id);;
                    if($setDett) {
                        $Suppliers->insertPaySupplier($id,$amount);
                        $theMsg = "<div class='alert alert-success'>le fournisseur est payé avec succés</div>";
                    }
                } catch(PDOException $e) {
                    $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
                }
            }
        }
    
    ?>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Voir Historique du Paiement</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="historyPay">
                <table class="table table-showHistory">
                    <thead class="table-info">
                        <tr>
                            <th>NO</th>
                            <th>Paiement</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody class="showHistoryOnTable">

                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
    </div>

    <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus"></i> Nouveau
    </button>

    <div class="table-responsive">
        <?php
            if(isset($theMsg)){
                echo $theMsg;
                header("refresh: 2; url=suppliers.php");
            }
        ?>
        <table class="table table-hover table-bordered">
            <thead class="thead-primary">
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Nom et Prenom</th>
                    <th scope="col">Nom de sa boutique</th>
                    <th scope="col">Dette</th>
                    <th scope="col">historique</th>
                    <th scope="col">Payer</th>
                    <th scope="col">Modifier</th>
                    <th scope="col">Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php $getSuppliers = $Suppliers->getSuppliers(); foreach($getSuppliers as $supplier): ?>
                <tr>
                    <td scope="row"><?=$supplier['id'];?></td>
                    <td><?=$supplier['FullName'];?></td>
                    <td><?=$supplier['BoutiqueName'];?></td>
                    <td><?=($supplier['ssDette'] - (int)$supplier['ssDette']) == 0 ? (int)$supplier['ssDette'] : $supplier['ssDette'];?></td>
                    <td>
                    <button type="button" class="btn btn-info btnShowHistory" data-idsupp="<?=$supplier['id'];?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fas fa-eye"></i>
                    </button>
                    </td>
                    <td>
                        <?php if($supplier['ssDette'] != 0): ?>
                        <button type="button" class="btn btn-success btnPaySupp" data-bs-toggle="modal" data-bs-target="#payModal">
                            <i class="fas fa-hand-holding-usd"></i>
                        </button>
                        <?php endif;?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btnEditSupp" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btnDeleteSupp" data-del="<?=$supplier['id'];?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i>
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