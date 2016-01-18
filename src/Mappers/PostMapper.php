<?php

namespace RestService\src\Mappers;

use RestService\app\Components\Model\AbstractMapper;

class PostMapper extends AbstractMapper
{
    protected $entityIdColumnName = 'id';
    protected $tableName = 'posts';
    protected $entityClass = 'RestService\src\Entities\Post';
}
