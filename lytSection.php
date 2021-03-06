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
require_once "lyt_Section.php";

$lytSectionPostList = array();

////////////////////////////////////////////////////////////////////////////////
// global
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Process the change in section.
function lytSectionChangeProcess()
{
   global $lytSectionList;
   
   for ($index = 0; $index < count($lytSectionList); $index++)
   {
      if (lytGetValue("section") == lytSectionGetDir($index))
      {
         lytLoginSetSection($index);
      }
   }
}

////////////////////////////////////////////////////////////////////////////////
// Display the section create
function lytSectionCreatePage()
{
   // Not using the secure address.
   if (!lytConnectionIsSecure())
   {
      return "";
   }

   $page = "";
   
   // Post verify password.
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      lytSectionCreateProcess();
   }      
   // Display lytLogin form.
   else
   {
      $page = _SectionCreatePageLoad();
   }

   return $page;
}

////////////////////////////////////////////////////////////////////////////////
// Create a new section.
function lytSectionCreateProcess()
{
   global $lytSectionList;
   
   $name       = lytGetValue("sectionName");
   $orderedKey = lytGetValue("sectionKey");
   $dirName    = lytGetValue("sectionDir");

   $section = array("Name" => $name, "Key" => $orderedKey, "Dir" => $dirName);
   
   // Find the proper location inside the list.
   for ($index = 0; $index < count($lytSectionList); $index++)
   {
      // new section has a larger key.
      if ($lytSectionList["Key"] < $section["Key"])
      {
         continue;
      }
      
      // New section has the exact same key but larger name.
      if ($lytSectionList["Key"]  == $section["Key"] &&
      $lytSectionList["Name"] <= $section["Name"])
      {
         continue;
      }
      
      // Found our location in the list
      break;
   }
   
   // Insert into the list.
   if ($index != count($lytSectionList))
   {
      array_splice($lytSectionList, $index, 0, $section);
   }
   // Append onto the list
   else
   {
      array_push($lytSectionList, $section);
   }
   
   // Store the new list
   _SectionStoreAppend();
   lytLoginSetSection($index);
   
   // Create an empty post file.
   $postList = "" .
      "<?php\n".
      "\$lytSectionPostList = array();\n";

   zFileStoreText(_GetPostFile($dirName), $postList, false);
}

////////////////////////////////////////////////////////////////////////////////
// Display the section Post create
function lytSectionPostCreatePage()
{
   // Not using the secure address.
   if (!lytConnectionIsSecure())
   {
      return "";
   }

   $page = "";
   
   // Post verify password.
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      lytSectionPostCreateProcess();
   }      
   // Display lytLogin form.
   else
   {
      $page = _SectionPostCreatePageLoad();
   }

   return $page;
}

////////////////////////////////////////////////////////////////////////////////
// Get the number of sections.
function lytSectionGetCount()
{
   global $lytSectionList;
   
   return count($lytSectionList);
}

////////////////////////////////////////////////////////////////////////////////
// Get a section name
function lytSectionGetDir($index)
{
   global $lytSectionList;
   
   if ($index < 0 || count($lytSectionList) <= $index)
   {
      return "";
   }

   return $lytSectionList[$index]["Dir"];
}

////////////////////////////////////////////////////////////////////////////////
// Get a section name
function lytSectionGetName($index)
{
   global $lytSectionList;
   
   if ($index < 0 || count($lytSectionList) <= $index)
   {
      return "";
   }

   return $lytSectionList[$index]["Name"];
}

////////////////////////////////////////////////////////////////////////////////
// Get the total post count
function lytSectionPostGetCount()
{
   global $lytSectionPostList;
   
   return count($lytSectionPostList);
}

////////////////////////////////////////////////////////////////////////////////
// Get the post information
function lytSectionPostGetBody($index)
{
   global $lytSectionPostList;
   
   if ($index < 0 || count($lytSectionPostList) <= $index)
   {
      return "";
   }
   
   return _ToSafeString($lytSectionPostList[$index]["Body"], false, true);
}

