<?php 
/** themes
 * --- -- -- - - -
 * auto select one id of L = array.LENGTH, per month:
 * ( ( (month*31 + day) % L*2 ) div 2 )
 * this will allow theme exchange every 2 days
 * 
 * 
 * 4-day rollup:
 * --- -- -- - - -
 * supports up to 21 themes/season
 * = 84 themes/year
 * 
 * algo:
 * ( ( ( month*31 + day) % L*4 ) div 4 )
 * change seasonal theme every 4 days
 * ... Catch Exceptions:
 * if (month == 12) : month = 0
 * if (month == 6 && day == 1) then day = 2
 * 
 */

// decide a theme season according to month
$mo = intval(date('m'));

switch ($mo) {
    case 12: case 1: case 2:
        $season = 'winter';
        break;

    case 3: case 4: case 5:
        $season = 'spring';
        break;

    case 6: case 7: case 8:
        $season = 'summer';
        break;

    case 9: case 10: case 11:
        $season = 'fall';
        break;

    default:
        $season = 'spring';
}


$theme_def = [
    
    # --- summer ---

    's0' => [
        'css' => 'summer-00',
        'url' => 'https://unsplash.com/photos/7OT-2bsl3Fo',
        'label' => 'Aydin Hassan: Single hot air balloon at sunrise in Cappadocia, Turkey'
    ],

    's1' => [
        'css' => 'summer-01',
        'url' => 'https://www.freepik.com/free-photo/nazare-portugal_7487018.htm',
        'label' => 'frimufilms: North beach and ocean in Nazare, Portugal'
    ],

    's2' => [
        'css' => 'summer-02',
        'url' => 'https://www.pxfuel.com/en/desktop-wallpaper-elkiv',
        'label' => '@pxfuel: Faded orange lines'
    ],

    's5' => [
        'css' => 'summer-05',
        'url' => 'https://www.vecteezy.com/vector-art/6691305',
        'label' => 'Mohammad Arfa Affan: 3d Vectors (@Vecteezy)'
    ],

    's9' => [
        'css' => 'summer-09',
        'url' => 'https://unsplash.com/photos/cNagAGEok9k',
        'label' => 'Willian Justen de Vasconcellos: Desert, Huacachina, Peru'
    ],

    's10' => [
        'css' => 'summer-10',
        'url' => 'https://unsplash.com/photos/Qw3w0oBH63s',
        'label' => 'Carolinie Cavalli: Blue Peaks in Hout Bay (Cape Town, South Africa)'
    ],

    # --- fall ---

    'f8' => [
        'css' => 'fall-08',
        'url' => 'https://www.flickr.com/photos/docsearls/12114022885/',
        'label' => 'Doc Searls: Amazing Santa Barbara sunset (2014/01)'
    ],

    'f9' => [
        'css' => 'fall-09',
        'url' => 'https://www.freepik.com/free-photo/flock-birds-flying-during-sunset_13962565.htm',
        'label' => 'wirestock: View of a flock of birds flying into a beautiful sky during sunset'
    ],

    'f10' => [
        'css' => 'fall-10',
        'url' => 'https://unsplash.com/photos/P8VMwYFY-Es',
        'label' => 'Romello Williams: Turks and Caicos Islands'
    ],

    'f11' => [
        'css' => 'fall-11',
        'url' => 'https://unsplash.com/photos/jCL98LGaeoE',
        'label' => 'Eberhard Grossgasteiger: Sass long, Langkofel, Sasso lungo, Val Gherdeina'
    ],

    'f12' => [
        'css' => 'fall-12',
        'url' => 'https://unsplash.com/photos/Kdb6POEOSiE',
        'label' => 'Dave: Fog and Ripples (Door County, WI, USA)'
    ],

    'f14' => [
        'css' => 'fall-14',
        'url' => 'https://unsplash.com/photos/tMvuB9se2uQ',
        'label' => 'Sergey Pesterev: Sunset in the mountains (Hurghada International Airport, Egypt)'
    ],


    # --- winter ---

    'w3' =>  [
        'css' => 'winter-03',
        'url' => 'https://unsplash.com/photos/yzkoleKww6w',
        'label' => 'Raimond Klavins: Snow covered mountain under blue sky during daytime, Himalayas'
    ],

    'w8' =>  [
        'css' => 'winter-08',
        'url' => 'https://www.freepik.com/free-photo/3d-iceberg-blue-sea_3585791.htm',
        'label' => 'kjpargeter: 3d iceberg in blue sea'
    ],

    'w9' =>  [
        'css' => 'winter-09',
        'url' => 'https://www.freepik.com/free-photo/abstract-water-waves-with-ink-dots_5068293.htm',
        'label' => 'freepik: Abstract water waves with ink dots'
    ],

    'w10' =>  [
        'css' => 'winter-10',
        'url' => 'https://unsplash.com/photos/D1eFDB4CMj0',
        'label' => 'Vidar Nordli-Mathisen: Skogsøya, Norway'
    ],

    'w12' =>  [
        'css' => 'winter-12',
        'url' => 'https://unsplash.com/photos/RsRTIofe0HE',
        'label' => 'John Fowler: Near sunset at White Sands National Monument, New Mexico, USA'
    ],

    'w15' =>  [
        'css' => 'winter-15',
        'url' => 'https://unsplash.com/photos/dTSaC-S-7fs',
        'label' => 'Ricardo Gomez Angel: Silhouette of mountains during daytime'
    ],

    'w16' =>  [
        'css' => 'winter-16',
        'url' => 'https://unsplash.com/photos/twukN12EN7c',
        'label' => 'Simon Berger: Altmünster am Traunsee, Neukirchen bei Altmünster, Oberösterreich, Österreich'
    ],



    # --- spring ---

    'g1' => [
        'css' => 'spring-01',
        'url' => 'https://www.flickr.com/photos/youngsabroad/26305344342/',
        'label' => 'A Young Retirement: Bokeh background'
    ],

    'g4' => [
        'css' => 'spring-04',
        'url' => 'https://www.pxfuel.com/en/desktop-wallpaper-evgsz',
        'label' => '@pxfuel: Nature, lights'
    ],

    'g9' => [
        'css' => 'spring-09',
        'url' => 'https://www.flickr.com/photos/112072356@N06/14882252902/',
        'label' => 'auimeesri: banana leaf; blur green of banana leaf with water drop'
    ],

    'g11' => [
        'css' => 'spring-11',
        'url' => 'https://unsplash.com/photos/87MIF4vqHWg',
        'label' => 'Kent Pilcher: Selective focus photography of plant'
    ],

    'g12' => [
        'css' => 'spring-12',
        'url' => 'https://unsplash.com/photos/n7a2OJDSZns',
        'label' => 'Harli Marten: Photo of blue and pink sea'
    ]

];


