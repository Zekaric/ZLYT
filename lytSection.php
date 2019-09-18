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

////////////////////////////////////////////////////////////////////////////////
// global
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Create a new section.
function lytSectionCreate($name, $orderedKey)
{
    global $lytSectionList;

    $section = array("Name" => $name, "Key" => $orderedKey);

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
    _SectionStore();
}

////////////////////////////////////////////////////////////////////////////////
// Get a section name
function lytSectionGet($index)
{
    global $lytSectionList;

    $result = array();

    $result["Name"] = $lytSectionList[$index]["Name"];
    $result["Link"] =
}

////////////////////////////////////////////////////////////////////////////////
// local
// function
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Save the new section list.
function _SectionStore()
{
    $str =
        "<?php\n" .
        "\$lytSectionList = array();\n\n";

    for ($index = 0; $index < count($lytSectionList); $index++)
    {
        $str .= "\$lytSectionList[" . $index ."] = array(\"Name\" => \"" . $lytSectionList["Name"] . ", \"Key\" => " . $lytSectionList["Key"] . ");\n";
    }

    zFileStoreText(LYT_FILE_NAME_SECTION, $str, true);
}
