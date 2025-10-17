<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Emico\AttributeLanding\Model\ResourceModel\Page as PageResourceModel;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Registry;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;

/**
 * @SuppressWarnings("PHPMD.ExcessivePublicCount")
 * @SuppressWarnings("PHPMD.ExcessiveClassComplexity")
 */
class LandingPage extends AbstractExtensibleModel implements LandingPageInterface, UrlRewriteGeneratorInterface
{
    protected $_eventPrefix = 'emico_attributelanding_page';

    /**
     * @var Config
     */
    protected $config;

    /**
     * LandingPage constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param Config $config
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        Config $config,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );

        $this->config = $config;
    }

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
        return (int)$this->getData(self::PAGE_ID);
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
    public function getExtensionAttributes()
    {
        /** @phpstan-ignore-next-line */
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(LandingPageExtensionInterface $extensionAttributes): LandingPageInterface
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get name
     * @return string
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
    public function setName(?string $name): LandingPageInterface
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
    public function setUrlPath(?string $urlPath): LandingPageInterface
    {
        return $this->setData(self::URL_PATH, $urlPath);
    }

    /**
     * Get category_id
     * @return int|null
     */
    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * Set category_id
     * @param int $categoryId
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setCategoryId(?int $categoryId): LandingPageInterface
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
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
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setHeading(?string $heading): LandingPageInterface
    {
        return $this->setData(self::HEADING, $heading);
    }

    /**
     * Get header_image
     * @return string|null
     */
    public function getHeaderImage()
    {
        return $this->getData(self::HEADER_IMAGE);
    }

    /**
     * Set header_image
     * @param string $headerImage
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setHeaderImage(?string $headerImage): LandingPageInterface
    {
        return $this->setData(self::HEADER_IMAGE, $headerImage);
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
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaTitle(?string $metaTitle): LandingPageInterface
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
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaKeywords(?string $metaKeywords): LandingPageInterface
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
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaDescription(?string $metaDescription): LandingPageInterface
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
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setContentFirst(?string $contentFirst): LandingPageInterface
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
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setContentLast(?string $contentLast): LandingPageInterface
    {
        return $this->setData(self::CONTENT_LAST, $contentLast);
    }

    /**
     * Get filter_attributes
     * @return string|null
     */
    public function getFilterAttributes()
    {
        return $this->getData(self::FILTER_ATTRIBUTES);
    }

