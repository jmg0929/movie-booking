<?php
session_start();

// 세션 삭제
unset($_SESSION['username']);

// 세션 종료
session_destroy();

// 로그인 페이지로 리다이렉트
header("Location: http://localhost/movie_page.html");
exit;
?>