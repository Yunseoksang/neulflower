/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** ******  left menu  *********************** **/
$(function () {
    //$('#sidebar-menu li ul').slideUp();  //�ڵ����� �ö󰡴°� ���.
    $('#sidebar-menu li').removeClass('active');

    $('#sidebar-menu ul.side-menu>li').on('click touchstart', function() {

       $(this).siblings().find("ul").slideUp();

        var link = $('a', this).attr('href');

        if(link) { 
            window.location.href = link;
        } else {
            if ($(this).is('.active')) {
                $(this).removeClass('active');
                //$('ul', this).slideUp();
            } else {
                //$('#sidebar-menu li').removeClass('active');
                //$('#sidebar-menu li ul').slideUp();
                
                $(this).addClass('active');
                $('ul', this).slideDown();
            }
        }
    });

    $('#menu_toggle').click(function () {
        if ($('body').hasClass('nav-md')) {
            $('body').removeClass('nav-md').addClass('nav-sm');
            $('.left_col').removeClass('scroll-view').removeAttr('style');
            $('.sidebar-footer').hide();

            if ($('#sidebar-menu li').hasClass('active')) {
                $('#sidebar-menu li.active').addClass('active-sm').removeClass('active');
            }
        } else {
            $('body').removeClass('nav-sm').addClass('nav-md');
            $('.sidebar-footer').show();

            if ($('#sidebar-menu li').hasClass('active-sm')) {
                $('#sidebar-menu li.active-sm').addClass('active').removeClass('active-sm');
            }
        }
    });
});

/* Sidebar Menu active class */
$(function () {
    var url = window.location;
    $('#sidebar-menu a[href="' + url + '"]').parent('li').addClass('current-page');
    $('#sidebar-menu a').filter(function () {
        return this.href == url;
    }).parent('li').addClass('current-page').parent('ul').slideDown().parent().addClass('active');
});

/** ******  /left menu  *********************** **/
/** ******  right_col height flexible  *********************** **/
$(".right_col").css("min-height", $(window).height());
$(window).resize(function () {
    $(".right_col").css("min-height", $(window).height());
});
/** ******  /right_col height flexible  *********************** **/



/** ******  tooltip  *********************** **/
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
/** ******  /tooltip  *********************** **/
/** ******  progressbar  *********************** **/
if ($(".progress .progress-bar")[0]) {
    $('.progress .progress-bar').progressbar(); // bootstrap 3
}
/** ******  /progressbar  *********************** **/
/** ******  switchery  *********************** **/
if ($(".js-switch")[0]) {
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {
            color: '#26B99A'
        });
    });
}
/** ******  /switcher  *********************** **/
/** ******  collapse panel  *********************** **/
// Close ibox function
$('.close-link').click(function () {
    var content = $(this).closest('div.x_panel');
    content.remove();
});

// Collapse ibox function
$('.collapse-link').click(function () {
    var x_panel = $(this).closest('div.x_panel');
    var button = $(this).find('i');
    var content = x_panel.find('div.x_content');
    content.slideToggle(200);
    (x_panel.hasClass('fixed_height_390') ? x_panel.toggleClass('').toggleClass('fixed_height_390') : '');
    (x_panel.hasClass('fixed_height_320') ? x_panel.toggleClass('').toggleClass('fixed_height_320') : '');
    button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
    setTimeout(function () {
        x_panel.resize();
    }, 50);
});
/** ******  /collapse panel  *********************** **/
/** ******  iswitch  *********************** **/
if ($("input.flat")[0]) {
    $(document).ready(function () {
        $('input.flat').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    });
}
/** ******  /iswitch  *********************** **/
/** ******  star rating  *********************** **/
// Starrr plugin (https://github.com/dobtco/starrr)
var __slice = [].slice;

