@extends('layouts.admin')

@section('title', 'Manajemen Pengguna (Dosen)')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Manajemen Pengguna</h1>
            <p class="text-gray-500 mt-1 text-sm">Kelola akun dosen yang dapat mengakses sistem reservasi.</p>
        </div>
        <button type="button" onclick="openCreateModal()"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl transition cursor-pointer shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            + Tambah Dosen
        </button>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4 text-green-700">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-4 text-red-700">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIP..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <button type="submit" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition cursor-pointer">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.users.index') }}" class="px-4 py-3 text-sm text-gray-500 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition flex items-center">
                Reset
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Nama</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Email</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">NIP</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Total Reservasi</th>
                        <th class="text-left px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Terdaftar</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->nip)
                                    <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ $user->nip }}</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 text-blue-700 font-semibold text-xs bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                                    {{ $user->reservations_count }} reservasi
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        onclick="openEditModal({{ json_encode($user) }})"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus akun {{ $user->name }}? Semua data reservasinya juga akan dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-gray-400 font-medium">Tidak ada akun dosen ditemukan.</p>
                                @if(request('search'))
                                    <p class="text-gray-300 text-xs mt-1">Coba ubah kata kunci pencarian.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Summary --}}
    <p class="text-xs text-gray-400 text-right">
        Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} dosen terdaftar
    </p>

</div>

{{-- ===== Modal Tambah Dosen ===== --}}
<div id="modal-create" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">Tambah Akun Dosen</h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            {{-- Info notice --}}
            <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-blue-700">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Dosen masuk menggunakan <strong class="font-bold">NIP + Email</strong> tanpa password. Tidak perlu mengatur password.</span>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="dosen@telu.ac.id">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIP</label>
                <input type="text" name="nip" value="{{ old('nip') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono" placeholder="1234567890123456">
                @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()"
                    class="flex-1 border border-gray-300 text-gray-700 rounded-xl py-3 font-semibold hover:bg-gray-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-blue-600 text-white rounded-xl py-3 font-semibold hover:bg-blue-700 transition cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== Modal Edit Dosen ===== --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">Edit Akun Dosen</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer text-2xl font-bold">&times;</button>
        </div>
        <form id="edit-form" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit-name" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="edit-email" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIP</label>
                <input type="text" name="nip" id="edit-nip"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()"
                    class="flex-1 border border-gray-300 text-gray-700 rounded-xl py-3 font-semibold hover:bg-gray-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-blue-600 text-white rounded-xl py-3 font-semibold hover:bg-blue-700 transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modal-create').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeCreateModal() {
        document.getElementById('modal-create').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openEditModal(user) {
        document.getElementById('edit-name').value  = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-nip').value   = user.nip ?? '';
        document.getElementById('edit-form').action = `/admin/users/${user.id}`;
        document.getElementById('modal-edit').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('modal-edit').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close modals on backdrop click
    ['modal-create', 'modal-edit'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });

    // Auto-open create modal if there are validation errors (from failed store)
    @if($errors->any() && old('_token'))
        openCreateModal();
    @endif
</script>

@endsection
