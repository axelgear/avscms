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
$title     = str_replace($characters_to_remove,$replace_with, $video['title']);
$desc      = str_replace($characters_to_remove,$replace_with, strip_tags($description));
$VID    = $video['VID'];

        header("Content-type: text/xml");
        print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        print "<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
        print "<channel>\n";
        print "<title>".$config['site_title']."</title>\n";
        print "<link>".$config['BASE_URL']."</link>\n";
        print "<description>".$config['meta_description']."</description>\n";
        print "<image>\n";
        print "\t<url>".$config['BASE_URL']."/templates/frontend/".$config['template']."/images/logo.png</url>\n";
        print "\t<title>".$config['site_title']." RSS feed</title>\n";
        print "\t<link>".$config['BASE_URL']."</link>\n";
        print "</image>\n";
        print "<language>en</language>\n";
        print "<generator>".$config['BASE_URL']."</generator>\n";
        print "<lastBuildDate>".date("r")."</lastBuildDate>\n";

        foreach($videos as $video) {
        echo "\t<item>\n" ;
        echo "\t\t<title>".$video['title']."</title>\n";
        echo "\t\t<guid>".$video['title']."</guid>\n" ;
        echo "\t\t<pubDate>".$video['adddate']."</pubDate>\n" ;
        echo "\t\t<link>".$config['BASE_URL']."/video/".$video['VID']."</link>\n" ;
        echo "\t\t<description><![CDATA[<img src=\"".$config['TMB_URL']."/".$video['VID']."/".$video['thumb'].".jpg\"><br>\n".$video['description']."]]></description>\n" ;
        echo "\t\t<dc:creator>".$config['site_title']."</dc:creator>\n" ;
        echo "\t</item>\n" ;
}
echo "</channel>\n";
echo "</rss>\n";
?>