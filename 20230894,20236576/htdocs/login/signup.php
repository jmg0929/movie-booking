<?php
// 데이터베이스 연결 설정
$db_host = "localhost";
$db_user = "root";
$db_password = "0000";
$db_name = "mydb";

$mysqli = new mysqli($db_host , $db_user, $db_password , $db_name);

// 연결 확인
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// POST로 받은 회원가입 정보
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // 비밀번호 해시화
$name = $_POST['name'];
$email = $_POST['email'];

// SQL 쿼리 준비
$query = "INSERT INTO users (username, password, name, email) VALUES (?, ?, ?, ?)";
$st = $mysqli->prepare($query);

// 변수 바인딩
$st->bind_param("ssss", $username, $password, $name, $email);

// 쿼리 실행 및 결과 확인
if ($st->execute()) {
    // 회원가입이 성공하면 회원가입 완료 페이지로 리다이렉트
    header('Location: signup_success.html');
    exit;
} else {
    echo "Error: " . $st->error;
}

// 연결 종료
$st->close();
$mysqli->close();

?>
