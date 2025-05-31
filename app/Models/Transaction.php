<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Transaction extends Model
// {
  
//     protected $table = 'transaction';
//     protected $fillable = ['name','description', 'user', 'amount'];
    
// }


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction'; // Consider using plural table name (convention)
    
    protected $fillable = [
        'account_id',
        'name',
        'description',
        'amount',
        'transaction_type',
        'is_delete',
        'is_active',
        'user'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_delete' => 'boolean',
        'transaction_date' => 'datetime'
    ];

    // Transaction types
    const TYPE_CASH = 'Cash';
    const TYPE_BANK = 'Bank';
    const TYPE_MOBILE_BANKING = 'Mobile banking';
    const TYPE_CREDIT_CARD = 'Credit Card';

    public static function getTransactionTypes()
    {
        return [
            self::TYPE_CASH => 'Cash',
            self::TYPE_BANK => 'Bank',
            self::TYPE_MOBILE_BANKING => 'Mobile Banking',
            self::TYPE_CREDIT_CARD => 'Credit Card'
        ];
    }

    // Relationships
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeDeposits($query)
    {
        return $query->where('transaction_type', self::TYPE_DEPOSIT);
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('transaction_type', self::TYPE_WITHDRAWAL);
    }

    public function scopeActive($query)
    {
        return $query->where('is_delete', false);
    }

    // Helper methods
    
}
?>