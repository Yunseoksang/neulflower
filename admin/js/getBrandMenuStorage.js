var brandList = new Object();
var menuList = new Object();

var basicBrandIdx = 0;
var basicMenuIdx = 0;

$(document).ready(function(){
    var url = "./api/selectBrandListStorage.php";
    var str = "category=all"; 
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false, async:false,dataType: "json", beforeSend: function() {},context: this,
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

 

function applyMenuList(inMenuList,menu_master_idx) {
    //console.log("inMenuList:"+JSON.stringify(inMenuList));

    $("#menu_master_idx").find("option:not([role='base'])").remove();

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



    if(menu_master_idx == "" ||  menu_master_idx == undefined ||  menu_master_idx == "null" ||   menu_master_idx == null  || typeof  menu_master_idx === 'undefined'){
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

    }else{ //menu_master_idx를 지정했으면 해당 menu_master_idx select
        for(key in inMenuList)
        {
            num++;
            var element = inMenuList[key];
            if(element.menu_master_idx == menu_master_idx){
                $("#menu_master_idx").append("<option selected value='"+element.menu_master_idx+"'>"+element.menu_name+"</option>");
            }else{
                $("#menu_master_idx").append("<option value='"+element.menu_master_idx+"'>"+element.menu_name+"</option>");
            }
        }

    }



}


function applyMenuListEmpty() {
    $("#menu_master_idx").find("option:not([role='base'])").remove();
}

