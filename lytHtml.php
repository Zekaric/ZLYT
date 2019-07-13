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
// includes
require_once "lytCaptcha.php";

////////////////////////////////////////////////////////////////////////////////
// lytHtmlDocStart
function lytHtmlDocStart($title)
{
   print <<<PRINT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="style_reset.css" >
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>$title</title>
 </head>
 <body>
PRINT;
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlDocStop
function lytHtmlDocStop()
{
   print <<<PRINT
 </body>
</html>
PRINT;
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlInputButton
function lytHtmlInputButton($nameStr, $valueStr)
{
   return "<input class=lyt type=submit name=" . $nameStr . " value=\"" . $valueStr "\" />";
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlInputCheck
function lytHtmlInputCheck($nameStr, $valueStr)
{
   if ($valueStr)
   {
      return "<input class=lyt type=checkbox name=" . $nameStr . " checked />";
   }

   return "<input class=lyt type=checkbox name=" . $nameStr . " />";
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlInputText
function lytHtmlInputText($nameStr, $valueStr)
{
   return "<input class=lyt type=text size=80 name=" . $nameStr . " value=\"" . $valueStr . "\" />";
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlLink
function lytHtmlLink($link, linkStr)
{
   return "<a href=\"" . $link . "\">" . $linkStr . "</a>";
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlPara
function lytHtmlPara(...$paraStrList)
{
   $str = "";
   foreach ($paraStrList as $paraStr)
   {
      $str .= "<p class=lyt>\n" . $paraStr . "</p>";
   }

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlParaTitle
function lytHtmlParaTitle($str)
{
   return "<p class=lytTitle>". $str . "</p>\n\n" .
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlParaTitleSub
function lytHtmlParaTitleSub($str)
{
   return "<p class=lytSubTitle>". $str . "</p>\n\n" .
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlStrNonBreaking
// change all the spaces to "&nbsp;".
function lytHtmlStrNonBreaking($str)
{
   return str_replace(" ", "&nbsp;", $str);
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlTableRow
function lytHtmlTableRow(...$rowStrList)
{
   $str = "";
   foreach ($rowStrList as $rowStr)
   {
      $str .= "<tr>\n" . $rowStr . "</tr>\n";
   }

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlTableCol
function lytHtmlTableCol(...$colStrList)
{
   $str = "";

   foreach ($colStrList as $colStr)
   {
      $str .= "<td class=lyt>" . $colStr . "</td>\n"
   }

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// lytHtmlTable
function lytHtmlTable($content)
{
   return "<table class=lyt><tbody>\n" . $content . "</tbody></table>\n";
}

?>