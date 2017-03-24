<?php

namespace MarceloD\CbpqSkydivers;

class Affiliated
{
    public $numberCbpq;

    public $status;

    public $category;

    public $license;

    public $name;

    public $nickname;

    public $club;

    public $federation;

    public $affiliation;

    public $expiration;

    public $src_image;

    public $error;

    public static function create(array $data = [])
    {
        $affiliated = new self();

        foreach (get_object_vars($affiliated) as $name => $oldValue) {
            $affiliated->{$name} = isset($data[$name]) ? $data[$name] : null;
        }

        return $affiliated;
    }
}
