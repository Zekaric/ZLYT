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

////////////////////////////////////////////////////////////////////////////////
// includes
require_once "zDebug.php";

require_once "lyt_Constant.php";
require_once "lyt_Config.php";

// If the site isn't configured yet then set it up.
if (!lytIsConfigured())
{
   require_once "lytAdmin.php";
   
   print lytAdminPage();
   exit(0);
}

// What are we wanting to do.
$op = lytGetValue("op");

if ($op = "login")
{
   require_once "lytLogin.php";
   
   print lytLoginPage();
   exit(0);
}

// Default screen.

//print lytTopicPage();
zDebugPrint("TODO: Topic display.");
exit(0);

?>
