#!/usr/bin/env python3
import re
import subprocess
import sys
from pathlib import Path

JSON_PATH = Path("tv/kanallakan.json")

CHANNELS = {
    "al-quran-al-kareem": "https://www.youtube.com/@SaudiQuranTv/live",
    "sunna-nabawiya": "https://www.youtube.com/@SaudiSunnahTv/live",
}

# Prefer a single muxed HLS URL (audio + video) up to 1080p.
FORMAT = "best[height<=1080][protocol^=m3u8]/best[height<=1080]"


def extract_stream_url(page_url: str) -> str:
    cmd = [
        "yt-dlp",
        "--no-playlist",
        "--no-warnings",
        "--get-url",
        "-f", FORMAT,
        page_url,
    ]
    result = subprocess.run(
        cmd,
        text=True,
        capture_output=True,
        timeout=90,
    )

    if result.returncode != 0:
        raise RuntimeError(result.stderr.strip() or "yt-dlp failed")

    urls = [line.strip() for line in result.stdout.splitlines()
            if line.strip().startswith(("http://", "https://"))]

    # We need exactly one directly playable URL for streamUrl.
    if len(urls) != 1:
        raise RuntimeError(
            f"Expected one playable URL, got {len(urls)}. "
            "Refusing to modify the JSON."
        )

    url = urls[0]

    # Prefer HLS; don't overwrite a good URL with an unexpected result.
    if "m3u8" not in url.lower():
        raise RuntimeError(
            "Extracted URL is not HLS/m3u8. Refusing to modify the JSON."
        )

    return url


def replace_stream_url(text: str, channel_id: str, new_url: str) -> tuple[str, str]:
    # Match only the object that contains this exact channel ID, then only streamUrl.
    pattern = re.compile(
        rf'("id"\s*:\s*"{re.escape(channel_id)}"'
        rf'(?:(?!"id"\s*:).)*?'
        rf'"streamUrl"\s*:\s*")([^"]*)(")',
        re.DOTALL,
    )

    matches = list(pattern.finditer(text))
    if len(matches) != 1:
        raise RuntimeError(
            f'Expected exactly one object with id="{channel_id}", '
            f"found {len(matches)}. Refusing to modify the JSON."
        )

    old_url = matches[0].group(2)
    updated = pattern.sub(
        lambda m: m.group(1) + new_url + m.group(3),
        text,
        count=1,
    )
    return updated, old_url


def main() -> int:
    if not JSON_PATH.exists():
        print(f"ERROR: {JSON_PATH} not found", file=sys.stderr)
        return 1

    original = JSON_PATH.read_text(encoding="utf-8")
    updated = original
    changes = []

    # Extract both first. If either fails, NOTHING is written.
    fresh_urls = {}
    for channel_id, page_url in CHANNELS.items():
        print(f"Extracting {channel_id} ...")
        fresh_urls[channel_id] = extract_stream_url(page_url)

    # Apply only the two streamUrl fields.
    for channel_id, new_url in fresh_urls.items():
        updated, old_url = replace_stream_url(updated, channel_id, new_url)
        if old_url != new_url:
            changes.append(channel_id)

    if not changes:
        print("No URL changes.")
        return 0

    # Final guard: only streamUrl values for the two target IDs may differ.
    # The regex replacement above is intentionally surgical.
    JSON_PATH.write_text(updated, encoding="utf-8")
    print("Updated only:", ", ".join(changes))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
