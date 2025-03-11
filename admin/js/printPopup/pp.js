/**
 * 프린트 팝업 공통 JS
 * 모든 페이지에서 간단히 사용할 수 있는 프린트 팝업 기능
 */

// 전역 변수로 모달 생성 여부 체크
var isPrintModalCreated = false;

// 프린트 팝업 초기화 함수 (페이지 로드 시 한 번만 호출)
function initPrintPopup() {
    console.log('프린트 팝업 초기화 시작');
    
    // 이미 모달이 존재하는지 확인
    if ($('#printModal').length > 0) {
        console.log('모달이 이미 존재함');
        isPrintModalCreated = true;
        return;
    }
    
    // 모달 HTML 생성
    var modalHtml = '<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">' +
        '<div class="modal-dialog modal-lg" role="document" style="max-width: 800px;">' +
        '<div class="modal-content">' +
        '<div class="modal-header bg-primary text-white py-2">' +
        '<h5 class="modal-title" id="printModalLabel">상세 정보</h5>' +
        '<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>' +
        '<div class="modal-body p-2" id="printModalBody">' +
        '<!-- 데이터가 여기에 동적으로 삽입됩니다 -->' +
        '</div>' +
        '<div class="modal-footer justify-content-center py-2">' +
        '<button type="button" class="btn btn-primary btn-sm" onclick="printModalContent()"><i class="fa fa-print"></i> 인쇄하기</button>' +
        '<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">닫기</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    
    // 모달 HTML을 body에 추가
    $('body').append(modalHtml);
    
    // 모달 생성 완료 표시
    isPrintModalCreated = true;
    
    console.log('프린트 팝업 초기화 완료');
}

// 데이터를 받아 프린트 팝업 열기
function openPrintPopup(data, title) {
    console.log('openPrintPopup 함수 호출됨:', title);
    
    // 모달이 없으면 초기화
    if (!isPrintModalCreated) {
        console.log('모달이 없어 초기화 실행');
        initPrintPopup();
    }
    
    if (!data || typeof data !== 'object') {
        console.error('데이터는 객체 형식이어야 합니다.');
        return;
    }
    
    // 모달 제목 설정
    $('#printModalLabel').text(title || '상세 정보');
    
    // 모달 내용 생성 - 단순 표 형식으로
    var contentHtml = '<div class="container-fluid p-0">';
    contentHtml += '<div class="row">';
    contentHtml += '<div class="col-12">';
    contentHtml += '<table class="table table-bordered table-sm">';
    contentHtml += '<tbody>';
    
    // 데이터 항목 순서대로 표시
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            contentHtml += '<tr>';
            contentHtml += '<th width="25%" class="bg-light">' + key + '</th>';
            contentHtml += '<td width="75%">' + (data[key] || '') + '</td>';
            contentHtml += '</tr>';
        }
    }
    
    contentHtml += '</tbody>';
    contentHtml += '</table>';
    contentHtml += '</div>'; // col-12
    contentHtml += '</div>'; // row
    contentHtml += '</div>'; // container-fluid
    
    // 모달 내용 삽입
    $('#printModalBody').html(contentHtml);
    
    console.log('모달 표시 시도');
    
    try {
        // 모달 표시
        $('#printModal').modal('show');
        console.log('모달 표시 완료');
    } catch (err) {
        console.error('모달 표시 중 오류:', err);
        alert('모달 표시 중 오류가 발생했습니다: ' + err.message);
        
        // jQuery와 Bootstrap이 제대로 로드되었는지 확인
        console.log('jQuery 버전:', $.fn.jquery);
        console.log('Bootstrap modal 존재 여부:', typeof $.fn.modal);
        console.log('모달 요소 존재 여부:', $('#printModal').length);
    }
}

