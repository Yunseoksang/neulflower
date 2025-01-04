var brandList = new Object();
var menuList = new Object();



$(document).ready(function(){
    var url = "./api/selectBrandList.php";
    var str = "category=all"; 
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data;
                    obj = JSON.stringify(obj); 
                    obj = JSON.parse(obj);


                    //var couponList = new Array(); //앞에서 글로벌 변수로 선언
                    brandList = {};
                    menuList = {};
                    var firstIndex = -1;
                    for(var i=0;i<obj.brandList.length;i++){

                        var BL = obj.brandList[i];
                        

                        brandList[obj.brandList[i].brand_idx] = BL;


                        if(i==0){
                            firstIndex = BL.brand_idx;
                        }

                        for(var j=0; j<BL.menuList.length;j++){

                            menuList[BL.menuList[j].menu_master_idx]  = BL.menuList[j].menu_name;

                        }

                        
                    }


                    //첫번째 항목 호출: couponList[obj.coupon_list[0].cu_idx]
                    applyBrandList(brandList); //브랜드 선택창에 목록으로 노출하기
                    applyMenuList(brandList[firstIndex].menuList);
        

                }else{
                    var msg = result.msg;
                    window.alert(msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax


    
    //브랜드 변경하면 그때 그때 바로 저장하기.
    $(document).on("change","#brand_idx",function(){


        if(brandChangeMode == 0){  //업로드 그룹을 클릭하거나, 새로운 메뉴위에 마우스를 올렸을때 브랜드와 메뉴가 변경되어 보여질때 change 이벤트를 호출하지 않기 위한 전역변수 설정.
            //console.log("brandChangeMode:"+brandChangeMode);
            brandChangeMode = 1;
            return;
        }

       //console.log(parseInt($(this).val()));
       //console.log(brandList[parseInt($(this).val())]);

       var new_brand_idx = parseInt($(this).val());
       var cu_idx = $("#coupon_img").attr("cu_idx");




            var url = "./contents/buy_in/api/updateBrandMenu.php";
            var str = "mode=brand&cu_idx="+cu_idx+"&brand_idx="+new_brand_idx; 
            //console.log(str);
            console.log("API 호출 url: " + url);
            console.log("API 호출 parameter: " + str);


            $.ajax( { 
                    type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                    success: function(result){
                        
                        var result_status = result.status;
                        if(result_status == 1)
                        {

                            toast('변경되었습니다.');


                            //$('#menu_master_idx').val(brandList[parseInt($(this).val())].menuList.menu_master_idx).trigger('change');  //선택 초기화.
                            menuChangeMode = 0;//change 이벤트 금지.
                            $('#menu_master_idx').val(null).trigger('change');  //선택 초기화.
                            couponList[cu_idx].menu_master_idx = null;
                            couponList[cu_idx].brand_idx = new_brand_idx;



                            if(new_brand_idx > 0 && brandList[new_brand_idx].menuList != undefined){ //브랜드를 선택했으면 하위 메뉴 목록도 업데이트
                                //console.log("1111:" + new_brand_idx);
                                //console.log("1111-2:" + brandList[new_brand_idx]);
                                //console.log("1111-3:" + JSON.stringify(brandList[new_brand_idx]));
                                //console.log("1111-4:" + brandList[new_brand_idx].menuList);

                                applyMenuList(brandList[new_brand_idx].menuList);

                            }else{

                                //applyMenuList(menuList); //브랜드 목록이 정해지지 않았으면 전체 메뉴목록 보이기 => 메모리 차지가 심해서 .
                                applyMenuListEmpty();
                            }


                                
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



    //메뉴 변경하면 그때 그때 바로 저장하기.
    $(document).on("change","#menu_master_idx",function(){


        if(menuChangeMode == 0){  //업로드 그룹을 클릭하거나, 새로운 메뉴위에 마우스를 올렸을때 브랜드와 메뉴가 변경되어 보여질때 change 이벤트를 호출하지 않기 위한 전역변수 설정.
            //console.log("menuChangeMode:"+menuChangeMode);
            menuChangeMode = 1;
            return;
        }

       //console.log(parseInt($(this).val()));

       var new_menu_master_idx = parseInt($(this).val());
       var cu_idx = $("#coupon_img").attr("cu_idx");


            var url = "./contents/buy_in/api/updateBrandMenu.php";
            var str = "mode=menu&cu_idx="+cu_idx+"&menu_master_idx="+new_menu_master_idx; 
            console.log("API 호출 url: " + url);
            console.log("API 호출 parameter: " + str);


            $.ajax( { 
                    type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                    success: function(result){
                        
                        var result_status = result.status;
                        if(result_status == 1)
                        {

                            toast('변경되었습니다.');
                            //$('#brand_idx').val(result.data.brand_idx).trigger('change');  //선택 초기화.
                            couponList[cu_idx].menu_master_idx = new_menu_master_idx;
                            
                            updateTodayBuyInPrice(new_menu_master_idx,couponList[cu_idx].expire_date); //메뉴가 바뀌었으므로 가격 변화 검증.

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

    
});



function applyBrandList(inBrandList) {
    /*
    inBrandList.forEach(function(element,index,Array) {
        if(index == 0){
            $("#brand_idx").append("<option selected value='"+element.brand_idx+"'>"+element.brand_name+"</option>");

        }else{
            $("#brand_idx").append("<option value='"+element.brand_idx+"'>"+element.brand_name+"</option>");

        }
    });

    */


    var num = 0;
    for(key in inBrandList)
    {
        num++;
        var element = inBrandList[key];
        if(num == 1){
             $("#brand_idx").append("<option selected value='"+element.brand_idx+"'>"+element.brand_name+"</option>");
        }else{
            $("#brand_idx").append("<option value='"+element.brand_idx+"'>"+element.brand_name+"</option>");
        }
    }

}

 

function applyMenuList(inMenuList) {
    //console.log("inMenuList:"+JSON.stringify(inMenuList));

    $("#menu_master_idx").html("");

    /*
    inMenuList.forEach(function(element,index,Array) {
        if(index == 0){
            $("#menu_master_idx").append("<option selected value='"+element.menu_master_idx+"'>"+element.menu_name+"</option>");
        }else{
            $("#menu_master_idx").append("<option value='"+element.menu_master_idx+"'>"+element.menu_name+"</option>");
        }
    });

    */


    var num = 0;
    for(key in inMenuList)
    {
        num++;
        var element = inMenuList[key];
        if(num == 1){
             $("#menu_master_idx").append("<option selected value='"+element.menu_master_idx+"'>"+element.menu_name+"</option>");
        }else{
            $("#menu_master_idx").append("<option value='"+element.menu_master_idx+"'>"+element.menu_name+"</option>");
        }
    }



}


function applyMenuListEmpty() {
    $("#menu_master_idx").html("");
}

