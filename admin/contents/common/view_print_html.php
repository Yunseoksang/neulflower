<?php
/**
 * 공통 프린트 기능
 * 다양한 페이지에서 데이터를 받아 세로형 목록으로 출력하는 기능
 */
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>인쇄 미리보기</title>
    <style>
        body {
            font-family: 'Malgun Gothic', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .print-title {
            font-size: 24px;
            font-weight: bold;
        }
        .print-date {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .print-content {
            margin-bottom: 30px;
        }
        .print-item {
            display: flex;
            border-bottom: 1px solid #ddd;
            padding: 8px 0;
        }
        .print-label {
            width: 30%;
            font-weight: bold;
            color: #333;
        }
        .print-value {
            width: 70%;
            word-break: break-all;
        }
        .print-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .print-buttons {
            text-align: center;
            margin-top: 20px;
        }
        .print-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .close-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .print-image {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
        }
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .print-table th, .print-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .print-table th {
            background-color: #f2f2f2;
        }
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .print-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .print-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="print-header">
            <div class="print-title" id="print-title">상세 정보</div>
            <div class="print-date" id="print-date"><?php echo date('Y-m-d H:i:s'); ?></div>
        </div>
        
        <div class="print-content" id="print-content">
            <!-- 데이터가 여기에 동적으로 삽입됩니다 -->
        </div>
        
        <div class="print-footer">
            이 문서는 시스템에서 자동 생성되었습니다.
        </div>
        
        <div class="print-buttons">
            <button class="print-btn" onclick="printPage()">인쇄하기</button>
            <button class="close-btn" onclick="window.close()">닫기</button>
        </div>
    </div>

    <script>
        // URL에서 파라미터 가져오기
        function getParameterByName(name, url = window.location.href) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        // 값이 이미지 URL인지 확인
        function isImageUrl(value) {
            if (typeof value !== 'string') return false;
            return value.match(/\.(jpeg|jpg|gif|png)$/i) !== null;
        }

        // 값이 테이블 데이터인지 확인
        function isTableData(value) {
            return Array.isArray(value) && value.length > 0 && typeof value[0] === 'object';
        }

        // 값 렌더링 (특수 타입 처리)
        function renderValue(value) {
            if (value === null || value === undefined) {
                return '';
            }
            
            // 이미지 URL인 경우
            if (isImageUrl(value)) {
                return '<img src="' + value + '" class="print-image" alt="이미지">';
            }
            
            // 테이블 데이터인 경우
            if (isTableData(value)) {
                var tableHtml = '<table class="print-table">';
                
                // 헤더 생성
                tableHtml += '<tr>';
                for (var key in value[0]) {
                    if (value[0].hasOwnProperty(key)) {
                        tableHtml += '<th>' + key + '</th>';
                    }
                }
                tableHtml += '</tr>';
                
                // 데이터 행 생성
                for (var i = 0; i < value.length; i++) {
                    tableHtml += '<tr>';
                    for (var key in value[i]) {
                        if (value[i].hasOwnProperty(key)) {
                            tableHtml += '<td>' + value[i][key] + '</td>';
                        }
                    }
                    tableHtml += '</tr>';
                }
                
                tableHtml += '</table>';
                return tableHtml;
            }
            
            // 일반 문자열인 경우 줄바꿈 처리
            if (typeof value === 'string') {
                return value.replace(/\n/g, '<br>');
            }
            
            return value;
        }

        // 페이지 로드 시 데이터 처리
        window.onload = function() {
            console.log('프린트 페이지 로드됨');
            
            // URL에서 데이터 가져오기 (JSON 문자열로 전달됨)
            var dataStr = getParameterByName('data');
            var title = getParameterByName('title') || '상세 정보';
            
            console.log('title:', title);
            console.log('dataStr:', dataStr);
            
            // 제목 설정
            document.getElementById('print-title').textContent = title;
            document.title = title + ' - 인쇄 미리보기';
            
            if (dataStr) {
                try {
                    // JSON 파싱
                    var data = JSON.parse(dataStr);
                    console.log('파싱된 데이터:', data);
                    var contentHtml = '';
                    
                    // 데이터 항목 생성
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            contentHtml += '<div class="print-item">';
                            contentHtml += '<div class="print-label">' + key + '</div>';
                            contentHtml += '<div class="print-value">' + renderValue(data[key]) + '</div>';
                            contentHtml += '</div>';
                        }
                    }
                    
                    // 내용 삽입
                    document.getElementById('print-content').innerHTML = contentHtml;
                } catch (e) {
                    console.error('데이터 파싱 오류:', e);
                    document.getElementById('print-content').innerHTML = '<p>데이터를 불러오는 중 오류가 발생했습니다: ' + e.message + '</p>';
                }
            } else {
                console.warn('전달된 데이터가 없습니다.');
                document.getElementById('print-content').innerHTML = '<p>표시할 데이터가 없습니다.</p>';
            }
        };

        // 인쇄 기능
        function printPage() {
            window.print();
        }
    </script>
</body>
</html> 
