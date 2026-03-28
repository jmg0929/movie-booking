<?php
session_start();

// 데이터베이스 연결
$servername = "localhost";
$db_username = "root";
$db_password = "0000";
$dbname = "mydb";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자 ID 가져오기
$user_name = $_SESSION['username'];

// 예약 현황 가져오기
$sql = "SELECT r.id, r.seat_num, s.show_date, s.show_time, m.title, r.showtime_id
        FROM reservations r
        JOIN showtimes s ON r.showtime_id = s.id
        JOIN movies m ON s.movie_id = m.id
        WHERE r.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

$stmt->close();

// 예약 취소
if (isset($_POST['cancel_reservation'])) {
    $reservation_id = intval($_POST['cancel_reservation']);

    // 예약 정보 가져오기
    $find_sql = "SELECT showtime_id, seat_num FROM reservations WHERE id = ?";
    $stmt_find = $conn->prepare($find_sql);
    $stmt_find->bind_param("i", $reservation_id);
    $stmt_find->execute();
    $result_find = $stmt_find->get_result();

    if ($result_find->num_rows > 0) {
        $reservation = $result_find->fetch_assoc();
        $showtime_id = $reservation['showtime_id'];
        $seat_num = $reservation['seat_num'];

        // 예약 삭제
        $cancel_sql = "DELETE FROM reservations WHERE id = ?";
        $stmt_cancel = $conn->prepare($cancel_sql);
        $stmt_cancel->bind_param("i", $reservation_id);
        $stmt_cancel->execute();

        // 좌석 예약 상태 업데이트
        $update_seat_sql = "UPDATE seats SET is_reserved = 0 WHERE showtime_id = ? AND seat_number = ?";
        $stmt_update_seat = $conn->prepare($update_seat_sql);
        $stmt_update_seat->bind_param("is", $showtime_id, $seat_num);
        $stmt_update_seat->execute();

        echo "<script>alert('예약이 취소되었습니다.');</script>";
        // 예약 취소 후 페이지 새로고침
        echo "<meta http-equiv='refresh' content='0'>";

        $stmt_cancel->close();
        $stmt_update_seat->close();
    } else {
        echo "예약 정보를 찾을 수 없습니다.";
    }

    $stmt_find->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이 페이지</title>
    <link rel="stylesheet" href="style_mypage.css">
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
        <a href="mypage.php">마이페이지</a>
    </nav>

<div class="container">
    <h2>내 예약 현황</h2>
    <table>
        <tr>
            <th>영화 제목</th>
            <th>상영 날짜</th>
            <th>상영 시간</th>
            <th>좌석 번호</th>
            <th>예약 취소</th>
        </tr>
        <?php foreach ($reservations as $reservation) : ?>
            <tr>
                <td><?= htmlspecialchars($reservation['title']) ?></td>
                <td><?= htmlspecialchars($reservation['show_date']) ?></td>
                <td><?= htmlspecialchars($reservation['show_time']) ?></td>
                <td><?= htmlspecialchars($reservation['seat_num']) ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['id']) ?>">
                        <button type="submit" name="cancel_reservation" class="btn-cancel" value="<?= htmlspecialchars($reservation['id']) ?>">취소</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

    <footer>
        <p>이용약관 | 개인정보 처리방침</p>
        <p>대표이사 : 전무건 · 사업자 등록 번호 : 011-2412-5354 · 본사 : 서울시 흑석로 84 중앙대학교 310관 508호</p>
        <p>고객센터: 010-3673-1181</p>
        <p>© MovieIsMyLife</p>
    </footer>

</body>
</html>
