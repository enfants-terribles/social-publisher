#!/bin/bash
# ---------------------------------------------------------------
# Dieses Skript dient ausschließlich der lokalen Entwicklung.
# Es erstellt eine saubere Plugin-ZIP-Datei für den Upload.
# Diese Datei gehört nicht ins Plugin-Repository oder ZIP-Archiv!
# ---------------------------------------------------------------

echo "🔧 Baue dist-Version von Social Publisher..."

# Zielordner vorbereiten
rm -rf dist
mkdir -p dist/social-publisher

# Dateien kopieren (ohne Dev-Zeug)
rsync -av \
  --exclude='.git*' \
  --exclude='node_modules' \
  --exclude='dist' \
  --exclude='*.zip' \
  --exclude='*.log' \
  --exclude='*.eslintrc*' \
  --exclude='*.DS_Store' \
  --exclude='build-plugin.sh' \
  ./ dist/social-publisher

# Plattformabhängiges SED-Kommando
if [[ "$OSTYPE" == "darwin"* ]]; then
  if command -v gsed &> /dev/null; then
    SED_CMD="gsed -i"
  else
    SED_CMD="sed -i ''"
  fi
else
  SED_CMD="sed -i"
fi

# 🧼 Entferne alle error_log() in PHP
find dist/social-publisher -type f -name "*.php" | while IFS= read -r file; do
  $SED_CMD '/error_log/d' "$file"
done

# Stelle sicher, dass console.warn-Zeilen korrekt bleiben
# Entferne keine reinen Textzeilen, die wie console.* aussehen könnten
# 🧼 Entferne console.log(), warn(), error() und debugger in JS (plattformunabhängig)
echo "🧼 JS-Debug-Zeilen werden entfernt..."

JS_FILES=$(find dist/social-publisher -type f -name "*.js")
JS_FILES_COUNT=$(echo "$JS_FILES" | wc -l)

echo "$JS_FILES" | while IFS= read -r file; do
  # Nur echte JS-Statements entfernen, keine reinen Strings
  $SED_CMD '/console\.\(log\|warn\|error\)(/d' "$file"
  $SED_CMD '/debugger/d' "$file"
  echo "🧹 Bereinigt: $file"
done

echo "📊 JS-Dateien bereinigt: $JS_FILES_COUNT"

# 🧽 Entferne versehentlich erzeugte temporäre Dateien wie *.js'' oder *.php''
find dist/social-publisher -type f \( -name "*''" -o -name "*~" \) -delete

# ZIP erstellen
cd dist
zip -r social-publisher.zip social-publisher
cd ..

echo "✅ Fertig: dist/social-publisher.zip ist produktionsbereit."