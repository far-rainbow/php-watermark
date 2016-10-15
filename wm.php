<?php
// GIT version
// 
//xdebug_start_trace();

// //////////////////////
// 1 IMAGETYPE_GIF
// 2 IMAGETYPE_JPEG
// 3 IMAGETYPE_PNG
// //////////////////////

$mTime = microtime(TRUE);

// влияющие на отображение коэффициенты
ini_set ( 'memory_limit', '256M' );
$in = './in'; // название директории входных данных, можно полный путь
$out = './out'; // название директории выходных данных, можно полный путь
$text = 'AutoEPC.net'; // тест марки
$font = "./ARIAL.TTF"; // файл шрифта
$JPEGquality = 70; // 0 = маленький файл и ужасное качество, 100 = отличное качество и огромный файл. Оптимум это 60-90
$PNGquality = 5; // 0 = no comression, 9 = max compression
$fontSZ = 4; // размер текста
$prozr = 50; // прозрачность 15-30 нормально
$threshold = 8; // плотность марок. чем больше значение, тем разряженнее, 8 нормально

$shadow = TRUE; // если тень отключить, то время выполнения увеличится на 15-20% ожидаемо, не проверял
                
// цикл перебора файлов в директории входных данных
$fn = getPath ( $in );
procDir($fn,'./');

$mTime = microtime(TRUE) - $mTime;

printf("Обработка завершена. Время исполнения: %.2f сек.\n", $mTime);

// обработка каталога, решение без итератора
// -----------------------------------------------------------------------------------------------------------------------------------------------------
//

function procDir($fn,$fCurDir) {
    global $out;
    //unset ($fn['.'],$fn['..']);
    foreach ( $fn as $fname => $fkey ) {
        if (! is_array ( $fkey )) {
            $fname_ = procFile(substr($fCurDir,2) . $fkey);
        } else {
            $curPath = $fCurDir . (string)$fname;
            if (!is_dir($out . $curPath)) {
                mkdir($out . $curPath);
            }
            procDir($fkey,$curPath . DIRECTORY_SEPARATOR);
        }
    }
}

// обработка файла
// -----------------------------------------------------------------------------------------------------------------------------------------------------
//

function procFile($fname) {
    
    global $in,$out,$JPEGquality,$PNGquality;
    
    $in_ = $in . DIRECTORY_SEPARATOR . $fname;
    $out_ = $out . DIRECTORY_SEPARATOR . $fname;
    
    $imType = exif_imagetype ( $in_ );
    switch ($imType) {
        case (IMAGETYPE_JPEG) :
            $im = imagecreatefromjpeg ( $in_ );
            $im = createWatermark ( $im );
            if (imagejpeg ( $im, $out_, $JPEGquality )) {
                printf("JPEG processed --> $in_".PHP_EOL);
            } else {
                printf("Create JPEG process failed --> $in_".PHP_EOL);
            }
            imagedestroy ( $im );
            break;
        case (IMAGETYPE_GIF) :
            $im = imagecreatefromgif ( $in_ );
            $im = createWatermark ( $im );
            if (imagegif ( $im, $out_ )) {
                printf("GIF processed --> $in_".PHP_EOL);
            } else {
                printf("Create GIF process failed --> $in_".PHP_EOL);
            }
            imagedestroy ( $im );
            break;
        case (IMAGETYPE_PNG) :
            $im = imagecreatefrompng ( $in_ );
            $im = createWatermark ( $im );
            if (imagepng ( $im, $out_, $PNGquality )) {
                printf("PNG processed --> $in_".PHP_EOL);
            } else {
                printf("Create PNG process failed --> $in_".PHP_EOL);
            }
            imagedestroy ( $im ); 
            break;
        default :
            printf ( PHP_EOL . ">>> Obnaruzhen file nepodhodjashij dla obrabotki: $in_" . PHP_EOL . PHP_EOL);
            break;
    }
    return($in_);
}

