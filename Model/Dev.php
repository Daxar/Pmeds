<?php declare(strict_types=1);

namespace Tingle\Pmeds\Model;

use Magento\Eav\Api\AttributeRepositoryInterface;

/**
 * Class Dev
 * Created for debugging purposes. TODO: DELETE THIS CLASS
 */
class Dev
{
    private $attributeRepository;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->attributeRepository = $attributeRepository;
    }

    public function run()
    {
        try {
            $this->dropAttributes();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        echo "Success";
    }

    private function dropAttributes()
    {
        $this->attributeRepository->deleteById(155);
        $this->attributeRepository->deleteById(157);
    }
}
