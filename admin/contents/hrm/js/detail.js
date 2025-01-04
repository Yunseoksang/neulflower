$(document).ready(function(){




    var url = "./contents/hrm/api/getInfo.php";
    //var hrm_idx = $(this).closest("tr").attr("hrm_idx");

    var hrm_idx = $("table.table_hrm_key_info").attr("hrm_idx");

    var str = "hrm_idx="+hrm_idx; //



    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){

                console.log(result);
                
                var result_status = result.status;
                if(result_status == 1)
                {


                    $("#main_list").hide();

                    //toast(result.msg);
                    var hrm_info = result.data.hrm_info;
                    $("#detail_section").find(".name").html(hrm_info.name);
                    $("#detail_section").find(".hrm_name").attr("hrm_idx",hrm_info.hrm_idx);
                    $("#detail_section").find(".hrm_status").html(hrm_info.hrm_status);

                    $("#detail_section").find(".t_jisa").html(hrm_info.t_jisa);
                    $("#detail_section").find(".t_organization").html(hrm_info.t_organization);
                    $("#detail_section").find(".job_position").html(hrm_info.job_position);
                    $("#detail_section").find(".job_grade").html(hrm_info.job_grade);
                    $("#detail_section").find(".t_office").html(hrm_info.t_office);
                    $("#detail_section").find(".work_type").html(hrm_info.work_type);
                    $("#detail_section").find(".work_time").html(hrm_info.work_time);
                    $("#detail_section").find(".disabled_type").html(hrm_info.disabled_type);
                    $("#detail_section").find(".disabled_grade").html(hrm_info.disabled_grade);
                    $("#detail_section").find(".date_of_employment").html(hrm_info.date_of_employment);
                    $("#detail_section").find(".date_of_resignation").html(hrm_info.date_of_resignation);

                    $("#detail_section").find(".tel").html(hrm_info.tel);
                    $("#detail_section").find(".tel_guardians").html(hrm_info.tel_guardians);
                    $("#detail_section").find(".affiliate_organization").html(hrm_info.affiliate_organization);
                    $("#detail_section").find(".address").html(hrm_info.address);


                    if(hrm_info.memo == null){
                        $("#detail_section").find(".memo").html("");

                    }else{
                        $("#detail_section").find(".memo").html(nl2br(hrm_info.memo));

                    }






                    //기본 탭 설정
                    $("#myTab").find("li[role='presentation']").removeClass("active").find("a").attr("aria-expanded","false");
                    $("#myTabContent").find("div[role='tabpanel']").removeClass("active").removeClass("in");


                    $("#myTab").find("li[role='presentation']").eq(0).addClass("active").find("a").attr("aria-expanded","true");
                    $("#tab_content2").addClass("active").addClass("in");



                    $(".ul_memo_product_history").find("li.memo_li").remove();
                    var memo_product_list = result.data.memo_product_list;
                    for(var i=0;i<memo_product_list.length;i++){
                        var $memo_li = $("#memo_sample").find("li.memo_li").clone(true);
                        $memo_li.attr("memo_idx",memo_product_list[i].memo_idx);
                        $memo_li.find(".tag_title").html(memo_product_list[i].memo_status);

                        // if(memo_product_list[i].memo_status == "전화상담"){
                        //     $memo_li.find(".tag").addClass("tag_green");
                        // }else if(memo_product_list[i].memo_status == "미팅"){
                        //     $memo_li.find(".tag").addClass("tag_blue");
                        // }




                        $memo_li.find("h2 .mdate").html(memo_product_list[i].md).attr('mdate',memo_product_list[i].mdate);

                        $memo_li.find("h2 .atitle").html(memo_product_list[i].memo_title);
                        $memo_li.find(".update_datetime").html(memo_product_list[i].utime);
                        $memo_li.find(".awriter").html(memo_product_list[i].admin_name);

                        $memo_li.find(".excerpt").html(memo_product_list[i].memo_text);
                        //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
                        $(".ul_memo_product_history").append($memo_li);
                    }





                    $(".ul_memo_history").find("li.memo_li").remove();
                    var memo_list = result.data.memo_list;
                    for(var i=0;i<memo_list.length;i++){
                        var $memo_li = $("#memo_sample").find("li.memo_li").clone(true);
                        $memo_li.attr("memo_idx",memo_list[i].memo_idx);
                        $memo_li.find(".tag_title").html(memo_list[i].memo_status);
                        if(memo_list[i].memo_status == "TM"){
                            $memo_li.find(".tag").addClass("tag_warning");
                        }else if(memo_list[i].memo_status == "전화상담"){
                            $memo_li.find(".tag").addClass("tag_green");
                        }else if(memo_list[i].memo_status == "미팅"){
                            $memo_li.find(".tag").addClass("tag_blue");
                        }else if(memo_list[i].memo_status == "메일"){
                            $memo_li.find(".tag").addClass("tag_primary");
                        }

                        $memo_li.find("h2 .mdate").html(memo_list[i].md).attr('mdate',memo_list[i].mdate);
                        $memo_li.find("h2 .atitle").html(memo_list[i].memo_title);
                        $memo_li.find(".update_datetime").html(memo_list[i].utime);
                        $memo_li.find(".awriter").html(memo_list[i].admin_name);

                        $memo_li.find(".excerpt").html(memo_list[i].memo_text);
                        //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
                        $(".ul_memo_history").append($memo_li);
                    }





                    $("#table_attach_list").find("tbody").empty();
                    var attachment_list = result.data.attachment_list;
                    for(var i=0;i<attachment_list.length;i++){

                        var $attach_tr = $("#attachment_sample").find("tr").clone(true);
                        $attach_tr.attr("attachment_idx", attachment_list[i].attachment_idx);
                        $attach_tr.attr("part", attachment_list[i].part);

                        $attach_tr.find(".th_num").text(i+1);
                        var filename_ex = attachment_list[i].filename.split("/");

                        var this_lenth = filename_ex.length -1;


                        var fex = filename_ex[this_lenth].split('.');
                        var ext = fex[fex.length-1];


                        if(ext == "JPEG" || ext == "JPG" || ext == "PNG" || ext == "PDF"  || ext == "jpeg" || ext == "jpg" || ext == "png" || ext == "pdf"){
                            $attach_tr.find(".td_filename a").text(filename_ex[this_lenth]).attr("link",attachment_list[i].filename);
                        }else{
                            $attach_tr.find(".td_filename a").text(filename_ex[this_lenth]).attr("href",attachment_list[i].filename).attr("download","");

                        }



                        $("#table_attach_list").find("tbody").append($attach_tr);
                    }


                    $("#detail_section").removeClass("hide").show();

                    
                }else{
                    var msg = result.msg;
                    window.alert(msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax


















});





