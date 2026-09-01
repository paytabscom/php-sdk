# Release Checklist

Use this checklist before publishing a new public SDK release.

## Pre-Release

- [ ] Confirm intended version bump follows semver policy
- [ ] Update `Paytabs::VERSION` in `src/Paytabs.php` and the `SDK version` -if needed- header in `docs/usage/*.md` (composer.json intentionally has no `version` field — releases are tag-driven)
- [ ] Update `CHANGELOG.md` with release notes
- [ ] Verify licensing and governance docs are current
- [ ] Verify security reporting path points to PayTabs Bug Bounty Program
- [ ] Confirm logger mode and destination are configured as intended for the target environment

## Validation

- [ ] `composer validate --strict`
- [ ] `composer lint`
- [ ] `composer test`
- [ ] `composer audit` reports no advisories
- [ ] Coverage has not dropped since the previous release (reported by the CI coverage step)
- [ ] CI is green on every job: `lint`, `runtime-php81`, and the whole `test` matrix
- [ ] Run live tests only when required: `PAYTABS_RUN_LIVE_TESTS=1 composer test`
- [ ] Manual smoke check from README quick start in clean environment

## Docs and Samples

- [ ] README reflects current recommended integration path
- [ ] Webhook docs promote `BrowserAsPost` as default
- [ ] Samples are sanitized (no secrets/PAN/CVV)
- [ ] Every callback sample still fails closed on an invalid signature
- [ ] `Samples/.env.sample` covers any new configuration key

## Publish

- [ ] Create and push release tag
- [ ] Publish release notes
- [ ] Publish package artifact (if applicable)

## Post-Release

- [ ] Verify CI status for release tag
- [ ] Monitor issue tracker for regressions
- [ ] Prepare hotfix plan if critical issue appears
