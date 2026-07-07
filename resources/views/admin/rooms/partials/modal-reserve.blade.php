<!-- Modal Direct Reserve Room -->
<div id="modal-reserve" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Buat Reservasi Langsung</h3>
            <button type="button" onclick="toggleModal('reserve', false)" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold">&times;</button>
        </div>

        <form action="{{ route('admin.rooms.reserve') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <input type="hidden" name="room_id" id="reserve-room-id">

            <!-- Selected Room -->
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Ruangan Pilihan</label>
                <div id="reserve-room-name" class="text-lg font-bold text-gray-800 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"></div>
            </div>

            <!-- User Pemesan (Lecturer / Admin) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dosen / Pemesan *</label>
                <select name="user_id" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected($user->id === auth()->id())>{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Pilih dosen yang akan menggunakan ruangan tersebut.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Reservasi *</label>
                    <input type="date" name="tanggal" required min="{{ now()->toDateString() }}" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai *</label>
                    <input type="time" name="jam_mulai" required min="07:00" max="18:30" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai *</label>
                    <input type="time" name="jam_selesai" required min="07:00" max="18:30" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Tujuan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Reservasi *</label>
                <select name="tujuan" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Pilih Tujuan</option>
                    <option value="Sidang">Sidang</option>
                    <option value="Meeting">Meeting</option>
                    <option value="Ujian Sidang Tugas Akhir">Ujian Sidang Tugas Akhir</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Catatan tambahan mengenai reservasi..."></textarea>
            </div>

            <div class="pt-6 border-t flex justify-end gap-3">
                <button type="button" onclick="toggleModal('reserve', false)" class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm">
                    Buat Reservasi
                </button>
            </div>
        </form>
    </div>
</div>