////////////////////////////////////////////////////////////////////////////////
// Get the post information
function lytSectionPostGetDate($index)
{
   global $lytSectionPostList;
   
   if ($index < 0 || count($lytSectionPostList) <= $index)
   {
      return "";
   }
   
   return $lytSectionPostList[$index]["Date"];
}

////////////////////////////////////////////////////////////////////////////////
// Get the post title.  
function lytSectionPostGetTitle($index)
{
   global $lytSectionList;
   global $lytSectionPostList;
   
   if ($index < 0 || count($lytSectionPostList) <= $index)
   {
      return "";
   }
   
   return _ToSafeString($lytSectionPostList[$index]["Title"], true, true);
}

////////////////////////////////////////////////////////////////////////////////
// Start up the post code. This function needs to be called first.
function lytSectionPostStart()
{
   $sectionIndex = lytLoginGetSection();

   return _LoadPostFile($sectionIndex);
}

////////////////////////////////////////////////////////////////////////////////
// Process the addition of the new post.
function lytSectionPostCreateProcess()
{
   global $lytSectionList;
   
   lytSectionPostStart();
   
   global $lytSectionPostList;
   
   $title = lytGetValue("sectionPostTitle");
   $body  = lytGetValue("sectionPostBody");
   date_default_timezone_set("America/Vancouver");
   $date  = date("Y-m-d H:i T");

   $index = count($lytSectionPostList);

   $lytSectionPostList[$index] = array(
      "Title" => $title,
      "Date"  => $date,
      "Body"  => $body);
      
   _SectionPostStoreAppend();
}

////////////////////////////////////////////////////////////////////////////////
// local
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Get the post file name.
function _GetPostFile($sectionIndex)
{
   return LYT_FILE_NAME_SECTION_POST_PRE . lytSectionGetDir($sectionIndex) . ".php";
}

////////////////////////////////////////////////////////////////////////////////
// Load in the post file.
function _LoadPostFile($sectionIndex)
{
   global $lytSectionList;
   global $lytSectionPostList;

   if ($sectionIndex >= count($lytSectionList))
   {
      return false;
   }

   // Get the section post file.
   $sectionPostFile = _GetPostFile($sectionIndex);

   // Load/parse the file.
   require_once $sectionPostFile;
}

////////////////////////////////////////////////////////////////////////////////
// Compose the section create page.
function _SectionCreatePageLoad()
{
   $page = lytPageLoad();
   
   $page = lytPageReplaceColumnMain($page, lytPageGetSectionCreateForm());
   $page = lytPageReplaceCommon(    $page);

   return $page;
}

////////////////////////////////////////////////////////////////////////////////
// Compose the section post create page.
function _SectionPostCreatePageLoad()
{
   $page = lytPageLoad();
   
   $page = lytPageReplaceColumnMain($page, lytPageGetSectionPostCreateForm());
   $page = lytPageReplaceCommon(    $page);

   return $page;
}

////////////////////////////////////////////////////////////////////////////////
// Save the new section list.
//function _SectionStore()
//{
//   global $lytSectionList;
//
//   $str =
//      "<?php\n" .
//      "\$lytSectionList = array();\n\n";
//   
//   for ($index = 0; $index < count($lytSectionList); $index++)
//   {
//      $str .= "\$lytSectionList[" . $index ."] = array(" . 
//         "\"Name\" => \"" . $lytSectionList[$index]["Name"] . "\", " .
//         "\"Key\"  => \"" . $lytSectionList[$index]["Key"]  . "\", " .
//         "\"Dir\"  => \"" . $lytSectionList[$index]["Dir"]  . "\");\n";
//   }
//   
//   zFileStoreText(LYT_FILE_NAME_SECTION, $str, true);
//}

