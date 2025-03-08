<link href="./contents/statistics/flower_consulting_m/css/bill.css?time=<?=time()?>" rel="stylesheet">


<div class="row">

  <div class="col-md-12 col-sm-12 col-xs-12">




  <table class="table table-bordered bill_table"  consulting_idx="<?=$_REQUEST['consulting_idx']?>">

    <tbody>
        <tr>
            <td class="tr_td head_td">
                <table class="bill_table_head">
                    <tr>
                        <th class="bth_col1">작성일</th>
                        <th rowspan=2 class="bth_col2">거 래 명 세 서</th>
                        <th class="bth_col3">일련번호</th>
                    </tr>
                    <tr>
                        <td class="bth_col4 todate"><?=$todate?></td>
                        
                        <td class="bth_col6 unique_number"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="tr_td">

            </td>
        </tr>
        <tr>
            <td class="tr_td bill_table_body_td">
                <table class="bill_table_body" id="bill_table_body">
                    <tr>
                        <td class="td_sender">
                            <table id="table_sender">
                                <tr>
                                    <th class="sender_title" colspan="4">[ 공급자 ]</th>
                                </tr>
                                <tr>
                                    <th class="sth">상호</th>
                                    <td class="std"  colspan="3">(주)늘</td>
                                </tr>
                                <tr>
                                    <th class="sth">등록번호</th>
                                    <td class="std">812-87-03546</td>
                                    <th class="sth">대표</th>
                                    <td class="std">오정환</td>
                                </tr>
                                <tr>
                                    <th class="sth">주소</th>
                                    <td class="std"  colspan="3">부산광역시 북구 백양대로 1025, 229호(구포동, 협진태양쇼핑프라자)</td>
                                </tr>
                                <tr>
                                    <th class="sth">업태</th>
                                    <td class="std">도소매,서비스업</td>
                                    <th class="sth">종목</th>
                                    <td class="std">화환,화훼류및식물</td>
                                </tr>
                                <tr>
                                    <th class="sth">TEL</th>
                                    <td class="std"></td>
                                    <th class="sth">FAX</th>
                                    <td class="std"></td>
                                </tr>
                            </table>
                        </td>
                        <td class="m_td"> </td>
                        <td class="td_receiver">
                            <table id="table_receiver">
                                <tr>
                                    <th class="receiver_title" colspan="4">[ 공급받는자 ]</th>
                                </tr>
                                <tr>
                                    <th class="sth">상호</th>
                                    <td class="std r_company_name" colspan="3" ></td>
                                </tr>
                                <tr>
                                    <th class="sth">등록번호</th>
                                    <td class="std r_biz_num"></td>
                                    <th class="sth">대표</th>
                                    <td class="std r_ceo_name"></td>
                                </tr>
                                <tr>
                                    <th class="sth">주소</th>
                                    <td class="std r_address"  colspan="3"  ></td>
                                </tr>
                                <tr>
                                    <th class="sth">업태</th>
                                    <td class="std r_biz_part"></td>
                                    <th class="sth">종목</th>
                                    <td class="std r_biz_type" ></td>
                                </tr>
                                <tr>
                                    <th class="sth">TEL</th>
                                    <td class="std r_tel"  ></td>
                                    <th class="sth">E-mail</th>
                                    <td class="std r_email"  ></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="tr_td">

            </td>
        </tr>
        <tr>
            <td class="tr_td total_tr_table_td">
                <table id="total_tr_table">
                    <tr>
                        <th class="ttt_1">합계금액(부가세포함)</th>
                        <th class="ttt_2">일금</th>
                        <th class="ttt_3"></th>
                        <th class="ttt_4">원정</th>
                        <th class="ttt_5"></th>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="tr_td">

            </td>
        </tr>
        <tr>
            <td class="tr_td bill_list_table_td">
                <table id="bill_list_table">
                    <thead>
                        <tr>
                            <th class="blt_mm">          월         </th>
                            <th class="blt_dd">          일         </th>
                            <th class="blt_product">     품목       </th>
                            <th class="blt_package">     규격       </th>
                            <th class="blt_unit">        단위       </th>
                            <th class="blt_amount">      수량       </th>
                            <th class="blt_price">       단가       </th>
                            <th class="blt_price_sum">   공급가액   </th>
                            <th class="blt_vat">         세액       </th>
                            <th class="blt_etc">         비고       </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="sample hide">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_price">       </td>
                            <td class="blt_price_sum">   </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>

                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_price">       </td>
                            <td class="blt_price_sum">   </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_price">       </td>
                            <td class="blt_price_sum">   </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                        <tr class="">
                            <td class="blt_mm">          </td>
                            <td class="blt_dd">          </td>
                            <td class="blt_product">     </td>
                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_unit_price">  </td>
                            <td class="blt_price">       </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>

                    </tbody>


                    <tfoot>
                        <tr class="">
                            <td class="blt_title" colspan="3">   합계       </td>


                            <td class="blt_package">     </td>
                            <td class="blt_unit">        </td>
                            <td class="blt_amount">      </td>
                            <td class="blt_price">       </td>
                            <td class="blt_price_sum">   </td>
                            <td class="blt_vat">         </td>
                            <td class="blt_etc">         </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>





    </tbody>
    </table>



    <!-- footer content -->

    <!-- /footer content -->

  </div>
  <!-- /page content -->
</div>

</div>

