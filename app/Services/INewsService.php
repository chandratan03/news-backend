<?php

namespace App\Services;

interface INewsService{
    public function sync();
    public function paginate($pageSize, $data);
    public function paginateByPersonalize($pageSize, $data);


}
