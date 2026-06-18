# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project aims to follow Semantic Versioning.

## [Unreleased]

### Added

- Public release governance docs: LICENSE, SECURITY policy, CONTRIBUTING guide, and changelog baseline.
- GitHub issue routing config and repository support policy documentation.

### Changed

- Live gateway integration test execution is now opt-in via `PAYTABS_RUN_LIVE_TESTS=1`.
- Added release guidance for handling large legacy formatting baselines via separate style-only PRs.

### Security

- Webhook documentation emphasizes fail-closed signature validation.
- Vulnerability reporting guidance now explicitly routes to the PayTabs Bug Bounty Program.
- Added explicit bug bounty reporting URL: https://ai.paytabs.com/en/paytabs-bug-bounty/
