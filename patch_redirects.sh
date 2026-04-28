#!/bin/bash
FILES=$(find app/Filament/Resources -name "Create*.php" -o -name "Edit*.php")

for FILE in $FILES; do
  if ! grep -q "function getRedirectUrl" "$FILE"; then
    sed -i '$ d' "$FILE"
    echo "" >> "$FILE"
    echo "    protected function getRedirectUrl(): string" >> "$FILE"
    echo "    {" >> "$FILE"
    echo "        return \$this->getResource()::getUrl('index');" >> "$FILE"
    echo "    }" >> "$FILE"
    echo "}" >> "$FILE"
    echo "Patched $FILE"
  fi
done