$themes = [

    'summer' => [ 's1', 's2', 's5', 'g1', 's9', 's10', 's0' ],
        // 'g9', 'w16', 'g12'


    'fall'   => [ 'f10', 'f8', 's5', 'f9', 'f12', 'f14', 'f11' ],
        // 'g4', 's2', 's9', 'w16'


    'winter' => [ 'w3', 'w8', 'w9', 'w10', 'w12', 'w15', 'w16' ],
        // 'f14', 'f10', 'f14'


    'spring' => [ 'g1', 'g4', 'w16', 'g9', 'f9', 'g11', 'g12' ]
        // 'f10', 'f8', 'f14'

];

/** check algo in the begining of this file */
$day = intval(date('d'));
$themesLen = count($themes[$season]);
if ($mo == 12)              { $mo = 0; }
if ($mo == 6 && $day == 1)  { $day == 2; }
$imageID = intdiv( ( ( $mo*31 + $day) % $themesLen*4 ), 4 );

define('THEME', $theme_def[ $themes[ $season ][ $imageID ] ]);
// testing: define('THEME', $theme_def[ $themes[ 'spring' ][ 1 ] ]);

?>
    <!-- theme overides -->
<link href="/assets/css/themes/<?=THEME['css']?>.css" rel="stylesheet">
