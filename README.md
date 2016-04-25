# pptx_converter
Converts a single PPTX file into multiple *.html files for reveal.js

#Configure constants
###Define the following constants according to your own configuration:
```
define('PATH_TO_XML', __DIR__);
define('TARGET_HTML_DIR', __DIR__ . '/../reveal.js');
define('IMAGE_URL', '/img');
define('SECTION_BACKGROUND' , 'background.png');
define('OPENING_BACKGROUND', 'title.png');
define('SECTION_PREFIX', 'section_');
define('SLIDE_TEMPLATE', __DIR__ . '/slide_template.html');
define('SLIDE_CONTAINER_CLASS', 'slide_container');
define('SLIDE_TEXT_CLASS', 'slide_text');
define('SLIDE_IMAGE_CLASS', 'slide_image');
```
###Go through the PPTX slide deck and determine section titles + starting slide for that section
###Define $sections
```
$sections = [
	 0 => ['title' => 'Intro', 'end' => 10],
	 1 => ['title' => 'Setup and Configuration', 'end' => 47],
	 2 => ['title' => 'Question and Answer', 'end' => 99],
];
```
#Define the slide template
###Have a look at the sample file "slide_template.html"
###Assign this to SLIDE_TEMPLATE
###Make sure you have your CSS files in place

#Unzip PPTX files
###Use your favorite unzip utility to open up the PPTX file
###Copy the entire directory structure for "/ppt/slides/*" into PATH_TO_XML
###Be sure to also copy "/ppt/slides/_rels/* into PATH_TO_XML
###Copy "/media/*" into the folder which IMAGE_URL refers to

#Perform the conversion
###Run this command:
```
php convert.php
```
