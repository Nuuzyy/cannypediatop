@php
    [$statusLabel, $statusClasses] = match ($transaction->status) {
        'success' => ['Sukses', 'bg-emerald-500 text-slate-950'],
        'failed' => ['Gagal', 'bg-rose-500 text-slate-950'],
        default => ['Pending', 'bg-amber-400 text-slate-950'],
    };
@endphp

<span class="inline-flex min-h-8 items-center rounded-md px-3 text-sm font-bold {{ $statusClasses }}">
    {{ $statusLabel }}
</span>
