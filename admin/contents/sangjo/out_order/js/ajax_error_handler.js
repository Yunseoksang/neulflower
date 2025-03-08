/**
 * AJAX 에러 처리를 위한 유틸리티 함수
 * 500 에러 및 기타 서버 에러를 처리하고 로깅합니다.
 */

// AJAX 요청 함수 (jQuery 사용)
function ajaxRequest(url, data, successCallback) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.status === 1) {
                // 성공 처리
                successCallback(response);
            } else {
                // 서버에서 반환한 오류 처리
                handleAjaxError(response);
            }
        },
        error: function(xhr, status, error) {
            // HTTP 에러 처리 (500 등)
            handleHttpError(xhr, status, error, url, data);
        }
    });
}

// 서버에서 반환한 오류 처리
function handleAjaxError(response) {
    var errorMessage = response.msg || "알 수 없는 오류가 발생했습니다.";
    var errorCode = response.error_code || "";
    
    // 오류 메시지 표시
    alert(errorMessage);
    
    // 콘솔에 추가 정보 기록
    if (errorCode) {
        console.error("에러 코드: " + errorCode);
        console.error("관리자에게 이 에러 코드를 알려주세요.");
    }
}

// HTTP 에러 처리 (500 등)
function handleHttpError(xhr, status, error, url, data) {
    // 상태 코드에 따른 처리
    var errorMessage = "서버 오류가 발생했습니다.";
    
    if (xhr.status === 500) {
        errorMessage = "서버 내부 오류가 발생했습니다. (500)";
    } else if (xhr.status === 404) {
        errorMessage = "요청한 페이지를 찾을 수 없습니다. (404)";
    } else if (xhr.status === 403) {
        errorMessage = "접근이 거부되었습니다. (403)";
    } else if (xhr.status === 0) {
        errorMessage = "서버에 연결할 수 없습니다. 인터넷 연결을 확인하세요.";
    }
    
    // 오류 메시지 표시
    alert(errorMessage);
    
    // 콘솔에 추가 정보 기록
    console.error("AJAX 요청 실패:");
    console.error("URL: " + url);
    console.error("상태: " + status);
    console.error("에러: " + error);
    console.error("데이터: ", data);
    
    // 개발자 도구 콘솔에 전체 응답 기록
    console.error("전체 응답:", xhr);
}

// 사용 예시: 출고지시 처리
function processOutOrder(oocp_idx, storage_idx, admin_memo) {
    var data = {
        mode: "출고지시",
        oocp_idx: oocp_idx,
        storage_idx: storage_idx,
        admin_memo: admin_memo
    };
    
    ajaxRequest('/admin/contents/sangjo/out_order/api/save_product_output.php', data, function(response) {
        alert(response.msg);
        // 성공 후 추가 처리
        location.reload();
    });
}

// 사용 예시: 주문취소 처리
function cancelOrder(oocp_idx) {
    var data = {
        mode: "주문취소",
        oocp_idx: oocp_idx
    };
    
    ajaxRequest('/admin/contents/sangjo/out_order/api/save_product_output.php', data, function(response) {
        alert(response.msg);
        // 성공 후 추가 처리
        location.reload();
    });
} 