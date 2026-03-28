<?php

header('Content-Type: text/html; charset=UTF-8');

// 데이터베이스 연결 설정
$db_host = "localhost";
$db_user = "root";
$db_password = "0000";
$db_name = "mydb";

// POST로 받은 사용자명과 비밀번호
$username = $_POST['username'];
$password = $_POST['password'];

// 데이터베이스 연결
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

// 연결 오류 체크
if ($mysqli->connect_errno) {
    die('Connect Error: ' . $mysqli->connect_error);
}

// SQL 쿼리 작성 
$query = "SELECT password FROM users WHERE username = ?";
$st = $mysqli->prepare($query);
$st->bind_param("s", $username);
$st->execute();
$st->bind_result($hashed_password);
$st->fetch();
$st->close();

// 결과 확인 및 비밀번호 검증
if ($hashed_password && password_verify($password, $hashed_password)) {
    // 로그인 성공
    session_start();
    $_SESSION['username'] = $username;
    header('Location: http://localhost/movie_logout.html'); // 로그인 성공 페이지로 이동
    exit;
} else {
    // 로그인 실패
    echo "<script>alert('아이디 혹은 비밀번호가 잘못되었습니다.');";
    echo "window.location.href = 'login.html';</script>";
}

// 데이터베이스 연결 종료
$mysqli->close();
?>