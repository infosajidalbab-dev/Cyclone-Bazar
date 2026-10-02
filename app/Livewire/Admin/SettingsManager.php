<?php

namespace App\Livewire\Admin;

use App\Models\DeliveryZone;
use App\Models\Setting;
use Livewire\Component;

class SettingsManager extends Component
{
    // General Settings
    public string $store_name = 'Cyclone Mart';
    public string $contact_phone = '01700000000';
    public string $support_email = 'support@cyclonemart.com';
    public string $order_prefix = 'CM';

    // Logistics & Delivery
    public float $delivery_charge_dhaka = 60.00;
    public float $delivery_charge_outside = 120.00;
    public int $low_stock_threshold = 5;

    // Payment Flags
    public bool $cod_enabled = true;
    public bool $bkash_enabled = true;

    protected function rules(): array
    {
        return [
            'store_name' => 'required|string|max:100',
            'contact_phone' => ['required', 'string', 'regex:/^(?:\+?880|0)?1[3-9]\d{8}$/'],
            'support_email' => 'required|email|max:150',
            'order_prefix' => 'required|string|max:10',
            'delivery_charge_dhaka' => 'required|numeric|min:0',
            'delivery_charge_outside' => 'required|numeric|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'cod_enabled' => 'boolean',
            'bkash_enabled' => 'boolean',
        ];
    }

    public function mount(): void
    {
        $this->store_name = Setting::get('store_name', 'Cyclone Mart');
        $this->contact_phone = Setting::get('contact_phone', '01712345678');
        $this->support_email = Setting::get('support_email', 'support@cyclonemart.com');
        $this->order_prefix = Setting::get('order_prefix', 'CM');
        $this->low_stock_threshold = (int) Setting::get('low_stock_threshold', 5);
        $this->cod_enabled = (bool) Setting::get('cod_enabled', true);
        $this->bkash_enabled = (bool) Setting::get('bkash_enabled', true);

        // Load delivery charges from zones
        $dhakaZone = DeliveryZone::where('code', 'DHAKA_INSIDE')->first();
        if ($dhakaZone) {
            $this->delivery_charge_dhaka = (float) $dhakaZone->base_charge;
        }

        $outsideZone = DeliveryZone::where('code', 'OUTSIDE_DHAKA')->first();
        if ($outsideZone) {
            $this->delivery_charge_outside = (float) $outsideZone->base_charge;
        }
    }

    public function save(): void
    {
        $this->validate();

        Setting::set('store_name', $this->store_name, 'general', 'string');
        Setting::set('contact_phone', $this->contact_phone, 'general', 'string');
        Setting::set('support_email', $this->support_email, 'general', 'string');
        Setting::set('order_prefix', strtoupper(trim($this->order_prefix)), 'general', 'string');
        Setting::set('low_stock_threshold', $this->low_stock_threshold, 'inventory', 'integer');
        Setting::set('cod_enabled', $this->cod_enabled, 'payment', 'boolean');
        Setting::set('bkash_enabled', $this->bkash_enabled, 'payment', 'boolean');

        // Sync Delivery Zones in MySQL
        DeliveryZone::where('code', 'DHAKA_INSIDE')->update([
            'base_charge' => $this->delivery_charge_dhaka,
            'cod_available' => $this->cod_enabled,
        ]);

        DeliveryZone::where('code', 'OUTSIDE_DHAKA')->update([
            'base_charge' => $this->delivery_charge_outside,
            'cod_available' => $this->cod_enabled,
        ]);

        session()->flash('settings_status', 'স্টোর সেটিংস সফলভাবে আপডেট করা হয়েছে (Store configuration saved).');
    }

    public function render()
    {
        return view('livewire.admin.settings-manager');
    }
}
