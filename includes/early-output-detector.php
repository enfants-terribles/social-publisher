<?php
// early-output-detector.php

// Dieser Code prüft, ob vorzeitig Output generiert wurde (z. B. Leerzeichen, UTF-8 BOM etc.)
if ( headers_sent( $file, $line ) ) {
    error_log("⚠️ Frühzeitiger Output erkannt – bereits gesendete Header in Datei: $file, Zeile: $line");
}