////////////////////////////////////////////////////////////////////////////////
// Add a new section to the section list.
function _SectionStoreAppend()
{
   global $lytSectionList;

   $index = count($lytSectionList) - 1;

   $str = "\$lytSectionList[" . $index ."] = array(" . 
      "\"Name\" => \"" . $lytSectionList[$index]["Name"] . "\", " .
      "\"Key\"  => \"" . $lytSectionList[$index]["Key"]  . "\", " .
      "\"Dir\"  => \"" . $lytSectionList[$index]["Dir"]  . "\");\n";
   
   zFileAppendText(LYT_FILE_NAME_SECTION, $str, true);

   // Create the section post file
   $str = "<?php\n";

   zFileStoreText(_GetPostFile($index), $str, true);
}

////////////////////////////////////////////////////////////////////////////////
// Save the new post
function _SectionPostStoreAppend()
{
   global $lytSectionList;
   global $lytSectionPostList;

   $sectionIndex = lytLoginGetSection();
   $index        = count($lytSectionPostList) - 1;

   $str = "\$lytSectionPostList[" . $index . "] = array(" .
      "\"Title\" => \"" . $lytSectionPostList[$index]["Title"] . "\", " .
      "\"Date\"  => \"" . $lytSectionPostList[$index]["Date"]  . "\", " .
      "\"Body\"  => <<<ZLYTEND\n" .
      $lytSectionPostList[$index]["Body"]  . "\nZLYTEND\n);\n";
   
   zFileAppendText(_GetPostFile($sectionIndex), $str, true);
}

