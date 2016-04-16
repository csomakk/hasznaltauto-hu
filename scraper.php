<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$page_counter = 0;
$next_page = FALSE;

do { 
   $kovetkezo = "";
   $page_counter++;
   $pageurl = "http://www.hasznaltauto.hu/talalatilista/auto/0P5EDPTSFJF2GPQWMZ8FE4PFQ0AEPWW8WTD7U5FKC38K84H2R8G1OR2Z102S3SSE9LJG2ORW356KS2I9QPOG3CPYEOYROPKTWW8H9J8H3OIWP86DTCG02PYHLFM9M7DWJQWE7QWY3M6HADCSKLY5CUITACLM537UKTJUAE1CYZE3WRISDY6Z0UCA7D6AHGWKF6C6RG/page{$page_counter}";
   
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
      foreach ($talalat->find("div.felszereltseg") as $felszereltseg) {
        $felszereltseg = str_replace("&nbsp;", " ", $felszereltseg->innertext);
      }
   
      scraperwiki::save(   
        array('id'),
        array(
          'id' => $kod + "-" + date("Y/m/d"),
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
