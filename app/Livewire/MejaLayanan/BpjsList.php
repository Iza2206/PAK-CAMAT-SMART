<?php

namespace App\Livewire\MejaLayanan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BpjsSubmission;

class BpjsList extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $data = BpjsSubmission::where('nama_pemohon', 'like', "%{$this->search}%")
                    ->orderByDesc('created_at')
                    ->paginate(10);

        return view('livewire.meja-layanan.bpjs-list', [
            'data' => $data,
        ]);
    }
}
