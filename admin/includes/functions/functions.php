<?php
    spl_autoload_register('myAutoLoader');

    function myAutoLoader ($classeName) {
        $path = "classes/";
        $extension = ".class.php";
        $fileName = $path . $classeName . $extension;
        if(!file_exists($fileName)) {
            return false;
        }
        require $fileName;
    }   

    function getTitle() {
        global $pageTitle;
        if(isset($pageTitle)):
            return $pageTitle;
        else:
            return "Default";
        endif;

    }

    function removeComma($num) {
        return ($num - (int)$num) == 0 ? (int)$num : $num;  
    }
        // return "<div class='col-sm'>

    function AreaDisplayLayout($link,$name,$count,$color,$font) {
        return "<div class='col-md-6 col-lg-4'>
                    <div class='area $color mb-2'>
                        <a href='$link'>
                        <i class='$font'></i>
                            <div class='content'>
                            <strong class='text-uppercase'>{$name}</strong><p class='text-center'>{$count}</p>
                            </div>
                        </a>    
                    </div>
                </div>";
    }