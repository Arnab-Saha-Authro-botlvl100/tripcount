<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type',
        'account_name',
        'current_balance',
        'account_number',
        'bank_name',
        'branch',
        'routing_no',
        'mobile_account_number',
        'card_number',
        'card_csv',
        'card_expiry',
        'is_active',
        'is_delete',
        'user_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'current_balance' => 'decimal:2'
    ];

    // Account types constants
    const TYPE_CASH = 'Cash';
    const TYPE_BANK = 'Bank';
    const TYPE_MOBILE_BANKING = 'Mobile banking';
    const TYPE_CREDIT_CARD = 'Credit Card';

    public static function getAccountTypes()
    {
        return [
            self::TYPE_CASH => 'Cash',
            self::TYPE_BANK => 'Bank Account',
            self::TYPE_MOBILE_BANKING => 'Mobile Banking',
            self::TYPE_CREDIT_CARD => 'Credit Card'
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_delete', false);
    }

    // Helper methods
    public function isBankAccount()
    {
        return $this->account_type === self::TYPE_BANK;
    }

    public function isMobileBanking()
    {
        return $this->account_type === self::TYPE_MOBILE_BANKING;
    }

    public function isCreditCard()
    {
        return $this->account_type === self::TYPE_CREDIT_CARD;
    }

    public function isCash()
    {
        return $this->account_type === self::TYPE_CASH;
    }
}