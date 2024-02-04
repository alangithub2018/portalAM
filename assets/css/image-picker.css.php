<?php ob_start ("ob_gzhandler"); $etag = md5_file(__FILE__); header("Etag: $etag"); header("content-type: text/css; charset: UTF-8"); header ("Cache-Control:public, max-age=31536000"); $offset = 60 * 60 * 1800; $expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT"; header ($expire); ?>
ul.thumbnails.image_picker_selector {
  overflow: auto;
  list-style-image: none;
  list-style-position: outside;
  list-style-type: none;
  padding: 0px;
  margin: 0px; }
  ul.thumbnails.image_picker_selector ul {
    overflow: auto;
    list-style-image: none;
    list-style-position: outside;
    list-style-type: none;
    padding: 0px;
    margin: 0px; }
  ul.thumbnails.image_picker_selector li.group {width:100%;} 
  ul.thumbnails.image_picker_selector li.group_title {
    float: none; }
  ul.thumbnails.image_picker_selector li {
    margin: 0px 12px 12px 0px;
    float: left; }
    ul.thumbnails.image_picker_selector li .thumbnail {
      padding: 6px;
      border: 1px solid #dddddd;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none; }
      ul.thumbnails.image_picker_selector li .thumbnail img {
        -webkit-user-drag: none; }
    ul.thumbnails.image_picker_selector li .thumbnail.selected {
      background: #0088cc; }