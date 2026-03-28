<?php
session_start();

header('Content-Type: text/html; charset=UTF-8');

// 세션 확인
if (!isset($_SESSION['username'])) {
    echo "<script>alert('로그인이 필요합니다.'); window.location.href = '/login/login.html';</script>";
    exit;
}

// 세션에 저장된 사용자 이름 가져오기
$username = $_SESSION['username'];

$movie_id = $_GET['movie_id']; 

header("Location: movie_date.php?movie_id=".$movie_id);
exit;

?>
