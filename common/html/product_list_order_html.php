<link href="/common/css/product_list_order_html.css?time=<?=time()?>" rel="stylesheet" type="text/css" />

<script src="/common/js/product_list_order_html.js?time=<?=time()?>"></script>

<div class="row">


<div class="col-md-5 col-sm-5 col-xs-12">
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
              <input type="checkbox" class="icheck select-all" name='product' value=''>
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


<div class="col-md-7 col-sm-7 col-xs-12">
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


            <?php

            if($right_mode == "options"){?>

            <li class="li_right_header" >
              <div class="col-md-3 col-sm-3 col-xs-12 rl_product_name">
                  <input class="icheck select-all" type=checkbox checked="checked" name='product_idx' value='' >
                  <span class='li_product_name'>상품명</span>

              </div>
              <div class="col-md-2 col-sm-2 col-xs-6 rl_unit_price">
                  <span>단가 </span>

              </div>
              <div class="col-md-2 col-sm-2 col-xs-6 rl_option_price">
                  <span>추가금액 </span>
              </div>

              <div class="col-md-2 col-sm-2 col-xs-4 rl_options">
                  <span>옵션선택 </span>
              </div>

              <div class="col-md-1 col-sm-1 col-xs-4 rl_vat">
                  <span>부가세 </span>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-6 pull-right rl_input">
                  <span>수량 </span>
              </div>

            </li>
            <?}else{?>

            <li class="li_right_header" >
              <div class="col-md-3 col-sm-4 col-xs-12 rl_product_name">
                  <input class="icheck select-all" type=checkbox checked="checked" name='product_idx' value='' >
                  <span class='li_product_name'>상품명</span>

              </div>
              <div class="col-md-3 col-sm-3 col-xs-6 rl_unit_price">
                  <span>단가 </span>

              </div>
              <div class="col-md-2 col-sm-2 col-xs-4 rl_vat">
                  <span>부가세 </span>
              </div>
              <div class="col-md-3 col-sm-2 col-xs-6 pull-right rl_input">
                  <span>수량 </span>
              </div>

            </li>
            <?}?>
            
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
            <li class="li_total_client_price_sum">
                <div class="col-md-8 col-sm-8 col-xs-12 form-group pull-right div_total_client_price_sum ">
                  <span>합계금액:</span> <span class='total_client_price_sum'>0 원</span>
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
                <input type="checkbox" class="icheck" name='product' value=''>
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


      <?php
      if($right_mode == "options"){?>
        <div class='hide' id='product_li_add_sample'>

          <li product_idx=''   product_part=''  client_product_idx="" client_price="" client_unit_price="" client_price_tax="" >
            <div class="col-md-3 col-sm-3 col-xs-12 rl_product_name">
                <input class="icheck " type=checkbox checked="checked" name='product_idx' value='' >
                <span class='li_product_name'>추가할 제품명</span>

            </div>
            <div class="col-md-2 col-sm-2 col-xs-3 rl_unit_price">
                <span class="client_price" client_price="11000">11,000</span><span  class="won"> 원</span>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3 rl_option_price">
              <select name="option_price"  class="form-control">
                  <option value="0">추가금액</option>
                  <option value="직접입력">직접입력</option>
                  <option value="10000<">10,000원</option>
                  <option value="20000<">20,000원</option>
                  <option value="30000<">30,000원</option>
                  <option value="40000<">40,000원</option>
                  <option value="50000<">50,000원</option>
                  <option value="60000<">60,000원</option>
                  <option value="70000<">70,000원</option>
                  <option value="80000<">80,000원</option>
                  <option value="90000<">90,000원</option>
                  <option value="100000">100,000원</option>
                  <option value="110000">110,000원</option>
                  <option value="120000">120,000원</option>
                  <option value="130000">130,000원</option>
                  <option value="140000">140,000원</option>
                  <option value="150000">150,000원</option>
                  <option value="160000">160,000원</option>
                  <option value="170000">170,000원</option>
                  <option value="180000">180,000원</option>
                  <option value="190000">190,000원</option>
                  <option value="200000">200,000원</option>
                  <option value="210000">210,000원</option>
                  <option value="220000">220,000원</option>
                  <option value="230000">230,000원</option>
                  <option value="240000">240,000원</option>
                  <option value="250000">250,000원</option>
                  <option value="260000">260,000원</option>
                  <option value="270000">270,000원</option>
                  <option value="280000">280,000원</option>
                  <option value="290000">290,000원</option>
                  <option value="300000">300,000원</option>

              </select>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3 rl_options">
                <select name="options" class="form-control"> 
                  <option>옵션선택</option>
                </select>
            </div>

            <div class="col-md-1 col-sm-1 col-xs-3 rl_vat">
                <span class="client_price_tax" client_price_tax="11000">11,00</span><span  class="won"> 원</span>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-2 pull-right rl_input">
                <input type=number name='cnt' value='1' class='input_cnt form-control' >
                <i class='fa fa-minus-circle red btn_x_circle'></i>
            </div>

          </li>
        </div>
      <?}else{?>
        <div class='hide' id='product_li_add_sample'>

          <li product_idx=''   product_part=''  client_product_idx="" client_price="" client_unit_price="" client_price_tax="" >
            <div class="col-md-4 col-sm-5 col-xs-12 rl_product_name">
                <input class="icheck " type=checkbox checked="checked" name='product_idx' value='' >
                <span class='li_product_name'>추가할 제품명</span>

            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 rl_unit_price">
                <span class="client_price" client_price="11000">11,000</span><span  class="won"> 원</span>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-3 rl_vat">
                <span class="client_price_tax" client_price_tax="11000">11,00</span><span  class="won"> 원</span>
            </div>

            <div class="col-md-3 col-sm-2 col-xs-3 pull-right rl_input">
                <input type=number name='cnt' value='1' class='input_cnt form-control'>
                <i class='fa fa-minus-circle red btn_x_circle'></i>
            </div>

          </li>
        </div>
      <?}?>
      
