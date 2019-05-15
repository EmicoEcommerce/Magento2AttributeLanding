<?php


namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Emico\AttributeLanding\Model\ResourceModel\Page as PageResourceModel;
use Magento\Framework\Model\AbstractExtensibleModel;

class LandingPage extends AbstractExtensibleModel implements LandingPageInterface, UrlRewriteGeneratorInterface
{

    protected $_eventPrefix = 'emico_attributelanding_page';

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
    public function getPageId(): ?int
    {
        return $this->getData(self::PAGE_ID);
    }

    /**
     * Set page_id
     * @param string $pageId
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setPageId($pageId): LandingPageInterface
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
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setActive($active): LandingPageInterface
    {
        return $this->setData(self::ACTIVE, $active);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface|null
     */
    public function getExtensionAttributes(): ?LandingPageExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(LandingPageExtensionInterface $extensionAttributes): LandingPageInterface {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setName($name): LandingPageInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get url_path
     * @return string|null
     */
    public function getUrlPath(): ?string
    {
        return $this->getData(self::URL_PATH);
    }

    /**
     * Set url_path
     * @param string $urlPath
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setUrlPath($urlPath): LandingPageInterface
    {
        return $this->setData(self::URL_PATH, $urlPath);
    }

    /**
     * Get category_id
     * @return string|null
     */
    public function getCategoryId(): ?int
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * Set category_id
     * @param string $categoryId
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setCategoryId($categoryId): LandingPageInterface
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }

    /**
     * Get heading
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return $this->getData(self::HEADING);
    }

    /**
     * Set heading
     * @param string $heading
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setHeading($heading): LandingPageInterface
    {
        return $this->setData(self::HEADING, $heading);
    }

    /**
     * Get header_image
     * @return string|null
     */
    public function getHeaderImage(): ?string
    {
        return $this->getData(self::HEADER_IMAGE);
    }

    /**
     * Set header_image
     * @param string $headerImage
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setHeaderImage($headerImage): LandingPageInterface
    {
        return $this->setData(self::HEADER_IMAGE, $headerImage);
    }

    /**
     * Get meta_title
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * Set meta_title
     * @param string $metaTitle
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaTitle($metaTitle): LandingPageInterface
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * Get meta_keywords
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * Set meta_keywords
     * @param string $metaKeywords
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaKeywords($metaKeywords): LandingPageInterface
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * Get meta_description
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * Set meta_description
     * @param string $metaDescription
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaDescription($metaDescription): LandingPageInterface
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Get content_first
     * @return string|null
     */
    public function getContentFirst(): ?string
    {
        return $this->getData(self::CONTENT_FIRST);
    }

    /**
     * Set content_first
     * @param string $contentFirst
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setContentFirst($contentFirst): LandingPageInterface
    {
        return $this->setData(self::CONTENT_FIRST, $contentFirst);
    }

    /**
     * Get content_last
     * @return string|null
     */
    public function getContentLast(): ?string
    {
        return $this->getData(self::CONTENT_LAST);
    }

    /**
     * Set content_last
     * @param string $contentLast
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setContentLast($contentLast): LandingPageInterface
    {
        return $this->setData(self::CONTENT_LAST, $contentLast);
    }

    /**
     * Get filter_attributes
     * @return string|null
     */
    public function getFilterAttributes(): ?string
    {
        return $this->getData(self::FILTER_ATTRIBUTES);
    }

    /**
     * Set filter_attributes
     * @param string $filterAttributes
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setFilterAttributes($filterAttributes): LandingPageInterface
    {
        return $this->setData(self::FILTER_ATTRIBUTES, $filterAttributes);
    }

    /**
     * @return mixed
     */
    public function getUnserializedFilterAttributes(): array
    {
        if (null === $this->getFilterAttributes()) {
            return [];
        }
        return unserialize($this->getFilterAttributes());
    }
    
