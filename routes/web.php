<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Bukti_tfController;
use App\Http\Controllers\siswaController;
use App\Http\Controllers\nasabahController;
use App\Http\Controllers\tellerController;
use App\Http\Controllers\superVisorController;
use App\Http\Controllers\csController;
use App\Http\Controllers\supervisor\DataPetugasController;
use App\Http\Controllers\rekeningController;
use App\Http\Controllers\alamatController;
use App\Http\Controllers\landingPageController;
use App\Http\Controllers\adminController;
use App\Models\User;
use App\Models\VerifikasiLogin;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Auth\lupaPasswordController;
use App\Models\Rekening;

//halaman utama
Route::get('/', [landingPageController::class, 'index'])->name('/');

// Logic Tf Luar
Route::post('/Bukti_tf_transfer_luar', [Bukti_tfController::class, 'transfer_luar'])->name('bukti_tf.transfer_luar');

// nasabah
Route::middleware(['role:nasabah'])->group(function () {

    Route::get('/nasabah/dashboard', [nasabahController::class, 'index'])->name('nasabah.dashboard');
    Route::get('/nasabah/transfer', [nasabahController::class, 'transfer'])->name('nasabah.transfer');
    Route::get('/cek-rekening/{id}', [nasabahController::class, 'cekRekening']);
    Route::post('/transferProses', [nasabahController::class, 'transferLogic'])->name('transfer.proses');
});
//teller
Route::middleware(['role:teller'])->group(function () {

    Route::get('/teller/dashboard', [tellerController::class, 'index'])->name('teller.dashboard');
    //setoran
    Route::get('/teller/setoran', [tellerController::class, 'setoran'])->name('teller.setoran');
    Route::post('/teller/setoran/store', [tellerController::class, 'storeSetoran'])
        ->name('setoran.store');
    Route::put('/setoran/{id}', [tellerController::class, 'updateSetoran'])
        ->name('setoran.update');
    Route::delete('/setoran/{id}', [tellerController::class, 'destroySetoran'])
        ->name('setoran.destroy');
    Route::get('/setoran/struk/{id}', [tellerController::class, 'cetakStruk'])
        ->name('setoran.struk');
    Route::get('/setoran/export/{filter}', [TellerController::class, 'exportSetoran'])
        ->name('setoran.export');
    Route::get('/setoran/export-custom', [TellerController::class, 'exportSetoranCustom'])
        ->name('setoran.export.custom');

    //penarikan
    Route::get('/teller/penarikan', [tellerController::class, 'penarikan'])->name('teller.penarikan');
    Route::post('/penarikan/store', [tellerController::class, 'storePenarikan'])->name('penarikan.store');
    Route::put('/penarikan/update/{id}', [tellerController::class, 'updatePenarikan'])->name('penarikan.update');
    Route::delete('/penarikan/delete/{id}', [tellerController::class, 'destroyPenarikan'])->name('penarikan.delete');
    Route::get('/penarikan/struk/{id}', [tellerController::class, 'cetakStrukPenarikan'])->name('penarikan.struk');
    Route::get('/penarikan/export/{filter}', [tellerController::class, 'exportPenarikan'])->name('penarikan.export');
    Route::get('/penarikan/export/custom', [tellerController::class, 'exportPenarikanCustom'])->name('penarikan.export.custom');

    //transfer
    Route::get('/teller/transfer', [tellerController::class, 'transfer'])->name('teller.transfer');
    Route::post('/transfer/store', [tellerController::class, 'storeTransfer'])->name('transfer.store');
    Route::put('/transfer/update/{id}', [tellerController::class, 'updateTransfer'])->name('transfer.update');
    Route::delete('/transfer/delete/{id}', [tellerController::class, 'destroyTransfer'])->name('transfer.delete');
    Route::get('/transfer/struk/{id}', [TellerController::class, 'cetakStrukTransfer'])->name('transfer.struk');
    Route::get('/transfer/export/{filter}', [tellerController::class, 'exportTransfer'])->name('transfer.export');
    Route::get('/transfer/export-custom', [tellerController::class, 'exportTransferCustom'])->name('transfer.export.custom');

    // Route History Nasabah
    Route::get('/teller/history-nasabah', [tellerController::class, 'historyNasabah'])->name('teller.history_nasabah');
    // Route Cetak Buku Tabungan per Akun
    Route::get('/teller/cetak-buku/{id_rekening}', [App\Http\Controllers\tellerController::class, 'cetakBuku'])->name('teller.cetak_buku');
    Route::get('/teller/cetak-biodata/{id_rekening}', [tellerController::class, 'cetakBiodataBuku'])->name('teller.cetak_biodata');
    Route::get('/teller/cetak-koran/{id_rekening}', [tellerController::class, 'cetakKoran'])->name('teller.cetak_koran');

    //cari nama si norek
    Route::get('/cari-rekening/{norek}', [tellerController::class, 'cariRekening'])->name('transfer.cari_rekening');
    Route::get('/search-rekening', [tellerController::class, 'searchRekening'])->name('teller.search_rekening');
});