////////////////////////////////////////////////////////////////////////////////
// Convert a generic string to a PHP and Html Safe string.
// Convert the paragraphing to html
function _ToSafeString($string, $isTitle, $isPost)
{
   $result = str_replace("\"",       "&quot;",    $string);
   $result = str_replace("\'",       "&apos;",    $result);
   $result = str_replace("\$",       "&#36;",     $result);
   $result = str_replace("\\",       "&#92;",     $result);
   $result = str_replace("<",        "&lt;",      $result);
   $result = str_replace(">",        "&gt;",      $result);
   $result = str_replace("[-]",      "&nbsp;",    $result);
   $result = str_replace("[c]",      "&copy;",    $result);
   $result = str_replace("[r]",      "&reg;",     $result);
   $result = str_replace("[yen]",    "&yen;",     $result);
   $result = str_replace("[dollar]", "&#36;",     $result);
   $result = str_replace("[cent]",   "&cent;",    $result);
   $result = str_replace("[pound]",  "&pound;",   $result);
   $result = str_replace("[b-]",     "<strong>",  $result);
   $result = str_replace("[-b]",     "</strong>", $result);
   $result = str_replace("[i-]",     "<em>",      $result);
   $result = str_replace("[-i]",     "</em>",     $result);
   $result = str_replace("[^-]",     "<sup>",     $result);
   $result = str_replace("[-^]",     "</sup>",    $result);
   $result = str_replace("[v-]",     "<sub>",     $result);
   $result = str_replace("[-v]",     "</sub>",    $result);
   $result = str_replace("[c-]",     "<center>",  $result);
   $result = str_replace("[-c]",     "</center>", $result);
   $result = str_replace("[ret]",    "<br />\n",  $result);
   $result = str_replace("\r",       "",          $result);
   if ($isTitle)
   {
      $result = str_replace(" ", "&nbsp;", $result);
   }

   $result = str_replace("[img-]",   "<img src=\"lytImage/", $result);
   $result = str_replace("[-img]",   "\"/>",                 $result);

   // Comments can't embed links
   if ($isPost)
   {
      $result = str_replace("[link-]",  "<a href=\"", $result);
      $result = str_replace("[-link-]", "\">",        $result);
      $result = str_replace("[-link]",  "</a>",       $result);
   }
   else
   {
      // Convert back slashes if part of a comment.
	   $result = str_replace("/", "&#47;", $result);
   }

   // Get rid of leading and trailing whitespace;
   $result = trim($result);

   if ($isTitle)
   {
      // Paragraph stuff below is not applicable for titles.
      return $result;
   }
      
   $level     = 0;
   $isInPara  = false;
   $isNewPara = false;
   $scount    = strlen($result);
   $newResult = "";

   for ($index = 0; $index < $scount; $index++)
   {
      // Get the next letter.
      $letter = substr($result, $index, 1);

      // if the next letter is a start of a paragraph style...
      if ($letter == "[")
      {
         $tokenSmall = substr($result, $index, 4);
         $tokenLarge = substr($result, $index, 7);

         // Terminate the paragraph.
         if ($isInPara)
         {
            $newResult .= "</p>";
            $isInPara   = false;
         }

         // starting a bullet list...
         if      ($tokenSmall == "[*-]")
         {
            $newResult .= "<ul class=\"lytStylePostBody\">";
            $index     += 3;
            $level++;
         }
         // ending a bullet list...
         else if ($tokenSmall == "[-*]")
         {
            $newResult .= "</ul>";
            $index     += 3;
            $level--;
         }
         // starting a numbered list.
         else if ($tokenSmall == "[#-]")
         {
            $newResult .= "<ol class=\"lytStylePostBody\">";
            $index     += 3;
            $level++;
         }
         // ending a numbered list.
         else if ($tokenSmall == "[-#]")
         {
            $newResult .= "</ol>";
            $index     += 3;
            $level--;
         }
         // starting a point in a bullet or numbered list.
         else if ($tokenSmall == "[.-]")
         {
            $newResult .= "<li class=\"lytStylePostBody\">";
            $index     += 3;
            $level++;
         }
         // ending a point in a bullet or numbered list.
         else if ($tokenSmall == "[-.]")
         {
            $newResult .= "</li>";
            $index     += 3;
            $level--;
         }
         // starting a code section.
         else if ($tokenLarge == "[code-]")
         {
            $newResult .= "<p class=\"lytStylePostBodyCode\">";
            $index     += 6;
            $level++;
         }
         // ending a code section.
         else if ($tokenLarge == "[-code]")
         {
            $newResult .= "</p>";
            $index     += 6;
            $level--;
         }
         // Unknown markup.
         else
         {
            // Just dump the string verbatum.
            $newResult .= $letter;
         }

         if ($level == 0)
         {
            $isInPara  = false;
            $isNewPara = true;

            // Skip whitespace
            $index++;
            for (; $index < $scount; $index++)
            {
               $letter = substr($result, $index, 1);
               if ($letter == " "  ||
                   $letter == "\t" ||
                   $letter == "\n")
               {
                  continue;
               }
               break;
            }
            $index--;
         }
      }
      // If the next letter is a new line...
      else if ($letter == "\n" &&
               $level  == 0)
      {
         // Check for multiple new lines...
         $newLineCount = 1;
         for ($newLineIndex = $index + 1; $newLineIndex < $scount; $newLineIndex++)
         {
            $nextLetter = substr($result, $newLineIndex, 1);

            // Ignore whitespace.
            if      ($nextLetter == " " ||
                     $nextLetter == "\t")
            {
               continue;
            }
            // Next letter in another new line.
            else if ($nextLetter == "\n")
            {
               $newLineCount++;
            }
            // Next letter is something completely different.
            else
            {
               break;
            }
         }

         // If new line count is more than 1 then we start a new paragraph.
         if ($newLineCount > 1)
         {
            $isNewPara = true;
            $index     = $newLineIndex - 1;
         }
         // Not a series.  Just dump the new line.
         else
         {
            $newResult .= $letter;
         }
      }
      // if the next letter is not a start of a paragraph style...
      else
      {
         // First letter was not a special paragraph.
         if ($index == 0)
         {
            $newResult .= "<p class=\"lytStylePostBody\">\n";
            $isInPara   = true;
         }

         if ($isNewPara && 
             $level == 0)
         {
            if ($isInPara)
            {
               $newResult .= "</p>";
            }
            $newResult .= "<p class=\"lytStylePostBody\">\n";
            $isInPara   = true;
            $isNewPara  = false;
         }

         $newResult .= $letter;
      }
   }

   if ($isInPara)
   {
      $newResult .= "\n</p>";
   }

   return $newResult;
}
