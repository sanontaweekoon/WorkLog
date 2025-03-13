<?php
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

$fontPath = '../vendor/tecnickcom/tcpdf/fonts/THSarabunNew.ttf';

TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode', '', 32);

echo "Font added successfully!";
?>
