# PayTabs SDK
## Architecture Overview

The PayTabs SDK follows a modular and layered architecture design pattern, organized into distinct components that handle specific responsibilities:

```
src/
├── Profile/        # Authentication Layer
├── Request/        # Request Formation
├── Response/       # Response Handling
└── Http/Http.php   # Transport Layer
```

### Architectural Flow

1. **Authentication Layer** (Profile)
    - Manages API credentials

2. **Data Layer** (Builder)
    - Constructs payment payloads
    - Validates data structures
    - Formats request bodies

3. **Integration Layer** (Request)
    - Combines authentication and payload
    - Maps to appropriate endpoints
    - Prepares final API requests

4. **Processing Layer** (Response)
    - Handles API responses
    - Processes webhook notifications
    - Formats return data

5. **Transport Layer** (Http)
    - Executes HTTP requests
    - Maps responses
    - Handles communication errors

This architecture ensures:
- Clear separation of concerns
- Maintainable codebase
- Scalable integration
- Efficient error handling


This project contains the PayTabs SDK, which provides a simple and efficient way to integrate PayTabs payment gateway into your application.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [API Reference](#api-reference)
- [Contributing](#contributing)
- [License](#license)

## Installation

To install the SDK, you can use Composer:

```bash
composer require paytabs/sdk
```

## Usage

### Payment Requests

### Invoices
