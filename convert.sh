#
# (OS X, Unix and Linux)
#
# What is this?
#
# It's a shell script that is using ImageMagick to create all the icon files from one source icon.
#
# Stick the script in your 'www/res/icons' folder with your source icon 'my-hires-icon.png' then trigger it from Terminal.
#

ICON=${1:-"icon.png"}

cd android/icon/
convert $ICON -resize 36x36 icon-36-ldpi.png
convert $ICON -resize 48x48 icon-48-mdpi.png
convert $ICON -resize 72x72 icon-72-hdpi.png
convert $ICON -resize 96x96 icon-96-xhdpi.png

cd ../../ios/icon/
convert $ICON -resize 29 icon-29.png
convert $ICON -resize 40 icon-40.png 
convert $ICON -resize 50 icon-50.png 
convert $ICON -resize 57  icon-57.png
convert $ICON -resize 58  icon-29-2x.png
convert $ICON -resize 60  icon-60.png
convert $ICON -resize 72  icon-72.png
convert $ICON -resize 76  icon-76.png  
convert $ICON -resize 80  icon-40-2x.png
convert $ICON -resize 100 icon-50-2x.png
convert $ICON -resize 114 icon-57-2x.png     
convert $ICON -resize 120 icon-60-2x.png
convert $ICON -resize 144 icon-72-2x.png