// 모달 내용 인쇄 함수
function printModalContent() {
    // 인쇄 전 모달 크기 조정
    var $modalDialog = $('#printModal .modal-dialog');
    var originalClass = $modalDialog.attr('class');
    var originalStyle = $modalDialog.attr('style');
    
    // 인쇄를 위해 모달 크기 최적화
    $modalDialog.attr('class', 'modal-dialog');
    $modalDialog.attr('style', 'max-width: 210mm; width: 210mm;'); // A4 너비에 맞춤
    
    // 인쇄 제목 추가
    var title = $('#printModalLabel').text();
    var $printTitle = $('<div class="print-only-title text-center mb-2"><h3>' + title + '</h3><p class="text-muted">' + new Date().toLocaleString() + '</p></div>');
    $('#printModalBody').prepend($printTitle);
    
    // 테이블 스타일 최적화
    $('.table').addClass('table-print');
    
    // 인쇄 스타일 최적화
    $('body').append('<style id="print-optimize">@media print { @page { size: A4; margin: 10mm; } .table-print { width: 100% !important; } }</style>');
    
    // 인쇄 실행
    setTimeout(function() {
        window.print();
        
        // 인쇄 후 원래 상태로 복원
        $printTitle.remove();
        $('.table').removeClass('table-print');
        $('#print-optimize').remove();
        $modalDialog.attr('class', originalClass);
        if (originalStyle) {
            $modalDialog.attr('style', originalStyle);
        } else {
            $modalDialog.removeAttr('style');
        }
    }, 300);
}

// 데이터테이블 행 데이터로 프린트 팝업 열기
function openPrintPopupFromRow(rowData, titleMap, title) {
    if (!rowData) {
        console.error('행 데이터가 없습니다.');
        return;
    }
    
    console.log('행 데이터:', rowData);
    console.log('타이틀 맵:', titleMap);
    
    // 출력할 데이터 준비
    var printData = {};
    
    // titleMap에 따라 데이터 구성
    for (var key in titleMap) {
        if (titleMap.hasOwnProperty(key)) {
            var value = rowData[key];
            
            // 값이 undefined인 경우 빈 문자열로 대체
            if (value === undefined) {
                // col_0, col_1 등의 형식으로 저장된 DOM 데이터 확인
                if (key.indexOf('col_') === 0) {
                    var colIndex = parseInt(key.replace('col_', ''));
                    value = rowData['col_' + colIndex];
                }
            }
            
            printData[titleMap[key]] = value || '';
        }
    }
    
    console.log('프린트 데이터:', printData);
    
    // 모달 창으로 표시
    openPrintPopup(printData, title || '상세 정보');
}

