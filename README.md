# php-watermark
PHP script to watermark JPEG,GIF and PNG pictures.

# USAGE:

-- copy this git
-- copy your images directory in "in" dir
-- start PHP CLI scrpit:
  #server> php wm.php
-- wait until done
-- get watermarked files in "out" dir
-- PROFIT!

##WINDOWS FILE SYSTEM ONLY!
(you are free to rewrite it to *nix format, i`ll do it tomorrow)

This script watermark the whole dir tree placed into "in" dir. The result copy of dir tree is in "out" dir.
Also there are some huge JPEG files for benchmark purpose.
For example, my Intel Xeon 5320 (4 cores, 8 Gb) do this script with current file setup in ~40 seconds on Win7 + PHP 5.6

Also you are welcome to post your results in comments.

All this staff needs polishing and recycling =) not OOP, but just name and call styling

have a fun, this is free to use!
