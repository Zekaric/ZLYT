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
// Include
require_once "zDebug.php";
require_once "zFile.php";
require_once "zHtml.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";

require_once "lytLogin.php";

////////////////////////////////////////////////////////////////////////////////
// variables
$config = false;

////////////////////////////////////////////////////////////////////////////////
// API
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytAdminProcess
function lytAdminProcess($post)
{
}

////////////////////////////////////////////////////////////////////////////////
// Content
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytAdminDisplay
function lytAdminDisplay($server, $post)
{
   // Ensure the page to display is set to something.
   if ($post[CANDY_TAG_ADMIN_PAGE] === "")
   {
      $post[CANDY_TAG_ADMIN_PAGE] = CANDY_TAG_ADMIN_PAGE_CONFIG;
   }

   // Set up the menu.
   $strMENU = lytAdminDisplay_Menu($server, $post);   

   // Set up the form.
   $strFORM = "";
   if      ($post[CANDY_TAG_ADMIN_PAGE] === CANDY_TAG_ADMIN_PAGE_CONFIG)
   {
      $strFORM = lytAdminDisplay_FormConfig($server, $post);
   }
   else if ($post[CANDY_TAG_ADMIN_PAGE] == CANDY_TAG_ADMIN_PAGE_CATEGORY_LIST)
   {
      $strFORM = lytAdminDisplay_FormCategoryList($server, $post);
   }
   else if ($post[CANDY_TAG_ADMIN_PAGE] == CANDY_TAG_ADMIN_PAGE_POST)
   {
      $strFORM = lytAdminDisplay_FormPost($server, $post);
   }
   else if ($post[CANDY_TAG_ADMIN_PAGE] == CANDY_TAG_ADMIN_PAGE_POST_LIST)
   {
      $strFORM = lytAdminDisplay_FormPostList($server, $post);
   }
   else if ($post[CANDY_TAG_ADMIN_PAGE] == CANDY_TAG_ADMIN_PAGE_FILE_LIST)
   {
      $strFORM = lytAdminDisplay_FormFileList($server, $post);
   }
   else if ($post[CANDY_TAG_ADMIN_PAGE] == CANDY_TAG_ADMIN_PAGE_IMAGE_LIST)
   {
      $strFORM = lytAdminDisplay_FormImageList($server, $post);
   }
   
   // Set up the display.
   print <<<PRINT
  <p class=lytTitle>CANDY: Admin</h1>
  
  <p class=lyt>
  <table class=lyt>
   <tbody>
    <tr>
     <td valign=top>
      <!-- Menu -->
      $strMENU
     </td>
     <td>
      <!-- Form -->
      $strFORM
     </td>     
    </tbody>
   </table>
  </form>
PRINT;
}

////////////////////////////////////////////////////////////////////////////////
// lytAdminDisplay_Button
function lytAdminDisplay_Button($page, $op, $opStr)
{
   $strCANDY_TAG_LOGIN_KEY  = CANDY_TAG_LOGIN_KEY;
   $strCANDY_TAG_ADMIN_OP   = CANDY_TAG_ADMIN_OP;
   $strCANDY_TAG_ADMIN_PAGE = CANDY_TAG_ADMIN_PAGE;
   $strLOGIN_KEY            = lytConfigGetLoginKey();

   return = lytAdminDisplay_Form(
      $page,
      $op,
      "<input type=submit value=\"$opStr\">");
}

////////////////////////////////////////////////////////////////////////////////
// lytAdminDisplay_Form
function lytAdminDisplay_Form($page, $op, $content)
{
   $strCANDY_TAG_LOGIN_KEY    = CANDY_TAG_LOGIN_KEY;
   $strCANDY_TAG_ADMIN_OP     = CANDY_TAG_ADMIN_OP;
   $strCANDY_TAG_ADMIN_PAGE   = CANDY_TAG_ADMIN_PAGE;
   $loginKey                  = lytConfigGetLoginKey();

   return <<<PRINT
<form action=_admin_.php method=post>
 <input type=hidden name=$strCANDY_TAG_LOGIN_KEY  value=$loginKey>
 <input type=hidden name=$strCANDY_TAG_ADMIN_OP   value=$op>
 <input type=hidden name=$strCANDY_TAG_ADMIN_PAGE value=$page>
 $content
</form>
PRINT;
}

