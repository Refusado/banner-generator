<?php

// ATRIBUIÇÃO DE VALORES DINÂMICOS
$participants = [
    'Refused#0010',
    'Phmetro#0010',
    'Digo20games',
    'Yukari#6827',
    'Rox#5618',
    'Soft#1388',
    'Star#8558',
    'Zeroclown',
    'Refused#0010',
    'Phmetro#0010',
    'Digo20games',
    'Yukari#6827',
    'Rox#5618',
    'Soft#1388',
    'Star#8558',
    'Zeroclown',
];

$winners = [
    'Refused#0010',
    'Phmetro#0010',
];

$backgroundDir  = __DIR__ . '/img/base2.png';
$imageDir       = __DIR__ . '/img/mouse.png';
$image2Dir      = __DIR__ . '/img/fur.png';

$participantsColor  = [60, 41, 6];
$winnersColor       = [146, 93, 219];
$winnersStrokeColor = [5, 4, 6];
$winnersStrokeWidth = 2;

// CONFIGURAÇÕES AUTOMATICAS DE ATRIBUIÇÃO
define('WIDTH', getimagesize($backgroundDir)[0]);
define('HEIGHT', getimagesize($backgroundDir)[1]);

define('CENTER_X', floor(WIDTH / 2));
define('CENTER_Y', floor(HEIGHT / 2));

define('FONT_ATMA_BOLD', __DIR__ . '/fonts/atma-bold.ttf');
define('FONT_SOOPAFRESH', __DIR__ . '/fonts/soopafresh.ttf');

// CRIANDO A IMAGEM
$banner = imagecreatetruecolor(WIDTH, HEIGHT);
$background = imagecreatefrompng($backgroundDir);
imagecopyresampled($banner, $background, 0, 0, 0, 0, WIDTH, HEIGHT, WIDTH, HEIGHT);

// INSERINDO E CONFIGURANDO A IMAGEM DO PRIMEIRO PRÊMIO
$fur            = imagecreatefrompng($image2Dir);
$furWidth       = getimagesize($image2Dir)[0];
$furHeight      = getimagesize($image2Dir)[1];

$scale  = 0.75;
$px   = 792;
$py   = 160;
imagecopyresampled(
    $banner,
    $fur,
    $px - floor($furWidth / 2 * $scale),
    $py - floor($furHeight / 2 * $scale),
    0,
    0,
    $furWidth * $scale,
    $furHeight * $scale,
    $furWidth,
    $furHeight,
);

// INSERINDO E CONFIGURANDO A IMAGEM DO SEGUNDO PRÊMIO
$fanart         = imagecreatefrompng($imageDir);
$fanartWidth    = getimagesize($imageDir)[0];
$fanartHeight   = getimagesize($imageDir)[1];

$scale  = 0.35;
$px   = 792;
$py   = 450;
imagecopyresampled(
    $banner,
    $fanart,
    $px - floor($fanartWidth / 2 * $scale),
    $py - floor($fanartHeight / 2 * $scale),
    0,
    0,
    $fanartWidth * $scale,
    $fanartHeight * $scale,
    $fanartWidth,
    $fanartHeight,
);

// INSERINDO E CONFIGURANDO NOME DOS DOADORES
$px   = 120;
$py   = 280;
$px2  = 400;
$py2  = $py;
$columnMax  = 10;

for ($i = 0; $i < count($participants); $i++) {
    if ($i == $columnMax) {
        $px = $px2;
        $py = $py2;
    }

    imagettftext(
        $banner,
        16,
        0,
        $px,
        $py,
        imagecolorallocate($banner, ...$participantsColor),
        FONT_ATMA_BOLD,
        $participants[$i],
    );

    $py += 30;
}

// INSERINDO E CONFIGURANDO NOME DO PRIMEIRO VENCEDOR
$text       = $winners[0];
$fontFamily = FONT_SOOPAFRESH;
$fontsize   = 20;
$angle      = 5;

$px         = 792;
$py         = 260;

$textColor = imagecolorallocate($banner, ...$winnersColor);
$strokeColor = imagecolorallocate($banner, ...$winnersStrokeColor);

imagettfstroketext(
    $banner,
    $fontsize,
    $angle,
    $px,
    $py,
    $textColor,
    $strokeColor,
    $fontFamily,
    $text,
    $winnersStrokeWidth
);

// INSERINDO E CONFIGURANDO NOME DO SEGUNDO VENCEDOR
$text       = $winners[1];
$fontFamily = FONT_SOOPAFRESH;
$fontsize   = 20;
$angle      = -5;

$px         = 792;
$py         = 540;

$textColor = imagecolorallocate($banner, ...$winnersColor);
$strokeColor = imagecolorallocate($banner, ...$winnersStrokeColor);
imagettfstroketext(
    $banner,
    $fontsize,
    $angle,
    $px,
    $py,
    $textColor,
    $strokeColor,
    $fontFamily,
    $text,
    $winnersStrokeWidth
);

// FUNÇÃO PARA A CRIAÇÃO DE TEXTOS COM BORDAS
function imagettfstroketext(
    $srcImage,
    $fontSize,
    $angle,
    $px,
    $py,
    $textColor,
    $strokeColor,
    $fontFamily,
    $text,
    $strokeWidth
) {
    for ($c1 = ($px - abs($strokeWidth)); $c1 <= ($px + abs($strokeWidth)); $c1++) {
        for ($c2 = ($py - abs($strokeWidth)); $c2 <= ($py + abs($strokeWidth)); $c2++) {
            imagettftext(
                $srcImage,
                $fontSize,
                $angle,
                $c1 - (imageftbbox(
                    $fontSize,
                    0,
                    $fontFamily,
                    $text
                )[2] / 2),
                $c2,
                $strokeColor,
                $fontFamily,
                $text
            );
        }
    }

    return imagettftext(
        $srcImage,
        $fontSize,
        $angle,
        $px - (imageftbbox(
            $fontSize,
            0,
            $fontFamily,
            $text
        )[2] / 2),
        $py,
        $textColor,
        $fontFamily,
        $text
    );
}

header('Content-type: image/png');
imagepng($banner);
