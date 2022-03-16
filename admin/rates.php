<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        $pageTitle = "Devise";
        include "init.php";
        $Rates = new Rates();

        echo "<div class='container Rates'>";
            echo "<h1 class='text-center mt-3 mb-3'>Gestion du Devise</h1>";
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

                $checkRate = $Rates->checkRate($name,$symbol);
                
                if($checkRate) {
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
                        $insertRate = $Rates->insertRate($name,$symbol,$cost,$retail);
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                
                $getRate = $Rates->getRate($id); 
                
                $formErrors = [];

                if($Rates->checkRateForUpdate($name,$symbol,$id) > 0) {
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
                        $update = $Rates->updateRate($name,$symbol,$cost,$sale,$id);
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
                    $delete = $Rates->deleteRate($id);
                    if($delete >0){
                        $theMsg = "<div class='alert alert-success msg'>Devise a èté supprimer</div>";
                    }
                }catch(PDOException $e) {
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
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
                    header("refresh: 2; url=rates.php");
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
                    <?php $getRates = $Rates->getRates(); foreach($getRates as $rate): ?>
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

        <?php
        echo "</div>";
        include $tpl . "footer.php";
    else:
        header("Location: ../index.php");
    endif;
    ob_end_flush();
?>