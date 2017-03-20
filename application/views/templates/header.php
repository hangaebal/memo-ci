<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>제목 없음 - 메모장</title>
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/jquery-3.1.1.min.js"></script>
</head>
<body>
<header>
    <a href="/">
        <img id="iconImg" alt="아이콘" src="/img/icon.png">
        <p id="headerTitle">제목 없음 - 메모장</p>
    </a>

    <nav>
        <ul id="mainMenuUl">
            <?php foreach ($menu_list as $menu): ?>
                <li class="mainMenu">
                    <p><?php echo $menu->title?></p>
                    <ul class="subMenuUl <?php echo ($menu->has_year == 'Y')?'widthShortCut':'' ?>">
                        <?php foreach ($post_list as $post): ?>
                            <?php if ($menu->id == $post->menu_id): ?>
                                <li class="subMenu">
                                    <a href="/post/<?php echo $post->id?>">
                                        <p><?php echo $post->title?> <span><?php echo $post->year?></span></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

<section>