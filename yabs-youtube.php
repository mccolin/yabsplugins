<?php
/*
Plugin Name: YABS YouTube Awesome-izer
Plugin URI: http://yetanotherbeershow.com/
Description: A plugin that provides YouTube shortcodes and other YouTube-functionality helpers
Author: Colin McCloskey
Version: 0.9
Author URI: http://mccolin.com/
*/


/**
 * YouTube embed shortcode. Allows embedding of YouTube videos with embed
 * and thumbnail support in code body
 */
function yabs_youtube_code($atts) {
  extract(shortcode_atts(array(
    'id' => 'N0BWC4pVjM8',
    'width' => '560',
    'height' => '340'
  ), $atts));
  
  return build_youtube_tag($id, $width, $height);
}
add_shortcode('youtube','yabs_youtube_code');


/**
 * Function to extract a shortcode-formatted YouTube video id from
 * post body content and return the video id only
 */
function extract_youtube_id($content) {
  if( preg_match('/\[youtube.+id="(.*?)".*\]/i',$content,$regs) ) {
    return $regs[1];
  }
  else {
    return "NO_VIDEO";
  }
}

/** 
 * Build a YouTube Video embed tag from an id in the given
 * display size
 */
function build_youtube_tag($id, $width, $height) {
  return <<<EOT
  <object width="{$width}" height="{$height}">
    <param name="movie" value="http://www.youtube.com/v/{$id}?fs=1&amp;hl=en_US"></param>
    <param name="allowFullScreen" value="true"></param>
    <param name="allowscriptaccess" value="always"></param>
    <embed src="http://www.youtube.com/v/{$id}?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="{$width}" height="{$height}"></embed>
  </object>
EOT;
}

/**
 * Given post content, this function returns only the YouTube
 * video contained within the post and returns the video's embed
 * tag
 */
function extract_youtube_video($content, $width, $height) {
  if ( $id = extract_youtube_id($content) ) {
    return build_youtube_tag($id, $width, $height);
  }
}

/**
 * Extract only the URL for the embeddable video
 */
function extract_youtube_video_url($content) {
  if ( $id = extract_youtube_id($content) ) {
    return "http://www.youtube.com/v/{$id}?fs=1&amp;hl=en_US";
  }
}

/**
 * Given post content which contains a YouTube embed, return the
 * video's thumbnail URL
 */
function extract_youtube_thumb_url($content) {
  $id = extract_youtube_id($content);
  return "http://img.youtube.com/vi/${id}/0.jpg";     // Large Thumb
  // return "http://img.youtube.com/vi/${id}/2.jpg";  // Small Thumb  
}
