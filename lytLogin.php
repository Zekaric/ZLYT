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
require_once "zHtml.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";

require_once "lytTemplate.php";

////////////////////////////////////////////////////////////////////////////////
// API
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytLoginCrypt
function lytLoginCrypt($password)
{
   return password_hash($password, PASSWORD_DEFAULT);
}

////////////////////////////////////////////////////////////////////////////////
// lytLoginGetAddress
function lytLoginGetAddress()
{
   return lytConfigGetSiteAddressSecure() . "/_login_.php";
}

////////////////////////////////////////////////////////////////////////////////
// 
function lytLoginIsLoggedIn($server, $post)
{
   // We are on a secure connection and
   // We have done the login and
   // the login key is the same...
   if (lytLoginIsSecure($server)    &&
       lytConfigGetLoginKey() != "" &&
       lytConfigGetLoginKey() === $post[LYT_TAG_LOGIN_KEY])
   {
      // We are logged in.
      return true;
   }

   return false;
}

////////////////////////////////////////////////////////////////////////////////
// lytLoginIsSecure
function lytLoginIsSecure($server)
{
   $thisUri     = "https://" . $server["HTTP_HOST"] . $server["REQUEST_URI"];
   $expectedUri = lytConfigGetSiteAddressSecure() . "/" . lytConfigGetFolderCandy() . "/_login_.php";

   if ($thisUri === $expectedUri)
   {
      return true;
   }
   return false;
}

////////////////////////////////////////////////////////////////////////////////
// lytLoginPasswordVerify
function lytLoginPasswordVerify($post)
{
   if (password_verify($post[LYT_TAG_OWNER_PASSWORD], lytConfigGetOwnerPassword()))
   {
      return true;
   }

   return false;
}

////////////////////////////////////////////////////////////////////////////////
// Display
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytLoginContentGetForm
function lytLoginContentGetForm()
{
   $str = LYT_TAG_OWNER_PASSWORD;

   // First time lytLogin will set the password as well as lytLogin.
   if (lytConfigGetOwnerPassword() == "")
   {
      $button = "Set Password and Login";
   }
   // Not the first time logging in.  
   else
   {
      $button = "Login";
   }

   return "" .
      zHtmlForm(false, "_login_.php", 
         zHtmlTable("",
            zHtmlTableRow("",
               zHtmlTableCol("",
                  zHtmlStrNonBreaking("WebSite Password:"),
                  zHtmlFormInputPassword("", LYT_TAG_OWNER_PASSWORD)),
               zHtmlTableCol("",
                  "",
                  zHtmlFormInputButtonSubmit("", "", 
                     $button)))));
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
// lytLoginContentGetMessagePasswordMismatch
function lytLoginContentGetMessagePasswordMismatch()
{
/*
   $str = lytLoginGetAddress();

   return "" . 
   
  <p class=lytTitle>CANDY: Login</h1>
  
  <p class=lyt>Login failed.</p>

  <p class=lyt><a href="$str">Secure Login</p>
PRINT;
*/
return "";
}

////////////////////////////////////////////////////////////////////////////////
// Doc
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Get the login page
function lytPageLogin($server, $post)
{
   $content = "";
   
   // Config file could not be loaded.
   if      (!lytConfigLoad())
   {
      $content .= lytConfigContentGetMessageConfigIsMissing();
   }
   // Not using the secure address.
   else if (!lytLoginIsSecure($_SERVER))
   {
      $content .= lytLoginContentGetMessageInsecureAddress();
   }
   // Login...
   else
   {
      // Post verify password.
      if ($server['REQUEST_METHOD'] == 'POST')
      {
         /*
         // There isn't a password set yet.  Create one now.
         if (lytConfigGetOwnerPassword() === '')
         {
            lytConfigSetOwnerPassword(lytLoginCrypt($_POST[LYT_TAG_OWNER_PASSWORD]));
            lytConfigStore();
         }
      
         // There is a password set.  Compare passwords.
         if (lytLoginPasswordVerify($post))
         {
            // Create a session key as save it in the config file.
            $loginKey = "". rand();
            lytConfigSetLoginKey(lytLoginCrypt($loginKey));
            lytConfigStore();

            // Set the session key on the post.
            $_POST[LYT_TAG_LOGIN_KEY] = $lytLoginKey;

            // Button to the admin pages.
            lytAdminDisplay_Button(LYT_TAG_ADMIN_PAGE_CONFIG, "", "Go To Admin Pages");
         }
         // Password comparison failed. 
         else
         {
            lytLoginContentGetMessagePasswordMismatch();
         }
         */
      }      
      // Display lytLogin form.
      else
      {
         $content = lytLoginContentGetForm();
      }
   }

   $page = lytPageSet(
      "",
      "LYT Admin Login",
      "",
      "",
      $content,
      "",
      "");
      
   return $page;
}

?>