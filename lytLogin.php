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
require_once "zDebug.php";
require_once "zFile.php";
require_once "zHtml.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";

require_once "lytTemplate.php";

////////////////////////////////////////////////////////////////////////////////
// API
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytLoginIsSecure
function lytLoginIsSecure()
{
   global $lytConfig;
   
   $thisUri = "https://" . $_SERVER["HTTP_HOST"];

   if ($thisUri === $lytConfig[LYT_TAG_SITE_URL_SAFE])
   {
      return true;
   }
   return false;
}

////////////////////////////////////////////////////////////////////////////////
// lytLoginPasswordVerify
function lytLoginPasswordVerify()
{
   if (password_verify($_POST[LYT_TAG_OWNER_PASSWORD], lytConfigGetOwnerPassword()))
   {
      return true;
   }

   return false;
}

////////////////////////////////////////////////////////////////////////////////
// Process the login
function lytLoginProcess()
{
   global $lytConfig;
   
   // Check if this is the admin.
   if ($_POST["LoginName"] === $lytConfig[LYT_TAG_ADMIN_LOGIN] &&
       password_verify($_POST["loginPassword"], lytConfig[LYT_TAG_ADMIN_PASSWORD]))
   {
      setcookie("isAdmin", $_POST["loginPassword"], time() + 21600, "/");
   }       
}

////////////////////////////////////////////////////////////////////////////////
// lytLoginContentGetMessageInsecureAddress
function lytLoginContentGetMessageInsecureAddress()
{
   return "" .
      zHtmlParaHeader("", "1", 
         "LYT Login") . 
      zHtmlPara("", 
         "Insecure address used.") .
      zHtmlPara("",
         zHtmlLink("",
            lytLoginGetAddress(), "Secure Login"));
}

////////////////////////////////////////////////////////////////////////////////
// Page
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Display the login page
function lytLoginPage()
{
   $page = "";
   
   // Not using the secure address.
   if (lytLoginIsSecure())
   {
      // Post verify password.
      if ($_SERVER['REQUEST_METHOD'] == 'POST')
      {
         lytLoginProcess();
      }      
      // Display lytLogin form.
      else
      {
         $page = lytLoginPageLoad();
      }
   }

   print $page;
}

////////////////////////////////////////////////////////////////////////////////
// Compose the login page.
function lytLoginPageLoad()
{
   $page = lytTemplateLoadPage();
   
   $page = lytTemplateReplaceColumnMain(lytTemplateGetLoginForm());
   
   $page = lytTemplateReplaceCommon($page);

   return $page;
}

?>