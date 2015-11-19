<?php

namespace Skull\Helix;

use Skull\Http\Path;

class Helix
{
    /**
     * View filename.
     *
     * @var string
     */
    public static $filename;

    /**
     * Array of variables.
     *
     * @var array
     */
    public static $variables;

    /**
     * Name of temporary file.
     *
     * @var string
     */
    public static $cachedFile;

    /**
     * Contain current content of file.
     *
     * @var string
     */
    public static $tmpFileContent;

    /**
     * Path of application.
     *
     * @var string
     */
    protected static $path;

    /**
     * Create new instance.
     *
     * @param array $variables
     * @param string $file
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Set current content.
     *
     * @param void
     * @return $this
     */
    public static function setContent()
    {
        static::$tmpFileContent = file_get_contents(static::$filename);
    }

    /**
     * Replace simple variable.
     *
     * @param $var
     * @return $this
     */
    public static function replaceSimpleBraces($var)
    {
        preg_match_all('/(' . $var . '\.\S*?)/', static::$tmpFileContent, $z);
        if(count($z[0]) > 0)
        {
            $tmpVar = explode('.', $z[0][0]);

            static::$tmpFileContent = str_replace('{{ ' . $z[0][0] . ' }}', '<?php echo $' . $tmpVar[0] . '[' . $tmpVar[1] . '] ?>', static::$tmpFileContent);
        } else {
            static::$tmpFileContent = str_replace('{{ ' . $var . ' }}', '<?php echo $' . $var . ' ?>', static::$tmpFileContent);
        }
    }

    /**
     * First replacement.
     *
     * @param void
     * @return void
     */
    public static function preReplace()
    {
        $pattern = '/{{ (.*?) }}/';
        $replace = '<?php echo $$1; ?>';
        static::$tmpFileContent = preg_replace($pattern, $replace, static::$tmpFileContent);
    }

    /**
     * Replace loops statements.
     *
     * @param void
     * @return $this
     */
    public static function replaceLoopStatements()
    {
        $z = static::$tmpFileContent;
        preg_match_all('/{{ for (.*?) in (.*?) }}/', $z, $matches);
        if(count($matches) > 0)
        {
            for($i = 0; $i < count($matches[0]); $i++)
            {
                $z = str_replace('{{ for ' . $matches[1][$i] . ' in ' . $matches[2][$i] . ' }}', '<?php foreach($' . $matches[2][$i] . ' as $' . $matches[1][$i] . '): ?>', $z);
                $checkSynt = explode(".", $matches[2][$i]);
                if(count($checkSynt) > 1)
                {
                    $z = str_replace($matches[2][$i], $checkSynt[0] . '[' . $checkSynt[1] . ']', $z);
                }
                $z = str_replace('{{ ' . $matches[1][$i] . ' }}', '<?php echo $' . $matches[1][$i] . '; ?>', $z);
                $z = str_replace('{{ endfor }}', '<?php endforeach; ?>', $z);
            }
        }
        static::$tmpFileContent = $z;
    }

    /**
     * Convert variable name into a string.
     *
     * @param $var
     * @return string $var_name
     */
    public static function convertVariableNameIntoString($var)
    {
        foreach($GLOBALS as $varName => $value)
        {
            if ($value === $var)
            {
                return $varName;
            }
        }
        return false;
    }

    /**
     * Set variables and write this.
     *
     * @param array
     * @return 
     */
    public static function setTemporaryContent($args)
    {
        foreach($args AS $key => $value)
        {
            static::replaceSimpleBraces($key);
        }
        static::replaceLoopStatements();
        static::preReplace();
        static::writeCachedFile();
    }

    /**
     * Convert object to array.
     *
     * @param Object $that
     * @return array
     */
    public static function objectToArray($that)
    {
        $array = new ReflectionObject($that);
        $b = array();

        foreach ($array->getProperties(ReflectionProperty::IS_PUBLIC) AS $key => $value)
        {
            $b[$value->getName()] = $value->getValue($that);
        }
        return $b;
    }

    /**
     * Write cached file.
     *
     * @param void
     * @return $this
     */
    public static function writeCachedFile()
    {
        static::$cachedFile = md5(static::$filename) . ".php";
        file_put_contents(static::$path . '/app/resources/cache/' . static::$cachedFile, static::$tmpFileContent);
    }

    /**
     * Display result.
     *
     * @param void
     * @return string
     */
    public static function render(Path $path, $view, array $args)
    {
        static::$path = $path->path;
        static::$filename = $view;
        static::setContent();
        static::setTemporaryContent($args);
        foreach($args AS $key => $value)
        {
            $$key = $value;    
        }
        ob_start();
        include_once(static::$path . '/app/resources/cache/' . static::$cachedFile);
        $z = ob_get_contents();
        ob_end_clean();
        static::deleteCachedFile();
        return $z;
    }

    /**
     * Delete temporary file.
     *
     * @param void
     * @return void
     */
    public static function deleteCachedFile()
    {
        unlink(static::$path . '/app/resources/cache/' . static::$cachedFile);
    }
}
