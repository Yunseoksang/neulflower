
    // if(json.other_table_column != undefined){
    //    //console.log('other_table_column:'+json.other_table_column);
    // }
    if(json.sql_join != undefined){
        //console.log('sql_join:'+json.sql_join);
     }
    if(json.keyword_query != undefined){
       //console.log('keyword_query_result:'+json.keyword_query);
    }


    if(json.sql_where_after != undefined){
       //console.log('sql_where_after:'+json.sql_where_after);
    }


    //toast('test');

    $("#today_count").text(json.today_count);
    $("#reserved_count").text(json.reserved_count);
    $("#ready_count").text(json.ready_count);

    $("#total_client_price_sum").text(number_format(json.total_client_price_sum));
    $("#today_order_count").text(json.today_order_count);
    $("#this_month_order_count").text(json.this_month_order_count);




