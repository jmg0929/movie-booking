<?php
// POST 파라미터로 영화 ID, 상영 시간 ID, 좌석 번호 받기
if (!isset($_POST['movie_id']) || !isset($_POST['showtime_id']) || !isset($_POST['selected_seats'])) {
    echo "잘못된 접근입니다.";
    exit;
}

$movie_id = intval($_POST['movie_id']);
$showtime_id = intval($_POST['showtime_id']);
$selected_seat = htmlspecialchars($_POST['selected_seats']); // XSS 방지를 위해 htmlspecialchars 사용

// 데이터베이스 연결
$servername = "localhost";
$db_username = "root";
$db_password = "0000";
$dbname = "mydb";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 영화 이름 가져오기
$sql_movie = "SELECT title FROM movies WHERE id = ?";
$stmt_movie = $conn->prepare($sql_movie);
$stmt_movie->bind_param("i", $movie_id);
$stmt_movie->execute();
$result_movie = $stmt_movie->get_result();

if ($result_movie->num_rows > 0) {
    $movie = $result_movie->fetch_assoc();
    $movie_title = htmlspecialchars($movie['title']);
} else {
    echo "영화 정보를 가져오는 데 실패했습니다.";
    exit;
}
$stmt_movie->close();

// 상영 날짜와 시간 가져오기
$sql_showtime = "SELECT show_date, show_time FROM showtimes WHERE id = ?";
$stmt_showtime = $conn->prepare($sql_showtime);
$stmt_showtime->bind_param("i", $showtime_id);
$stmt_showtime->execute();
$result_showtime = $stmt_showtime->get_result();

if ($result_showtime->num_rows > 0) {
    $showtime = $result_showtime->fetch_assoc();
    $show_date = htmlspecialchars($showtime['show_date']);
    $show_time = htmlspecialchars($showtime['show_time']);
} else {
    echo "상영 시간 정보를 가져오는 데 실패했습니다.";
    exit;
}
$stmt_showtime->close();

$num_people = isset($_POST['num_people']) ? intval($_POST['num_people']) : 0;
$money = $num_people * 15000;

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제</title>
    <link rel="stylesheet" href="style_payment.css">
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

    <div class="payment">
        <div class="movieinfo">
            <h4>Movie Info</h4>
            <p>영화 제목: <b><?= $movie_title ?></b></p>
            <p>상영 날짜: <b><?= $show_date ?></b></p>
            <p>상영 시간: <b><?= $show_time ?></b></p>
            <p>예약 좌석: <b><?= $selected_seat ?></b></p>
        </div>

        <form action="process_payment.php" method="post">
            <h4>Payment</h4>
            <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
            <input type="hidden" name="showtime_id" value="<?= $showtime_id ?>">
            <input type="hidden" name="selected_seat" value="<?= $selected_seat ?>">
            
            <p>결제 금액: <?= $money ?>원</p>
            <label for="card_number">카드 번호:</label>
            <input type="text" id="card_number" name="card_number" required>
            <button type="submit">결제</button>
        </form>
    </div>

    <footer>
        <p>이용약관 | 개인정보 처리방침</p>
        <p>대표이사 : 전무건 · 사업자 등록 번호 : 011-2412-5354 · 본사 : 서울시 흑석로 84 중앙대학교 310관 508호</p>
        <p>고객센터: 010-3673-1181</p>
        <p>© MovieIsMyLife</p>
    </footer>

</body>
</html>
