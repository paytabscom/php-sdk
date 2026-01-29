# Todo List

## 📋 Payment Request Parameters

- [x] `user_defined` - Object of `udf[0-9]`
- [x] `card_discounts` - Array of objects [`discount_cards`, `discount_title`, `discount_amount/discount_percent`]
- [x] `token_info`
- [x] `donation`
- [x] `invoice` object
- [x] `customer_ref` - String
- [ ] BIN based card approval (PT-5217)

## 🔨 Builders (Payload)

### Own Form
- [x] `card_details` - Object with [`pan`, `cvv`, `expiry_year`, `expiry_month`]

### Managed Form
- [x] `payment_token` - String

### Apple Pay
- [ ] `apple_pay_token` - Object

### Samsung Pay
- [ ] `samsung_pay_token` - Object

## 📝 Requests

- [x] Invoice Cancel
- [x] Invoice SMS Send
- [ ] Invoice Search
- [x] Invoice Mark as Read
- [ ] valU Inquiry
- [ ] Sadad Inquiry
- [ ] PayLink

## 🎁 Other Features

- [x] Discount Patterns
- [x] Callback & Return

## 🏗️ PHP Scaffolding

- [x] Endpoint
- [x] Part
- [ ] Builder
- [ ] Request
- [ ] Response
- [ ] Response Payload
- [ ] Logger
- [ ] PaymentMethod

---

## ⚠️ API Issues & Bugs

### Parameter Inconsistencies
- [ ] `card_filter` is string while `card_discount` is array ?? similar functions
- [ ] `Sadad` payment request includes `amount` similar to `cart_amount` - invoice object should be similar
- [ ] Request/response params inconsistent: `trace`, `trace_code`, `ipn_trace`

### Response Issues
- [ ] **PT-6027**: Wrong invoice number returns 500 instead of proper error code (other APIs return 400 on error or 200 with error message)

### Feature Issues
- [ ] `return_using_get` not supported in invoice URLs
- [ ] **PT-6028**: Add `discount_id` to card_discounts and return it in response
- [ ] `invoice_ref` missing from add new invoice UI
- [ ] **PT-6034**: Payment channel not visible in own form requests
- [ ] Payment methods: PT2 accepts invalid codes in payment page but rejects request when invoice object present

### Recurring Payments Issues
- [ ] Customer details not taken from request if `class=recurring` (but taken if `class=ecomtoken`)
- [ ] Invoice object not taken from request if `class=recurring` (but taken if `class=ecomtoken`)
- [ ] Shipping details ignored
- [ ] Card filter ignored
- [ ] **PT-6032**: Card discounts worked in ecomToken but has UI bug
- [ ] **PT-6029**: Recurring fixed amount fixes discounted amount, not original amount
- [ ] **PT-6030**: If class=ecomtoken and donation mode active, any amount can be entered
- [ ] UDF not working if class=recurring (works if class=ecomtoken)
- [x] **PT-6035**: Enhanced tokenise `date` format differs from system formats

### Other Issues
- [ ] Alt currency `alt_currency` not visible with `card_discounts`
- [ ] **PT-6033**: Managed form `payment_token` can generate multiple payments
- [ ] Invoice mark as paid must return payment method code not title

## 🔜 Later

- [ ] Payment methods: Available in HPP & Invoice, NOT in Own & Managed
