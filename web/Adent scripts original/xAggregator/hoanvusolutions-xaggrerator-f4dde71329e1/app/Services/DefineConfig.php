<?php
namespace App\Services;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DefineConfig {
  const YES = 1;
  const NO = 0;
  const formatCSV = array(
      '' => 'Select One',
      'tubecorporate.com' =>'tubecorporate.com',
      'xhamster.com' =>'xhamster.com',
      'xvideos.com' =>'xvideos.com',
      'pornsharing.com' => 'webmaster.pornsharing.com',
      'pornhub' =>'hubtraffic.com(PornHub, TUBE8, YouPorn)',
      'redtube' =>'hubtraffic.com(REDTUBE)',
      'xtube' =>'hubtraffic.com(XTube)',
      'spankwire' =>'hubtraffic.com(Spankwire)'
  );
  
  const positionAd = array(
      '' => 'Select One',
      'adsCate' => 'category-page',
      'adsVideo' => 'video-page',
      'adsFooter' => 'footer'
  );
  
  const contact_type = array(
      '' => 'Select One',
      1 => 'Bug',
      2 => 'General Inquiries',
      3 => 'User photo verification',
      4 => 'Amateur program',
      5 => 'Press/Media inquiry',
      6 => 'Content Removal Request',
      7 => 'Copyright Issue'
  );
}