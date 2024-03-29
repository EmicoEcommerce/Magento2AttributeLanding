<?php

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\Data\StoreSelectableInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage as PageResourceModel;
use Magento\Framework\Model\AbstractModel;

class OverviewPage extends AbstractModel implements OverviewPageInterface, UrlRewriteGeneratorInterface
{
    protected $_eventPrefix = 'emico_attributelanding_overviewpage';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(PageResourceModel::class);
        parent::_construct();
    }

    /**
     * Get page_id
     * @return int|null
     */
    public function getPageId()
    {
        return $this->getData(self::PAGE_ID);
    }

    /**
     * Set page_id
     * @param string $pageId
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setPageId($pageId): OverviewPageInterface
    {
        return $this->setData(self::PAGE_ID, $pageId);
    }

    /**
     * Get active
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(self::ACTIVE);
    }

    /**
     * Set active
     * @param string $active
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setActive($active): OverviewPageInterface
    {
        return $this->setData(self::ACTIVE, $active);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setName($name): OverviewPageInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get url_path
     * @return string|null
     */
    public function getUrlPath()
    {
        return $this->getData(self::URL_PATH);
    }

    /**
     * Set url_path
     * @param string $urlPath
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setUrlPath($urlPath): OverviewPageInterface
    {
        return $this->setData(self::URL_PATH, $urlPath);
    }

    /**
     * Get heading
     * @return string|null
     */
    public function getHeading()
    {
        return $this->getData(self::HEADING);
    }

    /**
     * Set heading
     * @param string $heading
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setHeading($heading): OverviewPageInterface
    {
        return $this->setData(self::HEADING, $heading);
    }

    /**
     * Get meta_title
     * @return string|null
     */
    public function getMetaTitle()
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * Set meta_title
     * @param string $metaTitle
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setMetaTitle($metaTitle): OverviewPageInterface
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * Get meta_keywords
     * @return string|null
     */
    public function getMetaKeywords()
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * Set meta_keywords
     * @param string $metaKeywords
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setMetaKeywords($metaKeywords): OverviewPageInterface
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * Get meta_description
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * Set meta_description
     * @param string $metaDescription
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setMetaDescription($metaDescription): OverviewPageInterface
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Get content_first
     * @return string|null
     */
    public function getContentFirst()
    {
        return $this->getData(self::CONTENT_FIRST);
    }

    /**
     * Set content_first
     * @param string $contentFirst
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setContentFirst($contentFirst): OverviewPageInterface
    {
        return $this->setData(self::CONTENT_FIRST, $contentFirst);
    }

    /**
     * Get content_last
     * @return string|null
     */
    public function getContentLast()
    {
        return $this->getData(self::CONTENT_LAST);
    }

    /**
     * Set content_last
     * @param string $contentLast
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setContentLast($contentLast): OverviewPageInterface
    {
        return $this->setData(self::CONTENT_LAST, $contentLast);
    }

    /**
     * Get active stores IDs
     * @return int[]
     */
    public function getStoreIds(): array
    {
        return explode(',', $this->getData(self::STORE_IDS));
    }

    /**
     * @param int[] $storeIds
     * @return OverviewPageInterface
     */
    public function setStoreIds($storeIds): OverviewPageInterface
    {
        return $this->setData(self::STORE_IDS, implode(',', $storeIds));
    }

    /**
     * @return string
     */
    public function getUrlRewriteEntityType(): string
    {
        return 'landingpage_overview';
    }

    /**
     * @return int
     */
    public function getUrlRewriteEntityId(): int
    {
        return $this->getPageId();
    }

    /**
     * @return string
     */
    public function getUrlRewriteTargetPath(): string
    {
        return sprintf('emico_attributelanding/overviewPage/view/id/%d', $this->getPageId());
    }

    /**
     * @return string
     */
    public function getUrlRewriteRequestPath(): string
    {
        return $this->getUrlPath();
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(OverviewPageInterface::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(OverviewPageInterface::UPDATED_AT);
    }
}
