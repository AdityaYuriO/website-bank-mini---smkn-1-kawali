<div id="viewTambahData" class="fade-in hidden flex-1 mt-4">
    <div class="bg-white rounded-2xl sm:rounded-[24px] shadow-card p-4 sm:p-6 md:p-10 w-full border border-gray-50 overflow-y-auto custom-scrollbar">
        <h3 class="text-[20px] font-bold text-gray-800 mb-8 flex items-center gap-3">
            <div class="w-[6px] h-7 bg-brand-green rounded-full"></div>
            Tambah Data Petugas
        </h3>
        <form action="{{ route('tambah.petugas.admin') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Input Nama, Kelas, Password, Email tetap seperti biasa -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Nama Petugas</label>
                    <input type="text" name="name" placeholder="Masukkan nama petugas" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue shadow-sm">
                </div>
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Kelas</label>
                    <select name="kelas" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] focus:outline-none focus:border-brand-blue shadow-sm">
                        <option value="">Pilih Kelas</option>
                        <option value="X AK 1">X AK 1</option>
                        <option value="X AK 2">X AK 2</option>
                        <option value="XI AK 1">XI AK 1</option>
                        <option value="XI AK 2">XI AK 2</option>
                        <option value="XII AK 1">XII AK 1</option>
                        <option value="XII AK 2">XII AK 2</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Password</label>
                    <input type="password" name="password" placeholder="********" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue shadow-sm">
                </div>
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Email</label>
                    <input type="email" name="email" placeholder="contoh@gmail.com" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue shadow-sm">
                </div>

                <!-- Role Utama -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Role Utama</label>
                    <select name="role_id" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue shadow-sm">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Role Kedua (Opsional untuk Multi-Role) -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Role Kedua (Opsional)</label>
                    <select name="role_id_2" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue shadow-sm">
                        <option value="">-- Tidak Ada / Kosongkan --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-12">
                <button type="button" onclick="switchView('tabel')" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px]">Kembali</button>
                <button type="submit" class="w-full bg-button-gradient hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px] shadow-lg shadow-green-900/10">Simpan Petugas</button>
            </div>
        </form>
    </div>
</div>
