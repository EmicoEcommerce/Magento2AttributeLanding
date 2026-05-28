## Description

Create Landingpages based on predefined filters and categories.
Example Category T-shirts and filter color = red.

Specify any url you would like for this page, for example red-shirts.
Also you can define rich texts for SEO purposes on this landing pages.

Pages can be configured under Content > Manage pages and Manage overview pages.
Overview pages are a list view of landingpages.

Landingpages will be published in the sitemap. 

## Installation
Install package using composer
```sh
composer require emico/m2-attributelanding
```

Run installers
```sh
php bin/magento setup:upgrade
```

## Configuration
Configuration can be found under `Stores -> Configuration -> Emico Extensions -> Attribute landing pages -> General`.

- **Allow crosslink**: Allow landing pages to be crosslinked from within layered navigation filters. When enabled, if a visitor selects a combination of filters that exactly matches a landing page definition, they will be redirected to that landing page.
- **Append Category url suffix to landingpage Urls**: When enabled, the configured category URL suffix (e.g. `.html`) will be appended to landing page URLs. Changing this setting will update all landing page URLs — flush the cache and update any hardcoded links after saving.
- **Canonical link self referencing**: When enabled, the canonical link tag on a landing page points to the landing page's own URL instead of the underlying category URL.

## Contributors 
If you want to create a pull request as a contributor, use the guidelines of semantic-release. semantic-release automates the whole package release workflow including: determining the next version number, generating the release notes, and publishing the package.
By adhering to the commit message format, a release is automatically created with the commit messages as release notes. Follow the guidelines as described in: https://github.com/semantic-release/semantic-release?tab=readme-ov-file#commit-message-format.
