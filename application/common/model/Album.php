<?php


namespace app\common\model;

class Album extends Base
{
    protected $name = 'album';
    protected $autoWriteTimestamp = 'datetime';

    public function item() {
        return $this->hasMany('AlbumItem','album_id','id');
    }
}