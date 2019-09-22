<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Questions;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ResponseInterface;

class Validation extends Action
{
    public function execute()
    {
        $data = $this->getRequest()->getPost();

        $x = 1;
    }
}
