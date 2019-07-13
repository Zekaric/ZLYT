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
require_once "lytFile.php";
require_once "lytLogin.php";
require_once "lytHtml.php";

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
// lytConfigCreate
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
   $config[LYT_TAG_FOLDER_POST]            = $post[LYT_TAG_FOLDER_POST];
   $config[LYT_TAG_FOLDER_FILE]            = $post[LYT_TAG_FOLDER_FILE];
   $config[LYT_TAG_FOLDER_IMAGE_PREFIX]    = $post[LYT_TAG_FOLDER_IMAGE_PREFIX];

   // Create the initial image folder.
   $imageFolder  = $post[LYT_TAG_FOLDER_IMAGE_PREFIX];
   $imageFolder .= rand(0, 1000000);

   $config[LYT_TAG_FOLDER_IMAGE] = $imageFolder;
   
   // Store the config file.
   lytConfigStore();
   
   // Make the folders
   mkdir($config[LYT_TAG_FOLDER_POST]);
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

function lytConfigGetFolderLyt()
{
   global $config;
   return $config[LYT_TAG_FOLDER_lyt];
}

function lytConfigGetFolderFile()
{
   global $config;
   return $config[LYT_TAG_FOLDER_FILE];
}

function lytConfigGetFolderImage()
{
   global $config;
   global $nameFolderImage;
   return $config[LYT_TAG_FOLDER_IMAGE];
}   

function lytConfigGetFolderImagePrefix()
{
   global $config;
   return $config[LYT_TAG_FOLER_IMAGE_PREFIX];
}

function lytConfigGetFolderPost()
{
   global $config;
   return $config[LYT_TAG_FOLDER_POST];
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

   if (!lytFileIsExisting(lyt_CONFIG_FILE_NAME))
   {
      return false;
   }

   // Load in the values.
   $strlyt_CONFIG_FILE_NAME = lyt_CONFIG_FILE_NAME;
   require_once($strlyt_CONFIG_FILE_NAME);

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
      "   \$config[LYT_TAG_FOLDER_lyt             ] = '%s';\n" .
      "   \$config[LYT_TAG_FOLDER_FILE              ] = '%s';\n" .
      "   \$config[LYT_TAG_FOLDER_IMAGE             ] = '%s';\n" .
      "   \$config[LYT_TAG_FOLDER_IMAGE_PREFIX      ] = '%s';\n" .
      "   \$config[LYT_TAG_FOLDER_POST              ] = '%s';\n" .
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
      ""
      ,
      $config[LYT_TAG_COMPANY_NAME            ],
      $config[LYT_TAG_FOLDER_lyt            ],
      $config[LYT_TAG_FOLDER_FILE             ],
      $config[LYT_TAG_FOLDER_IMAGE            ],
      $config[LYT_TAG_FOLDER_IMAGE_PREFIX     ],
      $config[LYT_TAG_FOLDER_POST             ],
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
   
   lytFileStoreString(lyt_CONFIG_FILE_NAME, $configFileLines);
}


