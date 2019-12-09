<?php
/* lyt.php ***********7********************************************************

L.Y.T. : Log Your Thoughts

Author: Robbert de Groot

Description:

******************************************************************************/

/* MIT License ****************************************************************
Copyright (c) 2019 Robbert de Groot

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
require_once "zDebug.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";

require_once "lytAdmin.php";
require_once "lytConfig.php";
require_once "lytLogin.php";
require_once "lytSection.php";
require_once "lytUtil.php";

///////////////////////////////////////////////////////////////////////////////
// The main page for the program.

// This will only start a web session when things are secure.
lytLoginStart();

// What are we wanting to do.
$isFormPage = false;
$op         = lytGetValue("op");

// Admin page
if      (!lytIsConfigured() ||
         (lytLoginIsUserAdmin() &&
          $op == "admin"))
{
   print lytAdminPage();
   exit(0);
}
// Logout.
else if ($op == "logout")
{
   lytLoginStop();
   lytLoginStart();
}
// Login page
else if ($op == "login")
{
   $isFormPage = true;
   $page       = lytLoginPage();
}
// Change the current section.
else if ($op == "sectionChange")
{
   // Set the current section.
   lytSectionChangeProcess();
}
// Create a new section.
else if ($op == "sectionCreate")
{
   $isFormPage = true;
   $page       = lytSectionCreatePage();
}
// Add a new post to the section
else if ($op == "sectionPostCreate")
{
   $isFormPage = true;
   $page       = lytSectionPostCreatePage();
}

// If this is a form page then we have different behaviour.
if ($isFormPage)
{
   if ($page != "")
   {
      print $page;
      exit(0);
   }
}

// Default screen.
print lytPageDefault();

exit(0);
