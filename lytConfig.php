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
require_once "lyt_Config.php"

require_once "lytLogin.php";
require_once "lytTemplate.php";

////////////////////////////////////////////////////////////////////////////////
// API
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Get a URL value.
function lytGetValue($key)
{
   // Check the _GET (in URL) if there is a value...
   // If not, check the _POST if there is value...
   // If not then "" 
   return 
      (isset($_GET[$key]) ? 
         $_GET[$key]      : 
         (isset($_POST["op"]) ? $_POST["op"] : ""));
}

////////////////////////////////////////////////////////////////////////////////
// Check to see if the LYT system is configured yet.
function lytIsConfigured()
{
   global $lytConfig;
   
   return $lytConfig[LYT_TAG_IS_CONFIGURED];
}

////////////////////////////////////////////////////////////////////////////////
// If the image folder is randomized then this function renames the folder to 
// a new randomized name.
function lytConfigChangeImageFolder()
{
   global $lytConfig;
   
   if (!lytConfigIsFolderImageRandom())
   {
      return;
   }
   
   // Create the file image folder 
   $imageFolderOld = lytConfigGetIamgeFolder();
   $imageFolderNew = lytConfigGetImageFolderPrefix() . rand(0, 100000);
   
   // Rename the image folder.
   rename($imageFolderOld, $imageFolderNew);
   $lytConfig[LYT_TAG_FOLDER_IMAGE] = $imageFolderNew;
   
   // Update the config file.
   lytConfigStore();
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigGet
function lytConfigGetCompanyName()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_COMPANY_NAME];
}

function lytConfigGetFolderFile()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_FOLDER_FILE];
}

function lytConfigGetFolderImage()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_FOLDER_IMAGE];
}   

function lytConfigGetLoginKey()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_LOGIN_KEY];
}

function lytConfigGetOwnerAlias()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_OWNER_ALIAS];
}

function lytConfigGetOwnerName()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_OWNER_NAME];
}

function lytConfigGetOwnerPassword()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_OWNER_PASSWORD];
}

function lytConfigGetSiteAddressPublic()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_SITE_ADDRESS_PUBLIC];
}

function lytConfigGetSiteAddressSecure()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_SITE_ADDRESS_SECURE];
}

function lytConfigGetSiteName()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_SITE_NAME];
}

////////////////////////////////////////////////////////////////////////////////
// lytIs
function lytConfigIsUsingGoogleCaptcha()
{
   global $lytConfig;
   return $lytConfig[LYT_TAG_IS_USING_GOOGLE_CAPTCHA];
}

////////////////////////////////////////////////////////////////////////////////
// lytSet
function lytConfigSetOwnerPassword($password)
{
   global $lytConfig;
   $lytConfig[LYT_TAG_OWNER_PASSWORD] = $password;
}

function lytConfigSetLoginKey($key)
{
   global $lytConfig;
   $lytConfig[LYT_TAG_LOGIN_KEY] = $key;
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigStore
function lytConfigStore()
{
   global $lytConfig;
   
   $lytConfigFileContent = "" . 
      "<?php\n" .
      "   \$lytConfig[LYT_TAG_COMPANY_NAME             ] = '" . $lytConfig[LYT_TAG_COMPANY_NAME       ] . "';\n" .
      "   \$lytConfig[LYT_TAG_FOLDER_FILE              ] = '" . $lytConfig[LYT_TAG_FOLDER_FILE        ] . "';\n" .
      "   \$lytConfig[LYT_TAG_FOLDER_IMAGE             ] = '" . $lytConfig[LYT_TAG_FOLDER_IMAGE       ] . "';\n" .
      "   \$lytConfig[LYT_TAG_LOGIN_KEY                ] = '" . $lytConfig[LYT_TAG_LOGIN_KEY          ] . "';\n" .
      "   \$lytConfig[LYT_TAG_OWNER_ALIAS              ] = '" . $lytConfig[LYT_TAG_OWNER_ALIAS        ] . "';\n" .
      "   \$lytConfig[LYT_TAG_OWNER_NAME               ] = '" . $lytConfig[LYT_TAG_OWNER_NAME         ] . "';\n" .
      "   \$lytConfig[LYT_TAG_OWNER_PASSWORD           ] = '" . $lytConfig[LYT_TAG_OWNER_PASSWORD     ] . "';\n" .
      "   \$lytConfig[LYT_TAG_SITE_ADDRESS_PUBLIC      ] = '" . $lytConfig[LYT_TAG_SITE_ADDRESS_PUBLIC] . "';\n" .
      "   \$lytConfig[LYT_TAG_SITE_ADDRESS_SECURE      ] = '" . $lytConfig[LYT_TAG_SITE_ADDRESS_SECURE] . "';\n" .
      "   \$lytConfig[LYT_TAG_SITE_NAME                ] = '" . $lytConfig[LYT_TAG_SITE_NAME          ] . "';\n" .
      "?>\n";
   
   zFileStoreText(LYT_CONFIG_FILE_NAME, $lytConfigFileContent);
}

?>