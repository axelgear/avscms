<?php

//File access
define('_ENTER', true);
define('_VALID', true);
require 'include/config.php';
require 'include/function_global.php';

$characters_to_remove = array('&',"'",'"','>','<','-',',','/');
$replace_with = array('&amp;','&apos;','&quot;','&gt;','&lt;','','','');

$sql     = "SELECT VID, title, description, adddate, thumb FROM video WHERE active = '1' ORDER by addtime DESC LIMIT 50";
$rs      = $conn->execute($sql);
$videos = $rs->getrows();
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
echo "<channel>\n";
echo "<title>" . htmlspecialchars($config['site_title'], ENT_QUOTES, 'UTF-8') . "</title>\n";
echo "<link>" . htmlspecialchars($config['BASE_URL'], ENT_QUOTES, 'UTF-8') . "</link>\n";
echo "<description>" . htmlspecialchars($config['meta_description'], ENT_QUOTES, 'UTF-8') . "</description>\n";
echo "<image>\n";
echo "\t<url>" . htmlspecialchars($config['BASE_URL'] . "/templates/frontend/" . $config['template'] . "/images/logo.png", ENT_QUOTES, 'UTF-8') . "</url>\n";
echo "\t<title>" . htmlspecialchars($config['site_title'], ENT_QUOTES, 'UTF-8') . " RSS feed</title>\n";
echo "\t<link>" . htmlspecialchars($config['BASE_URL'], ENT_QUOTES, 'UTF-8') . "</link>\n";
echo "</image>\n";
echo "<language>en</language>\n";
echo "<generator>" . htmlspecialchars($config['BASE_URL'], ENT_QUOTES, 'UTF-8') . "</generator>\n";
echo "<lastBuildDate>" . date("r") . "</lastBuildDate>\n";
foreach ($videos as $video) {
    $clean_title = htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8');
    $clean_description = htmlspecialchars(strip_tags($video['description']), ENT_QUOTES, 'UTF-8');
    echo <<<XML
    <item>
        <title>{$clean_title}</title>
        <guid>{$clean_title}</guid>
        <pubDate>{$video['adddate']}</pubDate>
        <link>{$config['BASE_URL']}/video/{$video['VID']}</link>
        <description><![CDATA[<img src="{$config['TMB_URL']}/{$video['VID']}/{$video['thumb']}.jpg"><br>{$clean_description}]]></description>
        <dc:creator>{$config['site_title']}</dc:creator>
    </item>
XML;
}
echo "</channel>\n";
echo "</rss>\n";
?>
