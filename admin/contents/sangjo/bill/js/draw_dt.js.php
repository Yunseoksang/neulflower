

if(json.sdate_spread != undefined){
    //console.log(json.sdate_spread);
    var rf = parseInt(json.recordsFiltered);
    $("#calendar_dom button .progress").css("width","0px");
    for(var i=0; i < json.sdate_spread.length; i++){
        var this_sdate_info = json.sdate_spread[i];
        var this_cnt = parseInt(this_sdate_info['cnt']);
        var this_sdate = this_sdate_info['sdate'];
        var percent = 100; //(this_cnt/rf)*100;

        $("#calendar_dom button:contains('"+this_sdate+"') .progress").css('width',percent+'%');

    }

}



if(json.sql1 != undefined){
    console.log(json.sql1);

}
