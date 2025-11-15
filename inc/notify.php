<?php
require_once __DIR__ . '/config.php';
function notify_new_item($item){
  if(function_exists('mail') && MAIL_TO){
    $body = "Laporan baru:\nJenis: {$item['type']}\nJudul: {$item['title']}\nKategori: {$item['category']}\nLokasi: {$item['location']}\nPelapor: {$item['reporter_name']}\n";
    @mail(MAIL_TO, MAIL_SUBJECT, $body);
  }
  if(TELEGRAM_BOT_TOKEN && TELEGRAM_CHAT_ID){
    $text = "Laporan baru:%0AJenis: ".urlencode($item['type'])."%0AJudul: ".urlencode($item['title'])."%0AKategori: ".urlencode($item['category'])."%0ALokasi: ".urlencode($item['location'])."%0APelapor: ".urlencode($item['reporter_name']);
    $url = "https://api.telegram.org/bot".TELEGRAM_BOT_TOKEN."/sendMessage?chat_id=".TELEGRAM_CHAT_ID."&text=".$text;
    try{ @file_get_contents($url); }catch(Exception $e){}
  }
}
