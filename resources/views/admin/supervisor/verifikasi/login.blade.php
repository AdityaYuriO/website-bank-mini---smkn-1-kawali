@extends('layouts.admin')

@section('title','Supervisor Dashboard')
@section('header_title')
Selamat Datang, {{ $user->name ?? 'Administrator' }}!
@endsection
@section('header_subtitle', 'Lorem Ipsum is simply dummy text of the printing.')

@section('content')
@php
    $perPage = request('per_page', 10);
    if (!isset($data)) {
        $sampleItems = collect([
            (object)[
                'id' => 1,
                'user' => (object)[
                    'name' => 'Rina Anggraeni, S.Kom',
                    'email' => 'rina.cs@smkn1kawali.sch.id',
                    'role' => (object)['nama_role' => 'Customer Service'],
                    'role2' => null,
                ],
                'status' => 'pending',
                'created_at' => \Carbon\Carbon::now()->subMinutes(15),
                'waktu_verifikasi' => null,
            ],
            (object)[
                'id' => 2,
                'user' => (object)[
                    'name' => 'Dedi Supriadi',
                    'email' => 'dedi.teller@smkn1kawali.sch.id',
                    'role' => (object)['nama_role' => 'Teller'],
                    'role2' => null,
                ],
                'status' => 'disetujui',
                'created_at' => \Carbon\Carbon::now()->subHours(2),
                'waktu_verifikasi' => \Carbon\Carbon::now()->subHours(1)->format('Y-m-d H:i:s'),
            ],
            (object)[
                'id' => 3,
                'user' => (object)[
                    'name' => 'Asep Saepuloh',
                    'email' => 'asep.cs@smkn1kawali.sch.id',
                    'role' => (object)['nama_role' => 'Customer Service'],
                    'role2' => (object)['nama_role' => 'Teller'],
                ],
                'status' => 'ditolak',
                'created_at' => \Carbon\Carbon::now()->subDays(1),
                'waktu_verifikasi' => \Carbon\Carbon::now()->subDays(1)->addMinutes(10)->format('Y-m-d H:i:s'),
            ],
        ]);
        $page = request('page', 1);
        $data = new \Illuminate\Pagination\LengthAwarePaginator($sampleItems, $sampleItems->count(), $perPage, $page, [
            'path' => url('/admin/supervisor/verifikasi/login')
        ]);
    }
@endphp

