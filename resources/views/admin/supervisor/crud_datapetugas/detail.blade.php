<div id="viewDetailData" class="fade-in hidden flex-1 mt-4">
    <div class="bg-white rounded-2xl sm:rounded-[24px] shadow-card p-4 sm:p-6 md:p-10 w-full border border-gray-50 overflow-y-auto custom-scrollbar">
        <h3 class="text-[20px] font-bold text-gray-800 mb-8 flex items-center gap-3">
            <div class="w-[6px] h-7 bg-brand-green rounded-full"></div>
            Detail Data Petugas
        </h3>
        <form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Nama Petugas</label>
                    <input type="text" id="detail_nama" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none bg-gray-50 cursor-default" readonly>
                </div>
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Kelas</label>
                    <input type="text" id="detail_kelas" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none bg-gray-50 cursor-default" readonly>
                </div>
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Email</label>
                    <input type="email" id="detail_email" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none bg-gray-50 cursor-default" readonly>
                </div>
                <div>
                    <label class="block text-[13.5px] font-bold text-gray-500 mb-2">Role</label>
                    <input type="text" id="detail_role_text" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 focus:outline-none bg-gray-50 cursor-default" readonly>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-12">
                <button type="button" onclick="switchView('tabel')" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px]">Kembali</button>
                <div class="hidden sm:block"></div>
            </div>
        </form>
    </div>
</div>
