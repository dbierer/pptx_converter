<?php
// XML to UL/LI
// need to unzip PPTX file
// place slides\*.xml in directory indicated by $path
// place slides\_rel folder off directory indicated by $path

$rels  = glob($path . '/_rels/*.rels');
var_dump($rels);
$slide = 38;
if (file_exists($rels[$slide])) {
	$relXml = file_get_contents($rels[$slide]);
	// Target="../media/image239.tiff"/>
	preg_match_all('!/media/(.*?)"!', $relXml, $matches);
	var_dump($matches);
	$html .= '<div class="slide_text">' . PHP_EOL;
	if (isset($matches[1])) {
		foreach($matches[1] as $image) 
			$html .= sprintf('<img src="img/%s" />', $image);
	}
	$html .= '</div>' . PHP_EOL;
}
$html .= '</div>' . PHP_EOL;
