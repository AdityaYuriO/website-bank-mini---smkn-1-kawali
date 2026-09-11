<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if( session('success') )
        <script>
            alert({{ session('success') }})
        </script>
    @elseif (session('failed'))
        <script>
            alert({{ session('failed') }})
        </script>
    @endif



        <form action="{{ route('datamaster.gtk') }}" method="post">
            @csrf
            <label for=""> Nama Lengkap</label>
            <input type="text" name="nama_lengkap"> <br>
            <label for="">Jabatan</label>
            <select name="jabatan" id="">
                <option value="Guru">Guru</option>
                <option value="TU">TU</option>
            </select> <br>
            <label for=""> NUPTK </label>
            <input type="number" name="nuptk"> <br>
            <label for="">NIS</label>
            <input type="number" name="nip" id=""> <br>
            <label for="">NIK</label>
            <input type="number" name="nik"> <br>
            <label for="">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="">
                <option value="Laki-Laki">Laki Laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
            <br>
            <label for="Tempat Lahir">Tempat Lahir</label>
            <input type="text" name="tempat_lahir"> <br>
            <label for="">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir"> <br>
            <label for="">Agama</label>
            <input type="text" name="agama"> <br>
            <label for="">Kode Pos</label>
            <input type="number" name="kode_pos"> <br>
            <label for="">RT</label>
            <input type="number" name="rt"> <br>
            <label for="">RW</label>
            <input type="number" name="rw"> <br>
            <label for="">Dusun</label>
            <input type="text" name="dusun"> <br>
                    <!-- Provinsi -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Provinsi
                        </label>
                        <select name="provinsi" id="provinsi" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Provinsi</option>
                            @foreach ($provinsi as $prov)
                                <option value="{{ $prov->id }}">
                                    {{ ucwords(strtolower($prov->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kabupaten / Kota -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Kabupaten/Kota
                        </label>
                        <select name="kab_kota" id="kabupaten" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Kabupaten</option>
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Kecamatan
                        </label>
                        <select name="kecamatan" id="kecamatan" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Kecamatan</option>
                        </select>
                    </div>

                    <!-- Kelurahan / Desa -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Desa/Kelurahan
                        </label>
                        <select name="kelurahan" id="desa" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Desa</option>
                        </select>
                    </div>

            <button type="submit">Tambah</button>
        </form>
</body>
</html>


@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {

    // ==========================
    // PROVINSI -> KABUPATEN
    // ==========================
    $('#provinsi').change(function () {
        let id = $(this).val();

        $('#kabupaten').html('<option value="" disabled selected>Pilih Kabupaten</option>');
        $('#kecamatan').html('<option value="" disabled selected>Pilih Kecamatan</option>');
        $('#desa').html('<option value="" disabled selected>Pilih Desa</option>');

        if (!id) return;

        $.ajax({
            url: '/get-kabupaten/' + id,
            type: 'GET',
            success: function(data){
                $.each(data, function(index, item){
                    $('#kabupaten').append(
                        '<option value="'+item.id+'">'+item.name+'</option>'
                    );
                });
            },
            error: function(xhr){
                console.error('Gagal mengambil data kabupaten:', xhr.responseText);
            }
        });
    });

    // ==========================
    // KABUPATEN -> KECAMATAN
    // ==========================
    $('#kabupaten').change(function () {
        let id = $(this).val();

        $('#kecamatan').html('<option value="" disabled selected>Pilih Kecamatan</option>');
        $('#desa').html('<option value="" disabled selected>Pilih Desa</option>');

        if (!id) return;

        $.ajax({
            url: '/get-kecamatan/' + id,
            type: 'GET',
            success: function(data){
                $.each(data, function(index, item){
                    $('#kecamatan').append(
                        '<option value="'+item.id+'">'+item.name+'</option>'
                    );
                });
            },
            error: function(xhr){
                console.error('Gagal mengambil data kecamatan:', xhr.responseText);
            }
        });
    });

    // ==========================
    // KECAMATAN -> DESA
    // ==========================
    $('#kecamatan').change(function () {
        let id = $(this).val();

        $('#desa').html('<option value="" disabled selected>Pilih Desa</option>');

        if (!id) return;

        $.ajax({
            url: '/get-desa/' + id,
            type: 'GET',
            success: function(data){
                $.each(data, function(index, item){
                    $('#desa').append(
                        '<option value="'+item.id+'">'+item.name+'</option>'
                    );
                });
            },
            error: function(xhr){
                console.error('Gagal mengambil data desa:', xhr.responseText);
            }
        });
    });

    // ==========================
    // Form Submit Indicator
    // ==========================
    $('#formMasterSiswa').on('submit', function() {
        let btn = $('#btnSubmit');
        btn.prop('disabled', true);
        btn.html('<i class="ph ph-spinner animate-spin text-lg"></i> Menyimpan...');
    });
});
</script>
