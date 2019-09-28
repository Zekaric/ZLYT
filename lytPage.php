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
require_once "zDebug.php";
require_once "zFile.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";
require_once "lytLogin.php";

$lytPageColL     = "";
$lytPageLogin    = "";
$_pageTemplateSection     = "";
$lytPagePage     = "";
$lytPagePost     = "";
$lytPagePostCode = "";
$lytPagePostPara = "";

///////////////////////////////////////////////////////////////////////////////
// global
// function
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Display the default page.
function lytPageDefault()
{
   $page = lytPageLoad();
   
   $page = lytPageReplaceCommon($page);

   return $page;
}

///////////////////////////////////////////////////////////////////////////////
// Get the login form.
function lytPageGetLoginForm()
{
   global $lytPageLogin;
   
   return $lytPageLogin;
}

///////////////////////////////////////////////////////////////////////////////
// Get the link to the admin page.
function lytPageGetLinkAdmin()
{
   global $lytConfig;
   
   return $lytConfig[LYT_TAG_SITE_URL_SAFE] . "/lyt.php?op=admin";
}

///////////////////////////////////////////////////////////////////////////////
// Get the login link.
function lytPageGetLinkLogin()
{
   global $lytConfig;
   
   if (lytLoginIsUserAdmin())
   {
      return $lytConfig[LYT_TAG_SITE_URL_SAFE] . "/lyt.php?op=logout";
   }

   return $lytConfig[LYT_TAG_SITE_URL_SAFE] . "/lyt.php?op=login";
}

///////////////////////////////////////////////////////////////////////////////
// Load in the page template file.
function lytPageLoad()
{
   global $lytPageColL     ;
   global $lytPageLogin    ;
   global $_pageTemplateSection;
   global $lytPagePage     ;
   global $lytPagePost     ;
   global $lytPagePostCode ;
   global $lytPagePostPara ;
   
   $page = zFileLoadText("lytPage.html", false);
   
   $start           = strpos($page, "<!--LoginTemplate{-->", 0);
   $end             = strpos($page, "<!--}LoginTemplate-->", 0);
   $lytPageLogin    = str_replace(  "<!--LoginTemplate{-->", "", substr($page, $start, $end - $start));
   $page            = str_replace(  "<!--}LoginTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
                    
   $start                = strpos($page, "<!--SectionTemplate{-->", 0);
   $end                  = strpos($page, "<!--}SectionTemplate-->", 0);
   $_pageTemplateSection = str_replace(  "<!--SectionTemplate{-->", "", substr($page, $start, $end - $start));
   $page                 = str_replace(  "<!--}SectionTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
                    
   $start           = strpos($page, "<!--PostTemplate{-->", 0);
   $end             = strpos($page, "<!--}PostTemplate-->", 0);
   $lytPagePost     = str_replace(  "<!--PostTemplate{-->", "", substr($page, $start, $end - $start));
   $page            = str_replace(  "<!--}PostTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
                    
   $start           = strpos($page, "<!--PostParaTemplate{-->", 0);
   $end             = strpos($page, "<!--}PostParaTemplate-->", 0);
   $lytPagePostPara = str_replace(  "<!--PostParaTemplate{-->", "", substr($page, $start, $end - $start));
   $page            = str_replace(  "<!--}PostParaTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
                    
   $start           = strpos($page, "<!--PostCodeTemplate{-->", 0);
   $end             = strpos($page, "<!--}PostCodeTemplate-->", 0);
   $lytPagePostCode = str_replace(  "<!--PostCodeTemplate{-->", "", substr($page, $start, $end - $start));
   $page            = str_replace(  "<!--}PostCodeTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
                    
   $start           = strpos($page, "<!--ColLTemplate{-->", 0);
   $end             = strpos($page, "<!--}ColLTemplate-->", 0);
   $lytPageColL     = str_replace(  "<!--ColLTemplate{-->", "", substr($page, $start, $end - $start));
   $page            = str_replace(  "<!--}ColLTemplate-->", "", substr($page, 0, $start) . substr($page, $end));
   
   $lytPagePage  = $page;
   
   return $page;
}

///////////////////////////////////////////////////////////////////////////////
// Load in the admin page file.
function lytPageLoadAdmin()
{
   return zFileLoadText("lytPageAdmin.html", false);
}

///////////////////////////////////////////////////////////////////////////////
// Using the col left template.
function lytPageMakeColL($strLink, $strTitle)
{
   global $lytPageColL;
   
   $result = str_replace("[Link]",  $strLink,  $lytPageColL);
   $result = str_replace("[Title]", $strTitle, $result);
   
   return $result;
}

///////////////////////////////////////////////////////////////////////////////
// Replace the main part of the page.
function lytPageReplaceColumnMain($page, $body)
{
   return str_replace("[ColMain]", $body, $page);
}

///////////////////////////////////////////////////////////////////////////////
// Replace commong variables in the page template.
function lytPageReplaceCommon($page)
{
   global $lytConfig;

   $page = _ReplaceSectionList($page);

   $linkLogin = "<a href='" . lytPageGetLinkLogin() . "'>Login</a>";
   $linkAdmin = "";
   
   if (lytLoginIsUserAdmin())
   {
      $linkLogin = "<a href='" . lytPageGetLinkLogin() . "'>Logout</a>";
      $linkAdmin = "<a href='" . lytPageGetLinkAdmin() . "'>Admin</a>";
   }

   $page = str_replace("[LinkLogin]",     $linkLogin,                                     $page);
   $page = str_replace("[LinkAdmin]",     $linkAdmin,                                     $page);
   
   $page = str_replace("lytImage",        $lytConfig[LYT_TAG_SITE_URL_SAFE] . "/lytImage",$page);
   $page = str_replace("[SiteTitle]",     $lytConfig[LYT_TAG_SITE_NAME],                  $page);
   $page = str_replace("[SiteUrl]",       $lytConfig[LYT_TAG_SITE_URL],                   $page);
   $page = str_replace("[SiteUrlSafe]",   $lytConfig[LYT_TAG_SITE_URL_SAFE],              $page);
   
   return $page;
}

///////////////////////////////////////////////////////////////////////////////
// local
// function
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Replace the Section List
function _ReplaceSectionList($page)
{
   global $_pageTemplateSection;
   
   $sectionListStr = "";
   
   // For all sections...
   for ($index = 0; ; $index++)
   {
      // Get the section name.
      $name = lytSectionGetName($index);
      
      // End of list, we are done.
      if ($name == "")
      {
         break;
      }
      
      // Populate the section item string.
      $sectionItemStr = str_replace("[SectionName]", $name,                    $_pageTemplateSection);
      $sectionItemStr = str_replace("[SectionDir]",  lytSectionGetDir($index), $sectionItemStr);
      
      // Append to the section list string.
      $sectionListStr .= $sectionItemStr;
   }
   
   // Replace the place holder with the section list string.
   return str_replace("[SectionList]", $sectionListStr, $page);
}