function createWatermark($im) {
	global $text, $font, $fontSZ, $prozr, $color1, $color2, $threshold,$shadow;
	
	// создание копии входного изображения
	$imx = imagesx ( $im );
	$imy = imagesy ( $im );
	$imtc = imagecreatetruecolor ( $imx, $imy );
	imagecopy ( $imtc, $im, 0, 0, 0, 0, $imx, $imy );
	imagesavealpha ( $im, true );
	imagealphablending ( $im, true );
	
	// цвета марок и прозрачность
	$color1 = imagecolorallocate ( $im, 254, 254, 254 ); // цвет букв белый
	$color2 = imagecolorallocate ( $im, 0, 0, 0 ); // тень чёрный
	$color3 = imagecolorallocate ( $im, 255, 0, 0 ); // тестовый красный
	$white = imagecolorallocatealpha ( $im, 255, 255, 255, 127 );
	
	// imagefill ( $im, 0, 0, $white );
	
	$zk_ = sqrt ( $imx + $imy ); // этот коэф нужен для учёта масштаба изображения
	$shift = $zk_ * 2.75; // это сдвиг относительно центра изображения для коорд марки, чтобы она своим центром попадала в центр изображения, на глаз
	
	$imxc = $imx / 2; // центр по гор
	$imyc = $imy / 2; // центр по верт
	$delta = $zk_ * $threshold; // начальный шаг спирали наложения
	
	$u = $imxc - $shift; // вычисление начальной коорд наложения
	$w = $imyc + $shift; //
	
	$i = 1; // множитель шага спирали в начале равен единице
	
	if ($shadow) imagettftext ( $im, $fontSZ + $zk_, 45, $u, $w, $color2, $font, $text ); // по центру первая марка
	imagettftext ( $im, $fontSZ + $zk_, 45, $u + 2, $w + 2, $color1, $font, $text ); // по центру первая марка
	                                                                                 
	// далее заливаем спиралью
	while ( (($u >= - $imxc) && ($u <= $imx + $imxc)) && (($w >= - $imyc) && ($w <= $imy + $imyc)) ) {
		$rnd = rand ( 0, 2 );
		$j = $i;
		while ( $j >= 1 ) {
			$u = $u - $delta;
			if ($shadow) imagettftext ( $im, $fontSZ - $rnd + $zk_, 45, $u + $rnd, $w + $rnd, $color2, $font, $text );
			imagettftext ( $im, $fontSZ - $rnd + $zk_, 45, $u + $rnd + 2, $w + $rnd + 2, $color1, $font, $text );
			$j --;
		}
		
		$j = $i;
		while ( $j >= 1 ) {
			$w = $w - $delta;
			if ($shadow) imagettftext ( $im, $fontSZ - $rnd + $zk_, 45, $u + $rnd, $w + $rnd, $color2, $font, $text );
			imagettftext ( $im, $fontSZ - $rnd + $zk_, 45, $u + $rnd + 2, $w + $rnd + 2, $color1, $font, $text );
			$j --;
		}
		
		$i ++;
		$delta = - $delta;
	}
	
	// слияние чистой копии и копии с нанесённой маркой с коэф. прозрачности равным $prozr
	imagecopymerge ( $imtc, $im, 0, 0, 0, 0, $imx, $imy, $prozr );
	return $imtc;
}

// директорию в массив, итератор

function getPath($startPath) {
	$ritit = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( $startPath, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST );
	$r = array ();
	
	foreach ( $ritit as $splFileInfo ) {
		$path = $splFileInfo->isDir () ? array (
				$splFileInfo->getFilename () => array ()
		) : array (
				$splFileInfo->getFilename () 
		);
		
		for($depth = $ritit->getDepth () - 1; $depth >= 0; $depth --) {
			$path = array (
					$ritit->getSubIterator ( $depth )->current ()->getFilename () => $path
			);
		}
		
		$r = my_array_merge ( $r, $path );
	}
	return ($r);
}

// -- штатное слияние массивов не подходит, т.к. числовые ключи не сливаются! Баг обнаружился случайно
// т.к. в тесте попалась директория с чисто числовым именем. Вот функция, сливающая числовые ключи тоже. Медленнее встроенной конечно:

function my_array_merge ($arr,$ins) {
    if(is_array($arr))
    {
        if(is_array($ins)) foreach($ins as $k=>$v)
        {
            if(isset($arr[$k])&&is_array($v)&&is_array($arr[$k]))
            {
                $arr[$k] = my_array_merge($arr[$k],$v);
            }
            else {
                
                while (isset($arr[$k]))
                    $k++;
                    $arr[$k] = $v;
            }
        }
    }
    elseif(!is_array($arr)&&(strlen($arr)==0||$arr==0))
    {
        $arr=$ins;
    }
    return($arr);
}

//xdebug_stop_trace();
?>