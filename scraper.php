<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$page_counter = 0;
$next_page = FALSE;
do { 
   $kovetkezo = "";
   $page_counter++;
   $pageurl = "http://www.hasznaltauto.hu/talalatilista/auto/T4R1C47G6M6KO453QWL61U465TH1433L37CE8R6FPALFLUZK9LOS29KWSTKGAGG1YDMOK293ARIFGK0Y542OAP4J12J924F733LZYMLZA2034LIC7POTK4JZD6QYQEC3M531E53JAQIZHCPGFDJRP807HPDQRAE8F7M8H1SPJW1A390GCJIWT8PHECIHZO3F6IPI9O/page{$page_counter}";
   
   $html_content = scraperWiki::scrape($pageurl);
    
   $html = str_get_html($html_content);
   foreach ($html->find("div.talalati_lista") as $talalat) {  
      foreach ($talalat->find("h2 a") as $el) {
        $tipus = $el->innertext;
        $url = $el->href;
        $kod = substr($url, -7); 
      }
      foreach ($talalat->find("div.talalati_lista_vetelar strong") as $ar) {
        $ar = str_replace("&nbsp;", " ", $ar->innertext);
      }
      foreach ($talalat->find("div.talalati_lista_infosor") as $info) {
        $info = str_replace("&ndash;", ",", $info->innertext);
        $info = str_replace("&nbsp;", " ", $info);
        $info = str_replace("&sup3;", "3", $info);
        $info = explode(",",$info);
      }
      ///foreach ($talalat->find("div.felszereltseg") as $felszereltseg) {
      //  $felszereltseg = str_replace("&nbsp;", " ", $felszereltseg->innertext);
      //}
   
      scraperwiki::save(   
        array('id'),
        array(
          'id' => $kod,
          'type' => $el->innertext,
          'price' => $ar,
          'info' => $info,
          'url' => $url,
        //  'felsz' => $felszereltseg,
          'crapedate' => date("Y/m/d"),
        )
      );
   }
   foreach ($html->find("div.oldalszamozas a[title=Következő oldal]") as $kovetkezo) {
     print $page_counter . "\n";
   }
} while ($kovetkezo != "");
?>
