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

///////////////////////////////////////////////////////////////////////////////
// includes
require_once "zFile.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";

$lytTemplateLogin = "";
$lytTemplateMenu  = "";
$lytTemplatePage  = "";
$lytTemplatePost  = "";

///////////////////////////////////////////////////////////////////////////////
// Get the login form.
function lytTemplateGetLoginForm()
{
   global $lytTemplateLogin;
   
   return $lytTemplateLogin;
}

///////////////////////////////////////////////////////////////////////////////
// Load in the page template file.
function lytTemplateLoadPage()
{
   global $lytTemplateLogin;
   global $lytTemplateMenu;
   global $lytTemplatePage;
   global $lytTemplatePost;
   
   $page = zFileLoadText("lytPage.html", false);
   
   $start            = strpos($page, "<!--LoginTemplate{-->", 0);
   $end              = strpos($page, "<!--}LoginTemplate-->", 0);
   $lytTemplateLogin = str_replace(  "<!--LoginTemplate{-->", "", substr($page, $start, $end - $start));
   $page             = str_replace(  "<!--}LoginTemplate-->", "", substr($page, 0, $start) . substr($page, $end));

   $start            = strpos($page, "<!--MenuTemplate{-->", 0);
   $end              = strpos($page, "<!--}MenuTemplate-->", 0);
   $lytTemplateMenu  = str_replace(  "<!--MenuTemplate{-->", "", substr($page, $start, $end - $start));
   $page             = str_replace(  "<!--}MenuTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
                     
   $start            = strpos($page, "<!--PostTemplate{-->", 0);
   $end              = strpos($page, "<!--}PostTemplate-->", 0);
   $lytTemplatePost  = str_replace(  "<!--PostTemplate{-->", "", substr($page, $start, $end - $start));
   $page             = str_replace(  "<!--}PostTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
   
   $lytTemplatePage  = $page;
   
   return $page;
}

///////////////////////////////////////////////////////////////////////////////
// Load in the admin page file.
function lytTemplateLoadPageAdmin()
{
   return zFileLoadText("lytPageAdmin.html", false);
}

///////////////////////////////////////////////////////////////////////////////
// Replace the main part of the page.
function lytTemplateReplaceColumnMain($body)
{
   return str_replace("[ColMain]", $body);
}

///////////////////////////////////////////////////////////////////////////////
// Replace commong variables in the page template.
function lytTemplateReplaceCommon($page)
{
   global $lytConfig;
   
   $page = str_replace("[SiteName]",      $lytConfig[LYT_TAG_SITE_NAME],      $page);
   $page = str_replace("[SiteUrl]",       $lytConfig[LYT_TAG_SITE_URL],       $page);
   $page = str_replace("[SiteUrlSafe]",   $lytConfig[LYT_TAG_SITE_URL_SAFE],  $page);
   
   return $page;
}

?>