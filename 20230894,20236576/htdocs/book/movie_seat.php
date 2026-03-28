<?php
// 데이터베이스 연결
$servername = "localhost";
$db_username = "root";
$db_password = "0000";
$dbname = "mydb";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GET 파라미터로 영화 ID, 상영 시간 ID, 인원 수 받기
if (!isset($_GET['movie_id']) || !isset($_GET['showtime']) || !isset($_GET['num_people'])) {
    echo "영화 정보가 없습니다.";
    exit;
}

$movie_id = intval($_GET['movie_id']);
$showtime_id = intval($_GET['showtime']);
$num_people = intval($_GET['num_people']);

// 선택한 상영 시간의 좌석 정보 가져오기
$sql = "SELECT * FROM seats WHERE showtime_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $showtime_id);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>좌석 선택</title>
    <link rel="stylesheet" href="style_seat.css">
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

<form id="seatForm" action="payment.php" method="post">
    <h2>좌석 선택</h2>
    <div class="screen">SCREEN</div>

    <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
    <input type="hidden" name="showtime_id" value="<?= $showtime_id ?>">
    <input type="hidden" name="num_people" value="<?= $num_people ?>">
    <div class="seats">
        <?php foreach ($seats as $seat) : ?>
            <div class="seat <?= $seat['is_reserved'] ? 'reserved' : 'available' ?>" data-seat-num="<?= htmlspecialchars($seat['seat_number']) ?>">
                <?= htmlspecialchars($seat['seat_number']) ?>
            </div>
        <?php endforeach; ?>
    </div>
    <input type="hidden" id="selectedSeats" name="selected_seats">
    <button type="submit">다음</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedSeats = [];
        const numPeople = <?= $num_people ?>;
        
        document.querySelectorAll('.seat.available').forEach(function(seat) {
            seat.addEventListener('click', function() {
                const seatNum = seat.dataset.seatNum;
                if (selectedSeats.includes(seatNum)) {
                    selectedSeats = selectedSeats.filter(s => s !== seatNum);
                    seat.classList.remove('selected');
                } else if (selectedSeats.length < numPeople) {
                    selectedSeats.push(seatNum);
                    seat.classList.add('selected');
                }
                
                document.getElementById('selectedSeats').value = selectedSeats.join(',');
            });
        });
    });
</script>

<footer>
    <p>이용약관 | 개인정보 처리방침</p>
    <p>대표이사 : 전무건 · 사업자 등록 번호 : 011-2412-5354 · 본사 : 서울시 흑석로 84 중앙대학교 310관 508호</p>
    <p>고객센터: 010-3673-1181</p>
    <p>© MovieIsMyLife</p>
</footer>

</body>
</html>
    