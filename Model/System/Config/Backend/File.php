<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\System\Config\Backend;

class File extends \Magento\Config\Model\Config\Backend\File
{
    /**
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'png'];
    }
}
