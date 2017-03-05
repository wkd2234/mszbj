<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet" >
    <script src="/jquery-1.11.3/jquery.js"></script>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"><?= $project?></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li <?= 1==$navNum ? 'class="active"' : ""?>>
                        <a href="/index/Test/index">主页</a>
                    </li>
                    <li <?= 2==$navNum ? 'class="active"' : ""?>>
                        <a href="/index/Category/index">分类</a>
                    </li>
                    <li <?= 3==$navNum ? 'class="active"' : ""?>>
                        <a href="/index/Alllive/index">全部直播</a>
                    </li>
                    <li <?= 4==$navNum ? 'class="active"' : ""?>>
                        <a href="/index/Allvideo/index">全部视频</a>
                    </li>
                </ul>
                <? if(!isset($has_login) || empty($has_login)) { ?>
                <form class="navbar-form pull-right" action="/index/Test/index" method="POST">
                    <input class="span2" name="email" placeholder="Email" type="text">
                    <input class="span2" name="password" placeholder="Password" type="password">
                    <button class="btn btn-link" type="submit">Sign in</button>
                </form>
                <? }else{ ?>
                <ul class="nav navbar-nav navbar-right hidden-sm">
                    <li><a href="#">Person Page</a></li>
                </ul>
                <? } ?>
            </div>
        </div>
    </nav>
    <div class="container"></div>