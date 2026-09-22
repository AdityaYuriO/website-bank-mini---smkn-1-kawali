<div id="viewTambahData" class="fade-in hidden flex-1 mt-4">
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50">
        <form action="{{ route('tambah.nasabah.admin') }}" method="POST" id="nasabahFormTambah">
            @csrf

            <!-- SECTION 1: DATA PRIBADI -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Data Pribadi</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">NIS/NIP</label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('nis_nip') }}" id="nomor" name="nis_nip" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                        <input type="text" required value="{{ old('nama_lengkap') }}" id="nama_lengkap" name="nama_lengkap" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tempat Lahir</label>
                        <input type="text" required value="{{ old('tempat_lahir') }}" id="tempat_lahir" name="tempat_lahir" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tanggal Lahir</label>
                        <input type="date" value="{{ old('tanggal_lahir') }}" id="tanggal_lahir" name="tanggal_lahir" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Agama</label>
                        <select name="agama" id="agama" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Protestan" {{ old('agama') == 'Protestan' ? 'selected' : '' }}>Protestan</option>
                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Khonghucu" {{ old('agama') == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jurusan</label>
                        <select name="jurusan" id="jurusan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Jurusan</option>
                            <option value="1" {{ old('jurusan') == '1' ? 'selected' : '' }}>TKRO</option>
                            <option value="2" {{ old('jurusan') == '2' ? 'selected' : '' }}>TJKT</option>
                            <option value="3" {{ old('jurusan') == '3' ? 'selected' : '' }}>PPLG</option>
                            <option value="4" {{ old('jurusan') == '4' ? 'selected' : '' }}>DPIB</option>
                            <option value="5" {{ old('jurusan') == '5' ? 'selected' : '' }}>MPLB</option>
                            <option value="6" {{ old('jurusan') == '6' ? 'selected' : '' }}>AKL</option>
                            <option value="7" {{ old('jurusan') == '7' ? 'selected' : '' }}>SK</option>
                            <option value="8" {{ old('jurusan') == '8' ? 'selected' : '' }}>TU</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Pendidikan</label>
                        <select name="pendidikan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Pendidikan</option>
                            <option value="SD" {{ old('pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMK" {{ old('pendidikan') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            <option value="SMA" {{ old('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D1" {{ old('pendidikan') == 'D1' ? 'selected' : '' }}>D1</option>
                            <option value="D2" {{ old('pendidikan') == 'D2' ? 'selected' : '' }}>D2</option>
                            <option value="D3" {{ old('pendidikan') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1/D4" {{ old('pendidikan') == 'S1/D4' ? 'selected' : '' }}>S1/D4</option>
                            <option value="S2" {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jabatan</label>
                        <select name="jabatan" id="jabatan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Jabatan</option>
                            <option value="Siswa" {{ old('jabatan') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="Guru" {{ old('jabatan') == 'Guru' ? 'selected' : '' }}>Guru</option>
                            <option value="TU" {{ old('jabatan') == 'TU' ? 'selected' : '' }}>TU</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Identitas Utama</label>
                        <select name="jenis_identitas" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Jenis Identitas</option>
                            <option value="KTP" {{ old('jenis_identitas') == 'KTP' ? 'selected' : '' }}>KTP</option>
                            <option value="Kartu Keluarga" {{ old('jenis_identitas') == 'Kartu Keluarga' ? 'selected' : '' }}>Kartu Keluarga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('no_hp') }}" name="no_hp" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kode Pos</label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('kode_pos') }}" id="kode_pos" name="kode_pos" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Provinsi</label>
                            <select name="provinsi" id="provinsi" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                                <option value="" disabled selected>Pilih Provinsi</option>
                                @foreach ($provinsi as $prov)
                                <option value="{{ $prov->id }}">
                                    {{ ucwords(strtolower($prov->name)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kabupaten/Kota</label>
                            <select name="kab_kota" id="kabupaten" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                                <option value="" disabled selected>Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                                <option value="" disabled selected>Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kelurahan/Desa</label>
                            <select name="kelurahan" id="desa" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                                <option value="" disabled selected>Pilih Desa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Email</label>
                            <input required type="email" value="{{ old('email') }}" name="email" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">
                                Password
                            </label>

                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    required
                                    name="password"
                                    value="{{ old('password') }}"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 pr-10 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors"
                                >

                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
                                >
                                    <!-- Icon Mata -->
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423
                                            7.51 7.36 4.5 12 4.5c4.638 0 8.573
                                            3.007 9.963 7.178.07.207.07.431
                                            0 .639C20.577 16.49 16.64 19.5
                                            12 19.5c-4.638 0-8.577-3.007-9.964-7.178z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <div class="col-span-2 md:col-span-2">
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                        <textarea required name="alamat" class="w-full h-[115px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 resize-none focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DATA PIHAK YANG DAPAT DIHUBUNGI -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Data Pihak yang Dapat Dihubungi</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="flex flex-col gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                            <input required type="text" value="{{ old('nama_kontak_darurat') }}" name="nama_kontak_darurat" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" id="no_hp" value="{{ old('nomor_kontak_darurat') }}" name="nomor_kontak_darurat" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Hubungan dengan Pemohon</label>
                            <input required type="text" value="{{ old('hubungan_kontak_darurat') }}" name="hubungan_kontak_darurat" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                        <textarea required name="alamat_kontak_darurat" class="w-full h-[225px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 resize-none focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">{{ old('alamat_kontak_darurat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12">
                <button type="button" onclick="switchView('tabel')" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px]">Kembali</button>
                <button type="submit" class="w-full bg-button-gradient hover:bg-[#0e8f56] text-white font-bold py-3.5 rounded-xl transition-colors text-[15px]">Kirim</button>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {

// ============================
    // TOAST NOTIFIKASI
    // ============================

    @if(session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif

    @if(session('failed'))
        showToast("{{ session('failed') }}", 'error');
    @endif

    // ============================
    // AUTO ISI DATA SISWA
    // ============================

 $('#nomor').on('change', function () {
    let nomor = $(this).val();

    if (!nomor) return;

    $.ajax({
        url: "{{ url('/data-orang') }}/" + nomor,
        type: 'GET',

        success: function (response) {
            if (response.status) {

                $('#nama_lengkap').val(response.data.nama_lengkap);
                $('#jabatan').val(response.data.jabatan);
                $('#tempat_lahir').val(response.data.tempat_lahir);
                $('#tanggal_lahir').val(response.data.tanggal_lahir);
                $('#jenis_kelamin').val(response.data.jenis_kelamin);
                $('#agama').val(response.data.agama);
                $('#kode_pos').val(response.data.kode_pos);
                $('#no_hp').val(response.data.no_hp);
                $('#jurusan').val(response.data.jurusan);

                showToast(
                    'Data ' + response.jenis + ' berhasil dimuat',
                    'success'
                );

            } else {
                showToast(
                    'Data tidak ditemukan',
                    'error'
                );
            }
        },

        error: function () {
            showToast(
                'Terjadi kesalahan saat mengambil data.',
                'error'
            );
        }
    });
});

    // ============================
    // TITLE CASE
    // ============================

    function toTitleCase(text) {

        if (!text) return '';

        return text
            .toLowerCase()
            .replace(/\b\w/g, function (char) {
                return char.toUpperCase();
            });

    }


    // ============================
    // LOAD DATA WILAYAH
    // ============================

    function loadWilayah(parent, target, url, placeholder, reset = []) {

        $(parent).change(function () {

            let id = $(this).val();

            $(target).html(
                `<option value="" selected disabled>Pilih ${placeholder}</option>`
            );

            reset.forEach(function(item){

                $('#' + item).html(
                    `<option value="" selected disabled>Pilih ${
                        item == 'kecamatan'
                            ? 'Kecamatan'
                            : item == 'desa'
                            ? 'Desa'
                            : 'Data'
                    }</option>`
                );

            });

            if (!id) return;

            $.ajax({

                url: url + '/' + id,
                type: 'GET',

                success: function (data) {

                    $.each(data, function (index, item) {

                        $(target).append(
                            `<option value="${item.id}">
                                ${toTitleCase(item.name)}
                            </option>`
                        );

                    });

                },

                error: function () {

                    showToast('Gagal memuat data wilayah.', 'error');

                }

            });

        });

    }


    // ============================
    // PROVINSI
    // ============================

    loadWilayah(
        '#provinsi',
        '#kabupaten',
        '/get-kabupaten',
        'Kabupaten',
        ['kecamatan', 'desa']
    );


    // ============================
    // KABUPATEN
    // ============================

    loadWilayah(
        '#kabupaten',
        '#kecamatan',
        '/get-kecamatan',
        'Kecamatan',
        ['desa']
    );


    // ============================
    // KECAMATAN
    // ============================

    loadWilayah(
        '#kecamatan',
        '#desa',
        '/get-desa',
        'Desa'
    );

        // ============================
    // TOGGLE PASSWORD
    // ============================

    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword) {

        togglePassword.addEventListener('click', function () {

            const isPassword = password.type === 'password';

            password.type = isPassword ? 'text' : 'password';

            eyeIcon.innerHTML = isPassword
                ? `
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.98 8.223A10.477 10.477 0 001.934 12
                        C3.226 16.338 7.244 19.5 12 19.5
                        c1.658 0 3.236-.383 4.64-1.065M6.228
                        6.228A10.45 10.45 0 0112 4.5
                        c4.756 0 8.773 3.162 10.065
                        7.5a10.523 10.523 0 01-4.293
                        5.774M6.228 6.228L3 3m3.228
                        3.228l3.65 3.65m7.894
                        7.894L21 21m-3.228-3.228
                        l-3.65-3.65m0 0a3 3 0
                        10-4.243-4.243m4.243
                        4.243L9.88 9.88"/>
                `
                : `
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423
                        7.51 7.36 4.5 12 4.5c4.638 0
                        8.573 3.007 9.963 7.178.07.207.07.431
                        0 .639C20.577 16.49 16.64
                        19.5 12 19.5c-4.638
                        0-8.577-3.007-9.964-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0
                        3 3 0 016 0z"/>
                `;

        });

    }

});
</script>
