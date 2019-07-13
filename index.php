<?php
/* lyt.php ***********7********************************************************

L.Y.T. : Log Your Thoughts

Author: Robbert de Groot

Description:

******************************************************************************/

/* MIT License ****************************************************************
Copyright (c) 2019 Robbert de Groot

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
******************************************************************************/

////////////////////////////////////////////////////////////////////////////////
// includes
require_once "zcFile.php";

////////////////////////////////////////////////////////////////////////////////
// Get the arguments from the URL.
$siteTitle     = "Zekaric";
$siteAuth      = "Robbert de Groot";
$siteUrl       = "http://www.zekaric.com";
$siteUrlSafe   = "https://secure127.inmotionhosting.com/~zekari5";

// The op= command that will display the new post form.
$sitePost      = "blah";
   
////////////////////////////////////////////////////////////////////////////////
// Get the arguments from the URL.
$op            = (isset($_GET["op"]   ) ? $_GET["op"]    : "");
$value         = (isset($_GET["value"]) ? $_GET["value"] : "");
$topic         = (isset($_GET["topic"]) ? $_GET["topic"] : "Home");
$post          = (isset($_GET["post"] ) ? $_GET["post"]  : "");

///////////////////////////////////////////////////////////////////////////////
// Set up the file vars.
$imgDir        = $siteUrlSafe . "/image";
$lytFile       = $siteUrlSafe . "/lyt.js";

$topicDir      =                      $topic . "/";
$topicDirUrl   = $siteUrlSafe . "/" . $topic . "/";
$topicFile     =                   $topicDir    . "/lytTopic.js";
$topicFileUrl  =                   $topicDirUrl . "/lytTopic.js";
$topicPost     =                   $topicDir    . "/" . $post  . ".js";
$topicPostUrl  =                   $topicDirUrl . "/" . $post  . ".js";
$topicFileLink = "<script src='" . $topicFileUrl . "?modified=" . filemtime($topicFile) . "'></script>\n";
$topicPostLink = "<script src='" . $topicPostUrl . "?modified=" . filemtime($topicPost) . "'></script>\n";

// Prepare the script stuff.
$script        = 
   $lytFile . 
   $topicFileLink;
if ($post != "")
{
   $script    .= $topicPostLink;
}

$script       .= 
   "<script>\n" .
   "var _url      = '" . $siteUrl      . "';\n" .
   "var _urlSafe  = '" . $siteUrlSafe  . "';\n" .
   "var _imgDir   = '" . $imgDir       . "';\n" .
   "var _topic    = '" . $topic        . "';\n" .
   "var _post     = '" . $post         . "';\n";   
   
if ($post != "")
{
   $script    .= "var _isDisplayingPost = false;\n";
}
else
{
   $script    .= "var _isDisplayingPost = true;\n";
}

$script       .= "</script>\n";

///////////////////////////////////////////////////////////////////////////////
// If this is the first time check to see if there are the basics setup.
if (!zcDirIsExisting($topic))
{
   // Create the topic directory.
   zcDirCreate($topic);
   
   // Create the topis lyt.js script.
   $fileContent = 
      "_postCount        = 0;\n" .
      "_postId           = [];\n" .
      "_postTitle        = [];\n" .
      "_postAuth         = [];\n" .
      "_postDate         = [];\n" .
      "_postBody         = [];\n" .
      "_postIsCommentsOn = [];\n";
      
   $file = zcFileStoreText($topic . "/lytTopic.js", $fileContent);
}

///////////////////////////////////////////////////////////////////////////////
// Display the project list.
if      ($op == "")
{
   // Nothing special to do.
}

///////////////////////////////////////////////////////////////////////////////
// Get the page template.
$template = zcFileLoadText("page.tplt", false);

// Replace the wild cards with their values.
$template = str_replace("[Script]",           $script,                                 $template);
$template = str_replace("[PageTitle]",        $siteTitle . " " . $topic . " " . $post, $template);
$template = str_replace("[PageMenu]",         "",                                      $template);

// Ensure the images are displayed properly.
$template = str_replace("[ImageDir]",         $imgDir,                                 $template);

print $template;

?>