//customer service
Route::middleware(['role:customerservice'])->group(function () {

    Route::get('/data-orang/{nomor}', [siswaController::class, 'getData']);

    Route::get('/customerservice/dashboard', [csController::class, 'index'])->name('cs.dashboard');
    Route::get('/customerservice/keloladata', [rekeningController::class, 'keloladata'])->name('costumerservice.keloladata');
    Route::post('/customer/tambah', [rekeningController::class, 'store'])->name('tambah.rekening');
    Route::get('/customer/detail/{id}', [csController::class, 'detail'])->name('detail.nasabah.cs');
    Route::get('/customerservice/edit/{id}', [rekeningController::class, 'edit'])->name('edit.nasabah');
    Route::put('/customerservice/update/{id}', [rekeningController::class, 'update'])->name('update.nasabah');
    Route::delete('/customerservice/hapus/{id}', [rekeningController::class, 'destroy'])->name('hapus.nasabah');
    Route::post('/customer/import', [rekeningController::class, 'import'])->name('import.nasabah');
    Route::get('/customer/import/nasabah', [rekeningController::class, 'halamanImport'])->name('halaman.import');
    Route::get('costumer/print/{id}', [rekeningController::class, 'print'])->name('print');
    Route::get('/template/nasabah/excel', [csController::class, 'templateImport'])->name('template.nasabah');
    Route::get('/nasabah/download-template', [csController::class, 'downloadTemplate'])->name('download.template');
});

//ROLE SUPERVISOR
Route::middleware(['role:supervisor'])->group(function () {
    Route::get('/supervisor/dashboard', [superVisorController::class, 'index'])->name('supervisor.dashboard');

    Route::get('/supervisor/datanasabah', [superVisorController::class, 'nasabah'])->name('supervisor.datanasabah');

    Route::get('/supervisor/verifikasi', [superVisorController::class, 'transfer'])->name('supervisor.verifikasi');


    Route::get('/supervisor/revisi/{id}', [superVisorController::class, 'halamanRevisi'])->name('halaman.revisi');
    Route::put('/supervisor/revisi/{id}', [superVisorController::class, 'revisi'])->name('proses.revisi');
    Route::get('/supervisor/dataNasabah/{id}', [superVisorController::class, 'detailNasabah'])->name('detail.nasabah');
    Route::get('/supervisor/detail/rekening/{id}', [superVisorController::class, 'detail'])->name('detail.rekening.super');
    Route::delete('/supervisor/hapus/{id}', [superVisorController::class, 'destroy'])->name('hapus.nasabah.super');
    Route::post('/supervisor/aktif/{id}', [superVisorController::class, 'aktif'])->name('rekening.aktif');
    Route::get('/supervisor/verifikasi/rekening/', [superVisorController::class, 'verifikasiNasabah'])->name('supervisor.verifikasi.registrasi');

    // data petugas
    Route::get('/supervisor/datapetugas', [DataPetugasController::class, 'index'])->name('supervisor.datapetugas');
    Route::post('/datapetugas/store', [DataPetugasController::class, 'store'])->name('datapetugas.store');
    Route::put('/datapetugas/update/{id}', [DataPetugasController::class, 'update'])->name('datapetugas.update');
    Route::delete('/datapetugas/delete/{id}', [DataPetugasController::class, 'destroy'])->name('datapetugas.destroy');
    Route::get('/datapetugas/download-template', [DataPetugasController::class, 'downloadTemplate'])->name('datapetugas.download-template');
    Route::post('/datapetugas/import', [DataPetugasController::class, 'importExcel'])->name('datapetugas.import');

    // biaya transaksi
    Route::get('/supervisor/biayatransaksi', [superVisorController::class, 'biayatransaksi'])->name('supervisor.biayatransaksi');
    Route::get('/supervisor/biaya-transaksi', [superVisorController::class, 'biayatransaksi'])->name('supervisor.biayatransaksi');
    Route::post('/supervisor/biaya-transaksi/update', [superVisorController::class, 'updateBiayaTransaksi'])->name('supervisor.biayatransaksi.update');

    Route::get('/supervisor/saldo-minimum', [superVisorController::class, 'saldoMinimum'])->name('supervisor.saldominimum');
    Route::POST('/supervisor/saldo-minimum/update', [superVisorController::class, 'saldoMinimumUpdate'])->name('supervisor.saldominimumUpdate');

    //View Verifikasi Tf
    Route::get('/supervisor/verifikasi', [superVisorController::class, 'verifikasiTFF'])->name('supervisor.verifikasi');
    Route::get('/admin/produk/search', [superVisorController::class, 'searchData'])->name('supervisor.searchData');

    // Export Data Tf
    Route::get('/supervisor/export-transfer', [superVisorController::class, 'exportExcel'])->name('supervisor.exportTransfer');

    // logika verifikasi Tf
    Route::patch('/supervisor/verifikasi/status{id}', [superVisorController::class, 'verifikasiTf'])->name('supervisor.verifikasiTf');

    //Verifikasi Login
    Route::get('/supervisor/verifikasi-login', [superVisorController::class, 'verifikasiLogin'])->name('supervisor.verifikasi.login');
    Route::post('/supervisor/verifikasi-login/{id}/setujui', [superVisorController::class, 'setujuiLogin'])->name('supervisor.verifikasi.login.setujui');
    Route::post('/supervisor/verifikasi-login/{id}/tolak', [superVisorController::class, 'tolakLogin'])->name('supervisor.verifikasi.login.tolak');
    Route::delete('/supervisor/verifikasi/login/destroy-all', [SupervisorController::class, 'destroyAllLogin'])->name('supervisor.verifikasi.login.destroyAll');

    //print
    Route::get('supervisor/print/{id}', [superVisorController::class, 'print'])->name('print.super');

    //data master siswa
    Route::get('supervisor/halaman/datamaster/siswa', [superVisorController::class, 'halamanDataMaster'])->name('halaman.datamaster.siswa');
    Route::post('supervisor/datamaster/siswa', [superVisorController::class, 'dataMaster'])->name('datamaster.siswa');

    //data master GTK
    Route::get('/supervisor/halaman/datamaster/gtk', [superVisorController::class, 'halamanMasterGTK'])->name('halaman.datamaster.gtk');
    Route::post('/supervisor/datamaster', [superVisorController::class, 'dataMasterGTK'])->name('datamaster.gtk');
});
/// logika login na

