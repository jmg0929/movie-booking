<?php
// 데이터베이스 연결 설정
$db_host = "localhost";
$db_user = "root";
$db_password = "0000";
$db_name = "mydb";

// POST로 받은 사용자 이름
$username = $_POST['username'];

// 데이터베이스 연결
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

// 연결 오류 체크
if ($mysqli->connect_errno) {
    die('Connect Error: ' . $mysqli->connect_error);
}

// 중복된 사용자 이름이 있는지 확인
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "이미 사용 중인 사용자 이름입니다.";
} else {
    echo "사용할 수 있는 사용자 이름입니다.";
}

// Prepared Statement 및 데이터베이스 연결 종료
$stmt->close();
$mysqli->close();
?>
