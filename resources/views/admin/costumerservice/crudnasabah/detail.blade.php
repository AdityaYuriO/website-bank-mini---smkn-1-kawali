@php
    $nasabah = $nasabah ?? (object)[
        'nama_nasabah' => 'Muhammad Rizky Pratama',
        'nis_nip' => '212210045',
        'tempat_lahir' => 'Ciamis',
        'tanggal_lahir' => '2006-05-12',
        'jenis_kelamin' => 'Laki-laki',
        'jenis_identitas' => 'Kartu Pelajar',
        'agama' => 'Islam',
        'pendidikan' => 'SMK',
        'jabatan' => 'Siswa',
        'no_hp' => '081234567890',
        'email' => 'rizky@student.smkn1kawali.sch.id',
        'alamat' => 'Jl. Veteran No. 45, RT 01/02',
        'kelurahan' => 'Kawali',
        'kecamatan' => 'Kawali',
        'kab_kota' => 'Ciamis',
        'provinsi' => 'Jawa Barat',
        'kode_pos' => '46253',
        'nama_kontak_darurat' => 'Bambang Pratama',
        'no_hp_kontak_darurat' => '081987654321',
        'hubungan_kontak_darurat' => 'Orang Tua',
        'alamat_kontak_darurat' => 'Jl. Veteran No. 45, RT 01/02, Kawali, Ciamis',
        'pesan' => '',
        'nama_perevisi' => '',
        'jurusan' => (object)['nama_jurusan' => 'PPLG'],
        'rekening' => (object)['id' => '320701892001', 'no_rekening' => '320701892001', 'status_akun' => 'aktif']
    ];
@endphp

<div id="viewDetailData" class="fade-in hidden flex-1 mt-4">
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50">

        <!-- SECTION 1: DATA PRIBADI -->
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                <h3 class="text-[20px] font-bold text-gray-800">Detail Data Nasabah</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                    <input type="text" value="{{ $nasabah->nama_nasabah }}" id="detail_nama" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">NIS/NIP</label>
                    <input type="text" value="{{ $nasabah->nis_nip }}" id="detail_nis" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jurusan</label>
                    <input type="text" value="{{ $nasabah->jurusan->nama_jurusan ?? '-' }}" id="detail_jurusan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tempat Lahir</label>
                    <input type="text" value="{{ $nasabah->tempat_lahir }}" id="detail_tempat_lahir" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tanggal Lahir</label>
                    <input type="text" value="{{ $nasabah->tanggal_lahir }}" id="detail_tanggal_lahir" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Kelamin</label>
                    <input type="text" value="{{ $nasabah->jenis_kelamin }}" id="detail_jenis_kelamin" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Identitas Utama</label>
                    <input type="text" value="{{ $nasabah->jenis_identitas }}" id="detail_identitas" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Agama</label>
                    <input type="text" value="{{ $nasabah->agama }}" id="detail_agama" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Pendidikan</label>
                    <input type="text" value="{{ $nasabah->pendidikan }}" id="detail_pendidikan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jabatan</label>
                    <input type="text" value="{{ $nasabah->jabatan }}" id="detail_jabatan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                    <input type="text" value="{{ $nasabah->no_hp }}" id="detail_telepon" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Email</label>
                    <input type="text" value="{{ $nasabah->email }}" id="detail_email" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                    <textarea id="detail_alamat" class="w-full h-[155px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 resize-none bg-gray-50 focus:outline-none" readonly>{{ $nasabah->alamat }}</textarea>
                </div>
                <div class="col-span-1 flex flex-col gap-5">
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kelurahan</label>
                        <input type="text" value="{{ is_object($nasabah->desa ?? null) ? ($nasabah->desa->name ?? '') : ($nasabah->kelurahan ?? $nasabah->desa ?? '') }}" id="detail_kelurahan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kecamatan</label>
                        <input type="text" value="{{ is_object($nasabah->kecamatan ?? null) ? ($nasabah->kecamatan->name ?? '') : ($nasabah->kecamatan ?? '') }}" id="detail_kecamatan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kab/Kota</label>
                    <input type="text" value="{{ is_object($nasabah->kabupaten ?? null) ? ($nasabah->kabupaten->name ?? '') : (is_object($nasabah->kab_kota ?? null) ? ($nasabah->kab_kota->name ?? '') : ($nasabah->kab_kota ?? $nasabah->kabupaten ?? '')) }}" id="detail_kab_kota" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Provinsi</label>
                    <input type="text" value="{{ is_object($nasabah->provinsi ?? null) ? ($nasabah->provinsi->name ?? '') : ($nasabah->provinsi ?? '') }}" id="detail_provinsi" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kode Pos</label>
                    <input type="text" value="{{ $nasabah->kode_pos }}" id="detail_kode_pos" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
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
                        <input type="text" value="{{ $nasabah->nama_kontak_darurat }}" id="detail_kontak_nama" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                        <input type="text" value="{{ $nasabah->no_hp_kontak_darurat }}" id="detail_kontak_telepon" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Hubungan dengan Pemohon</label>
                        <input type="text" value="{{ $nasabah->hubungan_kontak_darurat }}" id="detail_kontak_hubungan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                    <textarea id="detail_kontak_alamat" class="w-full h-[225px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 resize-none bg-gray-50 focus:outline-none" readonly>{{ $nasabah->alamat_kontak_darurat }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 3: DATA REKENING -->
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                <h3 class="text-[20px] font-bold text-gray-800">Data Rekening</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">No. Rekening</label>
                    <input type="text" value="{{ $nasabah->rekening->no_rekening ?? '' }}" id="detail_rekening" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Status Rekening</label>
                    <input type="text" value="{{ $nasabah->rekening->status_akun ?? '' }}" id="detail_status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50" readonly>
                </div>
            </div>

            <!-- PESAN REVISI -->
            <div id="revisi_section" class="mt-6 hidden">
                <label id="label_perevisi" class="block text-[13px] font-semibold text-gray-500 mb-2">
                    📝 Pesan Revisi
                </label>

                <div id="detail_pesan"
                    class="w-full border border-amber-200 rounded-lg px-4 py-3 text-[14px] text-gray-700 bg-amber-50">
                </div>
            </div>
        </div>

        <!-- BUTTONS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12">
            <div class="hidden sm:block"></div>
            <button type="button" onclick="switchView('tabel')" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px]">Kembali</button>
        </div>
    </div>
</div>