    /**
     * Set filter_attributes
     * @param string $filterAttributes
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setFilterAttributes(?string $filterAttributes): LandingPageInterface
    {
        return $this->setData(self::FILTER_ATTRIBUTES, $filterAttributes);
    }

    /**
     * @return array
     *
     * phpcs:disable Magento2.Security.InsecureFunction.FoundWithAlternative
     */
    public function getUnserializedFilterAttributes(): array
    {
        if ($this->getFilterAttributes() === null) {
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
        /** @phpstan-ignore-next-line */
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
    public function getTweakwiseFilterTemplate()
    {
        return $this->getData(self::TWEAKWISE_FILTER_TEMPLATE);
    }

    /**
     * Set tweakwise_filter_template
     * @param string $tweakwiseFilterTemplate
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setTweakwiseFilterTemplate(?string $tweakwiseFilterTemplate): LandingPageInterface
    {
        return $this->setData(self::TWEAKWISE_FILTER_TEMPLATE, $tweakwiseFilterTemplate);
    }

    /**
     * Get tweakwise_filter_template
     * @return string|null
     */
    public function getTweakwiseSortTemplate()
    {
        return $this->getData(self::TWEAKWISE_SORT_TEMPLATE);
    }

    /**
     * Set tweakwise_filter_template
     * @param string $tweakwiseSortTemplate
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setTweakwiseSortTemplate(?string $tweakwiseSortTemplate): LandingPageInterface
    {
        return $this->setData(self::TWEAKWISE_SORT_TEMPLATE, $tweakwiseSortTemplate);
    }

    /**
     * Get tweakwise_builder_template
     * @return string|null
     */
    public function getTweakwiseBuilderTemplate(): ?string
    {
        return $this->getData(self::TWEAKWISE_BUILDER_TEMPLATE);
    }

    /**
     * Set tweakwise_builder_template
     * @param string $tweakwiseBuilderTemplate
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setTweakwiseBuilderTemplate(?string $tweakwiseBuilderTemplate): LandingPageInterface
    {
        return $this->setData(self::TWEAKWISE_BUILDER_TEMPLATE, $tweakwiseBuilderTemplate);
    }

    /**
     * Get active store ID
     * @return int
     */
    public function getStoreId(): int
    {
        return (int)$this->getData(self::STORE_ID);
    }

    /**
     * @param int $storeId
     * @return LandingPageInterface
     */
    public function setStoreId(int $storeId): LandingPageInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
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
        $urlPath = $this->getUrlPath();
        if ($this->config->isAppendCategoryUrlSuffix()) {
            $urlPath .= $this->config->getCategoryUrlSuffix();
        }

        return $urlPath;
    }

    /**
     * @return int
     */
    public function getOverviewPageId()
    {
        return $this->getData(LandingPageInterface::OVERVIEW_PAGE_ID);
    }

    /**
     * @param string|null $overviewPageId
     * @return LandingPageInterface
     */
    public function setOverviewPageId(?string $overviewPageId): LandingPageInterface
    {
        if ($overviewPageId === '') {
            $overviewPageId = null;
        }

        return $this->setData(LandingPageInterface::OVERVIEW_PAGE_ID, $overviewPageId);
    }

    /**
     * @return string|null
     */
    public function getOverviewPageImage()
    {
        return $this->getData(LandingPageInterface::OVERVIEW_PAGE_IMAGE);
    }

    /**
     * @param string|null $overviewPageImage
     * @return LandingPageInterface
     */
    public function setOverviewPageImage(?string $overviewPageImage): LandingPageInterface
    {
        return $this->setData(LandingPageInterface::OVERVIEW_PAGE_IMAGE, $overviewPageImage);
    }

    /**
     * @return bool
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
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
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
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
    public function getCanonicalUrl()
    {
        return $this->getData(LandingPageInterface::CANONICAL_URL);
    }

    /**
     * @param string $canonicalUrl
     * @return LandingPageInterface
     */
    public function setCanonicalUrl(?string $canonicalUrl): LandingPageInterface
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

    /**
     * @return array
     */
    public function getLandingPageDataWithoutStore(): array
    {
        $fields = [
            LandingPageInterface::PAGE_ID,
            LandingPageInterface::CREATED_AT,
            LandingPageInterface::UPDATED_AT,
            LandingPageInterface::OVERVIEW_PAGE_ID,
            LandingPageInterface::OVERVIEW_PAGE_IMAGE,
            LandingPageInterface::URL_PATH,
            LandingPageInterface::STORE_ID,
        ];

        if ($this->getData(LandingPageInterface::STORE_ID) === 0) {
            $fields[] = LandingPageInterface::NAME;
        }

        return array_combine(
            $fields,
            array_map(fn($field) => $this->getData($field), $fields)
        );
    }

    /**
     * @return array
     */
    public function getLandingPageDataForStore(): array
    {
        $fields = [
            LandingPageInterface::ACTIVE,
            LandingPageInterface::STORE_ID,
            LandingPageInterface::NAME,
            LandingPageInterface::URL_PATH,
            LandingPageInterface::CATEGORY_ID,
            LandingPageInterface::HEADING,
            LandingPageInterface::HEADER_IMAGE,
            LandingPageInterface::META_TITLE,
            LandingPageInterface::META_KEYWORDS,
            LandingPageInterface::META_DESCRIPTION,
            LandingPageInterface::CONTENT_FIRST,
            LandingPageInterface::CONTENT_LAST,
            LandingPageInterface::FILTER_ATTRIBUTES,
            LandingPageInterface::TWEAKWISE_FILTER_TEMPLATE,
            LandingPageInterface::TWEAKWISE_SORT_TEMPLATE,
            LandingPageInterface::TWEAKWISE_BUILDER_TEMPLATE,
            LandingPageInterface::FILTER_LINK_ALLOWED,
            LandingPageInterface::HIDE_SELECTED_FILTERS,
            LandingPageInterface::CANONICAL_URL,
        ];

        return array_combine(
            $fields,
            array_map(fn($field) => $this->getData($field), $fields)
        );
    }
}
