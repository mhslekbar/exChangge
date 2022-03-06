$(document).ready(function(){
    
    'use strict';
    

    $(".msg").slideDown(300,function(){
        $(this).append("<strong class='float-end'>x</strong>");
        $("strong").css("cursor","pointer");
    });
    $(".msg").on("click","strong",function(){
        $(this).closest(".msg").slideUp();
    });


    /** STart RAtes  */
    $(document).on("click",".editRate",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();

        $("#editModal #id").val(data[0]);
        $("#editModal #name").val(data[1]);
        $("#editModal #symbol").val(data[2]);
        $("#editModal #cost").val(data[3]);
        $("#editModal #sale").val(data[4]);
    });


    $(".Rates").on("click",".deleteRate",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#deleteModal #id").val(data[0]);
        
    });
    
    
    /** End RAtes  */

    /** STart Users */
    $(document).on("click",".btnEditUser",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();

        $("#editModal #id").val(data[0]);
        $("#editModal #username").val(data[1]);
        $("#editModal #name").val(data[2]);
        $("#editModal #phone").val(data[3]);
        $("#editModal #status").val(data[4]);

    });

    $(document).on("click",".btnDeleteUser",function(){
        $("#deleteModal #id").val($(this).data("del"));
    });
    
    /** End Users */

    /** Start Branch */
    
    $(document).on("click",".btnEditBranch",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();

        $("#editModal #id").val(data[0]);
        $("#editModal #name").val(data[1]);
        $("#editModal #caissier").val(data[7]);
        $("#editModal #location").val(data[3]);
        $("#editModal #solde").val(data[4]);
        $("#editModal #devise").val(data[6]);

    });

    $(document).on("click",".btnDeleteBranch",function(){
        var data = $(this).data("del");
        $("#deleteModal #id").val(data);
    });


    /** ENd Branch */

    /** Start Suppliers */

    $(document).on("click",".btnEditSupp",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#editModal #id").val(data[0]);
        $("#editModal #fname").val(data[1]);
        $("#editModal #butikName").val(data[2]);
    });

    $(".btnDeleteSupp").on("click",function(){
        var data = $(this).data("del");
        $("#deleteModal #id").val(data);
    });

    $(".btnPaySupp").on("click",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#payModal #id").val(data[0]);
        $("#payModal #dette").val(data[3]);
    });

    $(document).on("click",".btnShowHistory",function(){
        var idsupp = $(this).data("idsupp");
        $(".showHistoryOnTable").empty();
        $(".table-showHistory").removeClass("d-none");
        $.ajax({
            url: "Ajax/suppliers.ajax.php?do=showPaiementHistory",
            method: "POST",
            data: {"idsupp":idsupp},
            success: function(res){
                if(res != ""){
                    $.each(res,function(key,val){
                        $(".showHistoryOnTable").append("<tr>\
                            <td>"+val['ppid']+"</td>\
                            <td>"+val['ppPay']+"</td>\
                            <td>"+val['date']+"</td></tr>");
                    });
                }else {
                    $(".showHistoryOnTable").empty();
                    $(".table-showHistory").addClass("d-none");
                }
            }
        });
    });

    /** END Suppliers */



    /** Start charge Branch */

    $(document).on("change",".branchOnChange",function(){
       var idbranch = $(this).val();
       $("#amountDevise").val("");
       $("#amountMRU").val("");
       $("#pay").val("");
       $("#restant").val("");
        if(idbranch != "") {
            $.ajax({
                url: "Ajax/chargeBrnch.ajax.php?do=getCurrencyType",
                method: "POST",
                data: {"idbranch":idbranch},
                success: function(response){
                    if(response != "") {
                        var myDevise = response.split(":");
                        $(".montDevise span.ss").text(myDevise[0]);
                        $(".montDevise label.cost_price").text(myDevise[1]);
                    }
                }
            });
        } else {
            $(".montDevise span.ss").empty();
            $(".montDevise label.cost_price").empty();
        }
    });

    $(document).on("keyup","#amountDevise",function(){
        var cost_price = $(".montDevise label.cost_price").text();
        $("#restant").val("");
        if($(".montDevise span.ss").text() != ""){
            $("#amountMRU").val(parseFloat($(this).val()) * parseFloat(cost_price));
        }
    });

    $(document).on("keyup","#pay",function(){
        var amount = $("#amountMRU").val();
        var pay = $("#pay").val();
        $("#restant").val(parseFloat(amount) - parseFloat(pay));
    });


    // Delete CHarge 
    $(document).on("click",".btnDeleteCharge",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#deleteModal #id").val(data[0]);
        $("#deleteModal #supp").val(data[1]);
        $("#deleteModal #branch").val(data[2]);
        $("#deleteModal #amountDevise").val(data[3].split(" ")[0]);
        $("#deleteModal #reste").val(data[6]);
    });


    /** End Charger Branche */
    
    /** Start Customers */
    
    $(".btnApprove").on("click",function() {
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#approveModal #id").val(data[0]);
        $("#approveModal client").text(data[1]).css("font-weight" , "bold");

    });


    
    $(".btnDesApprove").on("click",function() {
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#desapproveModal #id").val(data[0]);
        $("#desapproveModal client").text(data[1]).css("font-weight" , "bold");

    });

    $(".btnEditClient").on("click",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#editModal #id").val(data[0]);
        $("#editModal #fname").val(data[1]);
        $("#editModal #phone").val(data[2]);
        $("#editModal #carteid").val(data[3]);
    });

    $(".btnDeleteClient").on("click",function() {
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();

        $("#deleteModal #id").val(data[0]);
        $("#deleteModal #addr").val(data[4]);
        $("#deleteModal #solde").val(data[5].split(" ")[0]);
    });



    /** End Customers */


    /** CAISSIER MODE  */

    /** START TRANSACTIONS  */

    $(document).on("change","#toCurr",function(){
        var toCurr = $(this).val();
        $("#costPrice").empty();
        $("#retailPrice").empty();
        $("#amountConvert").val("");
        $("#amountMain").val("");
        $("#amountBenef").val("");
        
        if(toCurr != "") {
            $.ajax({
                url: "Ajax/Transactions.ajax.php?do=getCurrencyPrice",
                method: "POST",
                data: {"toCurr":toCurr},
                success: function(res) {
                    if(res!=""){
                        $("#costPrice").append(res.split(" ")[0]);
                        $("#retailPrice").append(res.split(" ")[1]);
                        $(".convertMontant #symbol").text(res.split(" ")[2]);
                    }
                },
            });
        }else {
            $(".convertMontant #symbol").text("");            
        }
    });

    // Buy Currency

    $(document).on("keyup","#amountMain",function(){
        var amount = $(this).val();
        var costPrice = $("#costPrice").text();
        var retailPrice = $("#retailPrice").text();
        
        
        if(amount != '') {
            $("#amountConvert").val(amount  / costPrice);

            var X = amount * retailPrice; // fromCurr Is a retail price of this currency
            var Y = amount * costPrice; // costPrice is for thid currency
            var Z = X - Y;
            
            // var X = costPrice; //  Is a retail price of this currency
            // var Y = retailPrice; // costPrice is for thid currency
            // var Z = (Y - X) * amount;
            
            $("#amountBenef").val(Z);
        }else {
            $("#amountConvert").val("");
            $("#amountBenef").val("");
        }
    });


    // Sale Currency
    
    $(document).on("change","#fromCurrSale",function(){
        var toCurr = $(this).val();
        $("#costPrice").empty();
        $("#retailPrice").empty();
        $("#amountConvertSale").val("");
        $("#amountMainSale").val("");
        $("#amountBenefSale").val("");
        
        if(toCurr != "") {
            $.ajax({
                url: "Ajax/Transactions.ajax.php?do=getCurrencyPrice",
                method: "POST",
                data: {"toCurr":toCurr},
                success: function(res) {
                    if(res!=""){
                        $("#costPrice").append(res.split(" ")[0]);
                        $("#retailPrice").append(res.split(" ")[1]);
                        $(".convertMontant #symbol").text(res.split(" ")[2]);
                    }
                },
            });
        }else {
            $(".convertMontant #symbol").text("");            
        }
    });

    $(document).on("keyup","#amountMainSale",function(){
        var amount = $(this).val();
        var costPrice = $("#costPrice").text();
        var retailPrice = $("#retailPrice").text();
        
        
        if(amount != '') {
            $("#amountConvertSale").val(amount  * retailPrice);

            // var X = amount / retailPrice; // fromCurr Is a retail price of this currency
            // var Y = amount / costPrice; // costPrice is for thid currency

            var X = costPrice; //  Is a retail price of this currency
            var Y = retailPrice; // costPrice is for thid currency
            var Z = (Y - X) * amount;
            $("#amountBenefSale").val(Z);
        }else {
            $("#amountConvertSale").val("");
            $("#amountBenefSale").val("");
        }
    });

    $(".btnDeleteTrans").on("click",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#deleteModal #id").val(data[0]);
    });


    /** END TRANSACTIONS  */
    
    
    
    /** Start SEND AND Receipt Money   */
    $(document).on("change","#branchReceipt",function(){
        var  branchReceipt = $(this).val()
        var  branchSender  = $("#branchSender").val()
        $(".montDevise #symbolDevise").empty();
        $(".montDevise #rt_price").empty();
        $(".montDevise #ct_price").empty();
        
        if(branchReceipt  != ""){
            $.ajax({
                url: "Ajax/noCustomer.ajax.php?do=getRateOfBrnch",
                method: "POST",
                data: {"branchReceipt":branchReceipt,
                        "branchSender" : branchSender},
                success: function(res) {
                    if(res != "") {
                        $(".montDevise #rt_price").append(res.split(" ")[0]);
                        $(".montDevise #ct_price").append(res.split(" ")[1]);
                        $(".montDevise #symbolDevise").text(res.split(" ")[2]);
                    }else {
                        $(".montDevise #symbolDevise").empty();
                        $(".montDevise #rt_price").empty();
                        $(".montDevise #ct_price").empty();
    
                    }
                },
                error: function(err){
                    alert( "err:  " + err);
                }
            });
        }
    });


    $(document).on("keyup","#amountSender",function(){
        var amountSender  = $(this).val();
        var rt_price      = $("#rt_price").text(); 
        var ct_price      = $("#ct_price").text(); 
        
        var X,Y,Z;
        X = rt_price;
        Y = ct_price;
        Z = (X - Y) * amountSender;

        $("#amountReceipt").val(amountSender / rt_price);

        $("#Benef").val(Z);

    });


    $(document).on("click",".btnValider",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        $("#withDrwModal #id").val(data[0]);        
        $("#withDrwModal #branchReceipt").val(data[2]);         
    });

    // Search Rceipt

    $(document).on("keyup","#searchReceipt",function() {
        var branchReceipt = $("#branchReceipt").val();
        var searchReceipt = $(this).val();
        $(".tbl-search tbody").empty();
        $(".searchNotFound").remove();
 
        if(searchReceipt != ""){
            $.ajax({
                url: "Ajax/noCustomer.ajax.php?do=getCustomerOnSearchReceipt",
                method: "POST",
                data: {
                    "searchReceipt":searchReceipt,
                    "branchReceipt":branchReceipt,    
                    },
                success: function(res){
                    if(res != ""){
                        $(".tbl-search tbody").html(res);
                    }else {
                        $(".tbl-search").after("<span class='searchNotFound'>N'existe pas</span>");
                    }
                },
                error:function(err) {
                    alert(err);
                }
            });
        }else {
            getTransNoCustomersReceipt(branchReceipt);
        }
    });

    function getTransNoCustomersReceipt(branchReceipt) {
        $.ajax({
            url: "Ajax/noCustomer.ajax.php?do=getTransNoCustomersReceipt",
            method: "POST",
            data: {"branchReceipt" : branchReceipt},
            success: function(res){
                    if(res != ""){
                        $(".tbl-search tbody").html(res);
                    }
                },
        });

    }

    /** END SEND AND Receipt Money   */




});
