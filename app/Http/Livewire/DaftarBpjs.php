<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Bpjs;

class DaftarBpjs extends Component
{
    public $filterStatus = 'semua';
    public $previewUrl = null;

    protected $listeners = ['closeModal'];

    public function closeModal()
    {
        $this->previewUrl = null;
    }

    public function openModal($url)
    {
        $this->previewUrl = asset('storage/' . $url);
    }

    public function render()
    {
        $query = Bpjs::query();

        if ($this->filterStatus !== 'semua') {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.daftar-bpjs', [
            'data' => $query->latest()->get(),
        ]);
    }
}
?>