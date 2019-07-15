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
// The only file that DOES NOT require lyt.config.php

////////////////////////////////////////////////////////////////////////////////
// variables
$config = false;

////////////////////////////////////////////////////////////////////////////////
// API
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Create the configuration file.
function lytCreateConfig($post)
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
   $configFileContent = "" . 
      "<?php\n" .
      "   \$config[LYT_TAG_COMPANY_NAME             ] = '" . $config[LYT_TAG_COMPANY_NAME       ] . "';\n" .
      "   \$config[LYT_TAG_FOLDER_FILE              ] = '" . $config[LYT_TAG_FOLDER_FILE        ] . "';\n" .
      "   \$config[LYT_TAG_FOLDER_IMAGE             ] = '" . $config[LYT_TAG_FOLDER_IMAGE       ] . "';\n" .
      "   \$config[LYT_TAG_LOGIN_KEY                ] = '" . $config[LYT_TAG_LOGIN_KEY          ] . "';\n" .
      "   \$config[LYT_TAG_OWNER_ALIAS              ] = '" . $config[LYT_TAG_OWNER_ALIAS        ] . "';\n" .
      "   \$config[LYT_TAG_OWNER_NAME               ] = '" . $config[LYT_TAG_OWNER_NAME         ] . "';\n" .
      "   \$config[LYT_TAG_OWNER_PASSWORD           ] = '" . $config[LYT_TAG_OWNER_PASSWORD     ] . "';\n" .
      "   \$config[LYT_TAG_SITE_ADDRESS_PUBLIC      ] = '" . $config[LYT_TAG_SITE_ADDRESS_PUBLIC] . "';\n" .
      "   \$config[LYT_TAG_SITE_ADDRESS_SECURE      ] = '" . $config[LYT_TAG_SITE_ADDRESS_SECURE] . "';\n" .
      "   \$config[LYT_TAG_SITE_NAME                ] = '" . $config[LYT_TAG_SITE_NAME          ] . "';\n" .
      "?>\n";
   
   zFileStoreText(LYT_CONFIG_FILE_NAME, $configFileContent);
   
   // Make the folders
   mkdir($config[LYT_TAG_FOLDER_FILE]);
   mkdir($config[LYT_TAG_FOLDER_IMAGE]);
}

////////////////////////////////////////////////////////////////////////////////
// Page
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Display a form for creating the LYT site.
function lytCreatePageForm()
{
   return "" .
      zHtmlDoc(
         zHtmlHead(
            zHtmlHeadLinkCSS("style_reset.css"),
            zHtmlHeadLinkCSS("lyt.css"),
            zHtmlHeadTitle("Create LYT Web Site")),
            
         zHtmlBody("",
            zHtmlParaHeader("", "1", 
               "Create a LYT Web Site"),
               
            zHtmlPara("", 
               "LYT: L)og Y)our T)houghts",
      
               "LYT is a simple content managment system that is aimed at hobbiests and small businesses " .
               "than it is for large companies.  Its goal is to be simple. ",
      
               "All the following fields can be later changed by editing the \"" . LYT_CONFIG_FILE_NAME .
               "\" file that will be in the same folder as this _create_.html file. ",
               
               "If you try to \"Configure\" when there already exists a \"" . LYT_CONFIG_FILE_NAME . "\", " .
               "all following attempts after the first attempt will fail.  You will need to rename or " .
               "delete the \"" . LYT_CONFIG_FILE_NAME . "\" file first if you want to use this page again. "),
               
            zHtmlParaHeader("", "2", 
               "Configuration"),
               
            zHtmlPara("",
               zHtmlForm(false, "_create_.php", 
                  zHtmlTable("", 
                     zHtmlTableRow("", 
                        zHtmlTableCol("",
                           zHtmlStrNonBreaking("WebSite Name:"),
                           zHtmlFormInputText("", LYT_TAG_SITE_NAME, ""),
                           "WebSite name is the name you give your website."),
                           
                        zHtmlTableCol("",
                           zHtmlStrNonBreaking("WebSite Address Public:"),
                           zHtmlFormInputText("", LYT_TAG_SITE_ADDRESS_PUBLIC, "http://"),
                           "The address of the website that is insecure.  Include \"http://\""),
                           
                        zHtmlTableCol("",
                           zHtmlStrNonBreaking("WebSite Address Secure:"),
                           zHtmlFormInputText("", LYT_TAG_SITE_ADDRESS_SECURE, "https://"),
                           "The address of the website that is secure.  Include \"https://\""),
                           
                        zHtmlTableCol("",
                           "Company:",
                           zHtmlFormInputText("", LYT_TAG_COMPANY_NAME, ""),
                           "Your company name if applicable."),
                           
                        zHtmlTableCol("",
                           "Name:",
                           zHtmlFormInputText("", LYT_TAG_OWNER_NAME, ""),
                           "Your name or website maintainer's name."),
                           
                        zHtmlTableCol("",
                           "Alias:",
                           zHtmlFormInputText("", LYT_TAG_OWNER_ALIAS, ""),
                           "Your alias or website maintainer's alias."),
                           
                        zHtmlTableCol("",
                           "",
                           zHtmlFormInputButtonSubmit("", "", "Configure"),
                           "")))))));
}

////////////////////////////////////////////////////////////////////////////////
// Display the results of the creation.
function lytCreatePageResult()
{
   return "" .
      zHtmlDoc(
         zHtmlHead(
            zHtmlHeadLinkCSS("style_reset.css"),
            zHtmlHeadLinkCSS("lyt.css"),
            zHtmlHeadTitle("Creating LYT Web Site")),
            
         zHtmlBody("",
            zHtmlParaTitle("", "1", 
               "Create a LYT Web Site"),
            zHtmlPara("",
               LYT_CONFIG_FILE_NAME . " file created.",
               
               zHtmlLink(lytLoginGetAddress(), "Login"))));
}

////////////////////////////////////////////////////////////////////////////////
// Inform that there already exists a configuration.
function lytCreatePageMessageConfigAlreadyExists()
{
   return "" .
      zHtmlDoc(
         zHtmlHead(
            zHtmlHeadLinkCSS("style_reset.css"),
            zHtmlHeadLinkCSS("lyt.css"),
            zHtmlHeadTitle("LYT Web Site Already Exists")),
            
         zHtmlBody("",
            zHtmlParaHeader("", "1", 
               "Create a LYT Web Site"),
            zHtmlPara("",
               LYT_CONFIG_FILE_NAME . " file already exists.  Please delete or rename the file before trying to create again. ",
         
               zHtmlLink(lytLoginGetAddress(), "Login"))));
}

////////////////////////////////////////////////////////////////////////////////
// Page
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Display the config page.
$page = "";

// Config file already exists.
if      (zFileIsExisting(LYT_CONFIG_FILE_NAME))
{
   // Display warning
   // Create the page content.
   $page = lytCreatePageMessageConfigAlreadyExists();
}
// There's a post, create the config file.
else if ($server['REQUEST_METHOD'] == 'POST')
{
   lytCreateConfig($post);

   // Create the page content.
   $page = lytCreatePageResult();
}      
// Display the config form.
else
{
   $page = lytCreatePageForm();
}
   
// Send the page to the client.
return $page;

?>