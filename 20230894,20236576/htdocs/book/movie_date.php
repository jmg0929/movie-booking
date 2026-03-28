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

    // GET 파라미터로 영화 ID 받기
    if (!isset($_GET['movie_id'])) {
        echo "영화 정보가 없습니다.";
        exit;
    }

    $movie_id = intval($_GET['movie_id']);

    // 해당 영화의 상영 날짜 가져오기
    $sql = "SELECT DISTINCT show_date FROM showtimes WHERE movie_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $show_dates = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $show_dates[] = $row;
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
    <link rel="stylesheet" href="style_date.css">
    <title>날짜 및 시간 선택</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateShowtimes() {
            var movie_id = <?= $movie_id ?>;
            var selected_date = $("#show_date").val();

            $.ajax({
                url: "get_showtimes.php",
                type: "GET",
                data: {
                    movie_id: movie_id,
                    show_date: selected_date
                },
                success: function(data) {
                    $("#showtime").html(data);
                }
            });
        }
    </script>
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

<form id="showtimeForm" action="movie_seat.php" method="get">
    <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
    <h2>날짜 및 시간 선택</h2>

    <label for="show_date">상영 날짜:</label>
    <select id="show_date" name="show_date" onchange="updateShowtimes()" required>
        <option value="">날짜를 선택하세요</option>
        <?php foreach ($show_dates as $date) : ?>
            <option value="<?= htmlspecialchars($date['show_date']) ?>"><?= htmlspecialchars($date['show_date']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="showtime">상영 시간:</label>
    <select id="showtime" name="showtime" required>
        <option value="">시간을 선택하세요</option>
    </select>

    <label for="num_people">인원수:</label>
    <select id="num_people" name="num_people" required>
        <option value="1">1명</option>
        <option value="2">2명</option>
        <option value="3">3명</option>
        <option value="4">4명</option>
    </select>

    <button type="submit">다음</button>
</form>

<footer>
    <p>이용약관 | 개인정보 처리방침</p>
    <p>대표이사 : 전무건 · 사업자 등록 번호 : 011-2412-5354 · 본사 : 서울시 흑석로 84 중앙대학교 310관 508호</p>
    <p>고객센터: 010-3673-1181</p>
    <p>© MovieIsMyLife</p>
</footer>

</body>
</html>

