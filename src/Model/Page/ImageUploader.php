<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\Page;


use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Psr\Log\LoggerInterface;

class ImageUploader
{
    const MEDIA_PATH_OVERVIEW = 'attributelanding/overview';

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * ImageUploader constructor.
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param LoggerInterface $logger
     * @param StoreManager $storeManager
     */
    public function __construct(
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        LoggerInterface $logger,
        StoreManager $storeManager
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * Upload attribute landing page overview image
     */
    public function upload(): array
    {
        try {
            $mediaDir = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
            $target = $mediaDir->getAbsolutePath(self::MEDIA_PATH_OVERVIEW);

            /** @var Uploader $uploader */
            $uploader = $this->uploaderFactory->create(['fileId' => LandingPageInterface::OVERVIEW_PAGE_IMAGE]);

            $uploader->setAllowedExtensions(['jpg', 'png', 'gif']);
            $uploader->setAllowRenameFiles(true);

            $result = $uploader->save($target);
            $result['url'] = $this->getMediaUrl($result['file']);
            return $result;
        } catch (LocalizedException $e) {
            return [
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode(),
            ];
        } catch (Exception $e) {
            $this->logger->critical($e);
            return [
                'error' => __('Something went wrong while saving file.'),
                'errorcode' => $e->getCode(),
            ];
        }
    }

    /**
     * @param string $filename
     * @param bool   $relative
     * @return string
     */
    public function getMediaUrl(string $filename, bool $relative = false): string
    {
        /** @var Store $store */
        try {
            $store = $this->storeManager->getStore();
        } catch (NoSuchEntityException $e) {
            $store = $this->storeManager->getDefaultStoreView();
        }

        if ($relative) {
            return self::MEDIA_PATH_OVERVIEW . '/' . $filename;
        }

        return $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . self::MEDIA_PATH_OVERVIEW . '/' . $filename;
    }
}