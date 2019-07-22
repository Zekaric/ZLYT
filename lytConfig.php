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

////////////////////////////////////////////////////////////////////////////////
// API
////////////////////////////////////////////////////////////////////////////////

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
// lytConfigStore
function lytConfigStore()
{
   global $lytConfig;
   
   $lytConfigFileContent = "" . 
      "<?php\n" .
      "\$lytConfig = array();\n\n".
      "\$lytConfig[LYT_TAG_IS_CONFIGURED  ] = true;\n\n".
      "\$lytConfig[LYT_TAG_FOLDER_FILE    ] = '" . $lytConfig[LYT_TAG_FOLDER_FILE      ] . "';\n" .
      "\$lytConfig[LYT_TAG_FOLDER_IMAGE   ] = '" . $lytConfig[LYT_TAG_FOLDER_IMAGE     ] . "';\n\n" .
      "\$lytConfig[LYT_TAG_ADMIN_COMPANY  ] = '" . $lytConfig[LYT_TAG_ADMIN_COMPANY    ] . "';\n" .
      "\$lytConfig[LYT_TAG_ADMIN_LOGIN    ] = '" . $lytConfig[LYT_TAG_ADMIN_LOGIN      ] . "';\n" .
      "\$lytConfig[LYT_TAG_ADMIN_NAME     ] = '" . $lytConfig[LYT_TAG_ADMIN_NAME       ] . "';\n" .
      "\$lytConfig[LYT_TAG_ADMIN_PASSWORD ] = '" . $lytConfig[LYT_TAG_ADMIN_PASSWORD   ] . "';\n" .
      "\$lytConfig[LYT_TAG_SITE_NAME      ] = '" . $lytConfig[LYT_TAG_SITE_NAME        ] . "';\n" .
      "\$lytConfig[LYT_TAG_SITE_URL       ] = '" . $lytConfig[LYT_TAG_SITE_URL         ] . "';\n" .
      "\$lytConfig[LYT_TAG_SITE_URL_SECURE] = '" . $lytConfig[LYT_TAG_SITE_URL_SECURE  ] . "';\n\n" .
      "?>\n";
   
   zFileStoreText(LYT_CONFIG_FILE_NAME, $lytConfigFileContent);
}

?>