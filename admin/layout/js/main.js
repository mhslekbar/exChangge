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


    $(document).on("click",".deleteRate",function(){
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

    $(document).on("click",".btnDeleteTrans",function(){
        var data = $(this).closest("tr").children("td").map(function(){
            return $(this).text();
        }).get();
        
        $("#deleteTransModal #id").val(data[0]);
        $("#deleteTransModal #montant").val(data[1] . split(" ")[0]);
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
                    var i=1;
                    $.each(res,function(key,val){
                        $(".showHistoryOnTable").append("<tr>\
                            <td>"+i+"</td>\
                            <td>"+val['ppPay']+"</td>\
                            <td>"+val['ppType']+"</td>\
                            <td>"+val['date']+"</td></tr>");
                        i++;
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

    /** STart Benef Stats */

    $(document).on("change",".chooseBrnch",function() {
        var idBrnch     = $(this).val();
        var tomorrow   = $("#tomorrow").val();
        var today       = $("#today").val();
        var theDate = new Date();
            
        if(idBrnch != ""){
            $.ajax({
                url: "Ajax/BenefStats.ajax.php?do=getBenefOfBrnch",
                method: "POST",
                data:{
                    "idBrnch"  : idBrnch,
                    "tomorrow": tomorrow,
                    "today"    : today
                },
                success: function(res){
                    $(".content p#trans").text((res.split(" ")[0] * res.split(" ")[2]).toFixed(3));
                    $(".content p#noCust").text((res.split(" ")[1] * res.split(" ")[2]).toFixed(3));
                    var tot = parseFloat($(".content p#trans").text()) + parseFloat($(".content p#noCust").text());
                    
                    $(".spanTot total").text(parseFloat(tot) + " MRU");
                }
            });
        }else {
            
            $.ajax({
                url: "Ajax/BenefStats.ajax.php?do=getBenefOfAllBrnch",
                method: "POST",
                data:{
                    "tomorrow": tomorrow,
                    "today"    : today
                },
                success: function(res){
                    // alert(res);
                    $(".content p#trans").text((res.split(" ")[0]));
                    $(".content p#noCust").text((res.split(" ")[1]));
                    var tot = parseFloat($(".content p#trans").text()) + parseFloat($(".content p#noCust").text());
                    
                    $(".spanTot total").text(parseFloat(tot) + " MRU");
                }
            });
        }        
        
    });

    // tomorrow 
     
    $(document).on("change","#tomorrow",function(){
        var tomorrow   = $(this).val();
        var idBrnch     = $(".chooseBrnch").val();
        var today       = $("#today").val();
        
        if(idBrnch != ""){
            $.ajax({
                url: "Ajax/BenefStats.ajax.php?do=getBenefOfBrnch",
                method: "POST",
                data:{
                    "idBrnch"  : idBrnch,
                    "tomorrow": tomorrow,
                    "today"    : today
                },
                success: function(res){
                    $(".content p#trans").text((res.split(" ")[0] * res.split(" ")[2]).toFixed(3));
                    $(".content p#noCust").text((res.split(" ")[1] * res.split(" ")[2]).toFixed(3));
                    var tot = parseFloat($(".content p#trans").text()) + parseFloat($(".content p#noCust").text());
                    
                    $(".spanTot total").text(parseFloat(tot) + " MRU");
                }
            });
        }else {
            $.ajax({
                url: "Ajax/BenefStats.ajax.php?do=getBenefOfAllBrnch",
                method: "POST",
                data:{
                    "tomorrow": tomorrow,
                    "today"    : today
                },
                success: function(res){
                    // alert(res);
                    $(".content p#trans").text((res.split(" ")[0]));
                    $(".content p#noCust").text((res.split(" ")[1]));
                    var tot = parseFloat($(".content p#trans").text()) + parseFloat($(".content p#noCust").text());
                    
                    $(".spanTot total").text(parseFloat(tot) + " MRU");
                }
            });
        }
        
    });

    // Today 

    $(document).on("change","#today",function(){
        var today       = $(this).val();
        var tomorrow   = $("#tomorrow").val();
        var idBrnch     = $(".chooseBrnch").val();
        
        if(idBrnch != ""){
            $.ajax({
                url: "Ajax/BenefStats.ajax.php?do=getBenefOfBrnch",
                method: "POST",
                data:{
                    "idBrnch"  : idBrnch,
                    "tomorrow": tomorrow,
                    "today"    : today
                },
                success: function(res){
                    $(".content p#trans").text((res.split(" ")[0] * res.split(" ")[2]).toFixed(3));
                    $(".content p#noCust").text((res.split(" ")[1] * res.split(" ")[2]).toFixed(3));
                    var tot = parseFloat($(".content p#trans").text()) + parseFloat($(".content p#noCust").text());
                    
                    $(".spanTot total").text(parseFloat(tot) + " MRU");
                }
            });
        }else {
            $.ajax({
                url: "Ajax/BenefStats.ajax.php?do=getBenefOfAllBrnch",
                method: "POST",
                data:{
                    "tomorrow": tomorrow,
                    "today"    : today
                },
                success: function(res){
                    $(".content p#trans").text((res.split(" ")[0]));
                    $(".content p#noCust").text((res.split(" ")[1]));
                    var tot = parseFloat($(".content p#trans").text()) + parseFloat($(".content p#noCust").text());
                    
                    $(".spanTot total").text(parseFloat(tot) + " MRU");
                }
            });
        }
        
    });


    /** END Benef Stats */


});
