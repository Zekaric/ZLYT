<?php
/* zFile *********************************************************************

Author: Robbert de Groot

Description:

This file are some helper functions when dealing with files on the server.  It
does handle locking of the files via zLock; so that only one html connection
can only read/write a file at any given time.  

Often the lock flag is optional and defaults to true, meaning we try and lock
before doing anything with the file.

******************************************************************************/

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
require_once "zLock.php";

////////////////////////////////////////////////////////////////////////////////
// Create a directory.
function zDirCreate($dirName)
{
   return mkdir($dirName);
}

////////////////////////////////////////////////////////////////////////////////
// Check to see if the directory exists.
function zDirIsExisting($dirName)
{
   return file_exists($dirName);
}

////////////////////////////////////////////////////////////////////////////////
// Check to see if a file exists.
function zFileIsExisting($fileName)
{
   return file_exists($fileName);
}

////////////////////////////////////////////////////////////////////////////////
// Read in a complete text file as one big string.  Does not trim new lines.
function zFileLoadText($fileName, $isLocking)
{
   $text = "";
   
   // Open the file.
   $fp = zFileConnect($fileName, "r", $isLocking);
   if (zFileOpenIsGood($fp))
   {
	   return "zFileLoad: ERROR: Unable to open file '" . $fileName . "' for reading.";
   }

   // Read in the file contents.
   while (true)
   {
      $line = fgets($fp);
      if ($line == false)
      {
         break;
      }
        
      $text .= $line;
   }
      
   // Close the file.
   zFileDisconnect($fp);
   
   // Return the file contents.
   return $text;
}

////////////////////////////////////////////////////////////////////////////////
// Read in a complete text file as an array of strings.
function zFileLoadTextArray($fileName, $isLocking)
{
   $lineArray = array();
   
   // Open the file.
   $fp = zFileOpen($fileName, "r", $isLocking);
   if (!$fp)
   {
	   return "zFileLoad: ERROR: Unable to open file '" . $fileName . "' for reading.";
   }

   // Read in the file contents.
   while (true)
   {
      $line = fgets($fp);
      if ($line == false)
      {
         break;
      }
        
      array_push($lineArray, rtrim($line, "\n"));
   }
      
   // Close the file.
   zFileClose($fileName, $fp, $lock);
   
   // Return the array of lines.
   return $lineArray;
}

////////////////////////////////////////////////////////////////////////////////
// Check to see if the file open succeeded.
function zFileOpenIsGood($fp)
{
   return ($fp["file"] != false);
}

////////////////////////////////////////////////////////////////////////////////
// Open a file.
function zFileConnect($file, $mode, $isLocking)
{
   $fp = array();

   $fp["name"]       = $file;
   $fp["isLocking"]  = $isLocking;
   $fp["lock"]       = "";
   $fp["file"]       = false;

   if ($islocking)
   {
      $fp["lock"] = zLockCreateFile($file);
      if ($fp["lock"] == "")
      {
         return $fp;
      }
   }

   $fp["file"] = fopen($file, $mode);
   
   // File open failed.
   if (!$fp["file"])
   {
      if ($isLocking)
      {
         // Clean up
         zcUnLock($fp["lock"]);
      }
      $fp["file"] = false;
   }
   
   return $fp;
}

////////////////////////////////////////////////////////////////////////////////
// Close the open file.
function zFileDisconnect($fp)
{
   if ($fp["file"] != false)
   {
      fclose($fp);

      if ($fp["isLocking"] &&
          $fp["lock"] != "")
      {
         zcUnLock($fp["lock"]);
      }
   }
}

////////////////////////////////////////////////////////////////////////////////
// Store the file contents from a single string.
function zFileStoreText($fileName, $string, $isLocking)
{
   // Open the file.
   $fp = zFileConnect($fileName, "w", $isLocking);
   if (!$fp)
   {
      return false; // "zFileStore: ERROR: Unable to open file '" . $fileName . "' for writing.";
   }

   // Write the file contents.
   fwrite($fp, $string);
   
   // Close the file.
   zFileDisconnect($fp);

   return true;
}

////////////////////////////////////////////////////////////////////////////////
// Store the file contents from a string array.
function zFileStoreTextArray($fileName, $lineArray, $isLocking)
{
   // Open the file.
   $fp = zFileConnect($fileName, "w", $isLocking);
   if (!$fp)
   {
      return false; // "zFileStore: ERROR: Unable to open file '" . $fileName . "' for writing.";
   }

   // Write all the lines in the array to the file.
   $lineCount = count($lineArray);
   for ($index = 0; $index < $lineCount; $index++)
   {
      fwrite($fp, $lineArray[$index] . "\n");
   }
      
   // Close the file.
   zFileDisconnect($fp);

   return true;
}

?>