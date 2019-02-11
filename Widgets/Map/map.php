<?php
/**
 * map.php.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */
$map = array('<!DOCTYPE html>');
$map[] = '<html lang='.$_GET['lang'].' dir=ltr>';
$map[] = '<head>';
$map[] = '<title>Google Map</title>';
$map[] = '<style>';
$map[] = 'html,body,#map_canvas{height:100%}';
$map[] = 'body{margin:0 auto;padding:0;font-family:Tahoma;font-size:12px;text-align:center;line-height:1.5em}';
$map[] = '</style>';
$map[] = '<script src="//maps.google.com/maps/api/js?key='.$_GET['api_key'].'&language='.$_GET['lang'].'"></script>';
$map[] = '<meta charset=utf-8>';
$map[] = '<script>';
$map[] = 'function initialize() {';
$map[] = 'var myLatlng = new google.maps.LatLng("'.$_GET['latitude'].'","'.$_GET['lantitude'].'");';
$map[] = 'var myOptions = {';
$map[] = 'zoom:'.$_GET['zoom'].',';
$map[] = 'center:myLatlng,';
$map[] = 'mapTypeId:google.maps.MapTypeId.ROADMAP';
$map[] = '};';
$map[] = 'var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);';
$info = isset($_GET['info']) ? $_GET['info'] : '';
if (!empty($info)) {
    $map[] = "var infowindow = new google.maps.InfoWindow({content:'".str_replace(array('&lt;', '&gt;', '&#92;', "\r", "\n"), array('<', '>', '\\', '', '<br>'), $info)."'});";
    $map[] = 'var info = new google.maps.LatLng("'.$_GET['info_latitude'].'","'.$_GET['info_lantitude'].'");';
    $map[] = 'var marker = new google.maps.Marker({position:info,map:map});';
    $map[] = 'infowindow.open(map,marker);';
    $map[] = 'google.maps.event.addListener(marker,"click",function(){';
    $map[] = 'infowindow.open(map,marker);';
    $map[] = '});';
}
$map[] = '}';
$map[] = '</script>';
$map[] = '</head>';
$map[] = '<body onload="initialize()">';
$map[] = '<div id=map_canvas>Google Map</div>';
$map[] = '</body>';
$map[] = '</html>';
echo implode("\n", $map);
