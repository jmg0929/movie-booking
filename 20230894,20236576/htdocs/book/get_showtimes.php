<?php
// 데이터베이스 연결
$servername = "localhost";
$username = "root";
$password = "jskkmr0302";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['movie_id']) || !isset($_GET['show_date'])) {
    echo "<option value=''>시간을 선택하세요</option>";
    exit;
}

$movie_id = intval($_GET['movie_id']);
$show_date = $conn->real_escape_string($_GET['show_date']);

$sql = "SELECT s.id, s.show_time, (s.total_seat - COUNT(r.id)) AS remaining_seats
        FROM showtimes s
        LEFT JOIN reservations r ON s.id = r.showtime_id
        WHERE s.movie_id = ? AND s.show_date = ?
        GROUP BY s.id, s.show_time, s.total_seat";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $movie_id, $show_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['show_time']} (남은 좌석: {$row['remaining_seats']})</option>";
    }
} else {
    echo "<option value=''>해당 날짜에 상영 시간이 없습니다</option>";
}

$stmt->close();
$conn->close();
?>