////////////////////////////////////////////////////////////////////////////////
// Display
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytConfigDisplayForm
function lytConfigDisplayForm()
{
   print 
      lytHtmlParaTitle("Create a LYT Web Site") .
      lytHtmlPara(
         "LYT: L)og Y)our T)houghts",

         "LYT is a simple content managment system that is aimed at hobbiests and ".
         "small businesses than it is for large companies.  Its goal is to be simple " .
         "and efficient.",

         "All the following fields can be later changed by editing the \"lyt.config.php\"" .
         "file that will be in the same folder as this _create_.html file.",
         
         "If you try to \"Configure\" below more than once, all following attempts " .
         "after the first attempt will fail.  You will need to rename or delete the " .
         "\"lyt.config.php\" file first if you want to use this page again.") .
      lytHtmlParaTitleSub("Configuration") .
      lytHtmlPara(
         "<form action=_create_.php method=post>" .
         lytHtmlTable(
            lytHtmlRow(
               lytHtmlCol(
                  lytHtmlStrNonBreaking("WebSite Name:"),
                  lytHtmlInputText(LYT_TAG_SITE_NAME, ""),
                  "WebSite name is the name you give your website."),
               lytHtmlCol(
                  lytHtmlStrNonBreaking("WebSite Address Public:"),
                  lytHtmlInputText(LYT_TAG_SITE_ADDRESS_PUBLIC, ""),
                  "The address of the website that is insecure.  Include \"http://\""),
               lytHtmlCol(
                  lytHtmlStrNonBreaking("WebSite Address Secure:"),
                  lytHtmlInputText(LYT_TAG_SITE_ADDRESS_SECURE, ""),
                  "The address of the website that is secure.  Include \"https://\""),
               lytHtmlCol(
                  "Company:",
                  lytHtmlInputText(LYT_TAG_COMPANY_NAME, ""),
                  "Your company name if applicable."),
               lytHtmlCol(
                  "Name:",
                  lytHtmlInputText(LYT_TAG_OWNER_NAME, ""),
                  "Your name or website maintainer's name."),
               lytHtmlCol(
                  "Alias:",
                  lytHtmlInputText(LYT_TAG_OWNER_ALIAS, ""),
                  "Your alias or website maintainer's alias."),
               lytHtmlCol(
                  lytHtmlStrNonBreaking("Post&nbsp;Folder:"),
                  lytHtmlInputText(LYT_TAG_FOLDER_POST, ""),
                  "Sub folder where Post, your blogs and articles etc., will be placed."),
               lytHtmlCol(
                  lytHtmlStrNonBreaking("File&nbsp;Folder:"),
                  lytHtmlInputText(LYT_TAG_FOLDER_FILE, ""),
                  "Sub folder where general files will be placed."),
               lytHtmlCol(
                  lytHtmlStrNonBreaking("Image&nbsp;Folder&nbsp;Prefix:"),
                  lytHtmlInputText(LYT_TAG_FOLDER_IMAGE_PREFIX, ""),
                  "Sub folder where images will be placed."),
               lytHtmlCol(
                  "",
                  lytHtmlInputButton("", "Configure"),
                  ""))) .
         "</form>\n");
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigDisplayResult
function lytConfigDisplayResult()
{
   print 
      lytHtmlParaTitle("Create a lyt Web Site") .
      lytHtmlPara(
         lyt_CONFIG_FILE_NAME . " file created.",
         
         lytHtmlLink(lytLoginGetAddress(), "Login"));
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigDisplayMessageConfigAlreadyExists
function lytConfigDisplayMessageConfigAlreadyExists()
{
   print 
      lytHtmlParaTitle("Create a lyt Web Site") .
      lytHtmlPara(
         lyt_CONFIG_FILE_NAME . " file already exists.  Please delete or rename the file before trying to create again.",
         
         lytHtmlLink(lytLoginGetAddress(), "Login"));
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigDisplayMessageConfigIsMissing
function lytConfigDisplayMessageConfigIsMissing()
{
   print 
      lytHtmlParaTitle("lyt Config File Missing") .  
      lytHtmlPara(
         "Please create the file first.",
         
         lytHtmlLink("_create_.php", "Create"))
}

////////////////////////////////////////////////////////////////////////////////
// lytConfigDisplayMessage
function lytConfigDebugPrint()
{
   global $config;
   lytDebugPrintArray($config);
}

////////////////////////////////////////////////////////////////////////////////
// Doc
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytConfigDoc
function lytConfigDoc($server, $post)
{
   // Start the document.
   lytHtmlDocStart("Create LYT", "LYT");

   // Config file already exists.
   if (lytConfigLoad())
   {
      // Display warning
      lytConfigDisplayMessageConfigAlreadyExists();
   }
   // Display or process the create.
   else
   {
      // There's a post, create the config file.
      if ($server['REQUEST_METHOD'] == 'POST')
      {
         lytConfigCreate($post);
         lytConfigDisplayResult();
      }      
      // Display the config form.
      else
      {
         lytConfigDisplayForm();
      }
   }

   // Stop the document.
   lytHtmlDocStop();
}

?>