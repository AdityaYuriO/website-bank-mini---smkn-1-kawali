<div id="viewEditData" class="fade-in hidden flex-1 mt-4">
    <div class="bg-white rounded-2xl sm:rounded-[24px] shadow-card p-4 sm:p-6 md:p-10 w-full border border-gray-50 overflow-y-auto custom-scrollbar">
        <h3 class="text-[20px] font-bold text-gray-800 mb-8 flex items-center gap-3">
            <div class="w-[6px] h-7 bg-brand-green rounded-full"></div>
            Edit Data Petugas
        </h3>
        <form id="formEditPetugas" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Nama Petugas -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Nama Petugas</label>
                    <input type="text"
                        id="edit_nama"
                        name="name"
                        value="{{ old('name', $petugasEdit->user->name ?? '') }}"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all bg-white shadow-sm">
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Kelas</label>
                    <select name="kelas" id="edit_kelas"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all bg-white shadow-sm">
                        <option value="">Pilih Kelas</option>
                        <option value="X AK 1" {{ old('kelas', $petugasEdit->kelas ?? '') == 'X AK 1' ? 'selected' : '' }}>X AK 1</option>
                        <option value="X AK 2" {{ old('kelas', $petugasEdit->kelas ?? '') == 'X AK 2' ? 'selected' : '' }}>X AK 2</option>
                        <option value="XI AK 1" {{ old('kelas', $petugasEdit->kelas ?? '') == 'XI AK 1' ? 'selected' : '' }}>XI AK 1</option>
                        <option value="XI AK 2" {{ old('kelas', $petugasEdit->kelas ?? '') == 'XI AK 2' ? 'selected' : '' }}>XI AK 2</option>
                        <option value="XII AK 1" {{ old('kelas', $petugasEdit->kelas ?? '') == 'XII AK 1' ? 'selected' : '' }}>XII AK 1</option>
                        <option value="XII AK 2" {{ old('kelas', $petugasEdit->kelas ?? '') == 'XII AK 2' ? 'selected' : '' }}>XII AK 2</option>
                    </select>

                    @error('kelas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Password</label>
                    <div class="relative">
                        <input type="password"
                            id="edit_password"
                            name="password" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full border border-gray-200 rounded-lg pl-4 pr-11 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all bg-white shadow-sm">
                        <button type="button" onclick="togglePasswordVisibility('edit_password', 'icon_edit_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none p-1">
                            <i id="icon_edit_password" class="ph ph-eye text-[20px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Email</label>
                    <input type="email"
                        id="edit_email"
                        name="email"
                        value="{{ old('email', $petugasEdit->user->email ?? '') }}"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all bg-white shadow-sm">
                </div>

                <!-- Role Utama -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Role Utama</label>
                    <select name="role_id" id="edit_role" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none shadow-sm">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Role Kedua -->
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Role Kedua (Opsional)</label>
                    <select name="role_id_2" id="edit_role_2" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none shadow-sm">
                        <option value="">-- Tidak Ada / Kosongkan --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-12">
                <button type="button" onclick="switchView('tabel')" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px]">Kembali</button>
                <button type="submit" class="w-full bg-button-gradient hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px] shadow-lg shadow-green-900/10">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
