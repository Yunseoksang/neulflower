
$(document).ready(function() {

    $(document).on("click",".yearpicker-items",function(){
        var yyyy = $(this).text().replace("년","");
        resetDateRangeMonthly(yyyy);
    });


});


function resetDateRangeMonthly(dValue){
    startDate = dValue;
    endDate = dValue;
    date_apply = "on";
    date_part = "yyyy";
    $('#datatable-main').DataTable().ajax.reload();
}
