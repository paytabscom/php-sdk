# PayTabs PHP SDK (v3)

This repository contains the PayTabs PHP SDK. The SDK uses a composition-based design: small `Part` objects are combined into `Payloads`, which together with a `Profile` produce a `Request`. Responses are mapped into typed payloads and classified.

See the architecture guide: [ARCHITECTURE.md](ARCHITECTURE.md)

Diagrams are in: `docs/diagrams/`

Try the quick sample:

```bash
php Samples/IntegrationExample.php
```

This sample only composes and prints the request data (no network call).
