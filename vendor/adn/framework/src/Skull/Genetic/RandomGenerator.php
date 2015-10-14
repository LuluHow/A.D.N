<?php

namespace Skull\Genetic;

class RandomGenerator
{
    /**
     * Create new instance of Generator.
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Generate random string.
     *
     * @param int $length
     * @return string $randomString
     */
    public function string($length = 10)
    {
        $characters = '&é§èçà-_#@$ù%€Çë0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $randomKey = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
            $randomKey .= $characters[rand(0, $charactersLength - 1)];
        }
        return md5($randomKey . $randomString);
    }
}
