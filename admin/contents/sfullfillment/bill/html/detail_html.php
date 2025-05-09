

    <div class="row" id="right_settlement_list">



      <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="x_panel m_x_panel  col-md-6 col-sm-6 col-xs-6">
          
          
          <div class="x_title" >
            <div class="col-md-4 col-sm-4 col-xs-4">
                <h2 style="padding:10px;"><span id="right_category1_name">카테고리</span><small id="right_total_list_cnt"> 총: 0 건</small> </h2>
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8 title_right_col" style="text-align:right; padding-right:0px;">
                <div id="datatable-bill-popup_filter" class="">
                     <input type="text" name="right_keyword" class="form-control" placeholder="검색" aria-controls="datatable-bill-popup" autocomplete="off" style="display: inline-block; width:200px;">
                    <div class="pull-right" style="margin-left:3px; display: inline-block;"></div>

                </div>

                
            </div>
            
            <div class="clearfix"></div>
          </div>




          <!--style>
        /* Hide default checkbox */
        input.bill_checkbox {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 20px;
            height: 20px;
            background-color: #f1f1f1;
            border: 0;
            outline: none;
            cursor: pointer;
            position: relative;
        }
        
        /* Custom checkbox styles */
        input.bill_checkbox:checked::before {
            content: '✔';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #f1f1f1;
            color: #000;
            text-align: center;
            line-height: 20px;
        }
    </style -->

          <div class="x_content">
            <div class="row">
              <div class="col-sm-12 table_col_wrap">
                <div class="card-box table-responsive">
                  <p class="text-muted font-13 m-b-30">
                    <!--- 타이틀 설명---->
                  </p>

                  <table class="table table-striped dataTable mt-0" id="datatable-bill-popup">
                    <thead class="bg-secondary">
                        <tr>
                            <th class="bill_check"><input type=checkbox  class="bill_checkbox select-all-popup icheck " ></th>
                            <th class="bill_month">정산월</th>
                            <th class="unique_number">거래명세서 발급번호</th>
                            <th class="unique_number">거래명세서 발급상태</th>

                            <th class="oocp_idx">주문번호</th>
                            <th class="io_status">출고상태</th>

                            <th class="order_date">발주일</th>
                            <th class="out_date">배송일</th>

                            <th class="product_name">상품명</th>
                            <th class="order_count">수량</th>
                            <th class="client_price">단가</th>
                            <th class="price_sum">공급가액</th>
                            <th class="tax_sum">세액</th>
                            <th class="to_name">받는이</th>
                            <th class="bigo">비고</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="sample hide">
                            <td class="bill_check"><input type=checkbox name="oocp_idx[]" class="bill_checkbox icheck " ></td>
                            <td class="bill_month"></td>
                            <td class="unique_number"></td>
                            <td class="bill_status"></td>

                            <td class="oocp_idx"></td>
                            <td class="io_status"></td>

                            <td class="order_date"></td>
                            <td class="out_date"></td>

                            <td class="product_name"></td>
                            <td class="order_count"></td>
                            <td class="client_price"></td>
                            <td class="total_client_price"></td>
                            <td class="total_client_price_tax"></td>
                            <td class="to_name"></td>
                            <td class="bigo" title="수정하려면 더블클릭하세요" >
                              <!-- <input type="text" class="form-control input_bigo" name="bigo">
                              <button class="btn btn-success hide btn_save_bigo">저장</button> -->

                              <i class="fa fa-edit pointer btn_bigo_edit" title="입력/수정"></i>
                            </td>

                        </tr>

                    </tbody>
                    
                  </table>
                                
                </div>
              </div>
            </div>
          </div>


        </div>



        <!-- footer content -->

        <div style="text-align:center;">
          <button class="btn btn-success btn-bill-publish">거래명세서 저장</button>
          <!-- <button class="btn btn-primary btn-bill-complete hide">거래명세서 저장완료</button> -->
          <button class="btn btn-danger btn-bill-close hide">닫기</button>

        </div>

        <!-- /footer content -->

      </div>
      





    </div><!-- /right_settlement_list -->



    <div class="row hide" id="right_settlement_sheet">

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel m_x_panel  col-md-6 col-sm-6 col-xs-6">
          
          
          <div class="x_title" >
            <div class="col-md-4 col-sm-4 col-xs-4">
                <h2 style="padding:10px;">목록<small id="right_total_list_cnt"> 총: 0 건</small> </h2>
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8 title_right_col" style="text-align:right; padding-right:0px;">
                <div id="datatable-right_filter" class="">
                     <input type="text" name="right_keyword" class="form-control" placeholder="검색" aria-controls="datatable-right" autocomplete="off" style="display: inline-block; width:200px;">
                    <div class="pull-right" style="margin-left:3px; display: inline-block;"></div>

                </div>

                
            </div>
            
            <div class="clearfix"></div>
          </div>



          <div class="x_content">
            <div class="row">
              <div class="col-sm-12 table_col_wrap">
                <div class="card-box table-responsive">
                  <p class="text-muted font-13 m-b-30">
                    <!--- 타이틀 설명---->
                  </p>

                  <table class="table table-striped dataTable mt-0" id="datatable-right">
                    <thead class="bg-secondary">
                        <tr>
                            <th class="oocp_idx">주문번호</th>
                            <th class="out_date">배송일</th>
                            <th class="product_name">상품명</th>
                            <th class="order_count">수량</th>
                            <th class="client_price">단가</th>
                            <th class="price_sum">공급가액</th>
                            <th class="tax_sum">세액</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="sample hide">
                            <td class="oocp_idx"></td>
                            <td class="out_date"></td>
                            <td class="product_name"></td>
                            <td class="order_count"></td>
                            <td class="client_price"></td>
                            <td class="total_client_price"></td>
                            <td class="total_client_price_tax"></td>
                        </tr>

                    </tbody>
                    
                </table>
                                
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- footer content -->

        <!-- /footer content -->

      </div>
      <!-- /page content -->
    </div><!-- /right_settlement_sheet -->




