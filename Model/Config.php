<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Tingle\Pmeds\Api\Data\ConfigInterface;

class Config implements ConfigInterface
{
    private $typesList = [
        0 => 'text',
        1 => 'textarea',
        2 => 'select'
    ];

    /**
     * @inheritdoc
     */
    public function getTypes()
    {
        return $this->typesList;
    }
}
