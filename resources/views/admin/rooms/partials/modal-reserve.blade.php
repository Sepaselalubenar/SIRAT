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

            <!-- User Pemesan (Lecturer / TPA / Admin) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dosen/TPA / Pemesan *</label>
                <select name="user_id" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected($user->id === auth()->id())>{{ $user->name }} ({{ $user->role === 'pegawai' ? 'TPA' : ucfirst($user->role) }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Pilih dosen/TPA yang akan menggunakan ruangan tersebut.</p>
            </div>

            <!-- Tipe Reservasi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Reservasi *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-blue-600 bg-blue-50/50" id="admin-tipe-biasa-label">
                        <input type="radio" name="tipe_reservasi" value="biasa" checked class="sr-only" id="admin-radio-tipe-biasa">
                        <span class="text-lg mb-1">⏰</span>
                        <span class="text-xs font-semibold text-gray-800">Jam Spesifik</span>
                        <span class="text-[10px] text-gray-500">Pilih jam tertentu</span>
                    </label>
                    <label class="border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-gray-200" id="admin-tipe-sehari-penuh-label">
                        <input type="radio" name="tipe_reservasi" value="sehari_penuh" class="sr-only" id="admin-radio-tipe-sehari-penuh">
                        <span class="text-lg mb-1">📅</span>
                        <span class="text-xs font-semibold text-gray-800">Sehari Penuh</span>
                        <span class="text-[10px] text-gray-500">Satu/beberapa hari</span>
                    </label>
                </div>
            </div>

            <!-- Jam Spesifik Inputs -->
            <div id="admin-container-biasa" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Reservasi *</label>
                    <input type="date" name="tanggal" id="admin-input-tanggal" required min="{{ now()->toDateString() }}" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

            <!-- Sehari Penuh (Multi-Hari) Inputs -->
            <div id="admin-container-range" class="hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" id="admin-input-tanggal-mulai" min="{{ now()->toDateString() }}" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" id="admin-input-tanggal-selesai" min="{{ now()->toDateString() }}" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <p class="text-xs text-gray-400">Durasi: Sehari penuh (07:00 - 18:30) per hari. Hari Minggu tutup.</p>
            </div>

            <!-- Tujuan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Reservasi *</label>
                <select name="tujuan" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Pilih Tujuan</option>
                    <option value="Sidang">Sidang</option>
                    <option value="Rapat">Rapat</option>
                    <option value="Bimbingan">Bimbingan</option>
                    <option value="Ujian Sidang Tugas Akhir">Ujian Sidang Tugas Akhir</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan *</label>
                <textarea name="keterangan" required rows="3" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Catatan tambahan mengenai reservasi..."></textarea>
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

<script>
    (function() {
        const radioBiasa = document.getElementById('admin-radio-tipe-biasa');
        const radioSehari = document.getElementById('admin-radio-tipe-sehari-penuh');
        const labelBiasa = document.getElementById('admin-tipe-biasa-label');
        const labelSehari = document.getElementById('admin-tipe-sehari-penuh-label');

        const containerBiasa = document.getElementById('admin-container-biasa');
        const containerRange = document.getElementById('admin-container-range');

        const inputTanggal = document.getElementById('admin-input-tanggal');
        const inputJamMulai = document.querySelector('input[name="jam_mulai"]');
        const inputJamSelesai = document.querySelector('input[name="jam_selesai"]');
        const inputTanggalMulai = document.getElementById('admin-input-tanggal-mulai');
        const inputTanggalSelesai = document.getElementById('admin-input-tanggal-selesai');

        function toggleAdminTipe() {
            if (!radioBiasa || !radioSehari) return;
            if (radioBiasa.checked) {
                labelBiasa.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-blue-600 bg-blue-50/50";
                labelSehari.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-gray-200";

                containerBiasa.classList.remove('hidden');
                containerRange.classList.add('hidden');

                inputTanggal.setAttribute('required', 'required');
                inputJamMulai.setAttribute('required', 'required');
                inputJamSelesai.setAttribute('required', 'required');

                inputTanggalMulai.removeAttribute('required');
                inputTanggalSelesai.removeAttribute('required');
            } else {
                labelBiasa.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-gray-200";
                labelSehari.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-blue-600 bg-blue-50/50";

                containerBiasa.classList.add('hidden');
                containerRange.classList.remove('hidden');

                inputTanggal.removeAttribute('required');
                inputJamMulai.removeAttribute('required');
                inputJamSelesai.removeAttribute('required');

                inputTanggalMulai.setAttribute('required', 'required');
                inputTanggalSelesai.setAttribute('required', 'required');
            }
        }

        if (radioBiasa && radioSehari) {
            radioBiasa.addEventListener('change', toggleAdminTipe);
            radioSehari.addEventListener('change', toggleAdminTipe);
            toggleAdminTipe();
        }

        // Handle client-side Sunday validation for admin range and single date inputs
        const form = document.querySelector('#modal-reserve form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (radioSehari.checked) {
                    const tglMulai = inputTanggalMulai.value;
                    const tglSelesai = inputTanggalSelesai.value;
                    if (tglMulai && tglSelesai) {
                        let tempDate = new Date(tglMulai + 'T00:00:00');
                        let endDate = new Date(tglSelesai + 'T00:00:00');
                        let totalDays = 0;
                        let sundayCount = 0;
                        while (tempDate <= endDate) {
                            totalDays++;
                            if (tempDate.getDay() === 0) {
                                sundayCount++;
                            }
                            tempDate.setDate(tempDate.getDate() + 1);
                        }
                        if (sundayCount === totalDays) {
                            alert('Pemesanan ditutup untuk hari Minggu. Silakan sesuaikan rentang tanggal Anda.');
                            e.preventDefault();
                        }
                    }
                } else {
                    const tglVal = inputTanggal.value;
                    if (tglVal) {
                        const tglCheck = new Date(tglVal + 'T00:00:00');
                        if (tglCheck.getDay() === 0) {
                            alert('Pemesanan ditutup untuk hari Minggu.');
                            e.preventDefault();
                        }
                    }
                }
            });
        }
    })();
</script>
