# StockFlow Development Roadmap

This roadmap tracks the planned improvements for turning StockFlow from a university project into a stronger portfolio-ready inventory management system.

## Priority 1 — Critical Fixes

- [x] Fix registration-page navigation
- [x] Prevent product stock from becoming negative
- [x] Validate product quantity, price, purchase cost, and sales quantity
- [x] Show user-friendly messages instead of raw MySQL errors
- [x] Handle attempts to delete products that have sales or purchase history
- [x] Add confirmation prompts before delete operations
- [ ] Fix the broken logo on the login page
- [ ] Make the logout action easier to find

## Priority 2 — Reports and Analytics

- [x] Add date-range filtering
- [x] Add product filtering
- [x] Add category filtering
- [ ] Add supplier filtering
- [ ] Show best-selling products
- [ ] Show highest-revenue products
- [ ] Show most expensive products
- [ ] Show lowest-stock products
- [ ] Show highest purchase-cost products
- [ ] Show estimated profit per product
- [ ] Improve profit calculations so they are not based only on total sales minus total purchases
- [ ] Add product-performance tables
- [ ] Add charts for sales, purchases, and product performance
- [ ] Add report export options

## Priority 3 — Search and Data Management

- [ ] Add product search
- [ ] Add category search
- [ ] Add sorting by price, quantity, name, and category
- [ ] Add pagination for long tables
- [ ] Add empty-state messages
- [ ] Add success and validation alerts
- [ ] Prevent duplicate categories and invalid product data

## Priority 4 — Dashboard Improvements

- [ ] Add today's sales
- [ ] Add this month's sales
- [ ] Add low-stock count
- [ ] Add inventory value
- [ ] Add top-selling product
- [ ] Add recent sales
- [ ] Add recent purchases
- [ ] Add dashboard charts

## Priority 5 — User and Security Improvements

- [ ] Review authentication security
- [ ] Improve session handling
- [ ] Add proper role-based permissions
- [ ] Validate duplicate usernames
- [ ] Add stronger password validation
- [ ] Add logout confirmation if useful

## Priority 6 — User Interface Improvements

- [ ] Improve the login-page design
- [ ] Improve color consistency
- [ ] Improve responsive behavior
- [ ] Improve button and form spacing
- [ ] Add clearer navigation states
- [ ] Improve mobile usability

## Priority 7 — Demo and Portfolio Quality

- [ ] Replace unrealistic demo categories and products
- [ ] Replace unrealistic supplier names
- [x] Clean invalid stock values
- [ ] Add screenshots to the README
- [ ] Add a live demo if hosting becomes available
- [ ] Add release notes
- [ ] Add a project license

## Completed Milestones

- [x] Fix registration workflow
- [x] Improve product validation
- [x] Improve purchase validation
- [x] Improve category validation
- [x] Improve reports with filtering
- [x] Initialize Git repository
- [x] Add `.gitignore`
- [x] Publish repository on GitHub
- [x] Add comprehensive README
- [x] Move Git-tracked project into XAMPP `htdocs`
- [x] Fix registration-page navigation
