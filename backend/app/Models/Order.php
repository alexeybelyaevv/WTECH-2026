<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_PAID = 'PAID';
    public const STATUS_DELIVERED = 'DELIVERED';
    public const STATUS_CANCELED = 'CANCELED';

    public const PAYMENT_CARD = 'CARD';
    public const PAYMENT_CRYPTO = 'CRYPTO';
    public const PAYMENT_BANK_TRANSFER = 'BANK_TRANSFER';
    public const PAYMENT_CASH_ON_DELIVERY = 'CASH_ON_DELIVERY';

    public const SHIPPING_EMAIL = 'EMAIL';
    public const SHIPPING_PICKUP = 'PICKUP';
    public const SHIPPING_COURIER = 'COURIER';
    public const SHIPPING_ALZABOX = 'ALZABOX';
    public const SHIPPING_POST_OFFICE = 'POST_OFFICE';
    public const SHIPPING_PACKETA = 'PACKETA';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'promo_code_id',
        'status',
        'payment_method',
        'shipping_method',
        'currency',
        'subtotal',
        'discount_total',
        'shipping_total',
        'grand_total',
        'customer_full_name',
        'customer_email',
        'customer_phone',
        'country',
        'city',
        'address',
        'zip_code',
        'notes',
        'placed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'shipping_total' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'placed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<PromoCode, $this>
     */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return list<string>
     */
    public static function paymentMethods(): array
    {
        return [
            self::PAYMENT_CARD,
            self::PAYMENT_CRYPTO,
            self::PAYMENT_BANK_TRANSFER,
            self::PAYMENT_CASH_ON_DELIVERY,
        ];
    }

    /**
     * @return list<string>
     */
    public static function shippingMethods(): array
    {
        return [
            self::SHIPPING_EMAIL,
            self::SHIPPING_PICKUP,
            self::SHIPPING_COURIER,
            self::SHIPPING_ALZABOX,
            self::SHIPPING_POST_OFFICE,
            self::SHIPPING_PACKETA,
        ];
    }

    /**
     * @return array<string, float>
     */
    public static function shippingPrices(): array
    {
        return [
            self::SHIPPING_EMAIL => 0.0,
            self::SHIPPING_PICKUP => 0.0,
            self::SHIPPING_COURIER => 5.0,
            self::SHIPPING_ALZABOX => 3.0,
            self::SHIPPING_POST_OFFICE => 5.0,
            self::SHIPPING_PACKETA => 3.0,
        ];
    }
}