////////////////////////////////////////////////////////////////////////////////
// lytAdminDisplay_FormConfig
function lytAdminDisplay_FormConfig($server, $post)
{
   $str = 
      lytHtmlParaTitleSub("Configuration") .
      lytHtmlPara(
         lytAdminDisplay_Form(
            $post[CANDY_TAG_ADMIN_PAGE],
            CANDY_TAG_ADMIN_OP_CONFIG_UPDATE,
            lytHtmlTable(
               lytHtmlTableRow(
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("WebSite Name:"),
                     lytHtmlInputText(CANDY_TAG_SITE_NAME, lytConfigGetSiteName()),
                     "WebSite name is the name you give your website."),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("WebSite Address Public:"),
                     lytHtmlInputText(CANDY_TAG_SITE_ADDRESS_PUBLIC, lytConfigGetSiteAddressPublic()),
                     "The address of the website that is insecure.  Include \"http://\""),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("WebSite Address Secure:"),
                     lytHtmlInputText(CANDY_TAG_SITE_ADDRESS_SECURE, lytConfigGetSiteAddressSecure()),
                     "The address of the website that is secure.  Include \"https://\""),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("Company:"),
                     lytHtmlInputText(CANDY_TAG_COMPANY_NAME, lytConfigGetCompanyName()),
                     "Your company name if applicable."),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("Name:"),
                     lytHtmlInputText(CANDY_TAG_OWNER_NAME, lytConfigGetOwnerName()),
                     "Your name or website maintainer's name."),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("Alias:"),
                     lytHtmlInputText(CANDY_TAG_OWNER_ALIAS, lytConfigGetOwnerAlias()),
                     "Your alias or website maintainer's alias."),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("Use&nbsp;Google&nbsp;Recaptcha:"),
                     lytHtmlInputCheck(CANDY_TAG_IS_USING_GOOGLE_CAPTCHA, lytConfigIsUsingGoogleCaptcha()),
                     "For users to add comments to ensure they are not bots.  Google's recaptcha is good enough."),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("Google&nbsp;Credential:"),
                     lytHtmlInputText(CANDY_TAG_GOOGLE_CREDENTIAL, lytConfigGetGoogleCredential()),
                     "Your google credetial for recaptcha."),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("Google&nbsp;ID:"),
                     lytHtmlInputText(CANDY_TAG_GOOGLE_ID, lytConfigGetGoogleId()),
                     "Google ID for recaptcha."),
                  lytHtmlTableCol(
                     lytHtmlStrNonBreaking("Google&nbsp;Secret:"),
                     lytHtmlInputText(CANDY_TAG_GOOGLE_SECRET, lytConfigGetGoogleSecret()),
                     "Google secret response for successful recaptcha."),
                  lytHtmlTableCol(
                     "",
                     lytHtmlInputButton("", "Configure"),
                     "")))));

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// 
lytAdminDisplay_FormCategoryList($server, $post)
{
   return "";
}

////////////////////////////////////////////////////////////////////////////////
// 
lytAdminDisplay_FormFileList($server, $post)   
{
   return "";
}

////////////////////////////////////////////////////////////////////////////////
// 
lytAdminDisplay_FormImageList($server, $post)  
{
   return "";
}

////////////////////////////////////////////////////////////////////////////////
// 
lytAdminDisplay_FormPost($server, $post)
{
   return "";
}

////////////////////////////////////////////////////////////////////////////////
// 
lytAdminDisplay_FormPostList($server, $post)
{
   return "";
}

////////////////////////////////////////////////////////////////////////////////
// lytAdminDisplay_Menu
function lytAdminDisplay_Menu($server, $post)
{
   $strDISPLAY_CONFIG   = lytAdminDisplay_Button($post[CANDY_TAG_ADMIN_PAGE], CANDY_TAG_ADMIN_OP_DISPLAY_CONFIG,   "Manage Site Configuration");
   $strDISPLAY_CATEGORY = lytAdminDisplay_Button($post[CANDY_TAG_ADMIN_PAGE], CANDY_TAG_ADMIN_OP_DISPLAY_CATEGORY, "Manage Post Categories");
   $strDISPLAY_POST     = lytAdminDisplay_Button($post[CANDY_TAG_ADMIN_PAGE], CANDY_TAG_ADMIN_OP_DISPLAY_POST,     "Manage Posts");
   $strDISPLAY_FILE     = lytAdminDisplay_Button($post[CANDY_TAG_ADMIN_PAGE], CANDY_TAG_ADMIN_OP_DISPLAY_FILE,     "Manage Files");
   $strDISPLAY_IMAGE    = lytAdminDisplay_Button($post[CANDY_TAG_ADMIN_PAGE], CANDY_TAG_ADMIN_OP_DISPLAY_IMAGE,    "Manage Images");

   $str = <<<PRINT
<p class=lyt>
 $strDISPLAY_CONFIG
</p>
<p class=lyt>
 $strDISPLAY_CATEGORY 
 $strDISPLAY_POST
</p>
<p class=lyt>
 $strDISPLAY_FILE
</p>
<p class=lyt>
 $strDISPLAY_IMAGE
</p>
PRINT;

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// lytAdminDisplayButtonCategoryRemove
function lytAdminDisplayButtonCategoryRemove($categoryIndex)
{
   $strCANDY_TAG_LOGIN_KEY = CANDY_TAG_LOGIN_KEY;
   $strLOGIN_KEY           = lytConfigGetLoginKey();

   $strCANDY_TAG_ADMIN_OP  = CANDY_TAG_ADMIN_OP;
   $strADMIN_OP            = CANDY_TAG_ADMIN_OP_CATEGORY_REMOVE;

   print <<<PRINT
<form action=_admin_.php method=post>
 <input type=hidden name=$strCANDY_TAG_LOGIN_KEY value=$strLOGIN_KEY>
 <input type=hidden name=$strCANDY_TAG_ADMIN_OP  value=$strADMIN_OP>
 <p class=lyt><input class=lyt type=submit name=$categoryIndex value=X /></p>
</form>
PRINT;
}

////////////////////////////////////////////////////////////////////////////////
// Doc
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Display an admin page.
function lytAdminPage()
{
   $page = ""; 

   // Check Config file.
   if      (!lytIsConfigured())
   {
      $page = lytAdminPageForm();
   }
   // Check for login.
   else if (!lytLoginIsAdminLoggedIn($server, $post))
   {
      $page = lytLoginPageAdminLogin();
   }
   // Display or process the create.
   else
   {
      // There's a post, create the config file.
      if ($server['REQUEST_METHOD'] == 'POST')
      {
         $page = lytAdminProcess($server, $post);
      }      
      lytAdminPage($server, $post);
   }

   print $page;
}

?>