<div class="row">


<div class="col-md-6 col-sm-6 col-xs-12">
  <div class="x_panel x_panel_minium">
    <div class="x_title">
      <h2>제품 선택<small></small></h2>
        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
          <input type="text" class="form-control has-feedback-left" id="product_filter" placeholder="상품명 검색">
          <span class="fa fa-search form-control-feedback left" aria-hidden="true"></span>
        </div>
      
      <ul class="nav navbar-right panel_toolbox simple_toolbox">
        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </li>
        
        <li><a class="close-link"><i class="fa fa-close"></i></a>
        </li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content product_x_content">

      <div class="">
        <ul class="to_do ul_product_list">
          <li class="li_left_header" >

            <div class="col-md-8 col-sm-2 col-xs-12">
              <input type="checkbox" class="flat icheckbox" name='product' value=''>
              <span class='li_product_name'>제품명</span>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-6 form-group ll_client_price">
                  <span class="client_price">단가</span>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-6 form-group pull-right ll_client_tax">
                  <span class="client_price_tax">부가세</span>
            </div>

          </li>
        </ul>
      </div>
    </div>
  </div>
</div>


<div class="col-md-6 col-sm-6 col-xs-12">
  <div class="x_panel x_panel_minium">
    <div class="x_title">
      <h2>주문 제품<small></small></h2>
      <ul class="nav navbar-right panel_toolbox simple_toolbox">
        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </li>
        
        <li><a class="close-link"><i class="fa fa-close"></i></a>
        </li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content product_right_content">
        
      <div class="">
          <ul class="to_do ul_product_list_right ">

            <li class="li_right_header" >
              <div class="col-md-5 col-sm-5 col-xs-12 rl_product_name">
                  <input class="flat icheckbox " type=checkbox checked="checked" name='product_idx' value='' >
                  <span class='li_product_name'>상품명</span>

              </div>
              <div class="col-md-3 col-sm-3 col-xs-6 rl_unit_price">
                  <span>단가 </span>

              </div>
              <div class="col-md-2 col-sm-2 col-xs-4 rl_vat">
                  <span>부가세 </span>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-6 pull-right rl_input">
                  <span>수량 </span>
              </div>

            </li>
          </ul>



          <ul class="to_do selected_total_ul">
            <li class="li_summary">
              <span class='total'>Total</span> <span class=''>품목수:</span>  <span class='selected_product_num'>0</span> 
              
                <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right ">
                  <span class='total_selected_cnt'>총수량:</span> <span class='order_count_sum'>0 개</span>
                </div>
            </li>
            <li>
                <div class="col-md-8 col-sm-8 col-xs-12 form-group pull-right div_total_client_price ">
                  <span>공급단가:</span> <span class='total_client_price'>0 원</span> <span class="spliter">|</span> 
                  <span>부가세:</span> <span class='total_client_price_tax'>0 원</span>
                </div>
            </li>
            <li class="li_total_client_price_sum_calcu">
                <div class="col-md-8 col-sm-8 col-xs-12 form-group pull-right div_total_client_price_sum_calcu ">
                  <span>합계금액:</span> <span class='total_client_price_sum_calcu'>0 원</span>
                </div>
            </li>


          </ul>


      </div>
    </div>
  </div>
</div>


</div>



<div class='hide' id='product_li_sample'>
          
          <li product_idx=''  product_part=''  client_product_idx="" client_price="" client_price_tax="" client_price_sum="" >

              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="checkbox" class="flat icheckbox" name='product' value=''>
                <span class='li_product_name'>제품명</span>
              </div>

              <div class="col-md-2 col-sm-2 col-xs-6 form-group ll_client_price ">
                    <span class="client_price">11,000</span><span> 원</span>
              </div>

              <div class="col-md-2 col-sm-2 col-xs-6 form-group ll_client_tax pull-right">
                    <span class="client_price_tax">1,100</span><span> 원</span>
              </div>

              <div style='clear:right;'></div>
          </li>
          
      </div>


      <div class='hide' id='product_li_add_sample'>
          <li product_idx=''   product_part=''  client_product_idx="" client_price="" client_unit_price="" client_price_tax="" >
            <div class="col-md-5 col-sm-5 col-xs-12 rl_product_name">
                <input class="flat icheckbox " type=checkbox checked="checked" name='product_idx' value='' >
                <span class='li_product_name'>추가할 제품명</span>

            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 rl_unit_price">
                <span class="client_price" client_price="11000">11,000</span><span  class="won"> 원</span>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3 rl_vat">
                <span class="client_price_tax" client_price_tax="11000">11,00</span><span  class="won"> 원</span>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-3 pull-right rl_input">
                <input type=number name='cnt' value='1' class='input_cnt'>
                <i class='fa fa-minus-circle red btn_x_circle'></i>
            </div>

          </li>
      </div>
