/**
 * 공통 프린트 기능
 * 다양한 페이지에서 데이터를 받아 세로형 목록으로 출력하는 기능
 */

/**
 * 데이터를 받아 프린트 페이지를 엽니다.
 * @param {Object} data - 출력할 데이터 객체 (키-값 쌍)
 * @param {string} title - 프린트 페이지 제목
 * @param {Object} options - 추가 옵션 (선택사항)
 */
function openPrintPage(data, title, options) {
    if (!data || typeof data !== 'object') {
        console.error('데이터는 객체 형식이어야 합니다.');
        return;
    }
    
    // 기본 옵션 설정
    options = options || {};
    var width = options.width || 800;
    var height = options.height || 600;
    
    // JSON 문자열로 변환하고 URL 인코딩
    var dataStr = encodeURIComponent(JSON.stringify(data));
    
    // 프린트 페이지 열기
    var printWindow = window.open('/admin/contents/common/view_print_html.php?title=' + encodeURIComponent(title || '상세 정보') + '&data=' + dataStr, '_blank', 'width=' + width + ',height=' + height);
    
    // 창이 차단된 경우 처리
    if (!printWindow) {
        alert('팝업이 차단되었습니다. 팝업 차단을 해제해주세요.');
    }
}

/**
 * DataTable의 행 데이터를 받아 프린트 페이지를 엽니다.
 * @param {Object} rowData - DataTable의 행 데이터
 * @param {Array} columns - 출력할 열 정보 배열 [{data: 'column_name', title: '표시 이름'}, ...]
 * @param {string} title - 프린트 페이지 제목
 * @param {Object} options - 추가 옵션 (선택사항)
 */
function printDataTableRow(rowData, columns, title, options) {
    if (!rowData || !columns || !Array.isArray(columns)) {
        console.error('유효하지 않은 매개변수입니다.');
        return;
    }
    
    var printData = {};
    
    // 열 정보에 따라 데이터 구성
    columns.forEach(function(column) {
        if (column.data && column.title) {
            var value = rowData[column.data];
            if (value !== undefined) {
                printData[column.title] = value;
            }
        }
    });
    
    // 프린트 페이지 열기
    openPrintPage(printData, title, options);
}

/**
 * 테이블 요소에서 데이터를 추출하여 프린트 페이지를 엽니다.
 * @param {HTMLElement} tableElement - 테이블 요소
 * @param {string} title - 프린트 페이지 제목
 * @param {Object} options - 추가 옵션 (선택사항)
 */
function printHtmlTable(tableElement, title, options) {
    if (!tableElement || !tableElement.tagName || tableElement.tagName.toLowerCase() !== 'table') {
        console.error('유효한 테이블 요소가 아닙니다.');
        return;
    }
    
    var headers = [];
    var rows = [];
    
    // 헤더 추출
    var headerRow = tableElement.querySelector('thead tr');
    if (headerRow) {
        var headerCells = headerRow.querySelectorAll('th');
        headerCells.forEach(function(cell) {
            headers.push(cell.textContent.trim());
        });
    }
    
    // 데이터 행 추출
    var dataRows = tableElement.querySelectorAll('tbody tr');
    dataRows.forEach(function(row) {
        var rowData = {};
        var cells = row.querySelectorAll('td');
        
        cells.forEach(function(cell, index) {
            if (headers[index]) {
                rowData[headers[index]] = cell.textContent.trim();
            }
        });
        
        rows.push(rowData);
    });
    
    // 데이터 구성
    var printData = {
        '테이블 데이터': rows
    };
    
    // 프린트 페이지 열기
    openPrintPage(printData, title, options);
} 