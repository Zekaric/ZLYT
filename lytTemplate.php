<?php
/* MIT License ****************************************************************
Copyright (c) 2015 Robbert de Groot

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
require_once "zFile.php";

////////////////////////////////////////////////////////////////////////////////
// Load in the page template file.
function lytTemplateLoadPage()
{
   return zFileLoadText("lytTemplatePage.html", false);
}

////////////////////////////////////////////////////////////////////////////////
// lytPageSet
function lytPageSet($HtmlHead, $Title, $Menu, $ColL, $Content, $ColR, $Footer)
{
   $page = lytTemplateLoadPage();
   
   $page = str_replace("[HtmlHead]",    $HtmlHead,                            $page);
   $page = str_replace("[Title]",       $Title,                               $page);
   $page = str_replace("[Menu]",        $Menu,                                $page);
   $page = str_replace("[ColL]",        $ColL,                                $page);
   $page = str_replace("[Content]",     $Content,                             $page);
   $page = str_replace("[ColR]",        $ColR,                                $page);
   $page = str_replace("[Footer]",      $Footer,                              $page);
   $page = str_replace("[UrlSafe]",     $config[LYT_TAG_SITE_ADDRESS_SECURE], $page);
   $page = str_replace("[ImageFolder]", $config[LYT_TAG_FOLDER_IMAGE],        $page);

   return $page;
}

?>