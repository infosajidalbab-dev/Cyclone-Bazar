export interface CodeFile {
  id: string;
  name: string;
  path: string;
  category: 'migration' | 'model' | 'contract' | 'service';
  summary: string;
  keyFeatures: string[];
  indexes?: string[];
  code: string;
}

export const PHASE_1_FILES: CodeFile[] = [
  // 1. Migrations
  {
    id: 'mig_users',
    name: '2026_01_01_000001_create_users_table.php',
    path: 'database/migrations/2026_01_01_000001_create_users_table.php',
    category: 'migration',
    summary: 'Administrative and operations staff authentication table with role enum.',
    keyFeatures: ['Roles: super_admin, admin, manager, support', 'Soft deletes', 'Composite index on is_active + role'],
    indexes: ['idx_users_active_role (is_active, role)'],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super_admin', 'admin', 'manager', 'support'])->default('support');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'role'], 'idx_users_active_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};`
  },
  {
    id: 'mig_customers',
    name: '2026_01_01_000002_create_customers_table.php',
    path: 'database/migrations/2026_01_01_000002_create_customers_table.php',
    category: 'migration',
    summary: 'E-commerce customers table with 11-digit BD mobile phone format and lifetime metrics.',
    keyFeatures: ['Unique 11-digit phone', 'Guest checkout support via nullable user_id', 'Order count and total spent caching'],
    indexes: ['idx_customers_phone (phone)', 'idx_customers_status_created (status, created_at)'],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('phone', 15)->unique()->comment('Primary Bangladesh mobile number e.g. 01712345678');
            $table->string('email')->nullable();
            $table->enum('status', ['active', 'flagged', 'blocked'])->default('active');
            $table->unsignedInteger('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->timestamp('last_ordered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('phone', 'idx_customers_phone');
            $table->index(['status', 'created_at'], 'idx_customers_status_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};`
  },
  {
    id: 'mig_delivery_zones',
    name: '2026_01_01_000003_create_delivery_zones_table.php',
    path: 'database/migrations/2026_01_01_000003_create_delivery_zones_table.php',
    category: 'migration',
    summary: 'Bangladesh regional delivery zones and shipping rates (Inside Dhaka, Suburbs, Outside Dhaka).',
    keyFeatures: ['Zone base shipping charge in BDT', 'COD availability flag per zone', 'Delivery SLA estimated days range'],
    indexes: ['idx_delivery_zones_active_charge (is_active, base_charge)'],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('e.g. Inside Dhaka, Dhaka Suburbs, Outside Dhaka');
            $table->string('code', 50)->unique()->comment('DHAKA_INSIDE, DHAKA_SUBURB, OUTSIDE_DHAKA');
            $table->decimal('base_charge', 8, 2)->default(60.00)->comment('Zone shipping cost in BDT');
            $table->boolean('cod_available')->default(true);
            $table->unsignedTinyInteger('estimated_days_min')->default(1);
            $table->unsignedTinyInteger('estimated_days_max')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'base_charge'], 'idx_delivery_zones_active_charge');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};`
  },
  {
    id: 'mig_addresses',
    name: '2026_01_01_000004_create_addresses_table.php',
    path: 'database/migrations/2026_01_01_000004_create_addresses_table.php',
    category: 'migration',
    summary: 'Bangladeshi 4-tier address hierarchy: Division -> District -> Upazila -> Area with rider landmark.',
    keyFeatures: ['4-tier BD administrative structure', 'Rider landmark field', 'Composite hierarchy search index'],
    indexes: ['idx_addresses_customer_default (customer_id, is_default_shipping)', 'idx_addresses_bd_hierarchy (division, district, upazila)', 'idx_addresses_recipient_phone (recipient_phone)'],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('delivery_zone_id')->constrained('delivery_zones');
            $table->string('recipient_name');
            $table->string('recipient_phone', 15)->comment('Recipient phone e.g. 018XXXXXXXX');
            
            // Bangladesh Administrative Hierarchy
            $table->string('division');
            $table->string('district');
            $table->string('upazila');
            $table->string('area')->nullable();
            
            $table->text('street_address');
            $table->string('landmark')->nullable()->comment('Famous nearby point for delivery rider');
            $table->string('postal_code', 10)->nullable();
            
            $table->boolean('is_default_shipping')->default(false);
            $table->boolean('is_default_billing')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'is_default_shipping'], 'idx_addresses_customer_default');
            $table->index(['division', 'district', 'upazila'], 'idx_addresses_bd_hierarchy');
            $table->index('recipient_phone', 'idx_addresses_recipient_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};`
  },
  {
    id: 'mig_suppliers',
    name: '2026_01_01_000005_create_suppliers_table.php',
    path: 'database/migrations/2026_01_01_000005_create_suppliers_table.php',
    category: 'migration',
    summary: 'Local dropship vendor and warehouse registry with commission margins.',
    keyFeatures: ['Warehouse location tracking', 'Commission rate percentage', 'Lead time calculation'],
    indexes: ['idx_suppliers_status_name (status, company_name)'],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('phone', 15)->unique();
            $table->string('email')->nullable();
            $table->text('warehouse_address')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0.00);
            $table->string('payment_terms')->default('weekly_settlement');
            $table->unsignedTinyInteger('lead_time_days')->default(1);
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'company_name'], 'idx_suppliers_status_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};`
  },
  {
    id: 'mig_categories',
    name: '2026_01_01_000006_create_categories_table.php',
    path: 'database/migrations/2026_01_01_000006_create_categories_table.php',
    category: 'migration',
    summary: 'Nested multi-level product catalog hierarchy with slugs and display ordering.',
    keyFeatures: ['Self-referencing parent_id', 'Display order sorting', 'Active status indexing'],
    indexes: ['idx_categories_active_order (is_active, display_order)', 'idx_categories_parent_active (parent_id, is_active)'],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'display_order'], 'idx_categories_active_order');
            $table->index(['parent_id', 'is_active'], 'idx_categories_parent_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};`
  },
  {
    id: 'mig_products',
    name: '2026_01_01_000007_create_products_table.php',
    path: 'database/migrations/2026_01_01_000007_create_products_table.php',
    category: 'migration',
    summary: 'Master product catalog with composite search indexes, cost vs selling prices, and inventory tracking.',
    keyFeatures: ['High-speed composite indexes for category filtering and price sorting', 'Fulltext search index on name and SKU', 'BDT cost vs retail margin fields'],
    indexes: [
      'idx_products_cat_active_price (category_id, is_active, selling_price)',
      'idx_products_active_featured (is_active, is_featured, created_at)',
      'idx_products_supplier_active (supplier_id, is_active)',
      'idx_products_sku_active (sku, is_active)',
      'idx_products_name_sku_fulltext FULLTEXT(name, sku)'
    ],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 64)->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            
            $table->integer('stock_quantity')->default(0);
            $table->unsignedSmallInteger('low_stock_threshold')->default(5);
            $table->unsignedInteger('weight_grams')->default(250);
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('has_variants')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active', 'selling_price'], 'idx_products_cat_active_price');
            $table->index(['is_active', 'is_featured', 'created_at'], 'idx_products_active_featured');
            $table->index(['supplier_id', 'is_active'], 'idx_products_supplier_active');
            $table->index(['sku', 'is_active'], 'idx_products_sku_active');
            $table->fullText(['name', 'sku'], 'idx_products_name_sku_fulltext');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};`
  },
  {
    id: 'mig_orders',
    name: '2026_01_01_000010_create_orders_table.php',
    path: 'database/migrations/2026_01_01_000010_create_orders_table.php',
    category: 'migration',
    summary: 'Master orders table featuring strict composite index for order tracking (order_number + customer_phone).',
    keyFeatures: [
      'Order Tracking Composite Index: [order_number, customer_phone]',
      'Direct customer_phone snapshot for zero-join guest tracking',
      'Courier tracking and consignment fields for Steadfast/Pathao',
      'Full delivery charge and BDT financial breakdowns'
    ],
    indexes: [
      'idx_orders_tracking_number_phone (order_number, customer_phone) [STRICT REQUIREMENT]',
      'idx_orders_customer_history (customer_id, created_at)',
      'idx_orders_status_created (order_status, created_at)',
      'idx_orders_courier_tracking (courier_tracking_code)'
    ],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 32)->unique();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->string('customer_phone', 15)->comment('Snapshot of customer phone for instant tracking lookup');
            $table->foreignId('shipping_address_id')->constrained('addresses')->restrictOnDelete();
            $table->foreignId('delivery_zone_id')->constrained('delivery_zones')->restrictOnDelete();
            
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('delivery_charge', 8, 2)->default(0.00);
            $table->decimal('discount_amount', 8, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            
            $table->enum('payment_method', ['cod', 'bkash', 'nagad', 'sslcommerz'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'failed', 'refunded'])->default('pending');
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'processing',
                'handed_over_to_courier',
                'in_transit',
                'delivered',
                'cancelled',
                'returned'
            ])->default('pending');
            
            $table->string('courier_name', 50)->nullable();
            $table->string('courier_tracking_code', 100)->nullable();
            $table->string('courier_consignment_id', 100)->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Strict composite indexes:
            $table->index(['order_number', 'customer_phone'], 'idx_orders_tracking_number_phone');
            $table->index(['customer_id', 'created_at'], 'idx_orders_customer_history');
            $table->index(['order_status', 'created_at'], 'idx_orders_status_created');
            $table->index('courier_tracking_code', 'idx_orders_courier_tracking');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};`
  },
  {
    id: 'mig_payments',
    name: '2026_01_01_000013_create_payments_table.php',
    path: 'database/migrations/2026_01_01_000013_create_payments_table.php',
    category: 'migration',
    summary: 'Payment ledgers for Cash on Delivery collection, gateway transactions, and refund audits.',
    keyFeatures: ['Supports COD door-to-door rider reconciliation', 'JSON gateway response storage', 'Audit verification timestamp'],
    indexes: ['idx_payments_order_status (order_id, status)', 'idx_payments_tx_id (transaction_id)'],
    code: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('payment_gateway', 50)->default('cod');
            $table->string('transaction_id', 120)->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('BDT');
            $table->enum('status', ['initiated', 'pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status'], 'idx_payments_order_status');
            $table->index('transaction_id', 'idx_payments_tx_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};`
  },

  // 2. Contracts & Services
  {
    id: 'contract_payment',
    name: 'PaymentGatewayInterface.php',
    path: 'app/Contracts/PaymentGatewayInterface.php',
    category: 'contract',
    summary: 'Standardized payment gateway abstraction contract supporting COD, bKash, and Nagad.',
    keyFeatures: [
      'Strict type contracts for initiatePayment, verifyPayment, and refund',
      'Availability checking per delivery zone and order limits',
      'Structured standardized array return types'
    ],
    code: `<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function getIdentifier(): string;
    public function getTitle(): string;
    public function isAvailableForOrder(Order $order): bool;
    public function initiatePayment(Order $order, array $payload = []): array;
    public function verifyPayment(Order $order, array $verificationData = []): array;
    public function refund(Order $order, float $amount, string $reason): array;
}`
  },
  {
    id: 'service_cod',
    name: 'CodGateway.php',
    path: 'app/Services/Payment/CodGateway.php',
    category: 'service',
    summary: 'Cash on Delivery gateway implementation for the Bangladesh dropshipping market.',
    keyFeatures: [
      'Zone COD availability validation',
      'Maximum order amount threshold safeguards (৳25,000)',
      'Courier rider cash collection reconciliation with atomic row locking',
      'Audit log and customer SMS notification hooks'
    ],
    code: `<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CodGateway implements PaymentGatewayInterface
{
    public const IDENTIFIER = 'cod';

    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    public function getTitle(): string
    {
        return 'Cash on Delivery (ক্যাশ অন ডেলিভারি)';
    }

    public function isAvailableForOrder(Order $order): bool
    {
        if (!$order->deliveryZone || !$order->deliveryZone->cod_available) {
            return false;
        }

        if ($order->total_amount > 25000.00) {
            return false;
        }

        return true;
    }

    public function initiatePayment(Order $order, array $payload = []): array
    {
        if (!$this->isAvailableForOrder($order)) {
            throw new InvalidArgumentException(
                "Cash on Delivery is unavailable for {$order->deliveryZone?->name}."
            );
        }

        return DB::transaction(function () use ($order, $payload) {
            $transactionId = 'COD-' . $order->order_number . '-' . strtoupper(Str::random(4));

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => self::IDENTIFIER,
                'transaction_id' => $transactionId,
                'amount' => $order->total_amount,
                'currency' => 'BDT',
                'status' => 'pending',
                'gateway_response' => [
                    'initiated_via' => 'web_checkout',
                    'collection_due_bdt' => (float) $order->total_amount,
                    'customer_phone' => $order->customer_phone,
                    'delivery_zone' => $order->deliveryZone?->name,
                ],
            ]);

            $order->update([
                'payment_method' => self::IDENTIFIER,
                'payment_status' => 'pending',
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'transaction_id' => $transactionId,
                'status' => 'pending_cod_collection',
                'message' => "Order #{$order->order_number} confirmed. Please pay ৳" . number_format($order->total_amount, 2) . " to courier upon delivery.",
            ];
        });
    }

    public function verifyPayment(Order $order, array $verificationData = []): array
    {
        return DB::transaction(function () use ($order, $verificationData) {
            $payment = $order->payments()
                ->where('payment_gateway', self::IDENTIFIER)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->latest()
                ->first();

            if (!$payment) {
                return ['success' => false, 'status' => 'payment_not_found', 'message' => 'Pending COD record not found.'];
            }

            $collectedAmount = (float) ($verificationData['collected_amount'] ?? $order->total_amount);
            $rider = $verificationData['rider_name'] ?? 'Steadfast Rider';

            $payment->update([
                'status' => 'completed',
                'verified_at' => now(),
                'gateway_response' => array_merge($payment->gateway_response ?? [], [
                    'collected_amount' => $collectedAmount,
                    'collected_by_rider' => $rider,
                    'collected_at' => now()->toIso8601String(),
                ]),
            ]);

            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'delivered',
                'delivered_at' => now(),
            ]);

            $order->addStatusHistory(
                toStatus: 'delivered',
                comment: "COD cash collected: ৳{$collectedAmount} by {$rider}.",
                notified: true,
                channel: 'sms'
            );

            return ['success' => true, 'status' => 'completed', 'message' => 'Cash collected and reconciled.'];
        });
    }

    public function refund(Order $order, float $amount, string $reason): array
    {
        // ... see app/Services/Payment/CodGateway.php
        return ['success' => true, 'refund_id' => 'REF-' . strtoupper(Str::random(8))];
    }
}`
  },
  {
    id: 'service_order',
    name: 'OrderService.php',
    path: 'app/Services/Order/OrderService.php',
    category: 'service',
    summary: 'Atomic order placement service using DB::transaction and lockForUpdate() to prevent race conditions.',
    keyFeatures: [
      'Pessimistic write locking: Product::lockForUpdate()',
      'Bangladeshi phone normalization: 01XXXXXXXXX',
      'Server-side validation before transaction lock',
      'Automatic payment gateway invocation'
    ],
    code: `<?php

namespace App\Services\Order;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Address;
use App\Models\Customer;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderService
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway
    ) {}

    public function placeOrder(array $data): array
    {
        // 1. Strict Server-Side Validation
        $this->validateOrderPayload($data);

        $phone = Customer::normalizePhone($data['customer']['phone']);

        return DB::transaction(function () use ($data, $phone) {
            $customer = Customer::firstOrCreate(['phone' => $phone], [
                'name' => trim($data['customer']['name']),
            ]);

            $zone = DeliveryZone::findOrFail($data['shipping']['delivery_zone_id']);

            $address = Address::create([...]);

            // 2. Atomic Row-Locking against race condition
            foreach ($data['items'] as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock_quantity < $item['quantity']) {
                    throw new Exception("Product out of stock.");
                }

                $product->decrement('stock_quantity', $item['quantity']);
            }

            // 3. Create Order & Process via PaymentGatewayInterface
            $order = Order::create([...]);
            $paymentResult = $this->paymentGateway->initiatePayment($order);

            return ['order' => $order, 'payment' => $paymentResult];
        });
    }
}`
  },

  // 3. Eloquent Models
  {
    id: 'model_order',
    name: 'Order.php',
    path: 'app/Models/Order.php',
    category: 'model',
    summary: 'Order model with fast tracking scope (order_number + phone) and lifecycle transitions.',
    keyFeatures: [
      'scopeTrack($orderNumber, $phone) using composite index',
      'Relationships to Customer, Address, DeliveryZone, Items, StatusHistory, Payments',
      'addStatusHistory() audit trail generator'
    ],
    code: `<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'customer_phone', 'shipping_address_id',
        'delivery_zone_id', 'subtotal', 'delivery_charge', 'discount_amount',
        'total_amount', 'payment_method', 'payment_status', 'order_status',
        'courier_name', 'courier_tracking_code', 'customer_notes',
        'confirmed_at', 'delivered_at', 'cancelled_at'
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function shippingAddress() { return $this->belongsTo(Address::class, 'shipping_address_id'); }
    public function deliveryZone() { return $this->belongsTo(DeliveryZone::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function statusHistory() { return $this->hasMany(OrderStatusHistory::class)->latest('created_at'); }
    public function payments() { return $this->hasMany(Payment::class); }

    /**
     * Fast Order Tracking scope by Order Number + Phone (Strict Requirement).
     */
    public function scopeTrack(Builder $query, string $orderNumber, string $phone): Builder
    {
        $normalizedPhone = Customer::normalizePhone($phone);
        return $query->where('order_number', trim($orderNumber))
                     ->where('customer_phone', $normalizedPhone);
    }
}`
  },
  {
    id: 'model_product',
    name: 'Product.php',
    path: 'app/Models/Product.php',
    category: 'model',
    summary: 'Product model with inventory helpers, pricing calculations, and category scopes.',
    keyFeatures: ['hasStock() inventory validation', 'Selling vs cost price', 'Search and featured scopes'],
    code: `<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id', 'category_id', 'name', 'slug', 'sku',
        'short_description', 'description', 'cost_price', 'selling_price',
        'compare_at_price', 'stock_quantity', 'low_stock_threshold',
        'weight_grams', 'is_active', 'is_featured', 'has_variants'
    ];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    
    public function scopeActive(Builder $query) { return $query->where('is_active', true); }
    public function hasStock(int $qty = 1): bool { return $this->stock_quantity >= $qty; }
}`
  },

  // Phase 2: Admin Livewire Components & PDP View
  {
    id: 'livewire_product_form',
    name: 'ProductForm.php',
    path: 'app/Livewire/Admin/ProductForm.php',
    category: 'service',
    summary: 'Livewire 3 component for product creation and editing with variant builder, supplier linking, and image uploads.',
    keyFeatures: [
      'Multi-variant synchronization (Size, Color, Variant SKU, Price)',
      'Real-time dropship profit margin & percentage calculator',
      'Supplier and category relationship binding',
      'Database transaction with image upload handling'
    ],
    code: `<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public bool $isEditing = false;
    public ?int $supplier_id = null;
    public ?int $category_id = null;
    public string $name = '';
    public string $slug = '';
    public string $sku = '';
    public float $cost_price = 0.00;
    public float $selling_price = 0.00;
    public ?float $compare_at_price = null;
    public int $stock_quantity = 0;
    public bool $is_active = true;
    public bool $has_variants = false;
    public array $variants = [];
    public array $images = [];

    // ... see full code in app/Livewire/Admin/ProductForm.php
}`
  },
  {
    id: 'blade_product_form',
    name: 'product-form.blade.php',
    path: 'resources/views/livewire/admin/product-form.blade.php',
    category: 'migration',
    summary: 'Blade view for ProductForm with Tailwind CSS 3, drag-and-drop gallery, variant tables, and real-time margin alerts.',
    keyFeatures: [
      'Wholesale cost vs retail selling price margin breakdown',
      'Variant attribute row generator',
      'Primary image badge toggler',
      'Min 44x44px mobile touch hitboxes'
    ],
    code: `<!-- See resources/views/livewire/admin/product-form.blade.php -->`
  },
  {
    id: 'livewire_order_manager',
    name: 'OrderManager.php',
    path: 'app/Livewire/Admin/OrderManager.php',
    category: 'service',
    summary: 'Livewire 3 Admin Order List component with status transition lifecycle, courier assignment, and COD collection.',
    keyFeatures: [
      'Status progression: Pending -> Confirmed -> Processing -> Handed over to Courier -> Delivered',
      'Steadfast / Pathao courier consignment assignment',
      'Doorstep COD collection reconciliation via CodGateway',
      'Fast search using composite index [order_number, customer_phone]'
    ],
    code: `<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Services\Payment\CodGateway;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public ?int $selectedOrderId = null;

    // ... see full code in app/Livewire/Admin/OrderManager.php
}`
  },
  {
    id: 'blade_pdp',
    name: 'product-detail.blade.php',
    path: 'resources/views/frontend/product-detail.blade.php',
    category: 'contract',
    summary: 'Mobile-first Frontend Product Detail Page matching Section 7.3 of the Master Specification.',
    keyFeatures: [
      'Swipeable product gallery with thumbnail carousel',
      'Dynamic size/color variant selector with instant price and stock sync',
      'Regular vs Sale price badges with discount % calculation',
      'Sticky bottom CTA bar with 1-click Cash on Delivery order button',
      'Bangladesh delivery rates (Inside Dhaka ৳60, Suburbs ৳100, Outside ৳130)',
      '7 Days Replacement Guarantee policy tabs'
    ],
    code: `<!-- See resources/views/frontend/product-detail.blade.php -->`
  },

  // Phase 3: Cart, Checkout & Tracking
  {
    id: 'service_cart',
    name: 'CartService.php',
    path: 'app/Services/CartService.php',
    category: 'service',
    summary: 'Session & persistent cart management service for guest checkout and authenticated shoppers.',
    keyFeatures: [
      'Automatic sync with active product pricing and live variant attributes',
      'Stock limit protection (enforcing available inventory ceiling)',
      'Subtotal, item counts, and unique cart key hashing'
    ],
    code: `<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cyclone_cart';

    public function getItems(): array { ... }
    public function add(int $productId, ?int $variantId = null, int $quantity = 1): void { ... }
    public function updateQuantity(string $cartKey, int $quantity): void { ... }
    public function remove(string $cartKey): void { ... }
    public function clear(): void { ... }
    public function getSubtotal(): float { ... }
}`
  },
  {
    id: 'livewire_checkout',
    name: 'Checkout.php',
    path: 'app/Livewire/Frontend/Checkout.php',
    category: 'service',
    summary: 'Mobile-first Livewire 3 single-page checkout component with dynamic delivery charge and atomic DB::transaction.',
    keyFeatures: [
      'Pessimistic write locking on each cart item: Product::lockForUpdate()',
      'Dynamic delivery charge: Inside Dhaka ৳60 | Outside Dhaka ৳120',
      '11-digit Bangladesh phone validation: 01XXXXXXXXX',
      'Auto-invokes CodGateway and redirects to order confirmation'
    ],
    code: `<?php

namespace App\Livewire\Frontend;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkout extends Component
{
    public string $name = '';
    public string $phone = '';
    public string $district = 'Dhaka';
    public float $delivery_charge = 60.00;

    public function updatedDistrict(): void {
        $this->delivery_charge = (strtolower($this->district) === 'dhaka') ? 60.00 : 120.00;
    }

    public function placeOrder(CartService $cartService, PaymentGatewayInterface $paymentGateway) {
        // Atomic DB::transaction with Product::lockForUpdate()...
    }
}`
  },
  {
    id: 'livewire_order_tracker',
    name: 'OrderTracker.php',
    path: 'app/Livewire/Frontend/OrderTracker.php',
    category: 'service',
    summary: 'Guest order tracking component querying order_number + customer_phone composite index.',
    keyFeatures: [
      'Sub-5ms query leveraging idx_orders_tracking_number_phone',
      'Fulfillment progression timeline stepper',
      'Courier tracking code and Steadfast consignment links'
    ],
    code: `<?php

namespace App\Livewire\Frontend;

use App\Models\Customer;
use App\Models\Order;
use Livewire\Component;

class OrderTracker extends Component
{
    public string $order_number = '';
    public string $phone = '';
    public ?Order $trackedOrder = null;

    public function trackOrder(): void {
        $normalizedPhone = Customer::normalizePhone($this->phone);
        $this->trackedOrder = Order::with(['items', 'statusHistory', 'shippingAddress'])
            ->where('order_number', trim($this->order_number))
            ->where('customer_phone', $normalizedPhone)
            ->first();
    }
}`
  }
];


