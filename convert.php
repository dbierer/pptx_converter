<?php
// XML to UL/LI
// need to unzip PPTX file
// place slides\*.xml in directory indicated by PATH_TO_XML
// place slides\_rel folder off PATH_TO_XML

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

// need to define sections
$sections = [
	 0 => ['title' => 'Intro', 'end' => 10],
	 1 => ['title' => 'Setup and Configuration', 'end' => 47],
	 2 => ['title' => 'Question and Answer', 'end' => 99],
];

$path = PATH_TO_XML;
$template = file_get_contents(SLIDE_TEMPLATE);
$list = glob($path . '/*.xml');
$rels = glob($path . '/_rels/*.rels');
natsort($list);
natsort($rels);
$html = '';
$key = 0;
$end = 0;
$title = '';
$write = FALSE;

foreach ($list as $pos => $fn) {

	preg_match('!slide(\d+?)\.xml!', $fn, $matches);
	$slide = (int) $matches[1] ?? 0;
	if ($slide > $end) {
		if ($html) {
			$title = $sections[$key-1]['title'];
			$image = ($key) ? SECTION_BACKGROUND : OPENING_BACKGROUND;
			$sectionFile  = sprintf('%s/%s%02d.html', TARGET_HTML_DIR, SECTION_PREFIX, $key - 1);
			echo $sectionFile . PHP_EOL;
			$html = str_replace(['<ul></ul>','<',   '<ul>',  '</ul>',  '<li>',    "\n</li>", '</section>',    '<'], 
								['',          "\n<","\t<ul>","\t</ul>","\t\t<li>",'</li>',   "</section>\n", "\t\t\t\t<"], 
								 $html);
			$htmlToWrite = str_replace(['%BACKGROUND%','%SLIDES%','%KEY%','%TITLE%','%NEXT%'], 
									   [$image,$html,$key - 1,$title,sprintf('%02d',$key)], $template);
			file_put_contents($sectionFile, $htmlToWrite);
		}
		$html = '';
		$end  = $sections[$key++]['end'];
	}
	
	$xml = file_get_contents($fn);
	$xml = str_replace("\n", '', $xml);
	// <a:p><a:t>Ground Rules</a:t><a:t>Whatever</a:t></a:p>
	preg_match_all('!\<a:p\>(.*?)\</a:p\>!', $xml, $matches);
	// <a:t>Ground Rules</a:t><a:t>Whatever</a:t>
	preg_match_all('!\<a:t\>(.*?)\</a:t\>!', $matches[1][0], $sub);
	$header = trim(implode(' ',$sub[1]));
	$bg = '';
	if (stripos($header, 'Exercise') !== FALSE) {
		$bg = ' data-background="img/' . SECTION_BACKGROUND . '" ';
	}
	$html .= '<section' . $bg . '>';
	$html .= '<h3>' . $header . '</h3>' . PHP_EOL;
	$html .= '<div class="' . SLIDE_CONTAINER_CLASS . '">' . PHP_EOL;
	$html .= '<div class="' . SLIDE_TEXT_CLASS . '">' . PHP_EOL;
	$html .= '<ul>' . PHP_EOL;
	for ($x = 1; $x < count($matches[1]); $x++) {
		preg_match_all('!\<a:t\>(.*?)\</a:t\>!', $matches[1][$x], $sub);
		$text = implode(' ',$sub[1]);
		$text = trim($text);
		//$text = str_replace([],'',$text);
		$html .= ($text) ? '<li>' . $text . '</li>' : '';
	}
	$html .= '</ul>' . PHP_EOL;
	$html .= '</div>' . PHP_EOL;
	if (file_exists($rels[$pos])) {
		$relXml = file_get_contents($rels[$pos]);
		// Target="../media/image239.tiff"/>
		preg_match_all('!/media/(.*?)"!', $relXml, $matches);
		$html .= '<div class="' . SLIDE_IMAGE_CLASS . '">' . PHP_EOL;
		if (isset($matches[1])) {
			foreach($matches[1] as $image) 
				$html .= sprintf('<img src="' . IMAGE_URL . '/%s" />', $image);
		}
		$html .= '</div>' . PHP_EOL;
	}
	$html .= '</div>' . PHP_EOL;
	$html .= '</section>';
}
