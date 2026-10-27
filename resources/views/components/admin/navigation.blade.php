@props(['preview' => false])
@php
    $items = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'symbol' => '▦'],
        ['label' => 'Buku', 'route' => 'admin.books.index', 'pattern' => 'admin.books.*', 'symbol' => '▤'],
        ['label' => 'Kategori', 'route' => 'admin.categories.index', 'pattern' => 'admin.categories.*', 'symbol' => '▥'],
        ['label' => 'Anggota', 'route' => 'admin.members.index', 'pattern' => 'admin.members.*', 'symbol' => '♙'],
        ['label' => 'Peminjaman', 'route' => 'admin.loans.index', 'pattern' => 'admin.loans.*', 'symbol' => '⇄'],
    ];
@endphp
<nav {{ $attributes }} aria-label="Navigasi admin">
    <ul class="space-y-1">
        @foreach ($items as $item)
            @php
                $available = \Illuminate\Support\Facades\Route::has($item['route']);
                $active = request()->routeIs($item['pattern']) || ($preview && $item['route'] === 'admin.dashboard');
            @endphp
            <li>
                @if ($available)
                    <a href="{{ $preview && $item['route'] === 'admin.dashboard' ? route('design-system.admin') : route($item['route']) }}" @if ($active) aria-current="page" @endif @class(['flex min-h-11 items-center gap-3 rounded-md px-3 text-sm font-medium transition-colors', 'bg-brand-100 text-brand-700' => $active, 'text-muted hover:bg-surface hover:text-ink' => ! $active])>
                        <span aria-hidden="true" class="w-5 text-center text-lg">{{ $item['symbol'] }}</span>{{ $item['label'] }}
                    </a>
                @else
                    <span aria-disabled="true" class="flex min-h-11 items-center gap-3 rounded-md px-3 text-sm text-slate-400"><span aria-hidden="true" class="w-5 text-center text-lg">{{ $item['symbol'] }}</span>{{ $item['label'] }}<span class="ml-auto text-[10px]">Belum tersedia</span></span>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