(function ($, window) {
    var Starrr;

    Starrr = (function () {
        Starrr.prototype.defaults = {
            rating: void 0,
            numStars: 5,
            change: function (e, value) {
            }
        };

        function Starrr($el, options) {
            var i, _, _ref,
                    _this = this;

            this.options = $.extend({}, this.defaults, options);
            this.$el = $el;
            _ref = this.defaults;
            for (i in _ref) {
                _ = _ref[i];
                if (this.$el.data(i) != null) {
                    this.options[i] = this.$el.data(i);
                }
            }
            this.createStars();
            this.syncRating();
            this.$el.on('mouseover.starrr', 'span', function (e) {
                return _this.syncRating(_this.$el.find('span').index(e.currentTarget) + 1);
            });
            this.$el.on('mouseout.starrr', function () {
                return _this.syncRating();
            });
            this.$el.on('click.starrr', 'span', function (e) {
                return _this.setRating(_this.$el.find('span').index(e.currentTarget) + 1);
            });
            this.$el.on('starrr:change', this.options.change);
        }

        Starrr.prototype.createStars = function () {
            var _i, _ref, _results;

            _results = [];
            for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                _results.push(this.$el.append("<span class='glyphicon .glyphicon-star-empty'></span>"));
            }
            return _results;
        };

        Starrr.prototype.setRating = function (rating) {
            if (this.options.rating === rating) {
                rating = void 0;
            }
            this.options.rating = rating;
            this.syncRating();
            return this.$el.trigger('starrr:change', rating);
        };

        Starrr.prototype.syncRating = function (rating) {
            var i, _i, _j, _ref;

            rating || (rating = this.options.rating);
            if (rating) {
                for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                    this.$el.find('span').eq(i).removeClass('glyphicon-star-empty').addClass('glyphicon-star');
                }
            }
            if (rating && rating < 5) {
                for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                    this.$el.find('span').eq(i).removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                }
            }
            if (!rating) {
                return this.$el.find('span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
            }
        };

        return Starrr;

    })();
    return $.fn.extend({
        starrr: function () {
            var args, option;

            option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return this.each(function () {
                var data;

                data = $(this).data('star-rating');
                if (!data) {
                    $(this).data('star-rating', (data = new Starrr($(this), option)));
                }
                if (typeof option === 'string') {
                    return data[option].apply(data, args);
                }
            });
        }
    });
})(window.jQuery, window);

$(function () {
    return $(".starrr").starrr();
});