//Halaman Login
Route::get('/login', [loginController::class, 'index'])->name('login');

//proses login
Route::post('/login', [loginController::class, 'login'])->name('proses.login');

//logout
Route::post('/logout', [loginController::class, 'logout'])->name('logout');

Route::get('/verifikasi-login', function () {
    return view('auth.verifikasi');
})->name('auth.verifikasi');
Route::get('/lupa-password', [lupaPasswordController::class, 'index'])
    ->name('password.request');

Route::post('/lupa-password', [lupaPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [lupaPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [lupaPasswordController::class, 'resetPassword'])
    ->name('password.update');


Route::get('/cek-verifikasi-login/{id}', function ($id) {
    $verifikasi = App\Models\VerifikasiLogin::findOrFail($id);

    if ($verifikasi->status === 'disetujui') {
        $user = App\Models\User::find(session('user_id_verifikasi'));
        Auth::login($user);

        // Ambil role yang diminta saat login dari session
        $roleName = session('role_verifikasi');
        session(['active_role' => $roleName]); // Set session active_role untuk Middleware

        return response()->json([
            'status' => 'approved',
            'role' => $roleName
        ]);
    }

    return response()->json([
        'status' => $verifikasi->status
    ]);
});

Route::get('/cek-rekening/{id}', [Bukti_tfController::class, 'cekRekening']);
/// alamat
Route::get('/get-kabupaten/{id}', [alamatController::class, 'getKabupaten']);
Route::get('/get-kecamatan/{id}', [alamatController::class, 'getKecamatan']);
Route::get('/get-desa/{id}', [alamatController::class, 'getDesa']);

// ================= ROUTE PROTOTYPE ROLE ADMIN (100% PURE FRONTEND) =================
Route::prefix('admin')->group(function () {
    Route::redirect('/', '/admin/supervisor/dashboard');

    // Supervisor
    Route::get('/supervisor/dashboard', fn() => view('admin.supervisor.dashboard'))->name('admin.supervisor.dashboard');
    Route::get('/supervisor/datapetugas', fn() => view('admin.supervisor.datapetugas'))->name('admin.supervisor.datapetugas');
    Route::get('/supervisor/datanasabah', fn() => view('admin.supervisor.datanasabah'))->name('admin.supervisor.datanasabah');
    Route::get('/supervisor/biayatransaksi', fn() => view('admin.supervisor.biayatransaksi'))->name('admin.supervisor.biayatransaksi');
    Route::get('/supervisor/saldominimum', fn() => view('admin.supervisor.saldoMinimum'))->name('admin.supervisor.saldominimum');
    Route::get('/supervisor/saldo-minimum', fn() => view('admin.supervisor.saldoMinimum'));
    Route::get('/supervisor/datamaster/siswa', fn() => view('admin.supervisor.crud_datanasabah.datamasterSiswa'))->name('admin.supervisor.datamaster.siswa');
    Route::get('/supervisor/datamaster/gtk', fn() => view('admin.supervisor.crud_datanasabah.datamasterGTK'))->name('admin.supervisor.datamaster.gtk');
    Route::get('/supervisor/detail/nasabah', fn() => view('admin.supervisor.crud_datanasabah.detail'))->name('admin.supervisor.detail.nasabah');
    Route::get('/supervisor/print/nasabah', fn() => view('admin.supervisor.crud_datanasabah.print'))->name('admin.supervisor.print.nasabah');

    // Supervisor Verifikasi
    Route::get('/supervisor/verifikasi/login', fn() => view('admin.supervisor.verifikasi.login'))->name('admin.supervisor.verifikasi.login');
    Route::get('/supervisor/verifikasi/registrasi', fn() => view('admin.supervisor.verifikasi.registrasirekening'))->name('admin.supervisor.verifikasi.registrasi');
    Route::get('/supervisor/verifikasi/registrasirekening', fn() => view('admin.supervisor.verifikasi.registrasirekening'));
    Route::get('/supervisor/verifikasi/registrasirekening/detail', fn() => view('admin.supervisor.verifikasi.registrasirekening.detail'));
    Route::get('/supervisor/verifikasi/registrasirekening/revisi', fn() => view('admin.supervisor.verifikasi.registrasirekening.revisi'));
    Route::get('/supervisor/verifikasi/transfer', fn() => view('admin.supervisor.verifikasi.transfer'))->name('admin.supervisor.verifikasi.transfer');

    // Customer Service
    Route::get('/costumerservice/dashboard', fn() => view('admin.costumerservice.dashboard'))->name('admin.cs.dashboard');
    Route::get('/costumerservice/dashboard', fn() => view('admin.costumerservice.dashboard'))->name('admin.costumerservice.dashboard');
    Route::get('/costumerservice/keloladata', fn() => view('admin.costumerservice.keloladata'))->name('admin.cs.keloladata');
    Route::get('/costumerservice/keloladata', fn() => view('admin.costumerservice.keloladata'))->name('admin.costumerservice.keloladata');
    Route::get('/costumerservice/edit/{id?}', fn() => view('admin.costumerservice.crudnasabah.edit'))->name('admin.cs.edit');
    Route::get('/costumerservice/edit/{id?}', fn() => view('admin.costumerservice.crudnasabah.edit'))->name('admin.costumerservice.edit');
    Route::get('/costumerservice/import', fn() => view('admin.costumerservice.crudnasabah.import'))->name('admin.cs.import');
    Route::get('/costumerservice/import', fn() => view('admin.costumerservice.crudnasabah.import'))->name('admin.costumerservice.import');
    Route::get('/costumerservice/print', fn() => view('admin.costumerservice.crudnasabah.print'))->name('admin.cs.print');
    Route::get('/costumerservice/print', fn() => view('admin.costumerservice.crudnasabah.print'))->name('admin.costumerservice.print');

    // Teller
    Route::get('/teller/dashboard', fn() => view('admin.teller.dashboard'))->name('admin.teller.dashboard');
    Route::get('/teller/setoran', fn() => view('admin.teller.dashboard'))->name('admin.teller.setoran');
    Route::get('/teller/penarikan', fn() => view('admin.teller.dashboard'))->name('admin.teller.penarikan');
    Route::get('/teller/transfer', fn() => view('admin.teller.dashboard'))->name('admin.teller.transfer');
    Route::get('/teller/history-nasabah', fn() => view('admin.teller.dashboard'))->name('admin.teller.history_nasabah');
});

Route::get('/admin/supervisor/dashboard', [AdminController::class, 'dashboardSupervisor'])->name('admin.supervisor.dashboard');
Route::get('/admin/supervisor/verifikasi/transfer', [AdminController::class, 'verifikasiTFF'])->name('admin.supervisor.verifikasiTransfer');
