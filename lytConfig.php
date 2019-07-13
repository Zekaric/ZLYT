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
require_once "lytConstant.php";
require_once "lytDebug.php";
require_once "lytLogin.php";
require_once "lytTemplate.php";
require_once "zFile.php";
require_once "zHtml.php";

////////////////////////////////////////////////////////////////////////////////
// variables
$config = false;

////////////////////////////////////////////////////////////////////////////////
// API
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// If the image folder is randomized then this function renames the folder to 
// a new randomized name.
function lytConfigChangeImageFolder()
{
   global $config;
   
   if (!lytConfigIsFolderImageRandom())
   {
      return;
   }
   
   // Create the file image folder 
   $imageFolderOld = lytConfigGetIamgeFolder();
   $imageFolderNew = lytConfigGetImageFolderPrefix() . rand(0, 100000);
   
   // Rename the image folder.
   rename($imageFolderOld, $imageFolderNew);
   $config[LYT_TAG_FOLDER_IMAGE] = $imageFolderNew;
   
   // Update the config file.
   lytConfigStore();
}

////////////////////////////////////////////////////////////////////////////////
// Create the configuration file.
function lytConfigCreate($post)
{
   global $config;

   // Get the form data.
   $config[LYT_TAG_SITE_NAME]              = $post[LYT_TAG_SITE_NAME];
   $config[LYT_TAG_SITE_ADDRESS_PUBLIC]    = $post[LYT_TAG_SITE_ADDRESS_PUBLIC];
   $config[LYT_TAG_SITE_ADDRESS_SECURE]    = $post[LYT_TAG_SITE_ADDRESS_SECURE];
   $config[LYT_TAG_COMPANY_NAME]           = $post[LYT_TAG_COMPANY_NAME];
   $config[LYT_TAG_OWNER_NAME]             = $post[LYT_TAG_OWNER_NAME];
   $config[LYT_TAG_OWNER_ALIAS]            = $post[LYT_TAG_OWNER_ALIAS];
   $config[LYT_TAG_FOLDER_FILE]            = "lytFile";
   $config[LYT_TAG_FOLDER_IMAGE]           = "lytImage";
   
   // Store the config file.
   lytConfigStore();
   
   // Make the folders
   mkdir($config[LYT_TAG_FOLDER_FILE]);
   mkdir($config[LYT_TAG_FOLDER_IMAGE]);
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigGet
function lytConfigGetCompanyName()
{
   global $config;
   return $config[LYT_TAG_COMPANY_NAME];
}

function lytConfigGetFolderFile()
{
   global $config;
   return $config[LYT_TAG_FOLDER_FILE];
}

function lytConfigGetFolderImage()
{
   global $config;
   return $config[LYT_TAG_FOLDER_IMAGE];
}   

function lytConfigGetLoginKey()
{
   global $config;
   return $config[LYT_TAG_LOGIN_KEY];
}

function lytConfigGetOwnerAlias()
{
   global $config;
   return $config[LYT_TAG_OWNER_ALIAS];
}

function lytConfigGetOwnerName()
{
   global $config;
   return $config[LYT_TAG_OWNER_NAME];
}

function lytConfigGetOwnerPassword()
{
   global $config;
   return $config[LYT_TAG_OWNER_PASSWORD];
}

function lytConfigGetSiteAddressPublic()
{
   global $config;
   return $config[LYT_TAG_SITE_ADDRESS_PUBLIC];
}

function lytConfigGetSiteAddressSecure()
{
   global $config;
   return $config[LYT_TAG_SITE_ADDRESS_SECURE];
}

function lytConfigGetSiteName()
{
   global $config;
   return $config[LYT_TAG_SITE_NAME];
}

////////////////////////////////////////////////////////////////////////////////
// lytIs
function lytConfigIsUsingGoogleCaptcha()
{
   global $config;
   return $config[LYT_TAG_IS_USING_GOOGLE_CAPTCHA];
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigLoad
function lytConfigLoad()
{
   global $config;

   if (!zFileIsExisting(LYT_CONFIG_FILE_NAME))
   {
      return false;
   }

   // Load in the values.
   $strLYT_CONFIG_FILE_NAME = LYT_CONFIG_FILE_NAME;
   require_once($strLYT_CONFIG_FILE_NAME);

   return true;
}

////////////////////////////////////////////////////////////////////////////////
// lytSet
function lytConfigSetOwnerPassword($password)
{
   global $config;
   $config[LYT_TAG_OWNER_PASSWORD] = $password;
}

function lytConfigSetLoginKey($key)
{
   global $config;
   $config[LYT_TAG_LOGIN_KEY] = $key;
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigStore
function lytConfigStore()
{
   global $config;
   
   $configFileLines = sprintf("<?php\n" .
      "   \$config[LYT_TAG_COMPANY_NAME             ] = '%s';\n" .
      "   \$config[LYT_TAG_FOLDER_FILE              ] = '%s';\n" .
      "   \$config[LYT_TAG_FOLDER_IMAGE             ] = '%s';\n" .
      "   \$config[LYT_TAG_GOOGLE_CREDENTIAL        ] = '%s';\n" .
      "   \$config[LYT_TAG_GOOGLE_ID                ] = '%s';\n" .
      "   \$config[LYT_TAG_GOOGLE_SECRET            ] = '%s';\n" . 
      "   \$config[LYT_TAG_IS_USING_GOOGLE_CAPTCHA  ] = '%s';\n" .
      "   \$config[LYT_TAG_LOGIN_KEY                ] = '%s';\n" .
      "   \$config[LYT_TAG_OWNER_ALIAS              ] = '%s';\n" .
      "   \$config[LYT_TAG_OWNER_NAME               ] = '%s';\n" .
      "   \$config[LYT_TAG_OWNER_PASSWORD           ] = '%s';\n" .
      "   \$config[LYT_TAG_SITE_ADDRESS_PUBLIC      ] = '%s';\n" .
      "   \$config[LYT_TAG_SITE_ADDRESS_SECURE      ] = '%s';\n" .
      "   \$config[LYT_TAG_SITE_NAME                ] = '%s';\n" .
      "?>\n"
      ,
      $config[LYT_TAG_COMPANY_NAME            ],
      $config[LYT_TAG_FOLDER_FILE             ],
      $config[LYT_TAG_FOLDER_IMAGE            ],
      $config[LYT_TAG_GOOGLE_CREDENTIAL       ],
      $config[LYT_TAG_GOOGLE_ID               ],
      $config[LYT_TAG_GOOGLE_SECRET           ],
      $config[LYT_TAG_IS_USING_GOOGLE_CAPTCHA ],
      $config[LYT_TAG_LOGIN_KEY               ],
      $config[LYT_TAG_OWNER_ALIAS             ],
      $config[LYT_TAG_OWNER_NAME              ],
      $config[LYT_TAG_OWNER_PASSWORD          ],
      $config[LYT_TAG_SITE_ADDRESS_PUBLIC     ],
      $config[LYT_TAG_SITE_ADDRESS_SECURE     ], 
      $config[LYT_TAG_SITE_NAME               ]
   );
   
   lytFileStoreString(LYT_CONFIG_FILE_NAME, $configFileLines);
}


////////////////////////////////////////////////////////////////////////////////
// Display
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Display a form for creating the LYT site.
function lytConfigContentGetForm()
{
   return "" .
      zHtmlParaHeader("", "1", 
         "Create a LYT Web Site") .
      zHtmlPara("", 
         "LYT: L)og Y)our T)houghts",

         "LYT is a simple content managment system that is aimed at hobbiests and small businesses " .
         "than it is for large companies.  Its goal is to be simple. ",

         "All the following fields can be later changed by editing the \"" . LYT_CONFIG_FILE_NAME .
         "\" file that will be in the same folder as this _create_.html file. ",
         
         "If you try to \"Configure\" when there already exists a \"" . LYT_CONFIG_FILE_NAME . "\", " .
         "all following attempts after the first attempt will fail.  You will need to rename or ">
         "delete the \"" . LYT_CONFIG_FILE_NAME . "\" file first if you want to use this page again. ") .
      zHtmlParaHeader("", "2", 
         "Configuration") .
      zHtmlForm(false, "_create_.php", 
         zHtmlTable("", 
            zHtmlTableRow("", 
               zHtmlTableCol("",
                  zHtmlStrNonBreaking("WebSite Name:"),
                  zHtmlFormInputCheck("", LYT_TAG_SITE_NAME, ""),
                  "WebSite name is the name you give your website."),
               zHtmlTableCol("",
                  zHtmlStrNonBreaking("WebSite Address Public:"),
                  zHtmlFormInputCheck("", LYT_TAG_SITE_ADDRESS_PUBLIC, "http://"),
                  "The address of the website that is insecure.  Include \"http://\""),
               zHtmlTableCol("",
                  zHtmlStrNonBreaking("WebSite Address Secure:"),
                  zHtmlFormInputCheck("", LYT_TAG_SITE_ADDRESS_SECURE, "https://"),
                  "The address of the website that is secure.  Include \"https://\""),
               zHtmlTableCol("",
                  "Company:",
                  zHtmlFormInputCheck("", LYT_TAG_COMPANY_NAME, ""),
                  "Your company name if applicable."),
               zHtmlTableCol("",
                  "Name:",
                  zHtmlFormInputCheck("", LYT_TAG_OWNER_NAME, ""),
                  "Your name or website maintainer's name."),
               zHtmlTableCol("",
                  "Alias:",
                  zHtmlFormInputCheck("", LYT_TAG_OWNER_ALIAS, ""),
                  "Your alias or website maintainer's alias."),
               zHtmlTableCol("",
                  "",
                  zHtmlFormInputButtonSubmit("", "", "Configure"),
                  ""))));
}

////////////////////////////////////////////////////////////////////////////////
// Display the results of the creation.
function lytConfigContentGetResult()
{
   return "" .
      zHtmlParaTitle("", "1", "Create a LYT Web Site") .
      zHtmlPara(
         LYT_CONFIG_FILE_NAME . " file created. ",
         
         zHtmlLink(lytLoginGetAddress(), "Login"));
}

////////////////////////////////////////////////////////////////////////////////
// Inform that there already exists a configuration.
function lytConfigContentGetMessageConfigAlreadyExists()
{
   return "" .
      zHtmlParaHeader("", "1", "Create a LYT Web Site") .
      zHtmlPara(
         LYT_CONFIG_FILE_NAME . " file already exists.  Please delete or rename the file before trying to create again. ",
         
         zHtmlLink(lytLoginGetAddress(), "Login"));
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigContentGetMessageConfigIsMissing
function lytConfigContentGetMessageConfigIsMissing()
{
   print 
      zHtmlParaTitle("lyt Config File Missing") .  
      zHtmlPara(
         "Please create the file first. ",
         
         zHtmlLink("_create_.php", "Create"));
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigContentGetMessage
function lytConfigDebugPrint()
{
   global $config;
   lytDebugPrintArray($config);
}

////////////////////////////////////////////////////////////////////////////////
// Page
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Display the config page.
function lytPageConfig($server, $post)
{
   $content = "";
   
   // Config file already exists.
   if      (lytConfigLoad())
   {
      // Display warning
      $content .= lytConfigContentGetMessageConfigAlreadyExists();
   }
   // There's a post, create the config file.
   else if ($server['REQUEST_METHOD'] == 'POST')
   {
      lytConfigCreate($post);
      $content .= lytConfigContentGetResult();
   }      
   // Display the config form.
   else
   {
      $content .= lytConfigContentGetForm();
   }

   // Create the page content.
   $page = lytPageSet(
      "",
      "Create LYT",
      "",
      "",
      $content,
      "",
      "");
   
   // Send the page to the client.
   return $page;
}

?>