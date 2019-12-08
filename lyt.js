/* lyt.js ************7*********************************************************

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
// Variable

// The number of the most recent posts to display.
var _postDisplayCount = 10;

var _contentTemplate =
   "<table class=content>\n" +
   "[content]" +
   "</table>\n"

var _postTemplate = 
   " <tr>\n"
   "  <td class=contentTitle colspan=2><a href='lytColLink'>lytColTitle</a></td>\n" +
   " </tr><tr>\n" +
   "  <td class=contentAuth>lytColAuthor</td><td class=contentDate>lytColDate</td>" +
   " </tr><tr>\n" +
   "  <td class=contentText colspan=2>lytColBody</td>\n" +
   " </tr>\n";

var _commTemplate = "";

///////////////////////////////////////////////////////////////////////////////
// Display the content of the page.
function DisplayContent()
{
   if (_isDisplayingPost)
   {
      DisplayContentPost();
   }
   else
   {
      DisplayContentPostList();
   }
}

function DisplayContentL()
{
   document.write("");
}

function DisplayContentR()
{
   document.write("");
}

///////////////////////////////////////////////////////////////////////////////
// Display the content of the page.  A post and comments.
function DisplayContentPost()
{   
}

///////////////////////////////////////////////////////////////////////////////
// Display the content of the page.  Last postCount posts.
function DisplayContentPostList()
{
   var index,
   var postIndex;
   var stemp,
       link,
       constent,
       contentPost;
   
   content     = _contentTemplate;
   contentPost = "";
   
   // Get the the start of the posts.
   postIndex = _postCount - _postDisplayCount;
   if (postIndex < 0)
   {
      postIndex = 0;
   }
   
   // For all posts up to postCount...
   for (index = 0; index < _postDisplayCount; index++)
   {
      // No more posts.
      if (postIndex + index >= _postCount)
      {
         break;
      }
      
      link = _urlSafe + "/index.php?topic=" + _topic + "&post=" + _postId[postIndex + index];
      
      // Prepare the post.
      stemp = _postTemplate;
      
      stemp = stemp.replace(/lytColLink/g,   link);
      stemp = stemp.replace(/lytColTitle/g,  _postTitle[postIndex + index]);
      stemp = stemp.replace(/lytColAuthor/g, _postAuth[ postIndex + index]);
      stemp = stemp.replace(/lytColDate/g,   _postDate[ postIndex + index]);
      stemp = stemp.replace(/lytColBody/g,   HtmlFromBody(_postBody[ postIndex + index]));
      
      contentPost += stemp;
   }
   
   content = content.replace(/[content]/g, contentPost);
   
   documnet.write(content);
}

///////////////////////////////////////////////////////////////////////////////
// General functions
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Convert the string from encoded string to HTML.
function HtmlFromBody(string)
{
   string = string.replace(/h1-/g,     "<h1 class=post>");
   string = string.replace(/-h1/g,     "</h1>");
   string = string.replace(/h2-/g,     "<h2 class=post>");
   string = string.replace(/-h2/g,     "</h2>");
   string = string.replace(/h3-/g,     "<h3 class=post>");
   string = string.replace(/-h3/g,     "</h3>");
   string = string.replace(/h4-/g,     "<h4 class=post>");
   string = string.replace(/-h4/g,     "</h4>");
   string = string.replace(/p-/g,      "<p  class=post>");
   string = string.replace(/-p/g,      "</p>");
   string = string.replace(/-p-/g,     "</p><p  class=post>");
   string = string.replace(/\.-/g,     "<ul class=post>");
   string = string.replace(/-\./g,     "</ul>");
   string = string.replace(/1-/g,      "<ol class=post>");
   string = string.replace(/-1/g,      "</ol>");
   string = string.replace(/\*-/g,     "<li class=post>");
   string = string.replace(/-\*/g,     "</li>");
   string = string.replace(/\|-/g,     "<pre class=post>");
   string = string.replace(/-\|/g,     "</pre>");
   string = string.replace(/x-/g,      "<center>");
   string = string.replace(/-x/g,      "</center>");
   
   string = string.replace(/\img-/g,   "<img src='" + _imgDir");
   string = string.replace(/\-img/g,   "'/>");

   string = string.replace(/\_/g,      "&nbsp;");
   string = string.replace(/\r/g,      "<br />");
      
   string = string.replace(/\\/g,      "\");

   return string;
}

///////////////////////////////////////////////////////////////////////////////
// Ensure all spaces are not breaking.
function NoBreakingSpace(string)
{
   var index;

   for (index = 0; index < 5; index++)
   {
      string = string.replace(" ", "&nbsp;");
   }

   return string;
}

