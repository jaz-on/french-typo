#!/usr/bin/env bash
# .claude/hooks/lint-edited.sh
#
# PostToolUse hook — after Claude Edits or Writes a file, run the
# appropriate linter/formatter on it, and flag a missing ABSPATH guard on
# WordPress PHP files. Non-blocking: never fails even if issues are found;
# it just surfaces output so Claude sees it in the next tool result.
#
# Matched tools: Edit, Write.
# Base: wpfr-2026's lint-edited.sh (Valentin Grenier). Adds the ABSPATH
# check — a gap found repeatedly across jardin-*/wpis-* repos that phpcs
# alone doesn't catch. Adapted for French Typo, which is monolithic
# (no inc/includes/ split) — the check also covers the main plugin file.

set -u

payload=$(cat)

if command -v jq >/dev/null 2>&1; then
    file=$(printf '%s' "$payload" | jq -r '.tool_input.file_path // empty')
else
    file=$(printf '%s' "$payload" | python3 -c 'import json,sys; d=json.load(sys.stdin); print(d.get("tool_input",{}).get("file_path",""))' 2>/dev/null)
fi

[ -z "$file" ] && exit 0
[ ! -f "$file" ] && exit 0

cd "$(git rev-parse --show-toplevel 2>/dev/null || echo .)" || exit 0

case "$file" in
    *.php)
        # ABSPATH guard check — this repo is monolithic (no inc/includes/
        # split), so the check covers the main plugin file too.
        case "$file" in
            inc/*.php|includes/*.php|french-typo.php)
                if ! grep -q "defined( *'ABSPATH' *)" "$file" 2>/dev/null && \
                   ! grep -q 'defined( *"ABSPATH" *)' "$file" 2>/dev/null; then
                    echo "⚠ $file: no ABSPATH guard (defined('ABSPATH') || exit;)"
                fi
                ;;
        esac

        if [ -x vendor/bin/phpcs ]; then
            vendor/bin/phpcs "$file" 2>&1 | tail -20 || true
        fi
        ;;
    *.js|*.scss|*.md|*.json)
        # Pas de build JS dans ce repo (pas de package.json/node_modules) —
        # no-op tant qu'aucun prettier n'est installé à la racine.
        prettier="./node_modules/.bin/prettier"
        if [ -x "$prettier" ]; then
            "$prettier" --write "$file" 2>&1 | tail -5 || true
        fi
        ;;
esac

exit 0
