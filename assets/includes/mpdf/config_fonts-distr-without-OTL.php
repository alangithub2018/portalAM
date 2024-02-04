<?php
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
	"dejavusanscondensed" => array(
		'R' => "DejaVuSansCondensed.ttf",
		'B' => "DejaVuSansCondensed-Bold.ttf",
		'I' => "DejaVuSansCondensed-Oblique.ttf",
		'BI' => "DejaVuSansCondensed-BoldOblique.ttf",
	),
	"dejavusans" => array(
		'R' => "DejaVuSans.ttf",
		'B' => "DejaVuSans-Bold.ttf",
		'I' => "DejaVuSans-Oblique.ttf",
		'BI' => "DejaVuSans-BoldOblique.ttf",
	),
	"dejavuserif" => array(
		'R' => "DejaVuSerif.ttf",
		'B' => "DejaVuSerif-Bold.ttf",
		'I' => "DejaVuSerif-Italic.ttf",
		'BI' => "DejaVuSerif-BoldItalic.ttf",
	),
	"dejavuserifcondensed" => array(
		'R' => "DejaVuSerifCondensed.ttf",
		'B' => "DejaVuSerifCondensed-Bold.ttf",
		'I' => "DejaVuSerifCondensed-Italic.ttf",
		'BI' => "DejaVuSerifCondensed-BoldItalic.ttf",
	),
	"dejavusansmono" => array(
		'R' => "DejaVuSansMono.ttf",
		'B' => "DejaVuSansMono-Bold.ttf",
		'I' => "DejaVuSansMono-Oblique.ttf",
		'BI' => "DejaVuSansMono-BoldOblique.ttf",
	),
	"freesans" => array(
		'R' => "FreeSans.ttf",
		'B' => "FreeSansBold.ttf",
		'I' => "FreeSansOblique.ttf",
		'BI' => "FreeSansBoldOblique.ttf",
	),
	"freeserif" => array(
		'R' => "FreeSerif.ttf",
		'B' => "FreeSerifBold.ttf",
		'I' => "FreeSerifItalic.ttf",
		'BI' => "FreeSerifBoldItalic.ttf",
	),
	"freemono" => array(
		'R' => "FreeMono.ttf",
		'B' => "FreeMonoBold.ttf",
		'I' => "FreeMonoOblique.ttf",
		'BI' => "FreeMonoBoldOblique.ttf",
	),
	/* OCR-B font for Barcodes */
	"ocrb" => array(
		'R' => "ocrb10.ttf",
	),
	/* Miscellaneous language font(s) */
	"abyssinicasil" => array(/* Ethiopic */
		'R' => "Abyssinica_SIL.ttf",
	),
	"aboriginalsans" => array(/* Cherokee and Canadian */
		'R' => "AboriginalSansREGULAR.ttf",
	),
	"sundaneseunicode" => array(/* Sundanese */
		'R' => "SundaneseUnicode-1.0.5.ttf",
	),
	"aegean" => array(
		'R' => "Aegean.otf",
	),
	"aegyptus" => array(
		'R' => "Aegyptus.otf",
	),
	"akkadian" => array(/* Cuneiform */
		'R' => "Akkadian.otf",
	),
	"quivira" => array(
		'R' => "Quivira.otf",
	),
	"eeyekunicode" => array(/* Meetei Mayek */
		'R' => "Eeyek.ttf",
	),
	"lannaalif" => array(/* Tai Tham */
		'R' => "lannaalif-v1-03.ttf",
	),
	"daibannasilbook" => array(/* New Tai Lue */
		'R' => "DBSILBR.ttf",
	),
	"garuda" => array(/* Thai */
		'R' => "Garuda.ttf",
		'B' => "Garuda-Bold.ttf",
		'I' => "Garuda-Oblique.ttf",
		'BI' => "Garuda-BoldOblique.ttf",
	),
	/* SMP */
	"mph2bdamase" => array(
		'R' => "damase_v.2.ttf",
	),
	/* Indic */



	/* Arabic fonts */



	/* CJK fonts */
	"unbatang" => array(/* Korean */
		'R' => "UnBatang_0613.ttf",
	),
	"sun-exta" => array(
		'R' => "Sun-ExtA.ttf",
		'sip-ext' => 'sun-extb', /* SIP=Plane2 Unicode (extension B) */
	),
	"sun-extb" => array(
		'R' => "Sun-ExtB.ttf",
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
