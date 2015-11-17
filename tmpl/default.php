<?php
/**
*-------------------------------------------------------------------------------
* mod_dt_instagallery - DT InstaGallery display your images from Instagram in your
* Joomla website by detalhar - http://www.detalharweb.com.br
*-------------------------------------------------------------------------------
* @package mod_dt_instagallery
* @version 0.1.0
* @author detalhar http://http://www.detalharweb.com.br
* @copyright (C) 2015 detalhar. All Rights Reserved
* @license - GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html or see LICENSE.txt
*
* mod_dt_instagallery is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
**/

defined('_JEXEC') or die('Access Restricted');
$doc = JFactory::getDocument();
$client_id = $id;

if (!empty($client_id)) {
  switch ($get) {

    case 'tagged':
    //API Instagram  - Get tagged
    $url = 'https://api.instagram.com/v1/tags/'. $username .'/media/recent?client_id='.$client_id.'&count='.$count;
    $info = file_get_contents($url);
    $data = json_decode($info);
    break;

    case 'popular':
    //API Instagram - Get Popular
    $url = 'https://api.instagram.com/v1/media/popular?client_id='. $client_id .'&count='.$count;
    $info = file_get_contents($url);
    $data = json_decode($info);

    default:

    //API Instagram
    $url = 'https://api.instagram.com/v1/users/search?q=' .$username. '&client_id='.$client_id;
    $info = file_get_contents($url);
    $data = json_decode($info, true);
    $user_id = $data['data'][0]['id'];

    // get recent user images
    $url = 'https://api.instagram.com/v1/users/' .$user_id. '/media/recent/?client_id='.$client_id.'&count=' .$count;
    $info = file_get_contents($url);
    $data = json_decode($info);
    break;
}

// Show Module
  echo ($widthModule !== 0) ? '<div class="dt-instagallery" style="width:'.$widthModule.'px">' : '<div class="dt-instagallery" style="width:100%">';
  echo '<ul>';

  if (!empty($data)) {
    foreach ($data->data as $images) {
      echo '<li><a class="dt-insta-photo" href="'.$images->images->standard_resolution->url.'" rel="lightbox">';
      echo '<img class="dt-photo" src="'.$images->images->thumbnail->url.'" width="'.$width.'"alt="" />';
      echo '</a></li>';
    }
  }
  echo '</ul>';
  echo '</div>';

  $doc->addScriptDeclaration("
    window.onload = function(){
      baguetteBox.run('.dt-instagallery', {
        caption:true,
        animation: 'fadeIn',
      }
      );}");

}

else {
  echo '<div><p>' . JTEXT::_('MOD_DT_INSTAGALLERY_ERROR') .'</p></div>';
}
