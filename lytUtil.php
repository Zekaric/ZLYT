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

///////////////////////////////////////////////////////////////////////////////
// includes
require_once "lyt_Constant.php";
require_once "lyt_Config.php";

////////////////////////////////////////////////////////////////////////////////
// global
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// lytConnectionIsSecure
function lytConnectionIsSecure()
{
   if (isset($_SERVER['HTTPS']) &&
       $_SERVER['HTTPS'] === "on")
   {
      return true;
   }
   
   return false;
}

////////////////////////////////////////////////////////////////////////////////
// Get the site url based on whether it should be the safe url or not.
function lytGetSiteUrl()
{
   global $lytConfig;
   
   if (lytConnectionIsSecure())
   {
      return $lytConfig[TAG_LYT_SITE_URL_SAFE];
   }
   
   return $lytConfig[TAG_LYT_SITE_URL];
}

////////////////////////////////////////////////////////////////////////////////
// Get the page url for the site.  Will usually be always the same.
function lytGetSiteUrlPage()
{
   return lytGetSiteUrl() . "/lyt.php";
}

///////////////////////////////////////////////////////////////////////////////
// Get a URL value.
function lytGetValue($key)
{
   // Check the _GET (in URL) if there is a value...
   // If not, check the _POST if there is value...
   // If not then "" 
   if      (isset($_GET[$key]))
   {
      return $_GET[$key];
   }
   else if (isset($_POST[$key]))
   {
      return $_POST[$key];
   }

   return "";
}
