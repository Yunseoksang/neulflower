
$(document).ready(function(){





    $(document).on("click",".btn_cancel",function(e){

        var hrm_idx = parseInt($(this).attr("hrm_idx"));
        if(hrm_idx > 0){

            window.location.href='?page=hrm/detail&hrm_idx='+hrm_idx;

        }else{
            $("div.input_area input").val("");
            $("div.input_area textarea").val("");

        }



    });

    




    $(document).on("click",".btn_save",function(e){

        var url = "./contents/hrm/api/insertRow.php";


        var name                    = $("#name").val();

        var t_jisa                  = $("#t_jisa").val();
        var t_organization          = $("#t_organization").val();
        var t_office                = $("#t_office").val();

        var job_position            = $("#job_position").val();
        var job_grade               = $("#job_grade").val();
        var work_type               = $("#work_type").val();
        var work_time               = $("#work_time").val();
        var date_of_employment      = $("#date_of_employment").val();
        var date_of_resignation     = $("#date_of_resignation").val();
        var disabled_type           = $("#disabled_type").val();
        var disabled_grade          = $("#disabled_grade").val();
        var tel                     = $("#tel").val();
        var tel_guardians           = $("#tel_guardians").val();
        var affiliate_organization  = $("#affiliate_organization").val();
        var address                 = $("#address").val();
        var memo                    = $("#memo").val();
        


        if(name == ""){
            toast("이름은 필수입니다. JV");
            return;
        }




        var hrm_idx = parseInt($(this).attr("hrm_idx"));
        if(hrm_idx > 0){
            var data = {
                mode: "edit",
                hrm_idx: hrm_idx,

                name                    :   name                    ,
                t_jisa                  :   t_jisa                  ,
                t_organization          :   t_organization          ,
                t_office                :   t_office                ,
                job_position            :   job_position            ,
                job_grade               :   job_grade               ,
                work_type               :   work_type               ,
                work_time               :   work_time               ,
                date_of_employment      :   date_of_employment      ,
                date_of_resignation     :   date_of_resignation     ,
                disabled_type           :   disabled_type           ,
                disabled_grade          :   disabled_grade          ,
                tel                     :   tel                     ,
                tel_guardians           :   tel_guardians           ,
                affiliate_organization  :   affiliate_organization  ,
                address                 :   address                 ,
                memo                    :   memo                    
    
            };
        }else{
            var data = {
                mode: "input",

                name                    :   name                    ,
                t_jisa                  :   t_jisa                  ,
                t_organization          :   t_organization          ,
                t_office                :   t_office                ,
                job_position            :   job_position            ,
                job_grade               :   job_grade               ,
                work_type               :   work_type               ,
                work_time               :   work_time               ,
                date_of_employment      :   date_of_employment      ,
                date_of_resignation     :   date_of_resignation     ,
                disabled_type           :   disabled_type           ,
                disabled_grade          :   disabled_grade          ,
                tel                     :   tel                     ,
                tel_guardians           :   tel_guardians           ,
                affiliate_organization  :   affiliate_organization  ,
                address                 :   address                 ,
                memo                    :   memo                    

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

                        if(hrm_idx == undefined){
                            var hrm_idx = result.data.hrm_idx;
                        }

                        console.log(result);

                        showToast('저장되었습니다');
                        window.location.href='?page=hrm/detail&hrm_idx='+hrm_idx;

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

