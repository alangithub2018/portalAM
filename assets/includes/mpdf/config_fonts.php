<?php
// $this->backupSubsFont = array('dejavusanscondensed','arialunicodems','sun-exta');	// this will recognise most scripts
$this->backupSubsFont = array('dejavusanscondensed', 'freeserif');
$this->backupSIPFont = 'sun-extb';
$this->fonttrans = array(
	'times' => 'timesnewroman',
	'courier' => 'couriernew',
	'trebuchet' => 'trebuchetms',
	'comic' => 'comicsansms',
	'franklin' => 'franklingothicbook',
	'ocr-b' => 'ocrb',
	'ocr-b10bt' => 'ocrb',
	'damase' => 'mph2bdamase',
);
$this->fontdata = array(
	"freesans" => array(
		'R' => "FreeSans.ttf",
		'B' => "FreeSansBold.ttf",
		'I' => "FreeSansOblique.ttf",
		'BI' => "FreeSansBoldOblique.ttf",
		'useOTL' => 0xFF,
	),
);
$this->BMPonly = array(
	"dejavusanscondensed",
	"dejavusans",
	"dejavuserifcondensed",
	"dejavuserif",
	"dejavusansmono",
);
$this->sans_fonts = array('dejavusanscondensed', 'sans', 'sans-serif', 'cursive', 'fantasy', 'dejavusans', 'freesans', 'liberationsans',
	'arial', 'helvetica', 'verdana', 'geneva', 'lucida', 'arialnarrow', 'arialblack', 'arialunicodems',
	'franklin', 'franklingothicbook', 'tahoma', 'garuda', 'calibri', 'trebuchet', 'lucidagrande', 'microsoftsansserif',
	'trebuchetms', 'lucidasansunicode', 'franklingothicmedium', 'albertusmedium', 'xbriyaz', 'albasuper', 'quillscript',
	'humanist777', 'humanist777black', 'humanist777light', 'futura', 'hobo', 'segoeprint'
);

$this->serif_fonts = array('dejavuserifcondensed', 'serif', 'dejavuserif', 'freeserif', 'liberationserif',
	'timesnewroman', 'times', 'centuryschoolbookl', 'palatinolinotype', 'centurygothic',
	'bookmanoldstyle', 'bookantiqua', 'cyberbit', 'cambria',
	'norasi', 'charis', 'palatino', 'constantia', 'georgia', 'albertus', 'xbzar', 'algerian', 'garamond',
);

$this->mono_fonts = array('dejavusansmono', 'mono', 'monospace', 'freemono', 'liberationmono', 'courier', 'ocrb', 'ocr-b', 'lucidaconsole',
	'couriernew', 'monotypecorsiva'
);