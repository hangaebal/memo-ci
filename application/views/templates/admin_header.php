<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><html>
<head>
    <meta charset="UTF-8">
    <title>관리자</title>
    <!--<link rel="stylesheet" href="/css/normalize.css">-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <style>
        @media (min-width: 768px) {
            .navbar-right {
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>
<section class="container">

    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="/admin/menu">메뉴관리</a></li>
            <li><a href="/admin/post">포스트 관리</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="/admin/logout">로그아웃</a></li>
            <li><a href="/" target="_blank">사이트메인 (새 창)</a></li>
        </ul>
    </nav>

    <article style="margin-bottom: 50px;">