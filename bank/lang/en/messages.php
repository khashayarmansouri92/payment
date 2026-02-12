<?php

return [
    'sheba' => [
        'created' => 'Sheba request created successfully.',
        'updated' => 'Sheba request updated successfully.',
        'confirmed' => 'Request is Confirmed!',
        'canceled' => 'Request is Canceled!',
        'missing_idempotency_key' => 'Idempotency-Key header is required.',
        'note_required_on_cancel' => 'Note is required when canceling a request.',
        'operation_not_successful' => 'The operation was not successful.',
        'max_price_exceeded' => 'The requested amount exceeds the allowed limit.',
        'validation_failed' => 'Validation failed for Sheba create request.',
    ],
    'account' => [
        'invalid_sheba' => 'Sheba format is invalid.',
        'insufficient_balance' => 'Insufficient balance for account :id.',
    ],
    'log' => [
        'sheba_request_created' => 'Sheba request created.',
        'sheba_request_updated' => 'Sheba request updated.',
        'account_debited' => 'Account debited.',
        'account_credited' => 'Account credited.',
        'transaction_withdraw_created' => 'Withdraw transaction created.',
        'transaction_withdraw_completed' => 'Withdraw transaction completed.',
        'transaction_deposit_created' => 'Deposit transaction created.',
        'transaction_revert_created' => 'Revert transaction created.',
        'sheba_audit' => 'Sheba audit.',
        'sheba_state_changed' => 'Sheba request state changed.',
    ],
];

