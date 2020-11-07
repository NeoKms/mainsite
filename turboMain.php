<?php

// Локаль.
setlocale(LC_ALL, 'ru_RU');
date_default_timezone_set('Europe/Moscow');

$url = 'https://jrgreez.ru';

$out = '<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:yandex="http://news.yandex.ru" 
	 xmlns:media="http://search.yahoo.com/mrss/" 
	 xmlns:turbo="http://turbo.yandex.ru" 
	 version="2.0">
	<channel>
		<title>jrgreez.ru</title>
		<link>' . $url . '</link>
		<description>Главная страница</description>
		<language>ru</language>
		<turbo:analytics id="65367178" type="Yandex" params=""></turbo:analytics>';

$pages = [];

$page = '<div class="container text-center main-body">
	<div class="text-center">
				<img src="/keany12.png" class="img-fluid logo-img">
				<h2> Прости, основной сайт еще в разработке.</h2>
		<h3>Посмотри другие мои проекты:</h3>
		<p class="main_link"><a href="/shop"> --&gt; Магазин стикеров</a>
		</p><p class="github_link"><a href="https://github.com/NeoKms/sticker_shop" target="_blank">Github - NeoKms/sticker_shop</a></p>
		<p class="main_link"><a href="/school/"> --&gt; Онлайн-журнал для школы</a></p>
		<p class="github_link"><a href="https://github.com/NeoKms/school_journal" target="_blank">Github - NeoKms/school_journal</a></p>
		<p class="main_link"><a href="https://d5d1bmusasgbk48d08eo.apigw.yandexcloud.net/"> --&gt; Serverless сокращатель ссылок</a></p>
		<p class="github_link"><a href="https://github.com/NeoKms/serverless-link-shortener" target="_blank">Github - NeoKms/serverless-link-shortener</a></p>
	</div>
</div>';

$pages[] = [
    'text' => $page,
    'link'=> "https://jrgreez.ru/",
    'name' => 'Главная страница портфолио'
];
foreach ($pages as $page) {
    //    echoJson($page);
    $text = $page['text'];
    // Удаление лишних тегов.
    $text = strip_tags($text, '<p><img><iframe><br><ul><ol><li><b><strong><i><em><sup><sub><ins><del><small><big><pre></pre><abbr><u><a>');

    // Замена относительных ссылок.
    $text = str_replace('src="/', 'src="' . $url . '/', $text);
    $text = str_replace('href="/', 'href="' . $url . '/', $text);
    $text = str_replace('\\', '/', $text);

    $out .= '
			<item turbo="true">		
				<link>' . $page['link'] . '</link>
				<turbo:content>
					<![CDATA[
						<header>
							<h1>' . $page['name'] . '</h1>
						</header>
						' . $text . '
					]]>
				</turbo:content>			
			</item>';
}
$out .= '
	</channel>
</rss>';

header('Content-Type: text/xml; charset=utf-8');
echo $out;


function echoJson($data)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data,271);
    exit;
}
