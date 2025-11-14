<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $attributes = [
        'role' => 'customer',
    ];

    public function isAdmin(): bool
{
    return strtolower($this->role ?? '') === 'admin';
}

public function isCustomer(): bool
{
    return strtolower($this->role ?? '') === 'customer';
}

/**
 * Relationship: user's orders
 */
public function orders()
{
    return $this->hasMany(Order::class);
}

/**
 * Return recently viewed products for this user.
 * Strategy:
 *  - If a `product_user_views` table exists, read the most recent product_ids for this user.
 *  - Else if there's a session key `recently_viewed` (array of ids), use that.
 *  - Else fall back to latest active products.
 *
 * Returns an Eloquent Collection of Product models (preserves recency order).
 */
public function recentlyViewedProducts(int $limit = 6)
{
    // 1) Database-backed views table (preferred)
    if (Schema::hasTable('product_user_views')) {
        $ids = DB::table('product_user_views')
            ->where('user_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->pluck('product_id')
            ->unique()
            ->take($limit)
            ->toArray();

        if (count($ids)) {
            // load products and preserve order of ids
            $products = Product::whereIn('id', $ids)->get()->keyBy('id');
            $ordered = collect($ids)->map(fn($id) => $products->get($id))->filter();
            return $ordered;
        }
    }

    // 2) Session fallback (if front-end stored recent ids in session)
    if (function_exists('session')) {
        $sessionIds = session('recently_viewed', []);
        if (is_array($sessionIds) && count($sessionIds)) {
            $ids = array_slice(array_reverse($sessionIds), 0, $limit);
            $products = Product::whereIn('id', $ids)->get()->keyBy('id');
            $ordered = collect($ids)->map(fn($id) => $products->get($id))->filter();
            return $ordered;
        }
    }

    // 3) Fallback: latest active products
    return Product::where('is_active', true)->latest()->take($limit)->get();
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
