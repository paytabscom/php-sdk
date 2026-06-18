# Contributing

Thanks for your interest in improving the PayTabs PHP SDK.

## Development Setup

1. Install dependencies:

```bash
composer install
```

2. Run coding style checks (if configured in your environment):

```bash
vendor/bin/php-cs-fixer fix --dry-run --diff
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
