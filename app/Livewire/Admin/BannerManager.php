<?php

namespace App\Livewire\Admin;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithFileUploads;

class BannerManager extends Component
{
    use WithFileUploads;

    public ?int $bannerId = null;
    public string $title = '';
    public string $subtitle = '';
    public string $target_url = '';
    public string $position = 'hero';
    public int $display_order = 0;
    public bool $is_active = true;
    public $image = null;
    public ?string $existingImagePath = null;

    public bool $isModalOpen = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:255',
            'target_url' => 'nullable|string|max:255',
            'position' => 'required|in:hero,middle,footer',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'image' => $this->bannerId ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ];
    }

    public function openCreateModal(): void
    {
        $this->reset(['bannerId', 'title', 'subtitle', 'target_url', 'position', 'display_order', 'is_active', 'image', 'existingImagePath']);
        $this->is_active = true;
        $this->position = 'hero';
        $this->display_order = Banner::count();
        $this->isModalOpen = true;
    }

    public function openEditModal(int $id): void
    {
        $banner = Banner::findOrFail($id);
        $this->bannerId = $banner->id;
        $this->title = $banner->title;
        $this->subtitle = $banner->subtitle ?? '';
        $this->target_url = $banner->target_url ?? '';
        $this->position = $banner->position;
        $this->display_order = $banner->display_order;
        $this->is_active = (bool) $banner->is_active;
        $this->existingImagePath = $banner->image_path;
        $this->image = null;
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    public function toggleActive(int $id): void
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
        session()->flash('banner_status', 'ব্যানার স্ট্যাটাস আপডেট করা হয়েছে।');
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = $this->existingImagePath;
        if ($this->image) {
            $path = $this->image->store('banners', 'public');
            $imagePath = '/storage/' . $path;
        }

        Banner::updateOrCreate(
            ['id' => $this->bannerId],
            [
                'title' => trim($this->title),
                'subtitle' => $this->subtitle ? trim($this->subtitle) : null,
                'target_url' => $this->target_url ? trim($this->target_url) : null,
                'position' => $this->position,
                'display_order' => $this->display_order,
                'is_active' => $this->is_active,
                'image_path' => $imagePath,
            ]
        );

        $this->isModalOpen = false;
        session()->flash('banner_status', 'ব্যানার সফলভাবে সংরক্ষণ করা হয়েছে (Banner saved successfully).');
    }

    public function deleteBanner(int $id): void
    {
        Banner::findOrFail($id)->delete();
        session()->flash('banner_status', 'ব্যানার মুছে ফেলা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.admin.banner-manager', [
            'banners' => Banner::orderBy('position')->orderBy('display_order')->get(),
        ]);
    }
}
