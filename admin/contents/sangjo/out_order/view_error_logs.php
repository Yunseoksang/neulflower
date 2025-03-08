<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/lib.php';
admin_check();

// 로그 디렉토리 경로
$log_dir = $_SERVER["DOCUMENT_ROOT"].'/logs/ajax_errors';

// 로그 파일 목록 가져오기
$log_files = [];
if (file_exists($log_dir) && is_dir($log_dir)) {
    $files = scandir($log_dir);
    foreach ($files as $file) {
        if (strpos($file, 'error_') === 0 && pathinfo($file, PATHINFO_EXTENSION) === 'log') {
            $log_files[] = $file;
        }
    }
    // 최신 파일이 먼저 오도록 정렬
    rsort($log_files);
}

// 선택된 로그 파일
$selected_log = isset($_GET['log']) && in_array($_GET['log'], $log_files) ? $_GET['log'] : (count($log_files) > 0 ? $log_files[0] : '');

// 로그 내용 읽기
$log_content = '';
if ($selected_log) {
    $log_path = $log_dir . '/' . $selected_log;
    if (file_exists($log_path)) {
        $log_content = file_get_contents($log_path);
    }
}

// 로그 삭제 처리
if (isset($_POST['delete_log']) && isset($_POST['log_file']) && in_array($_POST['log_file'], $log_files)) {
    $file_to_delete = $log_dir . '/' . $_POST['log_file'];
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        // 페이지 리로드
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// 모든 로그 삭제 처리
if (isset($_POST['delete_all_logs'])) {
    foreach ($log_files as $file) {
        $file_to_delete = $log_dir . '/' . $file;
        if (file_exists($file_to_delete)) {
            unlink($file_to_delete);
        }
    }
    // 페이지 리로드
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>AJAX 에러 로그 확인</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .container {
            display: flex;
            gap: 20px;
        }
        .sidebar {
            flex: 0 0 250px;
            border-right: 1px solid #ddd;
            padding-right: 20px;
        }
        .content {
            flex: 1;
        }
        .log-list {
            list-style: none;
            padding: 0;
        }
        .log-list li {
            margin-bottom: 8px;
        }
        .log-list a {
            text-decoration: none;
            color: #0066cc;
            display: block;
            padding: 5px;
            border-radius: 3px;
        }
        .log-list a:hover, .log-list a.active {
            background-color: #f0f0f0;
        }
        .log-content {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 600px;
            overflow-y: auto;
        }
        .no-logs {
            color: #888;
            font-style: italic;
        }
        .actions {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .btn {
            padding: 5px 10px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-danger {
            background-color: #cc0000;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>AJAX 에러 로그 확인</h1>
    
    <div class="container">
        <div class="sidebar">
            <h2>로그 파일 목록</h2>
            <?php if (count($log_files) > 0): ?>
                <ul class="log-list">
                    <?php foreach ($log_files as $file): ?>
                        <li>
                            <a href="?log=<?php echo urlencode($file); ?>" <?php echo $file === $selected_log ? 'class="active"' : ''; ?>>
                                <?php echo htmlspecialchars($file); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="actions">
                    <?php if ($selected_log): ?>
                        <form method="post" onsubmit="return confirm('선택한 로그 파일을 삭제하시겠습니까?');">
                            <input type="hidden" name="log_file" value="<?php echo htmlspecialchars($selected_log); ?>">
                            <button type="submit" name="delete_log" class="btn btn-danger">선택한 로그 삭제</button>
                        </form>
                    <?php endif; ?>
                    
                    <form method="post" style="margin-top: 10px;" onsubmit="return confirm('모든 로그 파일을 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.');">
                        <button type="submit" name="delete_all_logs" class="btn btn-danger">모든 로그 삭제</button>
                    </form>
                </div>
            <?php else: ?>
                <p class="no-logs">로그 파일이 없습니다.</p>
            <?php endif; ?>
        </div>
        
        <div class="content">
            <h2>로그 내용</h2>
            <?php if ($selected_log && $log_content): ?>
                <div class="log-content"><?php echo htmlspecialchars($log_content); ?></div>
            <?php else: ?>
                <p class="no-logs">표시할 로그 내용이 없습니다.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 