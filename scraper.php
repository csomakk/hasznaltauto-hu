<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$page_counter = 0;
$next_page = FALSE;

do { 
   $kovetkezo = "";
   $page_counter++;
   $pageurl = "http://www.hasznaltauto.hu/talalatilista/auto/MUFL1UR1IJEREYEY893YMKI9OZAK6RKUYYTDIG56JG2MGFEHCD9S6H4HK8Z0UCJCWUT23IC966LEYSI5M9ZCH6PTCCGJK25ZOE6KRWCZODKY5J1A07Z3TKT3FC0F3DAARQDQYAJM16WF83K3R55O8F70GP895AK50RTPKWCE/page{$page_counter}";
   
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
      foreach ($talalat->find("p.talalati_lista_infosor") as $info) {
        $info = str_replace("&ndash;", ",", $info->innertext);
        $info = str_replace("&nbsp;", " ", $info);
        $info = str_replace("&sup3;", "3", $info);
        $info = explode(",",$info);
      }
      foreach ($talalat->find("div.felszereltseg") as $felszereltseg) {
        $felszereltseg = str_replace("&nbsp;", " ", $felszereltseg->innertext);
      }
   
      scraperwiki::save(   
        array('id'),
        array(
          'id' => $kod * 100000 + "-" + date("m") * 100 + date("d"),
          'kod' => $kod,
          'type' => $el->innertext,
          'price' => $ar,
          'info' => $info,
          'url' => $url,
          'felsz' => $felszereltseg,
          'crapedate' => date("Y/m/d"),
        )
      );
   }
   foreach ($html->find("div.oldalszamozas a[title=Következő oldal]") as $kovetkezo) {
     print $page_counter . "\n";
   }
} while ($kovetkezo != "");
?>
