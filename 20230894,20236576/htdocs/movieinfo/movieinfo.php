<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$db_username = "root"; // 데이터베이스 사용자 이름
$db_password = "jskkmr0302"; // 데이터베이스 비밀번호
$dbname = "mydb"; // 데이터베이스 이름

// 데이터베이스 연결
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

// 쿼리를 통해 영화 정보 가져오기 
$sql = "SELECT title, duration, release_date, rating, poster_url, description FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 데이터베이스에서 가져온 정보를 배열 형태로 가져옴
    $row = $result->fetch_assoc();
    $title = htmlspecialchars($row["title"]);
    $duration = htmlspecialchars($row["duration"]);
    $release_date = htmlspecialchars($row["release_date"]);
    $rating = htmlspecialchars($row["rating"]);
    $poster_url = htmlspecialchars($row["poster_url"]);
    $description = htmlspecialchars($row["description"]);
} else {
    $title = "제목을 찾을 수 없습니다.";
    $duration = "상영 시간을 찾을 수 없습니다.";
    $release_date = "개봉일을 찾을 수 없습니다.";
    $rating = "평점을 찾을 수 없습니다.";
    $poster_url = ""; // 이미지가 없을 경우 빈 문자열
    $description = "설명을 찾을 수 없습니다.";
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>영화 정보 페이지</title>
    <link rel="stylesheet" href="style_movieinfo.css">
</head>
<body>

<div class="container">
    <div class="movie-info-header">
        <h2><?php echo $title; ?></h2>
        <button class="close-button" onclick="goBack()">X</button>
    </div>
    <div class="movie-info">
        <img src="<?php echo $poster_url; ?>" alt="영화 포스터">
        <div class="movie-info-details">
            <p><strong>제목 | </strong> <?php echo $title; ?></p>
            <p><strong>상영 시간 | </strong> <?php echo $duration; ?> 분</p>
            <p><strong>개봉일 | </strong> <?php echo $release_date; ?></p>
            <p><strong>평점 | </strong> ⭐ <?php echo $rating; ?></p>
        </div>
    </div>

    <p><strong>줄거리 |</strong></p>
    <p class="crew">
        <?php echo $description; ?>
    </p>
</div>

<script>
function goBack() {
    window.history.back();
}
</script>

</body>
</html>
