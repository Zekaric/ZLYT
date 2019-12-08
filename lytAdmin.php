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
// include
require_once "zDebug.php";
require_once "zFile.php";
require_once "zHtml.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";

require_once "lytLogin.php";
require_once "lytConfig.php";

////////////////////////////////////////////////////////////////////////////////
// variables
$config = false;

////////////////////////////////////////////////////////////////////////////////
// global 
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Display the admin page.
function lytAdminPage()
{
   $page = ""; 

   // There's a post, create the config file.
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      lytAdminProcess();
   }      
   $page = _AdminPageLoad();

   print $page;
}

////////////////////////////////////////////////////////////////////////////////
// process the changes to the configuration of the site.
function lytAdminProcess()
{
   global $lytConfig;
    
   $lytConfig[TAG_LYT_ADMIN_COMPANY]   = $_POST["AdminCompany"];
   $lytConfig[TAG_LYT_ADMIN_NAME]      = $_POST["AdminName"];
   $lytConfig[TAG_LYT_ADMIN_LOGIN]     = $_POST["AdminLogin"];
   $lytConfig[TAG_LYT_SITE_TITLE]      = $_POST["SiteName"];
   $lytConfig[TAG_LYT_SITE_URL]        = $_POST["SiteUrl"];
   $lytConfig[TAG_LYT_SITE_URL_SAFE]   = $_POST["SiteUrlSafe"];

   // Setting a new password.
   if ($_POST["AdminPassword"] != "")
   {
      $lytConfig[TAG_LYT_ADMIN_PASSWORD] = password_hash($_POST["AdminPassword"], PASSWORD_DEFAULT);
   }

   lytConfigStore();
}

////////////////////////////////////////////////////////////////////////////////
// local
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Compose the Admin page.
function _AdminPageLoad()
{
   global $lytConfig;

   $page = lytPageLoadPage();

   $page = str_replace("lytAdminCompany",  $lytConfig[TAG_LYT_ADMIN_COMPANY],  $page);
   $page = str_replace("lytAdminName",     $lytConfig[TAG_LYT_ADMIN_NAME],     $page);
   $page = str_replace("lytAdminLogin",    $lytConfig[TAG_LYT_ADMIN_LOGIN],    $page);
   $page = str_replace("lytAdminPassword", "",                                 $page);
   $page = str_replace("lytSiteName",      $lytConfig[TAG_LYT_SITE_NAME],      $page);
   $page = str_replace("lytSiteUrl",       $lytConfig[TAG_LYT_SITE_URL],       $page);
   $page = str_replace("lytSiteUrlSafe",   $lytConfig[TAG_LYT_SITE_URL_SAFE],  $page);

   return $page;
}
