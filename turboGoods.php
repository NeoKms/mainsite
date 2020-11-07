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
		<description>Страницы товаров</description>
		<language>ru</language>
		<turbo:analytics id="65367178" type="Yandex" params=""></turbo:analytics>';

require_once '../autoload.php';
DBase::getInstance()->Connect(Config::get('db_user'), Config::get('db_password'), Config::get('db_base'));
$categoriesData=(new Categories([]))->getCategories();
$categories = [];
foreach ($categoriesData as $oneCat)
{
    $categories[$oneCat['id']] = $oneCat['name'];
}
$goods=(new Goods([]))->getList();
//$goods = [$goods];
$pages = [];
foreach ($goods as $oneGood)
{
    $page = '<div class="row">
		<div class="col-sm-6">
				<h2><b>Товар: </b>'.$oneGood['name'].'</h2><br>
				<h3><b>Категория: </b>'.$categories[$oneGood['category_id']].'</h3><br>
			<img class="goodImg-detail" src="shop/'.$oneGood['picture'].'" width="400">
		</div>
		<div class="col-sm-6 text">
			<br><br><br>
			<div>'.$oneGood['description'].'</div><br><br>
			<div><b>Стоимость: </b>'.$oneGood['price'].'₽</div>';
    if ($oneGood['special']>0) $page .= "<br><div><b>Специальное предложение!</b></div>";
    $page .='<br><p>Перейдите на полную страницу для "покупки".</p>
		</div>
	</div>';
    $pages[] = [
        'text'=>str_replace(["\t","\n"], '', $page),
        'name'=>$oneGood['name'],
        'link'=>"https://jrgreez.ru/shop/index.php?path=goods/detail/{$oneGood['id_good']}",
    ];
}

foreach ($pages as $page) {
    //    echoJson($page);
    $text = $page['text'];
    // Удаление лишних тегов.
    $text = strip_tags($text, '<p><img><iframe><br><ul><ol><li><b><strong><i><em><sup><sub><ins><del><small><big><pre></pre><abbr><u><a>');

    // Замена относительных ссылок.
    $text = str_replace('src="', 'src="' . $url . '/', $text);
    $text = str_replace('href="', 'href="' . $url . '/', $text);
    $text = str_replace('\\', '/', $text);

    $out .= '
			<item turbo="true">		
				<link>' . $page['link'] . '</link>
				<turbo:content>
					<![CDATA[
						<header>
							<h1>' . $page['name'] . '</h1>
							<menu>
								<a href="' . $url . '/shop">На главную</a>
								<a href="' . $url . '/shop/index.php?path=categories/1">Стикеры с Киану</a>
								<a href="' . $url . '/shop/index.php?path=categories/2">Мемы</a>
								<a href="' . $url . '/shop/index.php?path=user/auth">Авторизация</a>
								<a href="' . $url . '/shop/index.php?path=user/registration">Регистрация</a>
								<a href="' . $url . '/">Основной сайт</a>
							</menu>
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
