<?php

/**
 * @noinspection PhpMethodNamingConventionInspection
 * @noinspection PhpPropertyNamingConventionInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage as PageResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class OverviewPage extends AbstractModel implements OverviewPageInterface, IdentityInterface
{
    public const CACHE_TAG = 'emico_attributelanding_overviewpage';
    protected $_eventPrefix = 'emico_attributelanding_overviewpage';

    /**
     * Initialize resource model
     *
     * @return void
     * @throws LocalizedException
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected function _construct()
    {
        $this->_init(PageResourceModel::class);
        parent::_construct();
    }

    /**
     * Get page_id
     *
     * @return int
     */
    public function getPageId(): int
    {
        return (int) $this->getData(self::PAGE_ID);
    }

    /**
     * Set page_id
     *
     * @param int $pageId
     *
     * @return static
     */
    public function setPageId(int $pageId): static
    {
        return $this->setData(self::PAGE_ID, $pageId);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(self::ACTIVE);
    }

    /**
     * Set active
     *
     * @param bool $active
     *
     * @return static
     */
    public function setActive(bool $active): static
    {
        return $this->setData(self::ACTIVE, $active);
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     *
     * @param string|null $name
     *
     * @return static
     */
    public function setName(?string $name): static
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get url_path
     *
     * @return string|null
     */
    public function getUrlPath(): ?string
    {
        return $this->getData(self::URL_PATH);
    }

    /**
     * Set url_path
     *
     * @param string|null $urlPath
     *
     * @return static
     */
    public function setUrlPath(?string $urlPath): static
    {
        return $this->setData(self::URL_PATH, $urlPath);
    }

    /**
     * Get heading
     *
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return $this->getData(self::HEADING);
    }

    /**
     * Set heading
     *
     * @param string|null $heading
     *
     * @return static
     */
    public function setHeading(?string $heading): static
    {
        return $this->setData(self::HEADING, $heading);
    }

    /**
     * Get meta_title
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * Set meta_title
     *
     * @param string|null $metaTitle
     *
     * @return static
     */
    public function setMetaTitle(?string $metaTitle): static
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * Get meta_keywords
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * Set meta_keywords
     *
     * @param string|null $metaKeywords
     *
     * @return static
     */
    public function setMetaKeywords(?string $metaKeywords): static
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * Get meta_description
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * Set meta_description
     *
     * @param string|null $metaDescription
     *
     * @return static
     */
    public function setMetaDescription(?string $metaDescription): static
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Get content_first
     *
     * @return string|null
     */
    public function getContentFirst(): ?string
    {
        return $this->getData(self::CONTENT_FIRST);
    }

    /**
     * Set content_first
     *
     * @param string|null $contentFirst
     *
     * @return static
     */
    public function setContentFirst(?string $contentFirst): static
    {
        return $this->setData(self::CONTENT_FIRST, $contentFirst);
    }

    /**
     * Get content_last
     *
     * @return string|null
     */
    public function getContentLast(): ?string
    {
        return $this->getData(self::CONTENT_LAST);
    }

    /**
     * Set content_last
     *
     * @param string|null $contentLast
     *
     * @return static
     */
    public function setContentLast(?string $contentLast): static
    {
        return $this->setData(self::CONTENT_LAST, $contentLast);
    }

    /**
     * Get active stores ID
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @param int $storeId
     *
     * @return static
     */
    public function setStoreId(int $storeId): static
    {
        return $this->setData(self::STORE_ID, $storeId);
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
        return $this->getData(static::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(static::UPDATED_AT);
    }

    /**
     * @return array
     */
    public function getOverviewPageDataWithoutStore(): array
    {
        $fields = [
            static::PAGE_ID,
            static::CREATED_AT,
            static::UPDATED_AT,
            static::URL_PATH,
        ];

        if ($this->getData(static::STORE_ID) === 0) {
            $fields[] = static::NAME;
        }

        return array_combine(
            $fields,
            array_map(fn($field) => $this->getData($field), $fields),
        );
    }

    /**
     * @return array
     */
    public function getOverviewPageDataForStore(): array
    {
        $fields = [
            static::NAME,
            static::STORE_ID,
            static::ACTIVE,
            static::URL_PATH,
            static::HEADING,
            static::META_TITLE,
            static::META_KEYWORDS,
            static::META_DESCRIPTION,
            static::CONTENT_FIRST,
            static::CONTENT_LAST,
        ];

        return array_combine(
            $fields,
            array_map(fn($field) => $this->getData($field), $fields),
        );
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
