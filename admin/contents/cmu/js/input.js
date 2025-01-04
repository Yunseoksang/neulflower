
$(document).ready(function(){





    $(document).on("click",".btn_cancel",function(e){

        var cmu_idx = parseInt($(this).attr("cmu_idx"));
        if(cmu_idx > 0){

            window.location.href='?page=cmu/detail&cmu_idx='+cmu_idx;

        }else{
            $("div.input_area input").val("");
            $("div.input_area textarea").val("");

        }



    });

    




    $(document).on("click",".btn_save",function(e){

        var url = "./contents/cmu/api/insertRow.php";


        var title                   = $("#title").val();
        var t_jisa                  = $("#t_jisa").val();
        var manager                 = $("#manager").val();
        var c_company               = $("#c_company").val();
        var expense                 = $("#expense").val();
        var payment_date            = $("#payment_date").val();
        var amount                  = $("#amount").val();

        


        if(title == ""){
            toast("관리명은 필수입니다. JV");
            return;
        }




        var cmu_idx = parseInt($(this).attr("cmu_idx"));
        if(cmu_idx > 0){
            var data = {
                mode: "edit",
                cmu_idx: cmu_idx,

                title                   :   title                   ,
                t_jisa                  :   t_jisa                  ,
                manager                 :   manager                 ,
                c_company               :   c_company               ,
                expense                 :   expense                 ,
                payment_date            :   payment_date            ,
                amount                  :   amount                  
                
    
            };
        }else{
            var data = {
                mode: "input",

                title                   :   title                   ,
                t_jisa                  :   t_jisa                  ,
                manager                 :   manager                 ,
                c_company               :   c_company               ,
                expense                 :   expense                 ,
                payment_date            :   payment_date            ,
                amount                  :   amount                  

            };
        }








        var str = JSON.stringify(data); //

        console.log(url);
        console.log(str);
    
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {

                        if(cmu_idx == undefined){
                            var cmu_idx = result.data.cmu_idx;
                        }

                        console.log(result);

                        showToast('저장되었습니다');
                        window.location.href='?page=cmu/detail&cmu_idx='+cmu_idx;

                        //window.alert('저장되었습니다.');

                        
                    }else{
                        var msg = result.msg;
                        window.alert(msg);
                        console.log(result.data.query_insert);
                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax

    });






});

