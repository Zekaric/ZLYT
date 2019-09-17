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
    
   $lytConfig[LYT_TAG_ADMIN_COMPANY]   = $_POST["AdminCompany"];
   $lytConfig[LYT_TAG_ADMIN_NAME]      = $_POST["AdminName"];
   $lytConfig[LYT_TAG_ADMIN_LOGIN]     = $_POST["AdminLogin"];
   $lytConfig[LYT_TAG_SITE_NAME]       = $_POST["SiteName"];
   $lytConfig[LYT_TAG_SITE_URL]        = $_POST["SiteUrl"];
   $lytConfig[LYT_TAG_SITE_URL_SAFE]   = $_POST["SiteUrlSafe"];

   // Setting a new password.
   if ($_POST["AdminPassword"] != "")
   {
      $lytConfig[LYT_TAG_ADMIN_PASSWORD] = password_hash($_POST["AdminPassword"], PASSWORD_DEFAULT);
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

   $page = lytTemplateLoadPageAdmin();

   $page = str_replace("[AdminCompany]",  $lytConfig[LYT_TAG_ADMIN_COMPANY],  $page);
   $page = str_replace("[AdminName]",     $lytConfig[LYT_TAG_ADMIN_NAME],     $page);
   $page = str_replace("[AdminLogin]",    $lytConfig[LYT_TAG_ADMIN_LOGIN],    $page);
   $page = str_replace("[AdminPassword]", "",                                 $page);
   $page = str_replace("[SiteName]",      $lytConfig[LYT_TAG_SITE_NAME],      $page);
   $page = str_replace("[SiteUrl]",       $lytConfig[LYT_TAG_SITE_URL],       $page);
   $page = str_replace("[SiteUrlSafe]",   $lytConfig[LYT_TAG_SITE_URL_SAFE],  $page);

   return $page;
}
