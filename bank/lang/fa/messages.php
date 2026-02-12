<?php

return [
    'sheba' => [
        'created' => 'درخواست شبا با موفقیت ثبت شد.',
        'updated' => 'درخواست شبا با موفقیت به‌روزرسانی شد.',
        'confirmed' => 'درخواست تایید شد!',
        'canceled' => 'درخواست لغو شد!',
        'missing_idempotency_key' => 'هدر Idempotency-Key الزامی است.',
        'note_required_on_cancel' => 'در هنگام لغو درخواست، درج توضیحات الزامی است.',
        'operation_not_successful' => 'عملیات با موفقیت انجام نشد.',
        'max_price_exceeded' => 'مبلغ درخواستی از سقف مجاز بیشتر است.',
        'validation_failed' => 'اعتبارسنجی درخواست ایجاد شبا ناموفق بود.',
    ],
    'account' => [
        'invalid_sheba' => 'فرمت شماره شبا نامعتبر است.',
        'insufficient_balance' => 'موجودی حساب :id کافی نیست.',
    ],
    'log' => [
        'sheba_request_created' => 'درخواست شبا ایجاد شد.',
        'sheba_request_updated' => 'درخواست شبا به‌روزرسانی شد.',
        'account_debited' => 'از حساب برداشت شد.',
        'account_credited' => 'به حساب واریز شد.',
        'transaction_withdraw_created' => 'تراکنش برداشت ایجاد شد.',
        'transaction_withdraw_completed' => 'تراکنش برداشت تکمیل شد.',
        'transaction_deposit_created' => 'تراکنش واریز ایجاد شد.',
        'transaction_revert_created' => 'تراکنش برگشت ایجاد شد.',
        'sheba_audit' => 'لاگ ممیزی شبا.',
        'sheba_state_changed' => 'وضعیت درخواست شبا تغییر کرد.',
    ],
];