// 버튼 클릭 이벤트 핸들러 등록 (페이지 로드 후 자동 실행)
$(document).ready(function() {
    // 모달 초기화
    initPrintPopup();
    
    console.log('프린트 팝업 이벤트 핸들러 등록 시작');
    
    // 프린트 버튼 클릭 이벤트 처리
    $(document).on('click', '.pp-print-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('프린트 버튼 클릭됨 - 이벤트 핸들러 실행');
        
        var $btn = $(this);
        var dataType = $btn.data('type') || 'row';
        var dataId = $btn.data('id');
        var dataTitle = $btn.data('title') || '상세 정보';
        
        console.log('버튼 데이터:', {
            type: dataType,
            id: dataId,
            title: dataTitle,
            titlemap: $btn.data('titlemap')
        });
        
        if (dataType === 'row' && dataId) {
            // 데이터테이블에서 행 데이터 찾기
            var rowData = null;
            
            // 전역 datatable 변수가 있는 경우
            if (typeof window.datatable !== 'undefined') {
                try {
                    console.log('datatable 변수 존재, 데이터 접근 시도');
                    var rows = window.datatable.rows().data();
                    for (var i = 0; i < rows.length; i++) {
                        if (rows[i].io_idx == dataId || rows[i].idx == dataId) {
                            rowData = rows[i];
                            break;
                        }
                    }
                } catch (e) {
                    console.error('datatable 데이터 접근 중 오류:', e);
                }
            } else {
                console.log('datatable 변수가 정의되지 않음');
            }
            
            // DOM에서 직접 데이터 추출 시도
            if (!rowData) {
                console.log('DOM에서 데이터 추출 시도');
                rowData = extractDataFromDOM(dataId, $btn);
            }
            
            if (rowData) {
                console.log('데이터 추출 성공:', rowData);
                
                // 기본 타이틀 맵 (필요에 따라 페이지별로 재정의 가능)
                var defaultTitleMap = {
                    'io_idx': '고유번호',
                    'regist_datetime': '주문일',
                    't_storage_name': '출고지',
                    't_product_name': '품목',
                    'out_count': '수량',
                    'company_name': '고객사',
                    'to_place_name': '배송지',
                    'to_name': '받는분',
                    'to_hp': '휴대폰',
                    'to_address': '주소',
                    'delivery_memo': '주문자메모',
                    'admin_memo': '관리자메모'
                };
                
                // 사용자 정의 타이틀 맵이 있으면 사용, 없으면 기본 맵 사용
                var titleMapData = $btn.data('titlemap');
                var titleMap = defaultTitleMap;
                
                console.log('타이틀맵 데이터:', titleMapData, typeof titleMapData);
                
                // 문자열로 전달된 경우 전역 변수에서 찾기
                if (typeof titleMapData === 'string') {
                    console.log('문자열 타이틀맵, 전역 변수 확인:', titleMapData, window[titleMapData]);
                    if (window[titleMapData]) {
                        titleMap = window[titleMapData];
                    }
                } else if (typeof titleMapData === 'object') {
                    titleMap = titleMapData;
                }
                
                console.log('사용할 타이틀맵:', titleMap);
                
                try {
                    // 프린트 팝업 열기
                    console.log('openPrintPopupFromRow 함수 호출 시도');
                    openPrintPopupFromRow(rowData, titleMap, dataTitle);
                    console.log('openPrintPopupFromRow 함수 호출 완료');
                } catch (err) {
                    console.error('openPrintPopupFromRow 함수 호출 중 오류:', err);
                    alert('데이터 처리 중 오류가 발생했습니다: ' + err.message);
                }
            } else {
                console.warn('데이터를 찾을 수 없음');
                alert('데이터를 찾을 수 없습니다.');
            }
        } else if (dataType === 'custom') {
            // 사용자 정의 데이터 처리 (페이지별로 구현 필요)
            if (typeof window.customPrintData === 'function') {
                var customData = window.customPrintData(dataId);
                if (customData) {
                    openPrintPopup(customData, dataTitle);
                }
            } else {
                alert('사용자 정의 데이터 처리 함수가 없습니다.');
            }
        }
        
        return false;
    });
    
    console.log('프린트 팝업 이벤트 핸들러 등록 완료');
});

// DOM에서 데이터 추출 함수
function extractDataFromDOM(id, $btn) {
    try {
        // 버튼이 있는 행 찾기
        var $row = $btn.closest('tr');
        
        if ($row.length === 0) {
            console.error('해당 ID의 행을 찾을 수 없습니다:', id);
            return null;
        }
        
        console.log('DOM에서 데이터 추출 시작:', id);
        
        // 각 셀에서 데이터 추출
        var rowData = {
            io_idx: id,
            idx: id
        };
        
        // 모든 셀 데이터 추출
        $row.find('td').each(function(index) {
            var cellText = $(this).clone().children().remove().end().text().trim();
            rowData['col_' + index] = cellText;
            
            // 특정 인덱스에 따라 기본 필드 매핑 (view_delivery.php 기준)
            switch(index) {
                case 1: rowData.regist_datetime = cellText; break;
                case 2: rowData.t_storage_name = cellText; break;
                case 3: rowData.t_product_name = cellText; break;
                case 4: rowData.out_count = cellText; break;
                case 5: rowData.company_name = cellText; break;
                case 6: rowData.to_place_name = cellText; break;
                case 7: rowData.to_name = cellText; break;
                case 8: rowData.to_hp = cellText; break;
                case 9: rowData.to_address = cellText; break;
                case 10: rowData.delivery_memo = cellText; break;
                case 11: rowData.admin_memo = cellText; break;
            }
        });
        
        console.log('DOM에서 추출한 데이터:', rowData);
        return rowData;
    } catch (e) {
        console.error('DOM에서 데이터 추출 중 오류:', e);
        return null;
    }
} 