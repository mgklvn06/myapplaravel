# M-Pesa Integration TODO

- [ ] Update checkout/index.blade.php to include payment method selection (M-Pesa) and phone number input.
- [ ] Modify CheckoutController::store to initiate STK push after order creation and link to order.
- [ ] Update MpesaController::callback to update order status on successful payment.
- [ ] Check and set MPESA_* env variables in .env.
- [ ] Run php artisan migrate if not done.
- [ ] Test integration: start server, simulate checkout, check STK push on phone, verify callback updates.
