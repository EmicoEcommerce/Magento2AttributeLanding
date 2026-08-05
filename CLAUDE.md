# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Module identity

Magento 2 module `Emico_AttributeLanding` (package `emico/m2-attributelanding`). Namespace root: `Emico\AttributeLanding`, mapped from `src/`. PHP 8.1+.

## Commands

### Linting and static analysis

GrumPHP orchestrates all code quality checks. Run everything in one shot:

```sh
vendor/bin/grumphp run
```

Individual tools (all configured via `grumphp.yml` / `vendor/emico/code-quality/grumphp.base.yml`):

```sh
vendor/bin/phpstan analyse --configuration=phpstan.neon   # level 7
vendor/bin/phpcs --standard=phpcs.xml src/
vendor/bin/phpmd src/ text ruleset.xml
vendor/bin/parallel-lint src/
```

### Tests

Codeception with functional and unit suites:

```sh
vendor/bin/codecept run              # all suites
vendor/bin/codecept run Unit         # unit suite only
vendor/bin/codecept run Functional   # functional suite only
vendor/bin/codecept run Unit tests/Unit/SomeTest.php  # single test file
```

### Commit messages

This repo uses **semantic-release**. Commits must follow the Angular Conventional Commits format (`feat:`, `fix:`, `chore:`, etc.) — the CI pipeline derives the version number and release notes from them automatically.

## Architecture

### Two entity types

**LandingPage** — a category page with predefined layered-navigation filters applied. Configured under `Content > Manage pages`.

**OverviewPage** — a listing of related LandingPages. Configured under `Content > Manage overview pages`.

### Store-scoped data model

Each entity is split across two database tables:

| Table | Purpose |
|---|---|
| `emico_attributelanding_page` | Global fields: `page_id`, timestamps, `url_path`, `overview_page_id` |
| `emico_attributelanding_page_store` | Per-store fields: all SEO, content, filter, and category fields |
| `emico_attributelanding_overviewpage` | Global overview page data |
| `emico_attributelanding_overviewpage_store` | Per-store overview page data |

`store_id = 0` in the store table is the default/fallback. `LandingPageRepository::getByIdWithStore()` merges these two rows. `ResourceModel\Page::saveLandingPageStoreData()` writes only to the store table; `ResourceModel\Page::save()` writes only to the base table.

### Request flow

1. URL rewrite (`entity_type = landingpage`) routes to `Controller\LandingPage\View`.
2. The controller loads the merged landing page, sets `LandingPageContext` (request-scoped singleton), registers the category in the Magento registry, and delegates filter application to `FilterApplierInterface`.
3. `SeoObserver` (listening on `layout_render_before_*`) reads `LandingPageContext` to set page title, meta tags, and canonical URL.

### Extensible filter application

`FilterApplierInterface` is implemented by `AggregateFilterApplier`, which tries each registered applier in order and stops at the first one that returns `canApplyFilters() === true`. Out of the box only `MagentoFilterApplier` is registered. The companion package `emico/m2-attributelanding-tweakwise` adds a Tweakwise applier via `di.xml`.

Similarly, `FilterHiderInterface` (implemented by `MagentoFilterHider`) is used by `FilterHidePlugin` to hide already-applied filters from the layered navigation block — also replaceable for Tweakwise.

### URL rewrite lifecycle

- On **save** (`emico_attributelanding_page_save_after` / `emico_attributelanding_overviewpage_save_after`): `UrlRewriteGenerateObserver` calls `UrlRewriteService::generateRewrite()`, which removes existing rewrites and re-creates one per store.
- On **delete**: `UrlRewriteRemoveObserver` cleans up the rewrites.
- When the category URL suffix config changes: `CategoryUrlSuffixObserver` bulk-updates all existing landing page rewrites.
- CLI command `emico:attribute-landing:regenerate-rewrites` regenerates all rewrites on demand.

### Crosslink / UrlFinder

When `allow_crosslink` is enabled, `UrlFinder` builds a cached hash-map of `(category + filter set) → landing page URL`. Other modules (e.g. Tweakwise) call `UrlFinder::findUrlByFilters()` to redirect users to a matching landing page when they select the exact filter combination that defines one. The cache key is `attributelanding.lookup.filter_url`; it is invalidated by `InvalidateCacheObserver` on page save.

### Admin UI

Standard Magento UI Component grids and forms under `Content > Attribute Landing` (ACL: `Emico_AttributeLanding::*`). REST API for LandingPage CRUD is registered in `etc/webapi.xml` under `/V1/emico-attributelanding/page`.