    /**
     * @return array
     */
    public function getFilters(): array
    {
        $unserializedFilters = $this->getUnserializedFilterAttributes();
        if (!is_array($unserializedFilters)) {
            return [];
        }

        $filters = [];
        foreach ($unserializedFilters as $unserializedFilter) {
            $filters[] = new Filter($unserializedFilter['attribute'], $unserializedFilter['value']);
        }
        return $filters;
    }

    /**
     * Get tweakwise_filter_template
     * @return string|null
     */
    public function getTweakwiseFilterTemplate(): ?int
    {
        return $this->getData(self::TWEAKWISE_FILTER_TEMPLATE);
    }

    /**
     * Set tweakwise_filter_template
     * @param string $tweakwiseFilterTemplate
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setTweakwiseFilterTemplate($tweakwiseFilterTemplate): LandingPageInterface
    {
        return $this->setData(self::TWEAKWISE_FILTER_TEMPLATE, $tweakwiseFilterTemplate);
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
     * @return LandingPageInterface
     */
    public function setStoreIds($storeIds): LandingPageInterface
    {
        return $this->setData(self::STORE_IDS, implode(',', $storeIds));
    }

    /**
     * @return string
     */
    public function getUrlRewriteEntityType(): string
    {
        return 'landingpage';
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
        return sprintf('emico_attributelanding/landingPage/view/id/%d', $this->getPageId());
    }

    /**
     * @return string
     */
    public function getUrlRewriteRequestPath(): string
    {
        return $this->getUrlPath();
    }

    /**
     * @return int
     */
    public function getOverviewPageId(): ?int
    {
        return $this->getData(LandingPageInterface::OVERVIEW_PAGE_ID);
    }

    /**
     * @param int|null $overviewPageId
     * @return LandingPageInterface
     */
    public function setOverviewPageId($overviewPageId): LandingPageInterface
    {
        if ($overviewPageId === '') {
            $overviewPageId = null;
        }
        return $this->setData(LandingPageInterface::OVERVIEW_PAGE_ID, $overviewPageId);
    }

    /**
     * @return string|null
     */
    public function getOverviewPageImage(): ?string
    {
        return $this->getData(LandingPageInterface::OVERVIEW_PAGE_IMAGE);
    }

    /**
     * @param string|null $overviewPageImage
     * @return LandingPageInterface
     */
    public function setOverviewPageImage($overviewPageImage): LandingPageInterface
    {
        return $this->setData(LandingPageInterface::OVERVIEW_PAGE_IMAGE, $overviewPageImage);
    }

    /**
     * @return bool
     */
    public function getIsFilterLinkAllowed(): bool
    {
        return (bool) $this->getData(LandingPageInterface::FILTER_LINK_ALLOWED);
    }

    /**
     * @param bool $isFilterLinkAllowed
     * @return LandingPageInterface
     */
    public function setIsFilterLinkAllowed(bool $isFilterLinkAllowed = true): LandingPageInterface
    {
        return $this->setData(LandingPageInterface::FILTER_LINK_ALLOWED, $isFilterLinkAllowed);
    }

    /**
     * @return bool
     */
    public function getHideSelectedFilters(): bool
    {
        return (bool) $this->getData(LandingPageInterface::HIDE_SELECTED_FILTERS);
    }

    /**
     * @param bool $hideSelectedFilters
     * @return LandingPageInterface
     */
    public function setHideSelectedFilters(bool $hideSelectedFilters = true): LandingPageInterface
    {
        return $this->setData(LandingPageInterface::HIDE_SELECTED_FILTERS, $hideSelectedFilters);
    }

    /**
     * @return string
     */
    public function getCanonicalUrl(): ?string
    {
        return $this->getData(LandingPageInterface::CANONICAL_URL);
    }

    /**
     * @param string $canonicalUrl
     * @return LandingPageInterface
     */
    public function setCanonicalUrl(string $canonicalUrl): LandingPageInterface
    {
        return $this->setData(LandingPageInterface::CANONICAL_URL, $canonicalUrl);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(LandingPageInterface::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(LandingPageInterface::UPDATED_AT);
    }
}
