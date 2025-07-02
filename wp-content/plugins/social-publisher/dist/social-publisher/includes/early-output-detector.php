<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// early-output-detector.php

// Dieser Code prüft, ob vorzeitig Output generiert wurde (z. B. Leerzeichen, UTF-8 BOM etc.)
if ( headers_sent( $file, $line ) ) {
}