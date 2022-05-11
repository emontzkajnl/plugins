#!/bin/bash
brightness=20
contrast=0

convert $1/prev.png -brightness-contrast ${brightness}x${contrast} $1/prev_hover.png
convert $1/next.png -brightness-contrast ${brightness}x${contrast} $1/next_hover.png

convert $1/prev.png -modulate 100,0,100 $1/prev_disabled.png
convert $1/next.png -modulate 100,0,100 $1/next_disabled.png