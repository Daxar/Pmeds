<?php declare(strict_types=1);
namespace Tingle\Pmeds\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveSerialize implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        var_dump($observer->getEvent());
        die();
    }
}
