# Release Checklist

Use this checklist before publishing a new public SDK release.

## Pre-Release

- [ ] Confirm intended version bump follows semver policy
- [ ] Update `composer.json` version if required by your release process
- [ ] Update `CHANGELOG.md` with release notes
- [ ] Verify licensing and governance docs are current
- [ ] Verify security reporting path points to PayTabs Bug Bounty Program
- [ ] Confirm deployment logging env vars are configured (`PAYTABS_LOG_PATH`, `PAYTABS_LOG_BROWSER`)

## Validation

- [ ] `composer validate --strict`
- [ ] `composer lint`
- [ ] `composer test`
- [ ] Run live tests only when required: `PAYTABS_RUN_LIVE_TESTS=1 composer test`
- [ ] Manual smoke check from README quick start in clean environment
- [ ] If lint reports broad baseline formatting across legacy files, track it as a dedicated style migration PR and avoid mixing it with release-critical fixes.

## Docs and Samples

- [ ] README reflects current recommended integration path
- [ ] Webhook docs promote `BrowserAsPost` as default
- [ ] Samples are sanitized (no secrets/PAN/CVV)

## Publish

- [ ] Create and push release tag
- [ ] Publish release notes
- [ ] Publish package artifact (if applicable)

## Post-Release

- [ ] Verify CI status for release tag
- [ ] Monitor issue tracker for regressions
- [ ] Prepare hotfix plan if critical issue appears
