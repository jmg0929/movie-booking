//처음 페이지에 들어갈 때, 세션이 존재하는지 확인하고 페이지 띄워주는 코드

<?php

if (!isset($_SESSION['username'])) {
    header('Location: movie_page.html');
    exit();
}
else{
    header('Location: movie_logout.html');
}

?>
