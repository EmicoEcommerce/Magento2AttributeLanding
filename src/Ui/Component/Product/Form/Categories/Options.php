<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Ui\Component\Product\Form\Categories;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\App\RequestInterface;

/**
 * Options tree for "Categories" field
 */
class Options extends \Magento\Catalog\Ui\Component\Product\Form\Categories\Options implements OptionSourceInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $categoriesTree;

    /**
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param RequestInterface $request
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        RequestInterface $request
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->request = $request;
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    public function getCategoriesTree()
    {
        if ($this->categoriesTree === null) {
            $this->categoriesTree = parent::getCategoriesTree();
        }

        return $this->categoriesTree;
    }
}
