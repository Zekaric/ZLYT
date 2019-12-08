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

require_once "lytPage.php";

////////////////////////////////////////////////////////////////////////////////
// global
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Verify that a password is good.
function lytLoginIsPasswordGood()
{
   if (password_verify($_POST[TAG_LYT_OWNER_PASSWORD], lytConfigGetOwnerPassword()))
   {
      return true;
   }

   return false;
}

////////////////////////////////////////////////////////////////////////////////
// Is the user the admin
function lytLoginIsUserAdmin()
{
   if (lytConnectionIsSecure()                 &&
       isset($_SESSION[TAG_LYT_IS_USER_ADMIN]) &&
       $_SESSION[TAG_LYT_IS_USER_ADMIN])
   {
      return true;
   }
   return false;
}

////////////////////////////////////////////////////////////////////////////////
// Get the section the user is on.
function lytLoginGetSection()
{
   if (isset($_SESSION[TAG_LYT_SECTION]))
   {
      return $_SESSION[TAG_LYT_SECTION];
   }
   
   return 0;
}

////////////////////////////////////////////////////////////////////////////////
// Display the login page
function lytLoginPage()
{
   // Not using the secure address.
   if (!lytConnectionIsSecure())
   {
      return "";
   }

   $page = "";
   
   // Post verify password.
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      lytLoginProcess();
   }      
   // Display lytLogin form.
   else
   {
      $page = _LoginPageLoad();
   }

   return $page;
}

////////////////////////////////////////////////////////////////////////////////
// Process the login
function lytLoginProcess()
{
   global $lytConfig;
   
   // Check if this is the admin.
   $_SESSION[TAG_LYT_IS_USER_ADMIN] = false;
   if ($_POST["LoginName"] === $lytConfig[TAG_LYT_ADMIN_LOGIN] &&
       password_verify($_POST["LoginPassword"], $lytConfig[TAG_LYT_ADMIN_PASSWORD]))
   {
      $_SESSION[TAG_LYT_IS_USER_ADMIN] = true;
   }
   // Todo general user login.
   
   session_write_close();
}

////////////////////////////////////////////////////////////////////////////////
// Set the section
function lytLoginSetSection($index)
{
   $_SESSION[TAG_LYT_SECTION] = $index;
}

////////////////////////////////////////////////////////////////////////////////
// startup the code.
function lytLoginStart()
{
   session_start();
}

////////////////////////////////////////////////////////////////////////////////
// End the login session.
function lytLoginStop()
{
   $_SESSION = array();
   session_unset();
   session_destroy();
}

////////////////////////////////////////////////////////////////////////////////
// local
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Compose the login page.
function _LoginPageLoad()
{
   $page = lytPageLoad();
   
   $page = lytPageReplaceColumnMain($page, lytPageGetLoginForm());
   $page = lytPageReplaceCommon(    $page);

   return $page;
}
