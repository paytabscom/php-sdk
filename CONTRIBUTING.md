# Contributing

Thanks for your interest in improving the PayTabs PHP SDK.

## Development Setup

The SDK runs on PHP `^8.1`, because it is embedded in e-commerce platform plugins
that must work on older merchant hosting.

**Contributing requires PHP 8.2 or newer.** PHPUnit 11 dropped 8.1, so
`composer install` cannot resolve the dev dependencies there. The constraint
applies to the toolchain, not to the SDK.

CI runs the suite on 8.2, 8.3 and 8.4, plus a `--prefer-lowest` job on 8.2.

A separate `runtime-php81` job runs on **8.1** and executes only what a consumer
runs in production: `composer validate`, then a runtime-only install and
`composer audit`, with the dev dependencies dropped first. That job is what keeps
the 8.1 floor verified, so keep `src/` free of 8.2+ syntax and functions.

1. Install dependencies:

```bash
composer install
```

2. Check coding style (PHP CS Fixer, PER-CS):

```bash
composer lint
```

To apply fixes automatically:

```bash
composer format
```

3. Run tests:

```bash
composer test
```

By default, test runs skip live gateway integration checks.
To run live integration tests explicitly:

```bash
PAYTABS_RUN_LIVE_TESTS=1 composer test
```

## Pull Request Guidelines

- Keep changes focused and small.
- Include tests for behavior changes when possible.
- Update docs and samples when public behavior changes.
- Avoid introducing breaking changes without discussion.
- Never commit secrets, API keys, tokens, PAN, or CVV.

## Collaboration Workflow

- Use the repository issue templates for bug reports and feature requests.
- Use the pull request template checklist before requesting review.
- General product/support requests should use [SUPPORT.md](SUPPORT.md) channels.

## Commit and Release Notes

- Use clear commit messages describing intent and scope.
- Add notable behavior changes to CHANGELOG.md.

## Code of Conduct

By participating, you agree to maintain a respectful and constructive collaboration style.
