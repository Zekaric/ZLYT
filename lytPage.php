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

$_pageTemplate                = "";
$_pageTemplateColL            = "";
$_pageTemplateColR            = "";
$_pageTemplateLogin           = "";
$_pageTemplateSectionCreate   = "";
$_pageTemplateSectionListItem = "";
$_pageTemplateSectionPost     = "";
$_pageTemplateSectionPostNew  = "";

///////////////////////////////////////////////////////////////////////////////
// global
// function
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Display the default page.
function lytPageDefault()
{
   $page = lytPageLoad();

   $page = lytPageReplaceColumnMain($page, _BuildSectionPostContent());
   
   $page = lytPageReplaceCommon($page);

   return $page;
}

///////////////////////////////////////////////////////////////////////////////
// Get the login form.
function lytPageGetLoginForm()
{
   global $_pageTemplateLogin;
   
   return $_pageTemplateLogin;
}

///////////////////////////////////////////////////////////////////////////////
// Get the section create form.
function lytPageGetSectionCreateForm()
{
   global $_pageTemplateSectionCreate;
   
   return $_pageTemplateSectionCreate;
}

///////////////////////////////////////////////////////////////////////////////
// Get the link to the admin page.
function lytPageGetLinkAdmin()
{
   global $lytConfig;
   
   return $lytConfig[TAG_LYT_SITE_URL_SAFE] . "/lyt.php?op=admin";
}

///////////////////////////////////////////////////////////////////////////////
// Get the login link.
function lytPageGetLinkLogin()
{
   global $lytConfig;
   
   if (lytLoginIsUserAdmin())
   {
      return $lytConfig[TAG_LYT_SITE_URL_SAFE] . "/lyt.php?op=logout";
   }

   return $lytConfig[TAG_LYT_SITE_URL_SAFE] . "/lyt.php?op=login";
}

///////////////////////////////////////////////////////////////////////////////
// Load in the page template file.
function lytPageLoad()
{
   global $_pageTemplate               ;
   global $_pageTemplateColL           ;
   global $_pageTemplateColR           ;
   global $_pageTemplateLogin          ;
   global $_pageTemplateSectionCreate  ;
   global $_pageTemplateSectionListItem;
   global $_pageTemplateSectionPost    ;
   global $_pageTemplateSectionPostNew ;
   
   $_pageTemplate                = zFileLoadText("lytTemplatePage.html",            false);
   $_pageTemplateColL            = zFileLoadText("lytTemplateColL.html",            false);
   $_pageTemplateColR            = zFileLoadText("lytTemplateColR.html",            false);
   $_pageTemplateLogin           = zFileLoadText("lytTemplateLogin.html",           false);
   $_pageTemplateSectionCreate   = zFileLoadText("lytTemplateSectionCreate.html",   false);
   $_pageTemplateSectionListItem = zFileLoadText("lytTemplateSectionListItem.html", false);
   $_pageTemplateSectionPost     = zFileLoadText("lytTemplateSectionPost.html",     false);
   $_pageTemplateSectionPostNew  = zFileLoadText("lytTemplateSectionPostNew.html",  false);

   return $_pageTemplate;
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
   global $_pageTemplateColL;
   
   $result = str_replace("lytColLink",  $strLink,  $_pageTemplateColL);
   $result = str_replace("lytColTitle", $strTitle, $result);
   
   return $result;
}

///////////////////////////////////////////////////////////////////////////////
// Replace the main part of the page.
function lytPageReplaceColumnMain($page, $body)
{
   return str_replace("lytColMain", $body, $page);
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

   $page = str_replace("lytLinkLogin",    $linkLogin,                                     $page);
   $page = str_replace("lytLinkAdmin",    $linkAdmin,                                     $page);
   
   $page = str_replace("lytSiteName",     $lytConfig[TAG_LYT_SITE_TITLE],                 $page);
   $page = str_replace("lytSiteUrlSafe",  $lytConfig[TAG_LYT_SITE_URL_SAFE],              $page);
   $page = str_replace("lytSiteUrlPage",  lytGetSiteUrlPage(),                            $page);
   $page = str_replace("lytSiteUrl",      $lytConfig[TAG_LYT_SITE_URL],                   $page);

   // Blank out if nothing set.
   $page = str_replace("lytColL",   "", $page);
   $page = str_replace("lytColR",   "", $page);
   $page = str_replace("lytFooter", "", $page);

   if (lytConnectionIsSecure())
   {
      $page = str_replace("lytImage", $lytConfig[TAG_LYT_SITE_URL_SAFE] . "/lytImage",$page);
   }
   else
   {
      $page = str_replace("lytImage", $lytConfig[TAG_LYT_SITE_URL] . "/lytImage",$page);
   }
   
   return $page;
}

///////////////////////////////////////////////////////////////////////////////
// local
// function
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Build the section posts.
function _BuildSectionPostContent()
{
   global $lytConfig;
   global $_pageTemplateSectionPost;
   global $_pageTemplateSectionPostNew;
   
   $content = "";

   // Add the post form if admin is logged in.
   if (lytLoginIsUserAdmin())
   {
      $content .= $_pageTemplateSectionPostNew;
   }
   
   // Ensure the posts are loaded.
   lytSectionPostStart();
   
   // Display all the posts.
   for ($index = lytSectionPostGetCount() - 1; ; $index--)
   {
      // Get the post information.
      $title = lytSectionPostGetTitle($index);
      
      // If title is "" then there are no more posts.
      if ($title == "")
      {
         break;
      }
      
      $date  = lytSectionPostGetDate( $index);
      $body  = lytSectionPostGetBody( $index);
      
      // Compose the post
      $post = str_replace("lytSectionPostTitle",  $title,                         $_pageTemplateSectionPost);
      $post = str_replace("lytSectionPostDate",   $date,                          $post);
      $post = str_replace("lytSectionPostBody",   $body,                          $post);
      $post = str_replace("lytSectionPostAuthor", $lytConfig[TAG_LYT_ADMIN_NAME], $post);
      
      // Tack it onto the post list.
      $content .= $post;
   }
   
   return $content;
}

///////////////////////////////////////////////////////////////////////////////
// Replace the Section List
function _ReplaceSectionList($page)
{
   global $lytConfig;
   global $_pageTemplateSectionListItem;
   
   $sectionListStr = "";
   
   // Add the create section line if admin is logged in.
   if (lytLoginIsUserAdmin())
   {
      $sectionListStr .= "<a href=\"" . $lytConfig[TAG_LYT_SITE_URL_SAFE] . "/lyt.php?op=sectionCreate\">+ Section</a> - ";
   }

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
      $sectionItemStr = str_replace("lytSectionName", $name,                    $_pageTemplateSectionListItem);
      $sectionItemStr = str_replace("lytSectionDir",   lytSectionGetDir($index), $sectionItemStr);
      
      // Append to the section list string.
      $sectionListStr .= $sectionItemStr;
   }
   
   // Replace the place holder with the section list string.
   return str_replace("lytSectionList", $sectionListStr, $page);
}