<div id="viewTabel" class="fade-in flex flex-1 flex-col justify-start">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 px-1 gap-4">

        <h3 class="text-[20px] sm:text-[24px] font-bold text-gray-800">
            Data Verifikasi Login
        </h3>

        <div class="flex bg-gray-100 p-1 rounded-xl w-full sm:w-[300px]">
            <a href="{{ url('/admin/supervisor/verifikasi/login') }}"
                class="flex-1 px-2 sm:px-4 py-2 bg-white rounded-lg shadow-sm text-brand-blue font-bold text-xs sm:text-[13px] text-center transition-all">
                Login
            </a>

            <a href="{{ url('/admin/supervisor/verifikasi/registrasi') }}"
                class="flex-1 px-2 sm:px-4 py-2 text-gray-500 font-medium text-xs sm:text-[13px] text-center hover:text-brand-blue transition-colors">
                Registrasi
            </a>

            <a href="{{ url('/admin/supervisor/verifikasi/transfer') }}"
                class="flex-1 px-2 sm:px-4 py-2 text-gray-500 font-medium text-xs sm:text-[13px] text-center hover:text-brand-blue transition-colors">
                Transfer
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl sm:rounded-[20px] shadow-card p-4 sm:p-6 w-full">
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4 pb-3 border-b border-gray-100">
            <form action="{{ url('/admin/supervisor/verifikasi/login') }}" method="get" class="flex gap-2 items-center w-full md:w-auto">
                <div class="relative flex-1 md:flex-initial">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" placeholder="Cari data..."
                        value="{{ request('keyword') }}" name="keyword"
                        class="w-full md:w-[250px] pl-12 pr-4 py-2 bg-white border border-gray-100 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-700 placeholder-gray-400 shadow-sm transition-all">
                </div>

                <button type="submit" class="px-3.5 py-2 bg-brand-blue text-white text-[14px] font-medium rounded-xl shadow-sm hover:opacity-90 transition-all shrink-0">
                    <i class="ph ph-magnifying-glass text-lg"></i>
                </button>
            </form>
            <div class="flex flex-wrap items-center justify-between md:justify-end gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-[13px] text-gray-600 font-medium shrink-0">Tampilkan:</span>
                    <select onchange="changePerPage(this.value)" class="bg-white border border-gray-200 text-gray-700 text-xs sm:text-[13px] rounded-[10px] px-3 py-1.5 font-semibold focus:outline-none focus:border-brand-blue shadow-sm cursor-pointer">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 data</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 data</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 data</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 data</option>
                    </select>
                </div>
                @if($data->count() > 0)
                <button type="button" onclick="openConfirmModal({
                    title: 'Hapus Seluruh Data Login?',
                    message: 'Apakah Anda yakin ingin menghapus <strong>SELURUH data verifikasi login</strong>? Tindakan ini tidak dapat dibatalkan.',
                    type: 'danger',
                    confirmText: 'Ya, Hapus Semua',
                    onConfirm: () => {
                        document.querySelector('table tbody').innerHTML = '<tr><td colspan=\'6\' class=\'text-center py-8 text-gray-500\'>Tidak ada permintaan login</td></tr>';
                        showToast('Seluruh data verifikasi login berhasil dihapus (Mode Preview)', 'success');
                    }
                })" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 font-medium text-xs sm:text-[13px] rounded-xl transition-all border border-red-200">
                    <i class="ph ph-trash text-base sm:text-lg"></i>
                    <span>Hapus Semua Data</span>
                </button>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <thead>
                    <tr>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] w-12 border-b border-gray-100">No</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nama</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Email</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Role</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Status</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] text-center w-40 border-b border-gray-100">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="text-[14px] text-gray-800 font-medium">

                    @forelse($data as $index => $item)

                    <tr class="hover:bg-gray-50/50 transition-colors">

                        <td class="py-4 px-2 border-b border-gray-50 text-gray-600 pl-4">
                            {{ $data->firstItem() + $index }}.
                        </td>

                        <td class="py-4 px-2 border-b border-gray-50">
                            {{ $item->user->name }}
                        </td>

                        <td class="py-4 px-2 border-b border-gray-50">
                            {{ $item->user->email }}
                        </td>

                        <td class="py-4 px-2 border-b border-gray-50">
                            {{ $item->user->role->nama_role ?? '-' }}
                            @if($item->user->role2)
                            <span class="text-xs text-blue-600 font-bold">(& {{ $item->user->role2->nama_role }})</span>
                            @endif
                        </td>

                        <td class="py-4 px-2 border-b border-gray-50 status-col">
                            @if($item->status == 'pending')

                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs">
                                <i class="ph ph-clock"></i>
                                Pending
                            </span>

                            @elseif($item->status == 'disetujui')

                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs">
                                <i class="ph ph-check-circle"></i>
                                Disetujui
                            </span>

                            @else

                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs">
                                <i class="ph ph-x-circle"></i>
                                Ditolak
                            </span>

                            @endif
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50 action-col">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    type="button"
                                    onclick="viewDetail(
                                            '{{ $item->id }}',
                                            '{{ $item->user->name }}',
                                            '{{ $item->user->email }}',
                                            '{{ $item->user->role->nama_role ?? '-' }} {{ $item->user->role2 ? '& ' . $item->user->role2->nama_role : '' }}',
                                            '{{ $item->status }}',
                                            '{{ $item->created_at->format('d/m/Y H:i') }}',
                                            '{{ $item->waktu_verifikasi ? \Carbon\Carbon::parse($item->waktu_verifikasi)->format('d/m/Y H:i') : '-' }}'
                                        )"
                                    class="w-[30px] h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors" title="Lihat Detail"><i class="ph-fill ph-eye text-[16px]"></i>
                                </button>

                                @if($item->status == 'pending')

                                <button type="button" onclick="openConfirmModal({
                                    title: 'Setujui Login?',
                                    message: 'Apakah Anda yakin ingin menyetujui permintaan login untuk <strong>{{ $item->user->name }}</strong>?',
                                    type: 'success',
                                    confirmText: 'Ya, Setujui',
                                    onConfirm: () => {
                                        const row = this.closest('tr');
                                        row.querySelector('.status-col').innerHTML = '<span class=\'inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs\'><i class=\'ph ph-check-circle\'></i> Disetujui</span>';
                                        row.querySelector('.action-col').innerHTML = '<div class=\'flex items-center justify-center gap-2\'><button type=\'button\' onclick=\'viewDetail({{ $item->id }}, \"{{ $item->user->name }}\", \"{{ $item->user->email }}\", \"{{ $item->user->role->nama_role }}\", \"disetujui\", \"{{ $item->created_at->format('d/m/Y H:i') }}\", \"Baru saja\")\' class=\'w-[30px] h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors\' title=\'Lihat Detail\'><i class=\'ph-fill ph-eye text-[16px]\'></i></button><span class=\'text-[10px] text-gray-400\'><i class=\'ph ph-lock\'></i> Selesai</span></div>';
                                        showToast('Login {{ $item->user->name }} berhasil disetujui (Mode Preview)', 'success');
                                    }
                                })" class="w-[30px] h-[30px] rounded-full bg-[#d1fae5] text-[#10a163] flex items-center justify-center hover:bg-green-200 transition-colors" title="Setujui">
                                    <i class="ph-bold ph-check-circle text-[16px]"></i>
                                </button>

                                <button type="button" onclick="openConfirmModal({
                                    title: 'Tolak Login?',
                                    message: 'Apakah Anda yakin ingin menolak permintaan login untuk <strong>{{ $item->user->name }}</strong>?',
                                    type: 'danger',
                                    confirmText: 'Ya, Tolak',
                                    onConfirm: () => {
                                        const row = this.closest('tr');
                                        row.querySelector('.status-col').innerHTML = '<span class=\'inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs\'><i class=\'ph ph-x-circle\'></i> Ditolak</span>';
                                        row.querySelector('.action-col').innerHTML = '<div class=\'flex items-center justify-center gap-2\'><button type=\'button\' onclick=\'viewDetail({{ $item->id }}, \"{{ $item->user->name }}\", \"{{ $item->user->email }}\", \"{{ $item->user->role->nama_role }}\", \"ditolak\", \"{{ $item->created_at->format('d/m/Y H:i') }}\", \"Baru saja\")\' class=\'w-[30px] h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors\' title=\'Lihat Detail\'><i class=\'ph-fill ph-eye text-[16px]\'></i></button><span class=\'text-[10px] text-gray-400\'><i class=\'ph ph-lock\'></i> Selesai</span></div>';
                                        showToast('Login {{ $item->user->name }} berhasil ditolak (Mode Preview)', 'error');
                                    }
                                })" class="w-[30px] h-[30px] rounded-full bg-[#fee2e2] text-red-500 flex items-center justify-center hover:bg-red-200 transition-colors" title="Tolak">
                                    <i class="ph-bold ph-x-circle text-[16px]"></i>
                                </button>

                                @else

                                <span class="text-[10px] text-gray-400">
                                    <i class="ph ph-lock"></i>
                                    Selesai
                                </span>

                                @endif

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6"
                            class="text-center py-8 text-gray-500">
                            Tidak ada permintaan login
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>
        </div>
        <!-- Pagination -->
        <x-pagination :paginator="$data" />
    </div>

</div>
@include('admin.supervisor.verifikasi.login.detail')
@endsection

@section('scripts')
<script>
    function switchView(view) {
        document.getElementById('viewTabel')
            .classList.add('hidden');

        document.getElementById('viewDetailLogin')
            .classList.add('hidden');

        document.getElementById(view)
            .classList.remove('hidden');
    }

    function viewDetail(
        id,
        nama,
        email,
        role,
        status,
        waktuLogin,
        waktuVerifikasi
    ) {

        document.getElementById('detail_id').value = id;
        document.getElementById('detail_nama').value = nama;
        document.getElementById('detail_email').value = email;
        document.getElementById('detail_role').value = role;
        document.getElementById('detail_status').value = status;
        document.getElementById('detail_login').value = waktuLogin;
        document.getElementById('detail_verifikasi').value = waktuVerifikasi;

        switchView('viewDetailLogin');
    }

    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
</script>
@endsection
