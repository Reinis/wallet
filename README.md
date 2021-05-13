# Wallet

### Assumptions

- Transactions happen between the wallets only in the system
- It is possible to transfer to an arbitrarily named account outside the system but there are no validity checks
- The balance of a wallet can turn negative
- All transactions are in EUR currency

## Design

- Setup
    - Laravel Sail for setting up Docker container
    - Laravel Breeze with Inertia.js for app scaffolding
    - MariaDB SQL database
    - Min PHP version 7.4
- Backend
    - Use money pattern ([MoneyPHP][moneyphp] through `cknow/laravel-money`)
- Routing
    - Inertia.js bridge frontend and backend, route server requests to avoid page reloads
    - CSRF tokens are managed under the hood by axios
- Frontend
    - Vue.js for frontend
    - Tailwindcss for styling
    - Laravel Mix (Webpack) for asset processing
        - BrowserSync for live-reloading during development
    - Laravel Dusk for browser testing using Selenium

[moneyphp]: http://moneyphp.org/

### Tests

- Unit tests (none)
- Feature tests (incomplete) - covers authentication
- Browser tests (complete) - covers user stories

### Ideas for further development

- Improve user authentication by introducing multi-factor authentication using Laravel Fortify
- Store money in database as integers instead of strings
    - There is a pull request [1] for that, or it could be implemented from scratch
- Add multiple currency support [2]

[1]: https://github.com/cknow/laravel-money/pull/74

[2]: https://moneyphp.org/en/stable/features/currency-conversion.html

## Demo

![Wallet demo](wallet-demo.gif)
