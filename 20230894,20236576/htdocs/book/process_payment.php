<?php

session_start();

// POST 파라미터로 영화 ID, 상영 시간 ID, 좌석 번호 및 결제 정보 받기
if (!isset($_POST['movie_id']) || !isset($_POST['showtime_id']) || !isset($_POST['selected_seat']) || !isset($_POST['card_number'])) {
    echo "잘못된 접근입니다.";
    exit;
}

$movie_id = intval($_POST['movie_id']);
$showtime_id = intval($_POST['showtime_id']);
$selected_seat = htmlspecialchars($_POST['selected_seat']); 
$card_number = htmlspecialchars($_POST['card_number']); 

// 데이터베이스 연결
$servername = "localhost";
$db_username = "root";
$db_password = "0000";
$dbname = "mydb";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 실제 이 부분에서 결제 API 를 적용하여 결제를 구현해야하지만...
// 결제는 구현하지 못함

$selected_seats_array = explode(',', $selected_seat);

// 트랜잭션 시작
$conn->begin_transaction();

try {
    foreach ($selected_seats_array as $seat) {
        // 좌석 예약 상태 업데이트
        $sql_update = "UPDATE seats SET is_reserved = 1 WHERE showtime_id = ? AND seat_number = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("is", $showtime_id, $seat);
        $stmt_update->execute();
        $stmt_update->close();

        // 새로운 예약 정보 삽입
        $sql_insert = "INSERT INTO reservations (showtime_id, seat_num, username) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iss", $showtime_id, $seat, $_SESSION['username']);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    // 트랜잭션 커밋
    $conn->commit();

} catch (Exception $e) {
    // 트랜잭션 롤백
    $conn->rollback();
    echo "예약 중 오류가 발생했습니다: " . $e->getMessage();
    header("Location: check_user.php");
    exit; // 리다이렉션 이후에는 스크립트 실행을 중지하기 위해 exit()를 호출
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제 완료</title>
    <link rel="stylesheet" href="style_success.css">
</head>
<body>
    
<header>
        <h1>MovieIsMyLife</h1>
        <p>&nbsp;: MIML</p>
        <div class="header-buttons">
            <button onclick="location.href='/login/logout.php'">로그아웃</button>
        </div>
    </header>

    <nav>
        <a href="/movie_logout.html">홈</a>
        <a href="/movie_logout.html #current">영화</a>
        <a href="/mypage/mypage.php">마이페이지</a>
    </nav>

    <script>
        document.getElementById("logoutForm").addEventListener("submit", function(event) {
            event.preventDefault(); // 폼의 기본 동작 방지
            alert("로그아웃 되었습니다.");
            this.submit();
        });
    </script>

    <div class="container">
        <h2>예매가 완료되었습니다</h2>
        <p>결제가 정상적으로 처리되었습니다.<br> 예매 정보를 확인하려면 마이페이지를 방문해주세요.</p>
        <a href="/movie_logout.html" class="return-link">메인 페이지로 돌아가기</a>
    </div>

    <footer>
        <p>이용약관 | 개인정보 처리방침</p>
        <p>대표이사 : 전무건 · 사업자 등록 번호 : 011-2412-5354 · 본사 : 서울시 흑석로 84 중앙대학교 310관 508호</p>
        <p>고객센터: 010-3673-1181</p>
        <p>© MovieIsMyLife</p>
    </footer>

</body>
</html>