$(document).ready(function () {

    $('#stars').on('starrr:change', function (e, value) {
        $('#count').html(value);
    });


    $('#stars-existing').on('starrr:change', function (e, value) {
        $('#count-existing').html(value);
    });

});
/** ******  /star rating  *********************** **/
/** ******  table  *********************** **/
$('table input').on('ifChecked', function () {
    check_state = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('table input').on('ifUnchecked', function () {
    check_state = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});

var check_state = '';
$('.bulk_action input').on('ifChecked', function () {
    check_state = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('.bulk_action input').on('ifUnchecked', function () {
    check_state = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});
$('.bulk_action input#check-all').on('ifChecked', function () {
    check_state = 'check_all';
    countChecked();
});
$('.bulk_action input#check-all').on('ifUnchecked', function () {
    check_state = 'uncheck_all';
    countChecked();
});

function countChecked() {
    if (check_state == 'check_all') {
        $(".bulk_action input[name='table_records']").iCheck('check');
    }
    if (check_state == 'uncheck_all') {
        $(".bulk_action input[name='table_records']").iCheck('uncheck');
    }
    var n = $(".bulk_action input[name='table_records']:checked").length;
    if (n > 0) {
        $('.column-title').hide();
        $('.bulk-actions').show();
        $('.action-cnt').html(n + ' Records Selected');
    } else {
        $('.column-title').show();
        $('.bulk-actions').hide();
    }
}
/** ******  /table  *********************** **/
/** ******    *********************** **/
/** ******    *********************** **/
/** ******    *********************** **/
/** ******    *********************** **/
/** ******    *********************** **/
/** ******    *********************** **/
/** ******  Accordion  *********************** **/

$(function () {
    $(".expand").on("click", function () {
        $(this).next().slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

/** ******  Accordion  *********************** **/

/** ******  scrollview  *********************** **/
$(document).ready(function () {

    $(".scroll-view").niceScroll({
        touchbehavior: true,
        cursorcolor: "rgba(42, 63, 84, 0.35)"
    });

});
/** ******  /scrollview  *********************** **/

/** ******  NProgress  *********************** **/
if (typeof NProgress != 'undefined') {
    $(document).ready(function () {
        NProgress.start();
    });

    $(window).load(function () {
        NProgress.done();
    });
}
/** ******  NProgress  *********************** **//** ******  scrollview  *********************** **/
$(document).ready(function () {

    $(".scroll-view").niceScroll({
        touchbehavior: true,
        cursorcolor: "rgba(42, 63, 84, 0.35)"
    });

});
/** ******  /scrollview  *********************** **/




//콤마찍기
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

//콤마풀기
function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}


function x_alert(title,content){
    $.alert({
        title: title,
        content: content,
    });
}

function x_confirm(title,content){

    var return_val =true;
    $.confirm({
        title: title,
        content: content,
        buttons: {
            confirm: function () {
                //$.alert('Confirmed!');
                return_val =true;
            },
            cancel: function () {
                //$.alert('Canceled!');
                return_val =false;
            },
            /*
            somethingElse: {
                text: 'Something else',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function(){
                    $.alert('Something else?');
                }
            }
            */
        }
    });

    return return_val;
}




var last_ex_mode_time = 0; //중복실행 방지타임
function preventBubble(limit_time){ 
	/* 클릭한번에 세번씩 이벤트가 발생하는 이상한 현상때문에 임시로 조치. 모든 이벤트에  다음의 두 줄을 넣어서 짧은 시간에는 한번만 실행되게 조치.
		var check_time = preventBubble();
		if(!check_time) return false;

          ///다음의 경우 등에는 추가로 조치가 필요함.
          if(!confirm("삭제하시겠습니까?")){
		     last_ex_mode_time = $.now(); //중복실행방지
              return false;
		  }
		     last_ex_mode_time = $.now(); //중복실행방지
	*/



	var new_last_ex_mode_time  = $.now();

	var time_cha = new_last_ex_mode_time - last_ex_mode_time;
    last_ex_mode_time = new_last_ex_mode_time;

   // if(last_key_value == recent_key_value)
  
   if(typeof limit_time == 'undefined'){

        //console.log("time_cha_custom:"+time_cha);

        if(time_cha < 300){
            //window.alert('1');
            return false;
        }else{
            //window.alert('2');
            return true;
        }
   }else{

        //console.log("time_cha_custom:"+time_cha);

        if(time_cha < limit_time){
            //window.alert('1');
            return false;
        }else{
            //window.alert('2');
            return true;
        }
   }
    

}



$(document).ready(function () {

    ///Button Dropdown 값 선택시 선택값 보여지게하기.

    $(document).on("click","div.btn-group ul.dropdown-menu li a",function() {
        var selected_value = $(this).text();
        var selected_color_class = $(this).attr("selected_color");
        var col_name = $(this).closest(".btn-group").attr("col_name");
        var $btn =  $(this).closest(".btn-group").find("button").first();
        //선택된 값으로 변경하여 보여지게
        $btn.text(selected_value).val(selected_value);

        //선택된 옵션의 색상으로 변경
        $btn.removeAttr("class");
        $btn.addClass(col_name).addClass("btn").addClass(selected_color_class);

        $(this).closest(".btn-group").find("button.dropdown-toggle").removeAttr("class").addClass("btn").addClass("dropdown-toggle").addClass(selected_color_class);

    });



});



function number_format(num){ //숫자 세자리 컴마
    if(num == undefined || num == ""){
        return 0;
    }
    
    var new_num = num.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    return new_num;
}


function checkNull(str){
    if(str){
        return str;
    }else{
        return "";
    }
}


function getAttachIcon(fname){
    var this_fname = fname.split("/").pop();
    var ext = this_fname.split(".").pop();
    var icon = "";

    if(ext == "pdf"){
       icon = '<i class="fa fa-file-pdf-o"></i>';
    } else if(ext == "ppt"){
       icon = '<i class="fa fa-file-powerpoint-o"></i>';
    }else if(ext == "doc" || ext == "docx" || ext == "docm"){
       icon = '<i class="fa fa-file-word-o"></i>';
    }else if(ext == "xlsb" || ext == "xls" || ext == "csv" || ext == "xlsx"){
       icon = '<i class="fa fa-file-excel-o"></i>';
    }else if(ext == "jpg" || ext == "jpeg" || ext == "png" || ext == "gif"){
       icon = '<i class="fa fa-file-picture-o"></i>';
    }else if(ext == "mov" || ext == "mp4" || ext == "vod" || ext == "mpeg" || ext == "avi" || ext == "wmv" || ext == "flv" || ext == "asf"){
       icon = '<i class="fa fa-file-movie-o"></i>';
    }else if(ext == "mp3" || ext == "wav" || ext == "flac"){
       icon = '<i class="fa fa-file-sound-o"></i>';
    }else if(ext == "zip"){
       icon = '<i class="fa fa-file-zip-o"></i>';
    }else{
        icon = '<i class="fa fa-file"></i>';
    }

    return icon;
    
}