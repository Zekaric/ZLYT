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
// global
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Check to see if the LYT system is configured yet.
function lytIsConfigured()
{
   global $lytConfig;
   
   return $lytConfig[TAG_LYT_IS_CONFIGURED];
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
   $lytConfig[TAG_LYT_FOLDER_IMAGE] = $imageFolderNew;
   
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
      "\$lytConfig = array();\n\n" .
      "\$lytConfig[TAG_LYT_IS_CONFIGURED  ] = true;\n\n" .
      "\$lytConfig[TAG_LYT_FOLDER_FILE    ] = '" . $lytConfig[TAG_LYT_FOLDER_FILE   ] . "';\n" .
      "\$lytConfig[TAG_LYT_FOLDER_IMAGE   ] = '" . $lytConfig[TAG_LYT_FOLDER_IMAGE  ] . "';\n\n" .
      "\$lytConfig[TAG_LYT_ADMIN_COMPANY  ] = '" . $lytConfig[TAG_LYT_ADMIN_COMPANY ] . "';\n" .
      "\$lytConfig[TAG_LYT_ADMIN_LOGIN    ] = '" . $lytConfig[TAG_LYT_ADMIN_LOGIN   ] . "';\n" .
      "\$lytConfig[TAG_LYT_ADMIN_NAME     ] = '" . $lytConfig[TAG_LYT_ADMIN_NAME    ] . "';\n" .
      "\$lytConfig[TAG_LYT_ADMIN_PASSWORD ] = '" . $lytConfig[TAG_LYT_ADMIN_PASSWORD] . "';\n" .
      "\$lytConfig[TAG_LYT_SITE_TITLE     ] = '" . $lytConfig[TAG_LYT_SITE_TITLE    ] . "';\n" .
      "\$lytConfig[TAG_LYT_SITE_URL       ] = '" . $lytConfig[TAG_LYT_SITE_URL      ] . "';\n" .
      "\$lytConfig[TAG_LYT_SITE_URL_SAFE  ] = '" . $lytConfig[TAG_LYT_SITE_URL_SAFE ] . "';\n";

   zFileStoreText(LYT_CONFIG_FILE_NAME, $lytConfigFileContent, true);
}
